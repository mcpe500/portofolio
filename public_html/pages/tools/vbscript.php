<?php
/**
 * VBScript Editor (Monaco - Editor Only)
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">description</span>
            VBScript Editor
            <span class="text-xs font-normal px-2 py-0.5 rounded bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Editor Only</span>
        </h1>
        <div class="text-sm text-gray-500 dark:text-gray-400">
            Execution not supported in modern browsers
        </div>
    </div>

    <!-- Editor -->
    <div class="flex flex-1 overflow-hidden">
        <div class="w-full h-full flex flex-col">
            <div id="editor-container" 
                 class="flex-1 w-full h-full text-sm font-mono bg-gray-50 dark:bg-slate-900 text-gray-800 dark:text-gray-200">
            </div>
        </div>
    </div>
</div>

<!-- Monaco Editor loader -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.53.0/min/vs/loader.min.js"
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

<script>
    const editorContainer = document.getElementById('editor-container');
    let editorInstance;

    const initialCode = `' VBScript Example
Option Explicit

Dim message, name
name = "World"
message = "Hello, " & name & "!"

' This code cannot be executed in modern browsers
' but syntax highlighting works!

Sub ShowMessage()
    MsgBox message
End Sub

Function Add(a, b)
    Add = a + b
End Function

Dim result
result = Add(5, 10)
`;

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
            language: 'vb',
            automaticLayout: true,
            minimap: { enabled: false },
            wordWrap: 'on',
            fontSize: 14,
            theme: isDark ? 'vs-dark' : 'vs'
        });
    });
</script>
