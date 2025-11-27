<?php
/**
 * Hash & Encryption Tool
 * Compute hashes and encrypt/decrypt text using Crypto-JS
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">enhanced_encryption</span>
            Hash & Encryption
        </h1>
        <div class="flex gap-2">
            <button id="tab-hash" class="px-4 py-1.5 text-sm font-medium rounded-lg bg-primary text-white hover:bg-primary/90 transition-colors">
                Hashing
            </button>
            <button id="tab-encrypt" class="px-4 py-1.5 text-sm font-medium rounded-lg bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Encryption
            </button>
        </div>
    </div>

    <!-- Content -->
    <div class="flex-1 overflow-hidden relative">
        
        <!-- Hashing View -->
        <div id="view-hash" class="absolute inset-0 flex flex-col md:flex-row">
            <!-- Input (Left) -->
            <div class="w-full md:w-1/3 border-r border-gray-200 dark:border-slate-700 flex flex-col bg-white dark:bg-slate-900">
                <div class="p-4 space-y-4 flex-1 flex flex-col">
                    <div class="flex-1 flex flex-col">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Input Text</label>
                        <textarea id="hash-input" class="flex-1 w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 p-3 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-primary resize-none" placeholder="Enter text to hash..."></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">HMAC Key (Optional)</label>
                        <input type="text" id="hmac-key" class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Secret key for HMAC...">
                    </div>
                </div>
            </div>

            <!-- Output (Right) -->
            <div class="w-full md:w-2/3 bg-gray-50 dark:bg-slate-800 overflow-auto">
                <div class="p-6 space-y-4">
                    <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">Computed Hashes</h3>
                    <div id="hash-list" class="space-y-4">
                        <!-- Populated by JS -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Encryption View -->
        <div id="view-encrypt" class="absolute inset-0 flex flex-col hidden">
            <div class="flex-1 p-6 overflow-auto">
                <div class="max-w-4xl mx-auto space-y-6">
                    
                    <!-- Controls -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Algorithm</label>
                            <select id="algo-select" class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="AES">AES (Advanced Encryption Standard)</option>
                                <option value="DES">DES (Data Encryption Standard)</option>
                                <option value="TripleDES">Triple DES</option>
                                <option value="Rabbit">Rabbit</option>
                                <option value="RC4">RC4</option>
                                <option value="RC4Drop">RC4Drop</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Passphrase / Key</label>
                            <input type="text" id="enc-key" class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Secret passphrase">
                        </div>
                        <div class="flex items-end">
                            <div class="flex bg-gray-200 dark:bg-slate-700 rounded-lg p-1 w-full">
                                <button id="mode-encrypt" class="flex-1 py-1.5 text-sm font-medium rounded-md bg-white dark:bg-slate-600 shadow-sm text-primary transition-all">Encrypt</button>
                                <button id="mode-decrypt" class="flex-1 py-1.5 text-sm font-medium rounded-md text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-all">Decrypt</button>
                            </div>
                        </div>
                    </div>

                    <!-- Input/Output -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 h-[500px]">
                        <div class="flex flex-col">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Input</label>
                            <textarea id="enc-input" class="flex-1 w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 p-4 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-primary resize-none" placeholder="Text to process..."></textarea>
                        </div>
                        <div class="flex flex-col">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Output</label>
                            <div class="relative flex-1">
                                <textarea id="enc-output" readonly class="absolute inset-0 w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 p-4 text-sm font-mono focus:outline-none resize-none" placeholder="Result will appear here..."></textarea>
                                <button onclick="copyToClipboard(document.getElementById('enc-output').value, this)" class="absolute top-2 right-2 p-2 rounded-lg bg-white dark:bg-slate-700 text-gray-500 hover:text-primary shadow-sm border border-gray-200 dark:border-slate-600 transition-colors">
                                    <span class="material-symbols-outlined text-lg">content_copy</span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Crypto-JS Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>

<script>
// Elements
const tabHash = document.getElementById('tab-hash');
const tabEncrypt = document.getElementById('tab-encrypt');
const viewHash = document.getElementById('view-hash');
const viewEncrypt = document.getElementById('view-encrypt');

const hashInput = document.getElementById('hash-input');
const hmacKey = document.getElementById('hmac-key');
const hashList = document.getElementById('hash-list');

const algoSelect = document.getElementById('algo-select');
const encKey = document.getElementById('enc-key');
const modeEncrypt = document.getElementById('mode-encrypt');
const modeDecrypt = document.getElementById('mode-decrypt');
const encInput = document.getElementById('enc-input');
const encOutput = document.getElementById('enc-output');

let currentMode = 'encrypt';

// Tab Switching
function switchTab(tab) {
    if (tab === 'hash') {
        tabHash.classList.add('bg-primary', 'text-white');
        tabHash.classList.remove('bg-gray-100', 'dark:bg-slate-700', 'text-gray-700', 'dark:text-gray-300');
        tabEncrypt.classList.remove('bg-primary', 'text-white');
        tabEncrypt.classList.add('bg-gray-100', 'dark:bg-slate-700', 'text-gray-700', 'dark:text-gray-300');
        viewHash.classList.remove('hidden');
        viewEncrypt.classList.add('hidden');
    } else {
        tabEncrypt.classList.add('bg-primary', 'text-white');
        tabEncrypt.classList.remove('bg-gray-100', 'dark:bg-slate-700', 'text-gray-700', 'dark:text-gray-300');
        tabHash.classList.remove('bg-primary', 'text-white');
        tabHash.classList.add('bg-gray-100', 'dark:bg-slate-700', 'text-gray-700', 'dark:text-gray-300');
        viewEncrypt.classList.remove('hidden');
        viewHash.classList.add('hidden');
    }
}

tabHash.addEventListener('click', () => switchTab('hash'));
tabEncrypt.addEventListener('click', () => switchTab('encrypt'));

// --- Hashing Logic ---

const HASH_ALGOS = [
    { name: 'MD5', method: CryptoJS.MD5 },
    { name: 'SHA-1', method: CryptoJS.SHA1 },
    { name: 'SHA-256', method: CryptoJS.SHA256 },
    { name: 'SHA-512', method: CryptoJS.SHA512 },
    { name: 'SHA-224', method: CryptoJS.SHA224 },
    { name: 'SHA-384', method: CryptoJS.SHA384 },
    { name: 'SHA-3', method: CryptoJS.SHA3 },
    { name: 'RIPEMD-160', method: CryptoJS.RIPEMD160 }
];

function updateHashes() {
    const text = hashInput.value;
    const key = hmacKey.value;
    
    if (!text) {
        hashList.innerHTML = '<div class="text-center text-gray-500 dark:text-gray-400 py-8">Enter text to generate hashes</div>';
        return;
    }

    let html = '';
    HASH_ALGOS.forEach(algo => {
        let result;
        if (key) {
            // HMAC
            switch(algo.name) {
                case 'MD5': result = CryptoJS.HmacMD5(text, key); break;
                case 'SHA-1': result = CryptoJS.HmacSHA1(text, key); break;
                case 'SHA-256': result = CryptoJS.HmacSHA256(text, key); break;
                case 'SHA-512': result = CryptoJS.HmacSHA512(text, key); break;
                case 'SHA-224': result = CryptoJS.HmacSHA224(text, key); break;
                case 'SHA-384': result = CryptoJS.HmacSHA384(text, key); break;
                case 'SHA-3': result = CryptoJS.HmacSHA3(text, key); break;
                case 'RIPEMD-160': result = CryptoJS.HmacRIPEMD160(text, key); break;
            }
        } else {
            result = algo.method(text);
        }

        html += `
            <div class="bg-white dark:bg-slate-900 rounded-lg border border-gray-200 dark:border-slate-700 p-3 group hover:border-primary/50 transition-colors">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">${algo.name} ${key ? '(HMAC)' : ''}</span>
                    <button onclick="copyToClipboard('${result}', this)" class="opacity-0 group-hover:opacity-100 text-xs text-primary hover:underline transition-opacity">Copy</button>
                </div>
                <div class="font-mono text-sm text-gray-800 dark:text-gray-200 break-all">${result}</div>
            </div>
        `;
    });

    hashList.innerHTML = html;
}

hashInput.addEventListener('input', updateHashes);
hmacKey.addEventListener('input', updateHashes);

// --- Encryption Logic ---

function setEncMode(mode) {
    currentMode = mode;
    if (mode === 'encrypt') {
        modeEncrypt.classList.add('bg-white', 'dark:bg-slate-600', 'shadow-sm', 'text-primary');
        modeEncrypt.classList.remove('text-gray-600', 'dark:text-gray-400', 'hover:text-gray-900');
        modeDecrypt.classList.remove('bg-white', 'dark:bg-slate-600', 'shadow-sm', 'text-primary');
        modeDecrypt.classList.add('text-gray-600', 'dark:text-gray-400', 'hover:text-gray-900');
    } else {
        modeDecrypt.classList.add('bg-white', 'dark:bg-slate-600', 'shadow-sm', 'text-primary');
        modeDecrypt.classList.remove('text-gray-600', 'dark:text-gray-400', 'hover:text-gray-900');
        modeEncrypt.classList.remove('bg-white', 'dark:bg-slate-600', 'shadow-sm', 'text-primary');
        modeEncrypt.classList.add('text-gray-600', 'dark:text-gray-400', 'hover:text-gray-900');
    }
    processEncryption();
}

modeEncrypt.addEventListener('click', () => setEncMode('encrypt'));
modeDecrypt.addEventListener('click', () => setEncMode('decrypt'));

function processEncryption() {
    const text = encInput.value;
    const key = encKey.value;
    const algo = algoSelect.value;

    if (!text) {
        encOutput.value = '';
        return;
    }

    if (!key) {
        encOutput.value = 'Please enter a passphrase/key';
        return;
    }

    try {
        let result;
        const method = CryptoJS[algo];

        if (currentMode === 'encrypt') {
            result = method.encrypt(text, key).toString();
        } else {
            const bytes = method.decrypt(text, key);
            result = bytes.toString(CryptoJS.enc.Utf8);
            if (!result) throw new Error('Malformed UTF-8 data');
        }

        encOutput.value = result;
    } catch (e) {
        encOutput.value = 'Error: ' + e.message + '\n\n(Check if the key is correct or if the input is valid)';
    }
}

encInput.addEventListener('input', processEncryption);
encKey.addEventListener('input', processEncryption);
algoSelect.addEventListener('change', processEncryption);

// Utilities
window.copyToClipboard = async (text, btn) => {
    try {
        await navigator.clipboard.writeText(text);
        const originalText = btn.textContent;
        
        // Handle icon button vs text button
        if (btn.querySelector('.material-symbols-outlined')) {
            const icon = btn.querySelector('.material-symbols-outlined');
            const originalIcon = icon.textContent;
            icon.textContent = 'check';
            btn.classList.add('text-green-500');
            setTimeout(() => {
                icon.textContent = originalIcon;
                btn.classList.remove('text-green-500');
            }, 1500);
        } else {
            btn.textContent = 'Copied!';
            setTimeout(() => {
                btn.textContent = originalText;
            }, 1500);
        }
    } catch (err) {
        console.error('Failed to copy:', err);
    }
};

// Initial state
updateHashes();
</script>
