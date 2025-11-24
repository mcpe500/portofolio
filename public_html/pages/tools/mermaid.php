<?php
/**
 * Mermaid Diagram Editor (Monaco + Mermaid)
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">account_tree</span>
            Mermaid Editor
        </h1>
        <div class="flex items-center gap-2">
            <span class="text-sm text-gray-500 dark:text-gray-400 mr-2">Export PNG:</span>
            <?php foreach ([1, 2, 4, 8, 16] as $scale): ?>
                <button onclick="exportPng(<?= $scale ?>)" 
                        class="px-2 py-1 text-xs font-medium rounded bg-gray-100 dark:bg-gray-700 hover:bg-primary hover:text-white dark:hover:bg-primary transition-colors">
                    <?= $scale ?>x
                </button>
            <?php endforeach; ?>
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
        <div class="w-1/2 bg-white dark:bg-slate-800 overflow-auto p-4 flex items-center justify-center relative">
            <div id="preview-container" class="w-full h-full flex items-center justify-center">
                <div id="mermaid-output"></div>
            </div>
            <div id="error-message" class="absolute bottom-4 left-4 right-4 bg-red-100 text-red-700 p-3 rounded-lg text-sm hidden"></div>
        </div>
    </div>
</div>

<!-- Load Mermaid -->
<script type="module">
    import mermaid from 'https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.esm.min.mjs';
    
    mermaid.initialize({ 
        startOnLoad: false,
        theme: document.documentElement.classList.contains('dark') ? 'dark' : 'default',
        securityLevel: 'loose',
    });

    window.mermaid = mermaid; // Expose for use in non-module script
</script>

<!-- Monaco Editor loader -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs/loader.min.js"
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

<script>
    const editorContainer = document.getElementById('editor-container');
    const output = document.getElementById('mermaid-output');
    const errorMsg = document.getElementById('error-message');
    let editorInstance;
    let debounceTimer;

    const initialCode = `graph TD
    A[Start] --> B{Is it working?}
    B -- Yes --> C[Great!]
    B -- No --> D[Debug]
    D --> B`;

    async function render() {
        if (!editorInstance || !window.mermaid) return;
        const code = editorInstance.getValue();
        const model = editorInstance.getModel();

        try {
            // Check validity
            // mermaid.parse throws error on failure
            await window.mermaid.parse(code);
            
            // Clear errors
            monaco.editor.setModelMarkers(model, 'mermaid', []);
            errorMsg.classList.add('hidden');

            // Render
            output.innerHTML = '';
            const { svg } = await window.mermaid.render('graphDiv', code);
            output.innerHTML = svg;

        } catch (error) {
            // Show error in UI
            errorMsg.textContent = error.message;
            errorMsg.classList.remove('hidden');

            // Try to map error to editor markers
            // Mermaid error messages format: "Parse error on line X: ..."
            // We can try to extract line number
            const lineMatch = error.message.match(/line\s+(\d+)/i);
            if (lineMatch) {
                const line = parseInt(lineMatch[1], 10);
                monaco.editor.setModelMarkers(model, 'mermaid', [{
                    severity: monaco.MarkerSeverity.Error,
                    message: error.message,
                    startLineNumber: line,
                    startColumn: 1,
                    endLineNumber: line,
                    endColumn: 1000
                }]);
            }
        }
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
            language: 'markdown', // Using markdown as fallback for highlighting
            automaticLayout: true,
            minimap: { enabled: false },
            wordWrap: 'on',
            fontSize: 14,
            theme: isDark ? 'vs-dark' : 'vs'
        });

        editorInstance.onDidChangeModelContent(handleContentChange);

        // Initial render (wait for mermaid to be ready)
        const checkMermaid = setInterval(() => {
            if (window.mermaid) {
                clearInterval(checkMermaid);
                render();
            }
        }, 100);
    });

    // Export Function
    window.exportPng = async (scale) => {
        const svgElement = output.querySelector('svg');
        if (!svgElement) return;

        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const svgData = new XMLSerializer().serializeToString(svgElement);
        const img = new Image();
        
        const bbox = svgElement.getBoundingClientRect();
        const width = bbox.width * scale;
        const height = bbox.height * scale;

        canvas.width = width;
        canvas.height = height;

        const svgBlob = new Blob([svgData], { type: 'image/svg+xml;charset=utf-8' });
        const url = URL.createObjectURL(svgBlob);

        img.onload = () => {
            ctx.fillStyle = document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff';
            ctx.fillRect(0, 0, width, height);
            ctx.drawImage(img, 0, 0, width, height);
            
            const pngUrl = canvas.toDataURL('image/png');
            const downloadLink = document.createElement('a');
            downloadLink.href = pngUrl;
            downloadLink.download = `mermaid-diagram-${scale}x.png`;
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
            URL.revokeObjectURL(url);
        };

        img.src = url;
    };
</script>
