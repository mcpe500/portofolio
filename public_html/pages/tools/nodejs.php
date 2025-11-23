<?php
/**
 * Node.js Playground (Emulated)
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">terminal</span>
            Node.js Playground
            <span class="text-xs font-normal px-2 py-0.5 rounded bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">Emulated</span>
        </h1>
        <button id="run-btn" 
                class="px-4 py-1.5 text-sm font-medium rounded bg-primary text-white hover:bg-primary/90 transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">play_arrow</span>
            Run Code
        </button>
    </div>

    <!-- Editor & Console -->
    <div class="flex flex-1 overflow-hidden">
        <!-- Editor -->
        <div class="w-1/2 border-r border-gray-200 dark:border-slate-700 flex flex-col">
            <div id="editor-container" 
                 class="flex-1 w-full h-full text-sm font-mono bg-gray-50 dark:bg-slate-900 text-gray-800 dark:text-gray-200">
            </div>
        </div>

        <!-- Console -->
        <div class="w-1/2 bg-slate-900 text-slate-200 overflow-hidden flex flex-col font-mono text-sm">
            <div class="px-4 py-2 bg-slate-800 border-b border-slate-700 text-xs uppercase tracking-wider font-semibold text-slate-400 flex justify-between">
                <span>Console Output</span>
                <button id="clear-btn" class="hover:text-white transition-colors">Clear</button>
            </div>
            <div id="console-output" class="flex-1 p-4 overflow-auto whitespace-pre-wrap"></div>
        </div>
    </div>
</div>

<!-- Monaco Editor loader -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.53.0/min/vs/loader.min.js"
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

<script>
    const editorContainer = document.getElementById('editor-container');
    const runBtn = document.getElementById('run-btn');
    const clearBtn = document.getElementById('clear-btn');
    const consoleOutput = document.getElementById('console-output');
    let editorInstance;

    const initialCode = `// Node.js Emulation
// Note: This runs in the browser, so 'fs' and 'http' are not available.

console.log('Hello from Node.js Playground!');

// Process emulation
console.log('Process Platform:', process.platform);
console.log('Process Version:', process.version);

// Async example
setTimeout(() => {
    console.log('Async timeout finished!');
}, 1000);

// Error handling
try {
    throw new Error('Something went wrong');
} catch (e) {
    console.error(e.message);
}`;

    // Console Emulation
    const originalConsole = {
        log: console.log,
        error: console.error,
        warn: console.warn,
        info: console.info
    };

    function appendToConsole(type, args) {
        const line = document.createElement('div');
        line.className = `mb-1 ${type === 'error' ? 'text-red-400' : type === 'warn' ? 'text-amber-400' : 'text-slate-200'}`;
        
        const content = args.map(arg => {
            if (typeof arg === 'object') {
                try {
                    return JSON.stringify(arg, null, 2);
                } catch (e) {
                    return String(arg);
                }
            }
            return String(arg);
        }).join(' ');

        line.textContent = `> ${content}`;
        consoleOutput.appendChild(line);
        consoleOutput.scrollTop = consoleOutput.scrollHeight;
    }

    // Mock Node.js Globals
    const mockProcess = {
        platform: 'browser-shim',
        version: 'v18.x (emulated)',
        env: { NODE_ENV: 'development' },
        cwd: () => '/'
    };

    function runCode() {
        if (!editorInstance) return;
        const code = editorInstance.getValue();
        
        // Clear console for new run? Maybe optional. Let's keep history for now unless cleared.
        appendToConsole('info', ['--- Running ---']);

        // Hijack console
        const hijackedConsole = {
            log: (...args) => appendToConsole('log', args),
            error: (...args) => appendToConsole('error', args),
            warn: (...args) => appendToConsole('warn', args),
            info: (...args) => appendToConsole('info', args)
        };

        try {
            // Create a function with hijacked globals
            // We use 'process' as a parameter to inject our mock
            const func = new Function('console', 'process', 'require', code);
            
            // Mock require (very basic)
            const mockRequire = (module) => {
                if (module === 'fs') throw new Error("Module 'fs' is not implemented in browser playground.");
                if (module === 'path') return { join: (...args) => args.join('/') };
                return {};
            };

            func(hijackedConsole, mockProcess, mockRequire);
        } catch (error) {
            appendToConsole('error', [error]);
        }
    }

    runBtn.addEventListener('click', runCode);
    
    clearBtn.addEventListener('click', () => {
        consoleOutput.innerHTML = '';
    });

    // Configure Monaco loader
    require.config({
        paths: {
            'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.53.0/min/vs'
        }
    });

    // Worker config
    window.MonacoEnvironment = {
        getWorkerUrl: function () {
            const base = 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.53.0/min/';
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
            language: 'javascript',
            automaticLayout: true,
            minimap: { enabled: false },
            wordWrap: 'on',
            fontSize: 14,
            theme: isDark ? 'vs-dark' : 'vs'
        });

        // Initial Run
        setTimeout(runCode, 500);
    });
</script>
