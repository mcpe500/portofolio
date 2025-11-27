<?php
/**
 * REST Client / API Tester
 * Full-featured tool with Scripting, Collections, Environments, and Enhanced Response Viewer
 */
?>
<div class="flex h-[calc(100vh-64px)] bg-gray-50 dark:bg-slate-900 overflow-hidden">
    <!-- Sidebar -->
    <div class="w-80 bg-white dark:bg-slate-800 border-r border-gray-200 dark:border-slate-700 flex flex-col z-10">
        <!-- Sidebar Tabs -->
        <div class="flex border-b border-gray-200 dark:border-slate-700">
            <button class="flex-1 py-3 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-primary border-b-2 border-transparent hover:border-primary transition-colors active-sidebar-tab" data-tab="collections">Collections</button>
            <button class="flex-1 py-3 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-primary border-b-2 border-transparent hover:border-primary transition-colors" data-tab="history">History</button>
            <button class="flex-1 py-3 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-primary border-b-2 border-transparent hover:border-primary transition-colors" data-tab="env">Env</button>
        </div>

        <!-- Sidebar Content -->
        <div class="flex-1 overflow-y-auto p-4">
            <!-- Collections -->
            <div id="sidebar-collections" class="sidebar-content space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">My Collections</h3>
                    <div class="flex gap-1">
                        <button id="btn-import-coll" class="text-gray-500 hover:text-primary" title="Import"><span class="material-symbols-outlined text-lg">upload</span></button>
                        <button id="btn-export-coll" class="text-gray-500 hover:text-primary" title="Export"><span class="material-symbols-outlined text-lg">download</span></button>
                        <button id="btn-new-collection" class="text-primary hover:text-primary/80" title="New Collection"><span class="material-symbols-outlined text-lg">create_new_folder</span></button>
                    </div>
                </div>
                <div id="collections-list" class="space-y-2">
                    <!-- Dynamic Collections -->
                </div>
            </div>

            <!-- History -->
            <div id="sidebar-history" class="sidebar-content hidden space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Recent Requests</h3>
                    <button id="btn-clear-history" class="text-red-500 hover:text-red-600" title="Clear History"><span class="material-symbols-outlined text-lg">delete</span></button>
                </div>
                <div id="history-list" class="space-y-1">
                    <!-- Dynamic History -->
                </div>
            </div>

            <!-- Environments -->
            <div id="sidebar-env" class="sidebar-content hidden space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Environments</h3>
                    <button id="btn-new-env" class="text-primary hover:text-primary/80" title="New Environment"><span class="material-symbols-outlined text-lg">add</span></button>
                </div>
                
                <div class="space-y-2">
                    <label class="block text-xs text-gray-500">Active Environment</label>
                    <select id="active-env-select" class="w-full rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-2 py-1 text-sm">
                        <option value="none">No Environment</option>
                    </select>
                </div>

                <div id="env-list" class="space-y-2 mt-4">
                    <!-- Dynamic Environments -->
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top Bar -->
        <div class="bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700 p-4">
            <div class="flex gap-2 mb-4">
                <div class="flex-1 flex gap-2">
                    <select id="req-method" class="w-28 rounded-lg border border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 px-3 py-2 font-bold text-sm focus:ring-2 focus:ring-primary">
                        <option value="GET" class="text-green-600">GET</option>
                        <option value="POST" class="text-blue-600">POST</option>
                        <option value="PUT" class="text-orange-600">PUT</option>
                        <option value="PATCH" class="text-yellow-600">PATCH</option>
                        <option value="DELETE" class="text-red-600">DELETE</option>
                        <option value="HEAD">HEAD</option>
                        <option value="OPTIONS">OPTIONS</option>
                    </select>
                    <input id="req-url" type="text" placeholder="Enter URL or {{variable}}" class="flex-1 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-4 py-2 text-sm font-mono focus:ring-2 focus:ring-primary" />
                </div>
                <button id="btn-send" class="px-6 py-2 bg-primary text-white font-bold rounded-lg hover:bg-primary/90 flex items-center gap-2 transition-all">
                    <span class="material-symbols-outlined">send</span> Send
                </button>
                <button id="btn-import" class="px-4 py-2 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 flex items-center gap-2">
                    <span class="material-symbols-outlined">input</span> Import
                </button>
                <button id="btn-save" class="px-4 py-2 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 flex items-center gap-2">
                    <span class="material-symbols-outlined">save</span> Save
                </button>
            </div>

            <!-- Request Tabs -->
            <div class="flex gap-6 border-b border-gray-200 dark:border-slate-700">
                <button class="req-tab-btn active pb-2 text-sm font-medium border-b-2 border-primary text-primary" data-tab="params">Params</button>
                <button class="req-tab-btn pb-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-primary" data-tab="auth">Auth</button>
                <button class="req-tab-btn pb-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-primary" data-tab="headers">Headers</button>
                <button class="req-tab-btn pb-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-primary" data-tab="body">Body</button>
                <button class="req-tab-btn pb-2 text-sm font-medium border-b-2 border-transparent text-green-600 hover:text-green-700" data-tab="pre-script">Pre-req Script</button>
                <button class="req-tab-btn pb-2 text-sm font-medium border-b-2 border-transparent text-purple-600 hover:text-purple-700" data-tab="post-script">Post-Script</button>
                <button class="req-tab-btn pb-2 text-sm font-medium border-b-2 border-transparent text-orange-600 hover:text-orange-700" data-tab="tests">Tests</button>
            </div>
        </div>

        <!-- Request Panels -->
        <div class="flex-1 overflow-y-auto bg-gray-50 dark:bg-slate-900 p-4">
            
            <!-- Params -->
            <div id="panel-params" class="req-panel space-y-4">
                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">Query Parameters</h3>
                <div id="params-list" class="space-y-2"></div>
                <button onclick="addPair('params-list')" class="text-xs text-primary font-medium hover:underline">+ Add Parameter</button>
            </div>

            <!-- Auth -->
            <div id="panel-auth" class="req-panel hidden space-y-4">
                <div class="max-w-md">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</label>
                    <select id="auth-type" class="w-full rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm mb-4">
                        <option value="none">No Auth</option>
                        <option value="bearer">Bearer Token</option>
                        <option value="basic">Basic Auth</option>
                        <option value="apikey">API Key</option>
                    </select>

                    <div id="auth-bearer" class="auth-method hidden space-y-2">
                        <input id="auth-bearer-token" type="text" placeholder="Token" class="w-full rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm">
                    </div>

                    <div id="auth-basic" class="auth-method hidden space-y-2">
                        <input id="auth-basic-user" type="text" placeholder="Username" class="w-full rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm">
                        <input id="auth-basic-pass" type="password" placeholder="Password" class="w-full rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm">
                    </div>

                    <div id="auth-apikey" class="auth-method hidden space-y-2">
                        <input id="auth-apikey-key" type="text" placeholder="Key" class="w-full rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm">
                        <input id="auth-apikey-val" type="text" placeholder="Value" class="w-full rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm">
                        <select id="auth-apikey-loc" class="w-full rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm">
                            <option value="header">Header</option>
                            <option value="query">Query Params</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Headers -->
            <div id="panel-headers" class="req-panel hidden space-y-4">
                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">Request Headers</h3>
                <div id="headers-list" class="space-y-2"></div>
                <button onclick="addPair('headers-list')" class="text-xs text-primary font-medium hover:underline">+ Add Header</button>
            </div>

            <!-- Body -->
            <div id="panel-body" class="req-panel hidden space-y-4">
                <div class="flex gap-4 mb-2">
                    <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="body-type" value="none" checked class="text-primary"> <span class="text-sm">None</span></label>
                    <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="body-type" value="json" class="text-primary"> <span class="text-sm">JSON</span></label>
                    <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="body-type" value="form" class="text-primary"> <span class="text-sm">Form Data</span></label>
                    <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="body-type" value="raw" class="text-primary"> <span class="text-sm">Raw</span></label>
                </div>

                <div id="body-editor-json" class="body-editor hidden h-64">
                    <textarea id="body-json-content" class="w-full h-full font-mono text-sm p-3 rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-primary" placeholder="{ &quot;key&quot;: &quot;value&quot; }"></textarea>
                </div>
                <div id="body-editor-raw" class="body-editor hidden h-64">
                    <textarea id="body-raw-content" class="w-full h-full font-mono text-sm p-3 rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-primary" placeholder="Raw content"></textarea>
                </div>
                <div id="body-editor-form" class="body-editor hidden space-y-2">
                    <div id="form-list" class="space-y-2"></div>
                    <button onclick="addPair('form-list')" class="text-xs text-primary font-medium hover:underline">+ Add Field</button>
                </div>
            </div>

            <!-- Pre-Script -->
            <div id="panel-pre-script" class="req-panel hidden h-full flex flex-col">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">Pre-request Script (JavaScript)</h3>
                    <span class="text-xs text-gray-500">Available: pm.environment, pm.variables</span>
                </div>
                <textarea id="script-pre" class="flex-1 w-full font-mono text-sm p-3 rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-primary" placeholder="// Example: pm.environment.set('timestamp', new Date().toISOString());"></textarea>
            </div>

            <!-- Post-Script -->
            <div id="panel-post-script" class="req-panel hidden h-full flex flex-col">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">Post-request Script (JavaScript)</h3>
                    <span class="text-xs text-gray-500">Available: pm.response, pm.environment</span>
                </div>
                <textarea id="script-post" class="flex-1 w-full font-mono text-sm p-3 rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-primary" placeholder="// Example: pm.environment.set('token', pm.response.json().token);"></textarea>
            </div>

            <!-- Tests -->
            <div id="panel-tests" class="req-panel hidden h-full flex flex-col">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">Tests (JavaScript)</h3>
                    <div class="flex gap-2">
                        <button onclick="insertSnippet('status-200')" class="text-xs bg-gray-200 dark:bg-slate-700 px-2 py-1 rounded hover:bg-gray-300">Status 200</button>
                        <button onclick="insertSnippet('json-check')" class="text-xs bg-gray-200 dark:bg-slate-700 px-2 py-1 rounded hover:bg-gray-300">Check JSON</button>
                    </div>
                </div>
                <textarea id="script-tests" class="flex-1 w-full font-mono text-sm p-3 rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-primary" placeholder="// Example: pm.test('Status is 200', function() { pm.response.to.have.status(200); });"></textarea>
            </div>

        </div>

        <!-- Response Area -->
        <div class="h-1/2 bg-white dark:bg-slate-800 border-t border-gray-200 dark:border-slate-700 flex flex-col">
            <div class="flex items-center justify-between px-4 py-2 border-b border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900">
                <div class="flex gap-4">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Status: <span id="res-status" class="font-bold text-gray-800 dark:text-gray-200">-</span></span>
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Time: <span id="res-time" class="font-bold text-gray-800 dark:text-gray-200">-</span></span>
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Size: <span id="res-size" class="font-bold text-gray-800 dark:text-gray-200">-</span></span>
                </div>
                <div class="flex gap-4">
                    <button class="res-tab-btn active text-xs font-bold uppercase tracking-wider text-primary border-b-2 border-primary" data-tab="pretty">Pretty</button>
                    <button class="res-tab-btn text-xs font-bold uppercase tracking-wider text-gray-500 border-b-2 border-transparent hover:text-primary" data-tab="raw">Raw</button>
                    <button class="res-tab-btn text-xs font-bold uppercase tracking-wider text-gray-500 border-b-2 border-transparent hover:text-primary" data-tab="preview">Preview</button>
                    <button class="res-tab-btn text-xs font-bold uppercase tracking-wider text-gray-500 border-b-2 border-transparent hover:text-primary" data-tab="visualize">Visualize</button>
                    <button class="res-tab-btn text-xs font-bold uppercase tracking-wider text-gray-500 border-b-2 border-transparent hover:text-primary" data-tab="headers">Headers</button>
                    <button class="res-tab-btn text-xs font-bold uppercase tracking-wider text-gray-500 border-b-2 border-transparent hover:text-primary" data-tab="tests">Tests <span id="test-badge" class="hidden ml-1 px-1 rounded-full bg-gray-200 text-[10px]">0/0</span></button>
                    <button class="res-tab-btn text-xs font-bold uppercase tracking-wider text-gray-500 border-b-2 border-transparent hover:text-primary" data-tab="console">Console</button>
                </div>
            </div>

            <div class="flex-1 overflow-auto p-4 relative">
                <!-- Pretty -->
                <div id="res-panel-pretty" class="res-panel h-full">
                    <pre class="h-full overflow-auto rounded bg-gray-50 dark:bg-slate-900 p-2"><code id="res-pretty-content" class="language-json text-xs font-mono"></code></pre>
                </div>
                <!-- Raw -->
                <div id="res-panel-raw" class="res-panel hidden h-full">
                    <textarea id="res-raw-content" readonly class="w-full h-full font-mono text-xs p-2 rounded bg-gray-50 dark:bg-slate-900 border-none resize-none focus:ring-0"></textarea>
                </div>
                <!-- Preview -->
                <div id="res-panel-preview" class="res-panel hidden h-full bg-white">
                    <iframe id="res-preview-frame" class="w-full h-full border-none"></iframe>
                </div>
                <!-- Visualize -->
                <div id="res-panel-visualize" class="res-panel hidden h-full bg-white overflow-auto p-4">
                    <div id="res-visualize-container"></div>
                </div>
                <!-- Headers -->
                <div id="res-panel-headers" class="res-panel hidden h-full">
                    <div id="res-headers-list" class="grid grid-cols-[auto_1fr] gap-x-4 gap-y-1 text-xs font-mono"></div>
                </div>
                <!-- Tests -->
                <div id="res-panel-tests" class="res-panel hidden h-full space-y-2">
                    <div id="test-results-list" class="space-y-1"></div>
                </div>
                <!-- Console -->
                <div id="res-panel-console" class="res-panel hidden h-full bg-black text-green-400 font-mono text-xs p-2 overflow-auto">
                    <div id="console-output"></div>
                </div>
                
                <!-- Loading Overlay -->
                <div id="loading-overlay" class="hidden absolute inset-0 bg-white/80 dark:bg-slate-900/80 flex items-center justify-center z-20">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<dialog id="modal-import" class="p-0 rounded-lg shadow-xl bg-white dark:bg-slate-800 w-full max-w-2xl backdrop:bg-black/50">
    <div class="p-4 border-b border-gray-200 dark:border-slate-700 flex justify-between items-center">
        <h3 class="font-bold text-lg dark:text-white">Import cURL</h3>
        <button onclick="document.getElementById('modal-import').close()" class="text-gray-500 hover:text-gray-700">✕</button>
    </div>
    <div class="p-4 space-y-4">
        <p class="text-sm text-gray-600 dark:text-gray-400">Paste your cURL command below to import it.</p>
        <textarea id="curl-input" class="w-full h-48 font-mono text-xs p-3 rounded border border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary" placeholder="curl -X POST https://api.example.com/data -H 'Content-Type: application/json' -d '{...}'"></textarea>
    </div>
    <div class="p-4 border-t border-gray-200 dark:border-slate-700 flex justify-end gap-2">
        <button onclick="document.getElementById('modal-import').close()" class="px-4 py-2 rounded text-gray-600 hover:bg-gray-100">Cancel</button>
        <button id="btn-process-import" class="px-4 py-2 rounded bg-primary text-white hover:bg-primary/90">Import</button>
    </div>
