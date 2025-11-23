<?php
/**
 * AngularJS Playground (Monaco + AngularJS v1.x)
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">javascript</span>
            AngularJS Playground
            <span class="text-xs font-normal px-2 py-0.5 rounded bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">Legacy v1.x</span>
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
                <div id="app" ng-app="myApp" class="w-full h-full"></div>
            </div>
            <div id="error-message" class="absolute bottom-4 left-4 right-4 bg-red-100 text-red-700 p-3 rounded-lg text-sm hidden font-mono whitespace-pre-wrap"></div>
        </div>
    </div>
</div>

<!-- Load AngularJS -->
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script>

<!-- Monaco Editor loader -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.53.0/min/vs/loader.min.js"
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

<!-- HTMLHint for linting -->
<script src="https://cdn.jsdelivr.net/npm/htmlhint@1.6.3/dist/htmlhint.min.js"
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

<script>
    const editorContainer = document.getElementById('editor-container');
    const runBtn = document.getElementById('run-btn');
    const errorMsg = document.getElementById('error-message');
    const appContainer = document.getElementById('app-container');
    let editorInstance;

    const initialCode = `<div ng-controller="MainCtrl">
  <div class="p-8 text-center">
    <h1 class="text-3xl font-bold mb-4 text-red-600">Hello {{name}}!</h1>
    
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Enter your name:</label>
      <input type="text" ng-model="name" class="px-3 py-2 border rounded dark:bg-slate-700 dark:border-slate-600">
    </div>

    <p class="text-gray-600 dark:text-gray-300">
      You are typing: <strong>{{name}}</strong>
    </p>
  </div>
</div>

<script>
  var app = angular.module('myApp', []);
  app.controller('MainCtrl', function($scope) {
    $scope.name = 'AngularJS';
  });
<\/script>`;

    function runCode() {
        if (!editorInstance) return;
        const code = editorInstance.getValue();
        errorMsg.classList.add('hidden');
        
        appContainer.innerHTML = '<div id="app" class="w-full h-full"></div>';
        const appDiv = document.getElementById('app');

        try {
            const scriptMatch = code.match(/<script>([\s\S]*?)<\/script>/);
            let htmlContent = code;
            let scriptContent = '';

            if (scriptMatch) {
                scriptContent = scriptMatch[1];
                htmlContent = code.replace(scriptMatch[0], '');
            }

            appDiv.innerHTML = htmlContent;
            appDiv.setAttribute('ng-app', 'myApp');

            new Function('angular', scriptContent)(angular);
            angular.bootstrap(appDiv, ['myApp']);

        } catch (error) {
            errorMsg.textContent = error.message;
            errorMsg.classList.remove('hidden');
            console.error(error);
        }
    }

    function runLint() {
        if (!window.HTMLHint || !editorInstance || typeof monaco === 'undefined') return;

        const model = editorInstance.getModel();
        const code = model.getValue();

        // Basic HTMLHint rules
        const results = HTMLHint.verify(code, {
            'tagname-lowercase': true,
            'attr-lowercase': true,
            'doctype-first': false, // Not needed for snippet
            'id-unique': true,
            'src-not-empty': true,
            'attr-value-not-empty': false
        });

        const markers = results.map(r => ({
            severity: monaco.MarkerSeverity.Warning,
            message: r.message,
            startLineNumber: r.line,
            startColumn: r.col,
            endLineNumber: r.line,
            endColumn: r.col + 1
        }));

        monaco.editor.setModelMarkers(model, 'htmlhint', markers);
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
            language: 'html',
            automaticLayout: true,
            minimap: { enabled: false },
            wordWrap: 'on',
            fontSize: 14,
            theme: isDark ? 'vs-dark' : 'vs'
        });

        editorInstance.onDidChangeModelContent(() => {
            runLint();
        });

        // Initial Run
        setTimeout(runCode, 500);
        runLint();
    });
</script>
