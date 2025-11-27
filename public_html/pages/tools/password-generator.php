<?php
/**
 * Password Generator
 * Generate secure random passwords and strings
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">lock_reset</span>
            Password Generator
        </h1>
        <button id="generate-btn" class="px-4 py-1.5 text-sm font-medium rounded-lg bg-primary text-white hover:bg-primary/90 transition-colors shadow-sm flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">refresh</span>
            Generate
        </button>
    </div>

    <div class="flex flex-1 overflow-hidden">
        <!-- Options Section (Left) -->
        <div class="w-1/3 border-r border-gray-200 dark:border-slate-700 flex flex-col bg-white dark:bg-slate-900 overflow-auto">
            <div class="p-5 space-y-6">
                
                <!-- Length -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Length</label>
                        <span id="length-val" class="text-sm font-bold text-primary">16</span>
                    </div>
                    <input type="range" id="length" min="4" max="128" value="16" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700 accent-primary">
                    <div class="flex justify-between text-xs text-gray-400 mt-1">
                        <span>4</span>
                        <span>128</span>
                    </div>
                </div>

                <!-- Character Sets -->
                <div class="space-y-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Characters</label>
                    
                    <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800 cursor-pointer transition-colors">
                        <input type="checkbox" id="uppercase" checked class="w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">Uppercase</div>
                            <div class="text-xs text-gray-500">A-Z</div>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800 cursor-pointer transition-colors">
                        <input type="checkbox" id="lowercase" checked class="w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">Lowercase</div>
                            <div class="text-xs text-gray-500">a-z</div>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800 cursor-pointer transition-colors">
                        <input type="checkbox" id="numbers" checked class="w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">Numbers</div>
                            <div class="text-xs text-gray-500">0-9</div>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800 cursor-pointer transition-colors">
                        <input type="checkbox" id="symbols" checked class="w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">Symbols</div>
                            <div class="text-xs text-gray-500">!@#$%^&*...</div>
                        </div>
                    </label>
                </div>

                <!-- Advanced Options -->
                <div class="space-y-4 pt-4 border-t border-gray-200 dark:border-slate-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Advanced Options</label>
                    
                    <div>
                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Quantity</label>
                        <input type="number" id="quantity" value="1" min="1" max="50" class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Include String (Optional)</label>
                        <input type="text" id="include-string" placeholder="e.g. site_name" class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="exclude-similar" class="rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Exclude Similar (i, l, 1, L, o, 0, O)</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Output Section (Right) -->
        <div class="w-2/3 flex flex-col bg-gray-50 dark:bg-slate-800 overflow-hidden">
            <div class="flex-1 overflow-auto p-6">
                <!-- Main Result -->
                <div id="single-result-container" class="mb-8">
                    <div class="relative group">
                        <div id="main-password" class="w-full p-6 text-center text-3xl font-mono font-bold text-gray-800 dark:text-white bg-white dark:bg-slate-900 rounded-xl border-2 border-gray-200 dark:border-slate-700 shadow-sm break-all transition-all hover:border-primary/50">
                            Generating...
                        </div>
                        <button onclick="copyToClipboard(document.getElementById('main-password').textContent.trim(), this)" 
                            class="absolute top-1/2 -translate-y-1/2 right-4 p-2 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-500 hover:text-primary hover:bg-primary/10 transition-colors opacity-0 group-hover:opacity-100">
                            <span class="material-symbols-outlined">content_copy</span>
                        </button>
                    </div>
                    
                    <!-- Strength Meter -->
                    <div class="mt-4 flex items-center gap-4">
                        <div class="flex-1 h-2 bg-gray-200 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div id="strength-bar" class="h-full w-0 transition-all duration-500 ease-out"></div>
                        </div>
                        <span id="strength-text" class="text-sm font-medium text-gray-500">Calculating...</span>
                    </div>
                </div>

                <!-- Batch Results -->
                <div id="batch-results-container" class="hidden space-y-3">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">Generated Passwords</h3>
                        <button id="copy-all-btn" class="text-xs text-primary hover:underline">Copy All</button>
                    </div>
                    <div id="batch-list" class="grid gap-3">
                        <!-- Populated via JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Elements
const lengthSlider = document.getElementById('length');
const lengthVal = document.getElementById('length-val');
const uppercaseCb = document.getElementById('uppercase');
const lowercaseCb = document.getElementById('lowercase');
const numbersCb = document.getElementById('numbers');
const symbolsCb = document.getElementById('symbols');
const quantityInput = document.getElementById('quantity');
const includeStringInput = document.getElementById('include-string');
const excludeSimilarCb = document.getElementById('exclude-similar');
const generateBtn = document.getElementById('generate-btn');
const mainPassword = document.getElementById('main-password');
const strengthBar = document.getElementById('strength-bar');
const strengthText = document.getElementById('strength-text');
const singleResultContainer = document.getElementById('single-result-container');
const batchResultsContainer = document.getElementById('batch-results-container');
const batchList = document.getElementById('batch-list');
const copyAllBtn = document.getElementById('copy-all-btn');

// Character Sets
const CHARS = {
    upper: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
    lower: 'abcdefghijklmnopqrstuvwxyz',
    number: '0123456789',
    symbol: '!@#$%^&*()_+~`|}{[]:;?><,./-=',
    similar: 'il1LO0o'
};

// Update length display
lengthSlider.addEventListener('input', (e) => {
    lengthVal.textContent = e.target.value;
    generate();
});

// Listeners
[uppercaseCb, lowercaseCb, numbersCb, symbolsCb, excludeSimilarCb, includeStringInput].forEach(el => {
    el.addEventListener('input', generate);
});

quantityInput.addEventListener('input', () => {
    if (quantityInput.value > 50) quantityInput.value = 50;
    if (quantityInput.value < 1) quantityInput.value = 1;
    generate();
});

generateBtn.addEventListener('click', generate);

function generate() {
    const length = parseInt(lengthSlider.value);
    const quantity = parseInt(quantityInput.value) || 1;
    const includeStr = includeStringInput.value;
    const excludeSimilar = excludeSimilarCb.checked;
    
    let chars = '';
    if (uppercaseCb.checked) chars += CHARS.upper;
    if (lowercaseCb.checked) chars += CHARS.lower;
    if (numbersCb.checked) chars += CHARS.number;
    if (symbolsCb.checked) chars += CHARS.symbol;
    
    if (excludeSimilar) {
        chars = chars.split('').filter(c => !CHARS.similar.includes(c)).join('');
    }

    if (!chars) {
        mainPassword.textContent = 'Select options';
        strengthBar.style.width = '0%';
        strengthText.textContent = '';
        return;
    }

    const passwords = [];
    for (let i = 0; i < quantity; i++) {
        passwords.push(generateSinglePassword(length, chars, includeStr));
    }

    // Display
    if (quantity === 1) {
        singleResultContainer.classList.remove('hidden');
        batchResultsContainer.classList.add('hidden');
        
        const pwd = passwords[0];
        mainPassword.textContent = pwd;
        updateStrength(pwd);
    } else {
        singleResultContainer.classList.add('hidden');
        batchResultsContainer.classList.remove('hidden');
        
        batchList.innerHTML = passwords.map(pwd => `
            <div class="group flex items-center justify-between p-3 bg-white dark:bg-slate-900 rounded-lg border border-gray-200 dark:border-slate-700 hover:border-primary/50 transition-all">
                <span class="font-mono text-gray-800 dark:text-gray-200 break-all">${escapeHtml(pwd)}</span>
                <button onclick="copyToClipboard('${pwd.replace(/'/g, "\\'")}', this)" class="opacity-0 group-hover:opacity-100 p-1.5 rounded hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-500 transition-all">
                    <span class="material-symbols-outlined text-lg">content_copy</span>
                </button>
            </div>
        `).join('');
    }
}

function generateSinglePassword(length, chars, includeStr) {
    let password = '';
    const effectiveLength = Math.max(length - (includeStr ? includeStr.length : 0), 0);
    
    // Generate random part
    const array = new Uint32Array(effectiveLength);
    crypto.getRandomValues(array);
    
    for (let i = 0; i < effectiveLength; i++) {
        password += chars[array[i] % chars.length];
    }

    // Insert included string at random position
    if (includeStr) {
        const pos = Math.floor(Math.random() * (password.length + 1));
        password = password.slice(0, pos) + includeStr + password.slice(pos);
    }

    return password;
}

function updateStrength(password) {
    let score = 0;
    if (password.length > 8) score += 1;
    if (password.length > 12) score += 1;
    if (password.length > 16) score += 1;
    if (/[A-Z]/.test(password)) score += 1;
    if (/[a-z]/.test(password)) score += 1;
    if (/[0-9]/.test(password)) score += 1;
    if (/[^A-Za-z0-9]/.test(password)) score += 1;

    const maxScore = 7;
    const percent = Math.min((score / maxScore) * 100, 100);
    
    strengthBar.style.width = `${percent}%`;
    
    if (percent < 40) {
        strengthBar.className = 'h-full w-0 transition-all duration-500 ease-out bg-red-500';
        strengthText.textContent = 'Weak';
        strengthText.className = 'text-sm font-medium text-red-500';
    } else if (percent < 70) {
        strengthBar.className = 'h-full w-0 transition-all duration-500 ease-out bg-yellow-500';
        strengthText.textContent = 'Medium';
        strengthText.className = 'text-sm font-medium text-yellow-500';
    } else {
        strengthBar.className = 'h-full w-0 transition-all duration-500 ease-out bg-green-500';
        strengthText.textContent = 'Strong';
        strengthText.className = 'text-sm font-medium text-green-500';
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

window.copyToClipboard = async (text, btn) => {
    try {
        await navigator.clipboard.writeText(text);
        const icon = btn.querySelector('.material-symbols-outlined');
        const originalIcon = icon.textContent;
        
        icon.textContent = 'check';
        btn.classList.add('text-green-500');
        
        setTimeout(() => {
            icon.textContent = originalIcon;
            btn.classList.remove('text-green-500');
        }, 1500);
    } catch (err) {
        console.error('Failed to copy:', err);
    }
};

copyAllBtn.addEventListener('click', async () => {
    const passwords = Array.from(batchList.querySelectorAll('span')).map(span => span.textContent).join('\n');
    try {
        await navigator.clipboard.writeText(passwords);
        const originalText = copyAllBtn.textContent;
        copyAllBtn.textContent = 'Copied!';
        setTimeout(() => {
            copyAllBtn.textContent = originalText;
        }, 1500);
    } catch (err) {
        console.error('Failed to copy:', err);
    }
});

// Initial generate
generate();
</script>