</dialog>

<dialog id="modal-coll-import" class="p-0 rounded-lg shadow-xl bg-white dark:bg-slate-800 w-full max-w-2xl backdrop:bg-black/50">
    <div class="p-4 border-b border-gray-200 dark:border-slate-700 flex justify-between items-center">
        <h3 class="font-bold text-lg dark:text-white">Import Collection</h3>
        <button onclick="document.getElementById('modal-coll-import').close()" class="text-gray-500 hover:text-gray-700">✕</button>
    </div>
    <div class="p-4 space-y-4">
        <p class="text-sm text-gray-600 dark:text-gray-400">Paste Postman, Insomnia, or Swagger JSON/YAML here.</p>
        <textarea id="coll-import-input" class="w-full h-48 font-mono text-xs p-3 rounded border border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary"></textarea>
    </div>
    <div class="p-4 border-t border-gray-200 dark:border-slate-700 flex justify-end gap-2">
        <button onclick="document.getElementById('modal-coll-import').close()" class="px-4 py-2 rounded text-gray-600 hover:bg-gray-100">Cancel</button>
        <button id="btn-process-coll-import" class="px-4 py-2 rounded bg-primary text-white hover:bg-primary/90">Import</button>
    </div>
</dialog>

<dialog id="modal-env" class="p-0 rounded-lg shadow-xl bg-white dark:bg-slate-800 w-full max-w-md backdrop:bg-black/50">
    <div class="p-4 border-b border-gray-200 dark:border-slate-700 flex justify-between items-center">
        <h3 class="font-bold text-lg dark:text-white">Manage Environment</h3>
        <button onclick="document.getElementById('modal-env').close()" class="text-gray-500 hover:text-gray-700">✕</button>
    </div>
    <div class="p-4 space-y-4">
        <input id="env-name" type="text" placeholder="Environment Name (e.g. Production)" class="w-full rounded border border-gray-300 dark:border-slate-600 px-3 py-2">
        <div class="space-y-2">
            <label class="text-sm font-bold">Variables</label>
            <div id="env-vars-list" class="space-y-2 max-h-60 overflow-auto"></div>
            <button onclick="addEnvVarPair()" class="text-xs text-primary hover:underline">+ Add Variable</button>
        </div>
    </div>
    <div class="p-4 border-t border-gray-200 dark:border-slate-700 flex justify-end gap-2">
        <button onclick="document.getElementById('modal-env').close()" class="px-4 py-2 rounded text-gray-600 hover:bg-gray-100">Cancel</button>
        <button id="btn-save-env" class="px-4 py-2 rounded bg-primary text-white hover:bg-primary/90">Save</button>
    </div>
