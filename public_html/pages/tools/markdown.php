<?php
/**
 * Markdown Editor (Monaco + Marked)
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">markdown</span>
            Markdown Editor
        </h1>
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
        <div class="w-1/2 bg-white dark:bg-slate-800 overflow-auto p-8 prose dark:prose-invert max-w-none">
            <div id="preview"></div>
        </div>
    </div>
</div>

<!-- Load Libraries -->
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify/dist/purify.min.js"></script>

<!-- Monaco Editor loader -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs/loader.min.js"
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

<script>
    const editorContainer = document.getElementById('editor-container');
    const preview = document.getElementById('preview');
    let editorInstance;
    let debounceTimer;

    const initialCode = `# Hello Markdown

This is a live Markdown editor.

## Features
- **Bold** and *Italic* text
- Lists and [Links](https://example.com)
- Code blocks:
\`\`\`javascript
console.log('Hello World');
\`\`\`

> Blockquotes are supported too.

## Security
This preview is sanitized to prevent XSS attacks.
<script>alert('This script will be removed');<\/script>`;

    function render() {
        if (!editorInstance) return;
        const rawMarkdown = editorInstance.getValue();
        const html = marked.parse(rawMarkdown);
        const cleanHtml = DOMPurify.sanitize(html);
        preview.innerHTML = cleanHtml;
    }

    function handleContentChange() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(render, 300);
    }

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
            language: 'markdown',
            automaticLayout: true,
            minimap: { enabled: false },
            wordWrap: 'on',
            fontSize: 14,
            theme: isDark ? 'vs-dark' : 'vs'
        });

        editorInstance.onDidChangeModelContent(handleContentChange);

        // Initial render
        render();
    });
</script>
