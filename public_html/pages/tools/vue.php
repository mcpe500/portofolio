<?php
/**
 * Vue Playground (Monaco + Vue 3)
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">view_quilt</span>
            Vue Playground
        </h1>
        <button id="run-btn" 
                class="px-4 py-1.5 text-sm font-medium rounded bg-primary text-white hover:bg-primary/90 transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">play_arrow</span>
            Run Code
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
            <div id="app-container" class="w-full h-full">
                <div id="app"></div>
            </div>
            <div id="error-message" class="absolute bottom-4 left-4 right-4 bg-red-100 text-red-700 p-3 rounded-lg text-sm hidden font-mono whitespace-pre-wrap"></div>
        </div>
    </div>
</div>

<!-- Load Vue -->
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

<!-- Monaco Editor loader -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.53.0/min/vs/loader.min.js"
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

<script>
    const editorContainer = document.getElementById('editor-container');
    const runBtn = document.getElementById('run-btn');
    const errorMsg = document.getElementById('error-message');
    const appContainer = document.getElementById('app-container');
    let editorInstance;
    let currentApp = null;

    const initialCode = `const { createApp, ref } = Vue;

createApp({
  setup() {
    const message = ref('Hello Vue!');
    const count = ref(0);

    function increment() {
      count.value++;
    }

    return {
      message,
      count,
      increment
    }
  },
  template: \`
    <div class="p-8 text-center">
      <h1 class="text-3xl font-bold mb-4 text-green-600">{{ message }}</h1>
      <p class="mb-4 text-gray-600 dark:text-gray-300">
        Count is: {{ count }}
      </p>
      <button 
        @click="increment"
        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-colors"
      >
        Increment
      </button>
    </div>
  \`
}).mount('#app');`;

    function runCode() {
        if (!editorInstance) return;
        const code = editorInstance.getValue();
        errorMsg.classList.add('hidden');
        
        if (currentApp) {
            currentApp.unmount();
            currentApp = null;
        }

        appContainer.innerHTML = '<div id="app"></div>';

        try {
            const originalCreateApp = Vue.createApp;
            Vue.createApp = (...args) => {
                const app = originalCreateApp(...args);
                currentApp = app;
                return app;
            };

            new Function('Vue', code)(Vue);

            Vue.createApp = originalCreateApp;

        } catch (error) {
            errorMsg.textContent = error.message;
            errorMsg.classList.remove('hidden');
            console.error(error);
        }
    }

    runBtn.addEventListener('click', runCode);

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
            language: 'javascript', // Using JS since the code is JS with template strings
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
