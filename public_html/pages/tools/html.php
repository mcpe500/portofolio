<?php
/**
 * HTML Live Preview Tool (Monaco Editor + HTML linting + syntax highlighting)
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">html</span>
            HTML Preview
        </h1>
        <div class="flex items-center gap-4">
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer select-none">
                <input type="checkbox" id="live-toggle" class="rounded border-gray-300 text-primary focus:ring-primary" checked>
                Live Preview
            </label>
            <button id="render-btn"
                    class="px-3 py-1.5 text-sm font-medium rounded bg-primary text-white hover:bg-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                Render
            </button>
            <button id="fullscreen-btn"
                    class="p-1.5 rounded text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                    title="Fullscreen Preview">
                <span class="material-symbols-outlined">fullscreen</span>
            </button>
        </div>
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
        <div class="w-1/2 bg-white dark:bg-white overflow-hidden relative">
            <iframe id="preview-frame"
                    class="w-full h-full border-0"
                    sandbox="allow-scripts allow-modals allow-forms allow-same-origin"
                    title="Preview"></iframe>
        </div>
    </div>
</div>

<!-- Monaco Editor loader (no integrity; use a stable version) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs/loader.min.js"
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

<!-- HTMLHint for linting -->
<script src="https://cdn.jsdelivr.net/npm/htmlhint@1.6.3/dist/htmlhint.min.js"
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

<script>
    const editorContainer = document.getElementById('editor-container');
    const previewFrame = document.getElementById('preview-frame');
    const liveToggle = document.getElementById('live-toggle');
    const renderBtn = document.getElementById('render-btn');
    const fullscreenBtn = document.getElementById('fullscreen-btn');

    let debounceTimer;
    let editorInstance;

    const initialCode = `<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; padding: 2rem; color: #333; }
        h1 { color: #2563eb; }
        .card {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <h1>HTML Live Preview</h1>
    <p>Edit the code on the left to see changes instantly.</p>

    <div class="card">
        <h2>Features</h2>
        <ul>
            <li>Live updates</li>
            <li>Scoped CSS</li>
            <li>Sandboxed execution</li>
        </ul>
    </div>
</body>
</html>`;

    function getCode() {
        return editorInstance ? editorInstance.getValue() : initialCode;
    }

    function updatePreview() {
        const code = getCode();
        const doc = previewFrame.contentDocument || previewFrame.contentWindow.document;
        doc.open();
        doc.write(code);
        doc.close();
    }

    function runLint() {
        if (!window.HTMLHint || !editorInstance || typeof monaco === 'undefined') return;

        const model = editorInstance.getModel();
        const code = model.getValue();

        // Basic HTMLHint rules; tweak as needed
        const results = HTMLHint.verify(code, {
            'tagname-lowercase': true,
            'attr-lowercase': true,
            'doctype-first': true,
            'id-unique': true,
            'src-not-empty': true,
            'alt-require': false,
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

    function handleContentChange() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            runLint();
            if (liveToggle.checked) {
                updatePreview();
            }
        }, 500);
    }

    // UI: Live toggle
    liveToggle.addEventListener('change', () => {
        renderBtn.disabled = liveToggle.checked;
        if (liveToggle.checked) {
            updatePreview();
        }
    });

    // UI: Manual Render
    renderBtn.addEventListener('click', updatePreview);

    // UI: Fullscreen
    fullscreenBtn.addEventListener('click', () => {
        if (previewFrame.requestFullscreen) {
            previewFrame.requestFullscreen();
        } else if (previewFrame.webkitRequestFullscreen) { /* Safari */
            previewFrame.webkitRequestFullscreen();
        } else if (previewFrame.msRequestFullscreen) { /* IE11 */
            previewFrame.msRequestFullscreen();
        }
    });

    // Initial preview (fallback if Monaco fails to load)
    updatePreview();

    // Configure Monaco loader (cdnjs)
    require.config({
        paths: {
            'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs'
        }
    });

    // Optional: make workers load correctly (avoids extra console noise)
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
            value: initialCode.trimStart(),
            language: 'html',
            automaticLayout: true,
            minimap: { enabled: false },
            wordWrap: 'on',
            fontSize: 14,
            theme: isDark ? 'vs-dark' : 'vs'
        });

        editorInstance.onDidChangeModelContent(handleContentChange);

        // Initial lint + preview once Monaco is ready
        runLint();
        updatePreview();
    });
</script>
