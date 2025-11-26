<?php
/**
 * REST Client / API Tester
 * Full-featured tool like Postman for testing APIs
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">api</span>
            REST Client
        </h1>
        <div class="flex items-center gap-2">
            <button id="toggle-mode" class="px-3 py-1 text-xs font-medium rounded bg-gray-100 dark:bg-gray-700 hover:bg-primary hover:text-white dark:hover:bg-primary transition-colors">
                Advanced Mode
            </button>
            <button id="clear-history" class="px-3 py-1 text-xs font-medium rounded bg-gray-100 dark:bg-gray-700 hover:bg-red-500 hover:text-white transition-colors">
                Clear History
            </button>
        </div>
    </div>

    <div class="flex flex-1 overflow-hidden">
        <!-- Request Builder (Left) -->
        <div class="w-1/2 border-r border-gray-200 dark:border-slate-700 flex flex-col bg-white dark:bg-slate-900">
            <div class="flex-1 overflow-auto p-4 space-y-4">
                <!-- URL Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Request URL
                    </label>
                    <div class="flex gap-2">
                        <select id="http-method" class="rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary">
                            <option>GET</option>
                            <option>POST</option>
                            <option>PUT</option>
                            <option>PATCH</option>
                            <option>DELETE</option>
                            <option>HEAD</option>
                            <option>OPTIONS</option>
                        </select>
                        <input id="request-url" type="url" placeholder="https://jsonplaceholder.typicode.com/posts/1" value="https://jsonplaceholder.typicode.com/posts/1"
                            class="flex-1 rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" />
                        <button id="send-request" class="px-4 py-2 rounded-lg bg-primary text-white font-medium hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary">
                            Send
                        </button>
                    </div>
                </div>

                <!-- Advanced Options (Hidden by default) -->
                <div id="advanced-options" class="hidden space-y-4">
                    <!-- Tabs -->
                    <div class="border-b border-gray-200 dark:border-slate-700">
                        <nav class="flex gap-4">
                            <button class="tab-btn active px-3 py-2 text-sm font-medium border-b-2 border-primary text-primary" data-tab="query">Query Params</button>
                            <button class="tab-btn px-3 py-2 text-sm font-medium border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-primary" data-tab="headers">Headers</button>
                            <button class="tab-btn px-3 py-2 text-sm font-medium border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-primary" data-tab="auth">Auth</button>
                            <button class="tab-btn px-3 py-2 text-sm font-medium border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-primary" data-tab="body">Body</button>
                        </nav>
                    </div>

                    <!-- Query Params -->
                    <div id="tab-query" class="tab-content">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                <span>Add query parameters to your request</span>
                            </div>
                            <div id="query-params-list" class="space-y-2">
                                <!-- Dynamic query params -->
                            </div>
                            <button id="add-query-param" class="text-sm text-primary hover:underline">+ Add parameter</button>
                        </div>
                    </div>

                    <!-- Headers -->
                    <div id="tab-headers" class="tab-content hidden">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                <span>Add custom headers</span>
                            </div>
                            <div id="headers-list" class="space-y-2">
                                <!-- Dynamic headers -->
                            </div>
                            <button id="add-header" class="text-sm text-primary hover:underline">+ Add header</button>
                        </div>
                    </div>

                    <!-- Auth -->
                    <div id="tab-auth" class="tab-content hidden">
                        <div class="space-y-3">
                            <select id="auth-type" class="rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm w-full">
                                <option value="none">No Auth</option>
                                <option value="bearer">Bearer Token</option>
                                <option value="basic">Basic Auth</option>
                                <option value="apikey">API Key</option>
                            </select>
                            
                            <!-- Bearer Token -->
                            <div id="auth-bearer" class="hidden">
                                <label class="block text-sm font-medium mb-1">Token</label>
                                <input id="bearer-token" type="text" placeholder="Enter bearer token" class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm" />
                            </div>

                            <!-- Basic Auth -->
                            <div id="auth-basic" class="hidden space-y-2">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Username</label>
                                    <input id="basic-username" type="text" class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Password</label>
                                    <input id="basic-password" type="password" class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm" />
                                </div>
                            </div>

                            <!-- API Key -->
                            <div id="auth-apikey" class="hidden space-y-2">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Key</label>
                                    <input id="apikey-key" type="text" placeholder="X-API-Key" class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Value</label>
                                    <input id="apikey-value" type="text" class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Add to</label>
                                    <select id="apikey-location" class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm">
                                        <option value="header">Header</option>
                                        <option value="query">Query Params</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Body -->
                    <div id="tab-body" class="tab-content hidden">
                        <div class="space-y-2">
                            <div>
                                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Body Type</label>
                                <select id="body-type" class="rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm w-full">
                                    <option value="none">None</option>
                                    <option value="json">JSON</option>
                                    <option value="text">Raw Text</option>
                                    <option value="formdata">Form Data</option>
                                </select>
                            </div>
                            
                            <div id="body-json" class="hidden">
                                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Request Body (JSON)</label>
                                <textarea id="body-json-text" rows="10" placeholder='{"key": "value"}' class="w-full font-mono rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm"></textarea>
                            </div>
                            
                            <div id="body-text" class="hidden">
                                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Request Body (Text)</label>
                                <textarea id="body-text-text" rows="10" placeholder="Enter raw text..." class="w-full font-mono rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm"></textarea>
                            </div>

                            <div id="body-formdata" class="hidden">
                                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Form Data Fields</label>
                                <div id="formdata-list" class="space-y-2"></div>
                                <button id="add-formdata" class="text-sm text-primary hover:underline">+ Add field</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Request History -->
                <div>
                    <h3 class="text-sm font-medium mb-2">Recent Requests</h3>
                    <div id="history-list" class="space-y-1 max-h-32 overflow-auto">
                        <p class="text-xs text-gray-500 dark:text-gray-400">No history yet</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Response Viewer (Right) -->
        <div class="w-1/2 flex flex-col bg-gray-50 dark:bg-slate-800">
            <div class="flex-1 overflow-auto p-4">
                <div id="response-container" class="hidden space-y-4">
                    <!-- Status -->
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Status:</span>
                            <span id="response-status" class="px-2 py-1 text-xs font-medium rounded"></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Time:</span>
                            <span id="response-time" class="text-xs font-mono"></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Size:</span>
                            <span id="response-size" class="text-xs font-mono"></span>
                        </div>
                    </div>

                    <!-- Response Tabs -->
                    <div class="border-b border-gray-200 dark:border-slate-700">
                        <nav class="flex gap-4">
                            <button class="resp-tab-btn active px-3 py-2 text-sm font-medium border-b-2 border-primary text-primary" data-tab="body">Body</button>
                            <button class="resp-tab-btn px-3 py-2 text-sm font-medium border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-primary" data-tab="headers">Headers</button>
                            <button class="resp-tab-btn px-3 py-2 text-sm font-medium border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-primary" data-tab="raw">Raw</button>
                        </nav>
                    </div>

                    <!-- Body -->
                    <div id="resp-tab-body" class="resp-tab-content">
                        <pre id="response-body" class="bg-white dark:bg-slate-900 rounded-lg p-4 text-xs font-mono overflow-auto max-h-96"></pre>
                    </div>

                    <!-- Headers -->
                    <div id="resp-tab-headers" class="resp-tab-content hidden">
                        <pre id="response-headers" class="bg-white dark:bg-slate-900 rounded-lg p-4 text-xs font-mono overflow-auto max-h-96"></pre>
                    </div>

                    <!-- Raw -->
                    <div id="resp-tab-raw" class="resp-tab-content hidden">
                        <pre id="response-raw" class="bg-white dark:bg-slate-900 rounded-lg p-4 text-xs font-mono overflow-auto max-h-96"></pre>
                    </div>
                </div>

                <!-- Loading State -->
                <div id="loading-state" class="hidden flex items-center justify-center h-full">
                    <div class="text-center">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                        <p class="mt-2 text-sm text-gray-500">Sending request...</p>
                    </div>
                </div>

                <!-- Empty State -->
                <div id="empty-state" class="flex items-center justify-center h-full">
                    <div class="text-center text-gray-500 dark:text-gray-400">
                        <span class="material-symbols-outlined text-6xl mb-2">http</span>
                        <p class="text-sm">Send a request to see the response</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Axios for HTTP requests -->
<script src="https://cdn.jsdelivr.net/npm/axios@1.6.2/dist/axios.min.js"></script>

<script>
// State
let isAdvancedMode = false;
let requestHistory = JSON.parse(localStorage.getItem('restClientHistory') || '[]');

// Elements
const toggleModeBtn = document.getElementById('toggle-mode');
const advancedOptions = document.getElementById('advanced-options');
const sendBtn = document.getElementById('send-request');
const methodSelect = document.getElementById('http-method');
const urlInput = document.getElementById('request-url');
const authTypeSelect = document.getElementById('auth-type');
const bodyTypeSelect = document.getElementById('body-type');

const responseContainer = document.getElementById('response-container');
const loadingState = document.getElementById('loading-state');
const emptyState = document.getElementById('empty-state');

// Toggle Mode
toggleModeBtn.addEventListener('click', () => {
    isAdvancedMode = !isAdvancedMode;
    advancedOptions.classList.toggle('hidden', !isAdvancedMode);
    toggleModeBtn.textContent = isAdvancedMode ? 'Simple Mode' : 'Advanced Mode';
});

// Tab Switching
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const tab = btn.dataset.tab;
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('active', 'border-primary', 'text-primary');
            b.classList.add('border-transparent', 'text-gray-600', 'dark:text-gray-400');
        });
        btn.classList.add('active', 'border-primary', 'text-primary');
        btn.classList.remove('border-transparent', 'text-gray-600', 'dark:text-gray-400');
        
        document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
        document.getElementById(`tab-${tab}`).classList.remove('hidden');
    });
});

// Response Tab Switching
document.querySelectorAll('.resp-tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const tab = btn.dataset.tab;
        document.querySelectorAll('.resp-tab-btn').forEach(b => {
            b.classList.remove('active', 'border-primary', 'text-primary');
            b.classList.add('border-transparent', 'text-gray-600', 'dark:text-gray-400');
        });
        btn.classList.add('active', 'border-primary', 'text-primary');
        btn.classList.remove('border-transparent', 'text-gray-600', 'dark:text-gray-400');
        
        document.querySelectorAll('.resp-tab-content').forEach(c => c.classList.add('hidden'));
        document.getElementById(`resp-tab-${tab}`).classList.remove('hidden');
    });
});

// Auth Type Switching
authTypeSelect.addEventListener('change', (e) => {
    document.querySelectorAll('[id^="auth-"]').forEach(el => {
        if (el.id !== 'auth-type') el.classList.add('hidden');
    });
    if (e.target.value !== 'none') {
        document.getElementById(`auth-${e.target.value}`).classList.remove('hidden');
    }
});

// Body Type Switching
bodyTypeSelect.addEventListener('change', (e) => {
    document.querySelectorAll('[id^="body-"]').forEach(el => {
        if (el.id !== 'body-type') el.classList.add('hidden');
    });
    if (e.target.value !== 'none') {
        document.getElementById(`body-${e.target.value}`).classList.remove('hidden');
    }
});

// Dynamic Key-Value Pairs
function createKeyValuePair(containerId, key = '', value = '') {
    const container = document.getElementById(containerId);
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input type="text" placeholder="Key" value="${key}" class="flex-1 rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-2 py-1 text-sm" />
        <input type="text" placeholder="Value" value="${value}" class="flex-1 rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-2 py-1 text-sm" />
        <button class="px-2 py-1 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded" onclick="this.parentElement.remove()">✕</button>
    `;
    container.appendChild(div);
}

document.getElementById('add-query-param').addEventListener('click', () => createKeyValuePair('query-params-list'));
document.getElementById('add-header').addEventListener('click', () => createKeyValuePair('headers-list'));
document.getElementById('add-formdata').addEventListener('click', () => createKeyValuePair('formdata-list'));

// Send Request
sendBtn.addEventListener('click', async () => {
    const method = methodSelect.value;
    let url = urlInput.value.trim();
    
    if (!url) {
        alert('Please enter a URL');
        return;
    }

    // Show loading
    emptyState.classList.add('hidden');
    responseContainer.classList.add('hidden');
    loadingState.classList.remove('hidden');

    const startTime = Date.now();

    try {
        // Build request config
        const config = {
            method,
            url,
            headers: {}
        };

        // Add query params
        const queryParams = {};
        document.querySelectorAll('#query-params-list > div').forEach(div => {
            const inputs = div.querySelectorAll('input');
            const key = inputs[0].value.trim();
            const value = inputs[1].value.trim();
            if (key) queryParams[key] = value;
        });
        if (Object.keys(queryParams).length > 0) {
            config.params = queryParams;
        }

        // Add headers
        document.querySelectorAll('#headers-list > div').forEach(div => {
            const inputs = div.querySelectorAll('input');
            const key = inputs[0].value.trim();
            const value = inputs[1].value.trim();
            if (key) config.headers[key] = value;
        });

        // Add auth
        const authType = authTypeSelect.value;
        if (authType === 'bearer') {
            const token = document.getElementById('bearer-token').value.trim();
            if (token) config.headers['Authorization'] = `Bearer ${token}`;
        } else if (authType === 'basic') {
            const username = document.getElementById('basic-username').value.trim();
            const password = document.getElementById('basic-password').value.trim();
            if (username || password) {
                config.headers['Authorization'] = 'Basic ' + btoa(`${username}:${password}`);
            }
        } else if (authType === 'apikey') {
            const key = document.getElementById('apikey-key').value.trim();
            const value = document.getElementById('apikey-value').value.trim();
            const location = document.getElementById('apikey-location').value;
            if (key && value) {
                if (location === 'header') {
                    config.headers[key] = value;
                } else {
                    if (!config.params) config.params = {};
                    config.params[key] = value;
                }
            }
        }

        // Add body
        const bodyType = bodyTypeSelect.value;
        if (bodyType === 'json') {
            const jsonText = document.getElementById('body-json-text').value.trim();
            if (jsonText) {
                try {
                    config.data = JSON.parse(jsonText);
                    config.headers['Content-Type'] = 'application/json';
                } catch (e) {
                    alert('Invalid JSON: ' + e.message);
                    loadingState.classList.add('hidden');
                    emptyState.classList.remove('hidden');
                    return;
                }
            }
        } else if (bodyType === 'text') {
            config.data = document.getElementById('body-text-text').value;
            config.headers['Content-Type'] = 'text/plain';
        } else if (bodyType === 'formdata') {
            const formData = new FormData();
            document.querySelectorAll('#formdata-list > div').forEach(div => {
                const inputs = div.querySelectorAll('input');
                const key = inputs[0].value.trim();
                const value = inputs[1].value.trim();
                if (key) formData.append(key, value);
            });
            config.data = formData;
        }

        // Make request
        const response = await axios(config);
        const duration = Date.now() - startTime;

        // Display response
        displayResponse(response, duration);

        // Add to history
        addToHistory(method, url);

    } catch (error) {
        const duration = Date.now() - startTime;
        if (error.response) {
            displayResponse(error.response, duration);
        } else {
            loadingState.classList.add('hidden');
            responseContainer.classList.remove('hidden');
            document.getElementById('response-status').textContent = 'Error';
            document.getElementById('response-status').className = 'px-2 py-1 text-xs font-medium rounded bg-red-100 text-red-700';
            document.getElementById('response-body').textContent = error.message;
        }
    }
});

function displayResponse(response, duration) {
    loadingState.classList.add('hidden');
    responseContainer.classList.remove('hidden');

    // Status
    const status = response.status;
    const statusEl = document.getElementById('response-status');
    statusEl.textContent = `${status} ${response.statusText || ''}`;
    
    if (status >= 200 && status < 300) {
        statusEl.className = 'px-2 py-1 text-xs font-medium rounded bg-green-100 text-green-700';
    } else if (status >= 400) {
        statusEl.className = 'px-2 py-1 text-xs font-medium rounded bg-red-100 text-red-700';
    } else {
        statusEl.className = 'px-2 py-1 text-xs font-medium rounded bg-yellow-100 text-yellow-700';
    }

    // Time & Size
    document.getElementById('response-time').textContent = `${duration}ms`;
    const size = JSON.stringify(response.data).length;
    document.getElementById('response-size').textContent = size > 1024 ? `${(size/1024).toFixed(2)} KB` : `${size} B`;

    // Body
    try {
        document.getElementById('response-body').textContent = JSON.stringify(response.data, null, 2);
    } catch (e) {
        document.getElementById('response-body').textContent = response.data;
    }

    // Headers
    document.getElementById('response-headers').textContent = JSON.stringify(response.headers, null, 2);

    // Raw
    document.getElementById('response-raw').textContent = JSON.stringify({
        status: response.status,
        statusText: response.statusText,
        headers: response.headers,
        data: response.data
    }, null, 2);
}

function addToHistory(method, url) {
    const entry = { method, url, timestamp: Date.now() };
    requestHistory.unshift(entry);
    requestHistory = requestHistory.slice(0, 20); // Keep last 20
    localStorage.setItem('restClientHistory', JSON.stringify(requestHistory));
    renderHistory();
}

function renderHistory() {
    const list = document.getElementById('history-list');
    if (requestHistory.length === 0) {
        list.innerHTML = '<p class="text-xs text-gray-500 dark:text-gray-400">No history yet</p>';
        return;
    }
    
    list.innerHTML = requestHistory.map(entry => `
        <div class="flex items-center gap-2 p-2 rounded hover:bg-gray-100 dark:hover:bg-slate-800 cursor-pointer text-xs group" onclick='loadFromHistory(${JSON.stringify(entry)})'>
            <span class="font-medium text-primary">${entry.method}</span>
            <span class="flex-1 truncate text-gray-600 dark:text-gray-400">${entry.url}</span>
        </div>
    `).join('');
}

window.loadFromHistory = (entry) => {
    methodSelect.value = entry.method;
    urlInput.value = entry.url;
};

document.getElementById('clear-history').addEventListener('click', () => {
    if (confirm('Clear all request history?')) {
        requestHistory = [];
        localStorage.removeItem('restClientHistory');
        renderHistory();
    }
});

// Initialize
renderHistory();
</script>
