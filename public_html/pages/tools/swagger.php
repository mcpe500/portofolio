<?php
/**
 * Swagger Editor (Monaco + Swagger UI)
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">api</span>
            Swagger Editor
        </h1>
    </div>

    <!-- Editor & Preview -->
    <div class="flex flex-1 overflow-hidden">
        <!-- Editor (YAML) -->
        <div class="w-1/2 border-r border-gray-200 dark:border-slate-700 flex flex-col">
            <div id="editor-container" 
                 class="flex-1 w-full h-full text-sm font-mono bg-gray-50 dark:bg-slate-900 text-gray-800 dark:text-gray-200">
            </div>
        </div>

        <!-- Preview (Swagger UI) -->
        <div class="w-1/2 bg-white overflow-auto relative">
            <div id="swagger-ui"></div>
        </div>
    </div>
</div>

<!-- Load Swagger UI -->
<link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5/swagger-ui.css" />
<script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-bundle.js" crossorigin></script>
<script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-standalone-preset.js" crossorigin></script>
<!-- Load YAML Parser (js-yaml) for client-side parsing -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/js-yaml/4.1.0/js-yaml.min.js"></script>

<!-- Monaco Editor loader -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs/loader.min.js"
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

<script>
    const editorContainer = document.getElementById('editor-container');
    let editorInstance;
    let debounceTimer;

    const initialCode = `openapi: 3.0.0
info:
  title: Sample API
  description: A sample API to demonstrate Swagger Editor
  version: 1.0.0
servers:
  - url: http://api.example.com/v1
    description: Main (production) server
paths:
  /users:
    get:
      summary: Returns a list of users.
      description: Optional extended description in Markdown.
      responses:
        '200':
          description: A JSON array of user names
          content:
            application/json:
              schema: 
                type: array
                items: 
                  type: string`;

    function render() {
        if (!editorInstance) return;
        const yamlContent = editorInstance.getValue();
        const model = editorInstance.getModel();

        try {
            // Parse YAML to JSON object
            const spec = jsyaml.load(yamlContent);
            
            // Clear errors
            monaco.editor.setModelMarkers(model, 'yaml', []);

            // Initialize Swagger UI with the spec object
            SwaggerUIBundle({
                spec: spec,
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "BaseLayout"
            });
        } catch (e) {
            console.error("YAML Parse Error", e);
            
            // Map error to markers
            if (e.mark) {
                monaco.editor.setModelMarkers(model, 'yaml', [{
                    severity: monaco.MarkerSeverity.Error,
                    message: e.reason || e.message,
                    startLineNumber: e.mark.line + 1,
                    startColumn: e.mark.column + 1,
                    endLineNumber: e.mark.line + 1,
                    endColumn: e.mark.column + 2 // Highlight at least one char
                }]);
            }
        }
    }

    function handleContentChange() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(render, 500);
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
            language: 'yaml',
            automaticLayout: true,
            minimap: { enabled: false },
            wordWrap: 'on',
            fontSize: 14,
            theme: isDark ? 'vs-dark' : 'vs'
        });

        editorInstance.onDidChangeModelContent(handleContentChange);

        // Initial Render
        setTimeout(render, 500);
    });
</script>
