<?php
/**
 * Svelte Playground (Monaco + Svelte Compiler)
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">bolt</span>
            Svelte Playground
            <span class="text-xs font-normal px-2 py-0.5 rounded bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">Experimental</span>
        </h1>
        <button id="run-btn" 
                class="px-4 py-1.5 text-sm font-medium rounded bg-primary text-white hover:bg-primary/90 transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">play_arrow</span>
            Compile & Run
        </button>
    </div>

    <!-- Editor & Preview -->
    <div class="flex flex-1 overflow-hidden">
        <!-- Editor -->
        <div class="w-1/2 border-r border-gray-200 dark:border-slate-700 flex flex-col">
            <div id="editor-container" 
                 class="flex-1 w-full h-full text-sm font-mono bg-gray-50 dark:bg-slate-900 text-gray-800 dark:text-gray-200">
            </div>
        </div>

        <!-- Preview -->
        <div class="w-1/2 bg-white dark:bg-slate-800 overflow-auto relative">
            <div id="app-container" class="w-full h-full"></div>
            <div id="error-message" class="absolute bottom-4 left-4 right-4 bg-red-100 text-red-700 p-3 rounded-lg text-sm hidden font-mono whitespace-pre-wrap"></div>
        </div>
    </div>
</div>

<!-- Load Svelte Compiler -->
<script type="module">
    import * as svelte from 'https://unpkg.com/svelte@3.59.2/compiler.mjs';
    window.svelteCompiler = svelte;
</script>

<!-- Monaco Editor loader -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs/loader.min.js"
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

<script>
    const editorContainer = document.getElementById('editor-container');
    const runBtn = document.getElementById('run-btn');
    const errorMsg = document.getElementById('error-message');
    const appContainer = document.getElementById('app-container');
    let editorInstance;
    let currentApp = null;

    const initialCode = `<script>
	let count = 0;
	let name = 'Svelte';

	function handleClick() {
		count += 1;
	}
<\/script>

<div class="p-8 text-center">
	<h1 class="text-3xl font-bold mb-4 text-orange-600">Hello {name}!</h1>
	
	<p class="mb-4 text-gray-600 dark:text-gray-300">
		You clicked the button {count} times.
	</p>

	<button 
		on:click={handleClick}
		class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 transition-colors"
	>
		Click Me
	</button>
	
	<div class="mt-4">
		<input bind:value={name} class="px-3 py-2 border rounded dark:bg-slate-700 dark:border-slate-600" placeholder="Enter name">
	</div>
</div>

<style>
	h1 {
		text-transform: uppercase;
	}
</style>`;

    async function runCode() {
        if (!editorInstance || !window.svelteCompiler) return;
        const code = editorInstance.getValue();
        errorMsg.classList.add('hidden');
        
        if (currentApp) {
            currentApp.$destroy();
            currentApp = null;
        }
        appContainer.innerHTML = '';

        try {
            const { js, css } = window.svelteCompiler.compile(code, {
                format: 'esm',
                generate: 'dom',
                name: 'App',
                dev: true
            });

            if (css && css.code) {
                const style = document.createElement('style');
                style.textContent = css.code;
                document.head.appendChild(style);
            }

            const svelteInternalUrl = 'https://unpkg.com/svelte@3.59.2/internal/index.mjs';
            const modifiedJs = js.code.replace(/from ["']svelte\/internal["']/g, `from "${svelteInternalUrl}"`);

            const blob = new Blob([modifiedJs], { type: 'text/javascript' });
            const url = URL.createObjectURL(blob);

            const module = await import(url);
            const App = module.default;

            currentApp = new App({
                target: appContainer
            });

            URL.revokeObjectURL(url);

        } catch (error) {
            errorMsg.textContent = "Compilation Error: " + error.message;
            errorMsg.classList.remove('hidden');
            console.error(error);
        }
    }

    runBtn.addEventListener('click', runCode);

    // Configure Monaco loader
    require.config({
        paths: {
            'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs'
        }
    });

    // Worker config
    window.MonacoEnvironment = {
        getWorkerUrl: function () {
            const base = 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/';
            const code = `
                self.MonacoEnvironment = { baseUrl: '${base}' };
                importScripts('${base}vs/base/worker/workerMain.min.js');
            `;
            return 'data:application/javascript;charset=utf-8,' + encodeURIComponent(code);
        }
    };

    // Initialize Monaco
    require(['vs/editor/editor.main'], function () {
        const isDark = document.documentElement.classList.contains('dark');

        editorInstance = monaco.editor.create(editorContainer, {
            value: initialCode,
            language: 'html', // Svelte is HTML-like
            automaticLayout: true,
            minimap: { enabled: false },
            wordWrap: 'on',
            fontSize: 14,
            theme: isDark ? 'vs-dark' : 'vs'
        });

        // Initial Run
        setTimeout(runCode, 1000);
    });
</script>