</dialog>

<!-- Libraries -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-json.min.js"></script>

<script>
// --- State Management ---
const store = {
    collections: JSON.parse(localStorage.getItem('rc_collections') || '[]'),
    environments: JSON.parse(localStorage.getItem('rc_environments') || '[]'),
    activeEnvId: localStorage.getItem('rc_active_env') || 'none',
    history: JSON.parse(localStorage.getItem('rc_history') || '[]'),
    currentRequest: {
        method: 'GET',
        url: '',
        params: [],
        headers: [],
        auth: { type: 'none' },
        body: { type: 'none', content: '' },
        preScript: '',
        postScript: '',
        tests: ''
    }
};

// --- UI Helpers ---
function el(id) { return document.getElementById(id); }
function createInputPair(key = '', val = '', onDelete) {
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input type="text" placeholder="Key" value="${key}" class="flex-1 min-w-0 rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-2 py-1 text-sm key-input">
        <input type="text" placeholder="Value" value="${val}" class="flex-1 min-w-0 rounded border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-2 py-1 text-sm val-input">
        <button class="text-red-500 hover:bg-red-50 rounded px-2">✕</button>
    `;
    div.querySelector('button').onclick = () => div.remove();
    return div;
}

function addPair(containerId, key, val) {
    el(containerId).appendChild(createInputPair(key, val));
}

// --- Tabs Logic ---
document.querySelectorAll('.req-tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.req-tab-btn').forEach(b => b.classList.remove('active', 'border-primary', 'text-primary', 'border-transparent', 'text-gray-500'));
        document.querySelectorAll('.req-tab-btn').forEach(b => b.classList.add('border-transparent', 'text-gray-500'));
        btn.classList.remove('border-transparent', 'text-gray-500');
        btn.classList.add('active', 'border-primary', 'text-primary');
        
        document.querySelectorAll('.req-panel').forEach(p => p.classList.add('hidden'));
        el(`panel-${btn.dataset.tab}`).classList.remove('hidden');
    });
});

document.querySelectorAll('.res-tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.res-tab-btn').forEach(b => b.classList.remove('active', 'border-primary', 'text-primary', 'border-transparent', 'text-gray-500'));
        document.querySelectorAll('.res-tab-btn').forEach(b => b.classList.add('border-transparent', 'text-gray-500'));
        btn.classList.remove('border-transparent', 'text-gray-500');
        btn.classList.add('active', 'border-primary', 'text-primary');
        
        document.querySelectorAll('.res-panel').forEach(p => p.classList.add('hidden'));
        el(`res-panel-${btn.dataset.tab}`).classList.remove('hidden');
    });
});

document.querySelectorAll('.active-sidebar-tab').forEach(btn => { // Sidebar tabs
    btn.parentElement.querySelectorAll('button').forEach(b => {
        b.addEventListener('click', () => {
            b.parentElement.querySelectorAll('button').forEach(x => x.classList.remove('border-primary', 'text-primary'));
            b.classList.add('border-primary', 'text-primary');
            document.querySelectorAll('.sidebar-content').forEach(c => c.classList.add('hidden'));
            el(`sidebar-${b.dataset.tab}`).classList.remove('hidden');
        });
    });
});

// --- Auth & Body Toggles ---
el('auth-type').addEventListener('change', (e) => {
    document.querySelectorAll('.auth-method').forEach(m => m.classList.add('hidden'));
    if(e.target.value !== 'none') el(`auth-${e.target.value}`).classList.remove('hidden');
});

document.querySelectorAll('input[name="body-type"]').forEach(r => {
    r.addEventListener('change', (e) => {
        document.querySelectorAll('.body-editor').forEach(be => be.classList.add('hidden'));
        if(e.target.value !== 'none') el(`body-editor-${e.target.value}`).classList.remove('hidden');
    });
});

// --- Scripting Engine ---
const pm = {
    environment: {
        get: (key) => {
            const env = store.environments.find(e => e.id === store.activeEnvId);
            return env ? (env.variables[key] || '') : '';
        },
        set: (key, val) => {
            const env = store.environments.find(e => e.id === store.activeEnvId);
            if(env) {
                env.variables[key] = val;
                saveStore();
                renderEnvs(); // Update UI
            }
        }
    },
    variables: {
        get: (key) => pm.environment.get(key)
    },
    test: (name, callback) => {
        try {
            callback();
            logTestResult(name, true);
        } catch (e) {
            logTestResult(name, false, e.message);
        }
    },
    visualizer: {
        set: (template, data) => {
            // Simple visualizer implementation
            const container = el('res-visualize-container');
            // Basic template replacement
            let html = template;
            if (data) {
                Object.entries(data).forEach(([k, v]) => {
                    html = html.replace(new RegExp(`{{${k}}}`, 'g'), v);
                });
            }
            container.innerHTML = html;
        }
    },
    expect: (val) => ({
        to: {
            eql: (expected) => { if(val != expected) throw new Error(`Expected ${val} to equal ${expected}`); },
            have: {
                status: (code) => { if(val.status !== code) throw new Error(`Expected status ${code} but got ${val.status}`); }
            }
        }
    }),
    response: null // Will be set after request
};

function logTestResult(name, passed, error = '') {
    const div = document.createElement('div');
    div.className = `flex items-center gap-2 text-xs ${passed ? 'text-green-600' : 'text-red-600'}`;
    div.innerHTML = `
        <span class="material-symbols-outlined text-sm">${passed ? 'check_circle' : 'cancel'}</span>
        <span class="font-bold">${name}</span>
        ${error ? `<span class="text-gray-500">- ${error}</span>` : ''}
    `;
    el('test-results-list').appendChild(div);
    
    // Update badge
    const badge = el('test-badge');
    badge.classList.remove('hidden');
    const total = el('test-results-list').children.length;
    const passCount = el('test-results-list').querySelectorAll('.text-green-600').length;
    badge.textContent = `${passCount}/${total}`;
    badge.className = `ml-1 px-1 rounded-full text-[10px] ${passCount === total ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`;
}

function consoleLog(...args) {
    const div = document.createElement('div');
    div.textContent = '> ' + args.map(a => typeof a === 'object' ? JSON.stringify(a) : a).join(' ');
    el('console-output').appendChild(div);
}

// --- Request Execution ---
el('btn-send').addEventListener('click', async () => {
    el('loading-overlay').classList.remove('hidden');
    el('test-results-list').innerHTML = '';
    el('console-output').innerHTML = '';
    el('test-badge').classList.add('hidden');
    el('res-visualize-container').innerHTML = '';
    
    try {
        // 1. Prepare Data
        let url = el('req-url').value;
        const method = el('req-method').value;
        const headers = {};
        const params = {};
        
        // Collect Headers
        el('headers-list').querySelectorAll('div').forEach(d => {
            const k = d.querySelector('.key-input').value;
            const v = d.querySelector('.val-input').value;
            if(k) headers[k] = v;
        });
        
        // Collect Params
        el('params-list').querySelectorAll('div').forEach(d => {
            const k = d.querySelector('.key-input').value;
            const v = d.querySelector('.val-input').value;
            if(k) params[k] = v;
        });

        // 2. Run Pre-request Script
        const preScript = el('script-pre').value;
        if(preScript) {
            try {
                new Function('pm', 'console', preScript)(pm, { log: consoleLog });
            } catch(e) {
                consoleLog('Pre-request Script Error:', e.message);
            }
        }

        // 3. Variable Substitution
        const substitute = (str) => {
            return str.replace(/\{\{(.+?)\}\}/g, (_, key) => pm.environment.get(key) || `{{${key}}}`);
        };
        url = substitute(url);
        Object.keys(headers).forEach(k => headers[k] = substitute(headers[k]));
        Object.keys(params).forEach(k => params[k] = substitute(params[k]));

        // 4. Auth
        const authType = el('auth-type').value;
        if(authType === 'bearer') {
            headers['Authorization'] = `Bearer ${substitute(el('auth-bearer-token').value)}`;
        } else if(authType === 'basic') {
            const u = substitute(el('auth-basic-user').value);
            const p = substitute(el('auth-basic-pass').value);
            headers['Authorization'] = 'Basic ' + btoa(`${u}:${p}`);
        } else if(authType === 'apikey') {
            const k = substitute(el('auth-apikey-key').value);
            const v = substitute(el('auth-apikey-val').value);
            if(el('auth-apikey-loc').value === 'header') headers[k] = v;
            else params[k] = v;
        }

        // 5. Body
        let data = null;
        const bodyType = document.querySelector('input[name="body-type"]:checked').value;
        if(bodyType === 'json') {
            try {
                data = JSON.parse(substitute(el('body-json-content').value));
                headers['Content-Type'] = 'application/json';
            } catch(e) { consoleLog('JSON Parse Error'); }
        } else if(bodyType === 'raw') {
            data = substitute(el('body-raw-content').value);
        } else if(bodyType === 'form') {
            data = new FormData();
            el('form-list').querySelectorAll('div').forEach(d => {
                const k = d.querySelector('.key-input').value;
                const v = d.querySelector('.val-input').value;
                if(k) data.append(substitute(k), substitute(v));
            });
        }

        // 6. Send Request
        const startTime = Date.now();
        const res = await axios({ url, method, headers, params, data, validateStatus: () => true });
        const time = Date.now() - startTime;

        // 7. Update UI
        el('res-status').textContent = `${res.status} ${res.statusText}`;
        el('res-status').className = `font-bold ${res.status < 300 ? 'text-green-600' : 'text-red-600'}`;
        el('res-time').textContent = `${time}ms`;
        el('res-size').textContent = `${JSON.stringify(res.data).length} B`;
        
        // Pretty Print
        const contentType = res.headers['content-type'] || '';
        let prettyContent = '';
        if (contentType.includes('application/json') || typeof res.data === 'object') {
            prettyContent = JSON.stringify(res.data, null, 2);
            el('res-pretty-content').className = 'language-json';
        } else if (contentType.includes('text/html') || contentType.includes('application/xml')) {
             prettyContent = res.data; // Prism will handle HTML/XML
             el('res-pretty-content').className = 'language-html';
        } else {
            prettyContent = res.data;
            el('res-pretty-content').className = 'language-none';
        }
        el('res-pretty-content').textContent = prettyContent;
        Prism.highlightElement(el('res-pretty-content'));

        // Raw
        el('res-raw-content').value = typeof res.data === 'object' ? JSON.stringify(res.data) : res.data;

        // Preview (HTML)
        if (contentType.includes('text/html')) {
            const doc = el('res-preview-frame').contentWindow.document;
            doc.open();
            doc.write(res.data);
            doc.close();
        } else {
            const doc = el('res-preview-frame').contentWindow.document;
            doc.body.innerHTML = '<p style="font-family:sans-serif;color:#666;text-align:center;margin-top:20px">Preview not available for this content type</p>';
        }

        el('res-headers-list').innerHTML = '';
        Object.entries(res.headers).forEach(([k, v]) => {
            el('res-headers-list').innerHTML += `<div class="text-gray-500 text-right pr-2">${k}:</div><div class="text-gray-800 dark:text-gray-300 break-all">${v}</div>`;
        });

        // 8. Prepare PM Response
        pm.response = { 
            json: () => res.data, 
            status: res.status, 
            headers: res.headers,
            to: { have: { status: (c) => { if(res.status !== c) throw new Error(`Expected ${c} got ${res.status}`); } } }
        };

        // 9. Run Post-Script
        const postScript = el('script-post').value;
        if(postScript) {
            try {
                new Function('pm', 'console', postScript)(pm, { log: consoleLog });
            } catch(e) {
                consoleLog('Post-Script Error:', e.message);
            }
        }
        
        // 10. Run Tests
        const testScript = el('script-tests').value;
        if(testScript) {
            try {
                new Function('pm', 'console', testScript)(pm, { log: consoleLog });
            } catch(e) {
                consoleLog('Test Script Error:', e.message);
            }
        }

        // 11. History
        addToHistory({ method, url, time: Date.now() });

    } catch (err) {
        el('res-pretty-content').textContent = 'Error: ' + err.message;
        consoleLog('Request Failed', err);
    } finally {
        el('loading-overlay').classList.add('hidden');
    }
});

// --- History & Collections ---
function addToHistory(item) {
    store.history.unshift(item);
    if(store.history.length > 20) store.history.pop();
    saveStore();
    renderHistory();
}

function renderHistory() {
    el('history-list').innerHTML = store.history.map(h => `
        <div class="p-2 rounded hover:bg-gray-100 dark:hover:bg-slate-700 cursor-pointer text-xs" onclick="loadRequest('${h.url}', '${h.method}')">
            <span class="font-bold ${h.method === 'GET' ? 'text-green-600' : 'text-blue-600'}">${h.method}</span>
            <span class="truncate ml-2 text-gray-600 dark:text-gray-400">${h.url}</span>
        </div>
    `).join('');
}

function saveStore() {
    localStorage.setItem('rc_collections', JSON.stringify(store.collections));
    localStorage.setItem('rc_environments', JSON.stringify(store.environments));
    localStorage.setItem('rc_history', JSON.stringify(store.history));
    localStorage.setItem('rc_active_env', store.activeEnvId);
}

// --- Collections Import/Export ---
el('btn-export-coll').addEventListener('click', () => {
    const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(store.collections, null, 2));
    const downloadAnchorNode = document.createElement('a');
    downloadAnchorNode.setAttribute("href", dataStr);
    downloadAnchorNode.setAttribute("download", "collections.json");
    document.body.appendChild(downloadAnchorNode);
    downloadAnchorNode.click();
    downloadAnchorNode.remove();
});

el('btn-import-coll').addEventListener('click', () => {
    el('coll-import-input').value = '';
    el('modal-coll-import').showModal();
});

el('btn-process-coll-import').addEventListener('click', () => {
    const content = el('coll-import-input').value.trim();
    if(!content) return;
    try {
        const json = JSON.parse(content);
        let newColls = [];
        
        // Detect Format
        if (json.info && json.item) {
            // Postman v2.1
            newColls = parsePostman(json);
        } else if (json.resources && json._type === 'export') {
            // Insomnia
            newColls = parseInsomnia(json);
        } else if (Array.isArray(json)) {
            // Internal
            newColls = json;
        } else {
            throw new Error('Unknown format');
        }
        
        store.collections = [...store.collections, ...newColls];
        saveStore();
        renderCollections();
        el('modal-coll-import').close();
        alert('Import successful!');
    } catch(e) {
        alert('Import failed: ' + e.message);
    }
});

function parsePostman(json) {
    // Simplified Postman Parser
    return [{
        id: Date.now().toString(),
        name: json.info.name,
        requests: json.item.map(item => ({
            name: item.name,
            method: item.request.method,
            url: item.request.url.raw || item.request.url
        }))
    }];
}

function parseInsomnia(json) {
    // Simplified Insomnia Parser
    const reqs = json.resources.filter(r => r._type === 'request').map(r => ({
        name: r.name,
        method: r.method,
        url: r.url
    }));
    return [{
        id: Date.now().toString(),
        name: 'Insomnia Import',
        requests: reqs
    }];
}

function renderCollections() {
    el('collections-list').innerHTML = store.collections.map(c => `
        <div class="p-2 rounded bg-gray-50 dark:bg-slate-700 text-xs">
            <div class="font-bold mb-1">${c.name}</div>
            <div class="pl-2 space-y-1">
                ${c.requests ? c.requests.map(r => `
                    <div class="cursor-pointer hover:text-primary truncate" onclick="loadRequest('${r.url}', '${r.method}')">
                        <span class="font-mono text-[10px] ${r.method === 'GET' ? 'text-green-600' : 'text-blue-600'}">${r.method}</span> ${r.name || r.url}
                    </div>
                `).join('') : '<span class="text-gray-400">Empty</span>'}
            </div>
        </div>
    `).join('');
}

// --- Environments ---
function renderEnvs() {
    const select = el('active-env-select');
    select.innerHTML = '<option value="none">No Environment</option>';
    el('env-list').innerHTML = '';
    
    store.environments.forEach(env => {
        // Dropdown
        const opt = document.createElement('option');
        opt.value = env.id;
        opt.textContent = env.name;
        if(env.id === store.activeEnvId) opt.selected = true;
        select.appendChild(opt);

        // List
        const div = document.createElement('div');
        div.className = 'flex justify-between items-center p-2 rounded bg-gray-50 dark:bg-slate-700 text-xs';
        div.innerHTML = `<span>${env.name}</span> <button onclick="editEnv('${env.id}')" class="text-primary hover:underline">Edit</button>`;
        el('env-list').appendChild(div);
    });
}

el('btn-new-env').addEventListener('click', () => {
    el('env-name').value = '';
    el('env-vars-list').innerHTML = '';
    el('modal-env').dataset.mode = 'new';
    el('modal-env').showModal();
});

el('active-env-select').addEventListener('change', (e) => {
    store.activeEnvId = e.target.value;
    saveStore();
});

window.editEnv = (id) => {
    const env = store.environments.find(e => e.id === id);
    if(!env) return;
    el('env-name').value = env.name;
    el('env-vars-list').innerHTML = '';
    Object.entries(env.variables).forEach(([k, v]) => {
        el('env-vars-list').appendChild(createInputPair(k, v));
    });
    el('modal-env').dataset.mode = 'edit';
    el('modal-env').dataset.id = id;
    el('modal-env').showModal();
};

window.addEnvVarPair = () => {
    el('env-vars-list').appendChild(createInputPair());
};

el('btn-save-env').addEventListener('click', () => {
    const name = el('env-name').value;
    if(!name) return;
    
    const vars = {};
    el('env-vars-list').querySelectorAll('div').forEach(d => {
        const k = d.querySelector('.key-input').value;
        const v = d.querySelector('.val-input').value;
        if(k) vars[k] = v;
    });

    if(el('modal-env').dataset.mode === 'new') {
        store.environments.push({ id: Date.now().toString(), name, variables: vars });
    } else {
        const env = store.environments.find(e => e.id === el('modal-env').dataset.id);
        env.name = name;
        env.variables = vars;
    }
    
    saveStore();
    renderEnvs();
    el('modal-env').close();
});

// --- Snippets ---
window.insertSnippet = (type) => {
    const textarea = el('script-tests');
    let code = '';
    if(type === 'status-200') {
        code = `\npm.test("Status is 200", function () {\n    pm.response.to.have.status(200);\n});`;
    } else if(type === 'json-check') {
        code = `\npm.test("Check JSON", function () {\n    var jsonData = pm.response.json();\n    if(!jsonData) throw new Error("No JSON");\n});`;
    }
    textarea.value += code;
};

// --- cURL Import ---
el('btn-import').addEventListener('click', () => {
    el('curl-input').value = '';
    el('modal-import').showModal();
});

el('btn-process-import').addEventListener('click', () => {
    const curl = el('curl-input').value.trim();
    if(!curl) return;
    try {
        parseCurl(curl);
        el('modal-import').close();
    } catch(e) {
        alert('Error parsing cURL: ' + e.message);
    }
});

function tokenize(str) {
    const tokens = [];
    let current = '';
    let quote = null;
    let escaped = false;
    for(let i=0; i<str.length; i++) {
        const char = str[i];
        if(escaped) { current += char; escaped = false; continue; }
        if(char === '\\') { escaped = true; continue; }
        if(quote) {
            if(char === quote) quote = null;
            else current += char;
        } else {
            if(char === '"' || char === "'") quote = char;
            else if(char === ' ' || char === '\n') {
                if(current) tokens.push(current);
                current = '';
            } else current += char;
        }
    }
    if(current) tokens.push(current);
    return tokens;
}

function parseCurl(curl) {
    const tokens = tokenize(curl);
    let method = 'GET';
    let url = '';
    const headers = {};
    let body = '';
    let auth = null;

    for(let i=0; i<tokens.length; i++) {
        const t = tokens[i];
        if(t === 'curl') continue;
        
        if(t.startsWith('-')) {
            if(t === '-X' || t === '--request') method = tokens[++i];
            else if(t === '-H' || t === '--header') {
                const h = tokens[++i];
                const [k, v] = h.split(/:\s(.+)/);
                if(k) headers[k] = v || '';
            }
            else if(t === '-d' || t === '--data' || t === '--data-raw') {
                body = tokens[++i];
                if(method === 'GET') method = 'POST';
            }
            else if(t === '-u' || t === '--user') auth = tokens[++i];
        } else if(!url) {
            url = t;
        }
    }
    
    el('req-method').value = method;
    el('req-url').value = url;
    el('headers-list').innerHTML = '';
    Object.entries(headers).forEach(([k, v]) => addPair('headers-list', k, v));
    
    if(auth) {
        el('auth-type').value = 'basic';
        el('auth-type').dispatchEvent(new Event('change'));
        const [u, p] = auth.split(':');
        el('auth-basic-user').value = u;
        el('auth-basic-pass').value = p || '';
    }

    if(body) {
        try {
            const json = JSON.parse(body);
            document.querySelector('input[name="body-type"][value="json"]').click();
            el('body-json-content').value = JSON.stringify(json, null, 2);
        } catch(e) {
            document.querySelector('input[name="body-type"][value="raw"]').click();
            el('body-raw-content').value = body;
        }
    }
}

// Init
renderHistory();
renderEnvs();
renderCollections();
</script>
