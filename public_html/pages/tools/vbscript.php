<?php
/**
 * VBScript Editor + Preview
 *
 * Note: Modern browsers do not execute VBScript. This tool lets you edit
 * VBScript/HTML and preview the HTML layout. Any <script language="VBScript">
 * blocks will be ignored by the browser.
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-gray-700 dark:text-gray-300">description</span>
            <div>
                <h1 class="text-lg font-bold text-gray-900 dark:text-white">
                    VBScript Editor
                </h1>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Edit .vbs / classic ASP-style snippets and preview the surrounding HTML.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer select-none">
                <input type="checkbox" id="vb-live-toggle" class="rounded border-gray-300 text-primary focus:ring-primary" checked>
                Live Preview
            </label>
            <button id="vb-render-btn"
                    class="px-3 py-1.5 text-sm font-medium rounded bg-primary text-white hover:bg-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                Render
            </button>
        </div>
    </div>

    <!-- Editor & Preview -->
    <div class="flex flex-1 overflow-hidden">
        <!-- Editor -->
        <div class="w-1/2 border-r border-gray-200 dark:border-slate-700 flex flex-col">
            <div id="vb-editor-container"
                 class="flex-1 w-full h-full text-sm font-mono bg-gray-50 dark:bg-slate-900 text-gray-800 dark:text-gray-200">
            </div>
        </div>

        <!-- Preview -->
        <div class="w-1/2 bg-white dark:bg-white overflow-hidden relative">
            <iframe id="vb-preview-frame"
                    class="w-full h-full border-0"
                    sandbox="allow-same-origin"
                    title="VBScript Preview"></iframe>

            <div class="absolute bottom-2 left-2 right-2 bg-yellow-50 text-[11px] text-yellow-800 border border-yellow-200 rounded px-2 py-1">
                <strong>Note:</strong> VBScript is not executed in modern browsers. Only HTML/CSS is rendered.
            </div>
        </div>
    </div>
</div>

<!-- Monaco Editor loader -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs/loader.min.js"
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

<script>
    const vbEditorContainer = document.getElementById('vb-editor-container');
    const vbPreviewFrame = document.getElementById('vb-preview-frame');
    const vbLiveToggle = document.getElementById('vb-live-toggle');
    const vbRenderBtn = document.getElementById('vb-render-btn');

    let vbEditorInstance;
    let vbDebounceTimer;

    const vbInitialCode = `<html>
<head>
    <title>VBScript Demo</title>
</head>
<body>
    <h1>Classic VBScript Example</h1>

    <p>VBScript used to run in Internet Explorer only. Modern browsers ignore these blocks:</p>

    <script language="VBScript">
        MsgBox "Hello from VBScript (this will NOT run here)"
    </script>

    <p>The HTML layout still renders correctly in the preview.</p>
</body>
</html>`;

    function vbGetCode() {
        return vbEditorInstance ? vbEditorInstance.getValue() : vbInitialCode;
    }

    function vbUpdatePreview() {
        const code = vbGetCode();
        const doc = vbPreviewFrame.contentDocument || vbPreviewFrame.contentWindow.document;

        // Just write the code as-is; browser will ignore VBScript blocks
        doc.open();
        doc.write(code);
        doc.close();
    }

    function vbHandleContentChange() {
        clearTimeout(vbDebounceTimer);
        vbDebounceTimer = setTimeout(() => {
            if (vbLiveToggle.checked) {
                vbUpdatePreview();
            }
        }, 400);
    }

    vbLiveToggle.addEventListener('change', () => {
        vbRenderBtn.disabled = vbLiveToggle.checked;
        if (vbLiveToggle.checked) {
            vbUpdatePreview();
        }
    });

    vbRenderBtn.addEventListener('click', vbUpdatePreview);

    // Initial preview fallback
    vbUpdatePreview();

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

        vbEditorInstance = monaco.editor.create(vbEditorContainer, {
            value: vbInitialCode.trimStart(),
            language: 'vb',
            automaticLayout: true,
            minimap: { enabled: false },
            wordWrap: 'on',
            fontSize: 14,
            theme: isDark ? 'vs-dark' : 'vs'
        });

        vbEditorInstance.onDidChangeModelContent(vbHandleContentChange);

        // Initial preview once Monaco is ready
        vbUpdatePreview();
    });
</script>