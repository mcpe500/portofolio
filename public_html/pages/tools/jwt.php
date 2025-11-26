<?php
/**
 * JWT Decoder
 * Decode and inspect JWT tokens
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">key</span>
            JWT Decoder
        </h1>
        <div class="flex items-center gap-2">
            <button id="copy-header" class="px-3 py-1 text-xs font-medium rounded bg-gray-100 dark:bg-gray-700 hover:bg-primary hover:text-white transition-colors">
                Copy Header
            </button>
            <button id="copy-payload" class="px-3 py-1 text-xs font-medium rounded bg-gray-100 dark:bg-gray-700 hover:bg-primary hover:text-white transition-colors">
                Copy Payload
            </button>
        </div>
    </div>

    <!-- Warning Banner -->
    <div class="px-4 py-3 bg-yellow-50 dark:bg-yellow-900/20 border-b border-yellow-200 dark:border-yellow-800">
        <div class="flex items-start gap-2 text-sm">
            <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-500">warning</span>
            <div class="flex-1">
                <p class="font-medium text-yellow-800 dark:text-yellow-300">Debug Tool Only - Do Not Trust</p>
                <p class="text-xs text-yellow-700 dark:text-yellow-400 mt-1">This decoder is for debugging purposes only. Never paste tokens from production systems into untrusted tools. Signatures are NOT verified - tokens should be validated on your server.</p>
            </div>
        </div>
    </div>

    <div class="flex flex-1 overflow-hidden">
        <!-- Token Input (Left) -->
        <div class="w-1/2 border-r border-gray-200 dark:border-slate-700 flex flex-col bg-white dark:bg-slate-900">
            <div class="p-3 border-b border-gray-200 dark:border-slate-700">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">JWT Token</label>
            </div>
            <div class="flex-1 p-4">
                <textarea id="jwt-input" placeholder="Paste your JWT token here (e.g., eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...)" 
                    class="w-full h-full font-mono text-sm rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary resize-none"></textarea>
            </div>
            <div id="token-error" class="hidden px-4 py-2 bg-red-50 dark:bg-red-900/20 border-t border-red-200 dark:border-red-800">
                <p class="text-xs text-red-600 dark:text-red-400"></p>
            </div>
        </div>

        <!-- Decoded Output (Right) -->
        <div class="w-1/2 flex flex-col bg-gray-50 dark:bg-slate-800 overflow-auto">
            <div id="decoded-content" class="hidden p-6 space-y-4">
                <!-- Header -->
                <div>
                    <h3 class="text-sm font-bold mb-2 text-blue-600 dark:text-blue-400 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">description</span>
                        HEADER
                    </h3>
                    <pre id="header-content" class="bg-white dark:bg-slate-900 rounded-lg p-4 text-xs font-mono overflow-auto"></pre>
                    <div id="header-info" class="mt-2 text-xs text-gray-600 dark:text-gray-400"></div>
                </div>

                <!-- Payload -->
                <div>
                    <h3 class="text-sm font-bold mb-2 text-green-600 dark:text-green-400 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">content_paste</span>
                        PAYLOAD
                    </h3>
                    <pre id="payload-content" class="bg-white dark:bg-slate-900 rounded-lg p-4 text-xs font-mono overflow-auto"></pre>
                </div>

                <!-- Claims Explanation -->
                <div id="claims-section" class="hidden">
                    <h3 class="text-sm font-bold mb-2 text-purple-600 dark:text-purple-400">Claims Explanation</h3>
                    <div id="claims-list" class="space-y-2 text-xs"></div>
                </div>

                <!-- Signature -->
                <div>
                    <h3 class="text-sm font-bold mb-2 text-red-600 dark:text-red-400 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">verified_user</span>
                        SIGNATURE
                    </h3>
                    <div class="bg-white dark:bg-slate-900 rounded-lg p-4">
                        <p id="signature-content" class="font-mono text-xs break-all text-gray-600 dark:text-gray-400"></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">⚠️ Signature is NOT verified by this tool. Always verify signatures server-side.</p>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div id="empty-state" class="flex items-center justify-center h-full">
                <div class="text-center text-gray-500 dark:text-gray-400">
                    <span class="material-symbols-outlined text-6xl mb-2">security</span>
                    <p class="text-sm">Paste a JWT token to decode it</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jwt-decode library -->
<script src="https://cdn.jsdelivr.net/npm/jwt-decode@3.1.2/build/jwt-decode.min.js"></script>

<script>
// Elements
const jwtInput = document.getElementById('jwt-input');
const tokenError = document.getElementById('token-error');
const decodedContent = document.getElementById('decoded-content');
const emptyState = document.getElementById('empty-state');
const headerContent = document.getElementById('header-content');
const payloadContent = document.getElementById('payload-content');
const signatureContent = document.getElementById('signature-content');
const headerInfo = document.getElementById('header-info');
const claimsSection = document.getElementById('claims-section');
const claimsList = document.getElementById('claims-list');
const copyHeaderBtn = document.getElementById('copy-header');
const copyPayloadBtn = document.getElementById('copy-payload');

let currentHeader = null;
let currentPayload = null;

// Decode JWT
function decodeJWT() {
    const token = jwtInput.value.trim();

    if (!token) {
        emptyState.classList.remove('hidden');
        decodedContent.classList.add('hidden');
        tokenError.classList.add('hidden');
        return;
    }

    try {
        // Split token
        const parts = token.split('.');
        
        if (parts.length !== 3) {
            throw new Error('Invalid JWT format. Expected 3 parts separated by dots.');
        }

        // Decode header and payload
        const header = JSON.parse(atob(parts[0].replace(/-/g, '+').replace(/_/g, '/')));
        const payload = JSON.parse(atob(parts[1].replace(/-/g, '+').replace(/_/g, '/')));
        const signature = parts[2];

        currentHeader = header;
        currentPayload = payload;

        // Display
        emptyState.classList.add('hidden');
        decodedContent.classList.remove('hidden');
        tokenError.classList.add('hidden');

        // Header
        headerContent.textContent = JSON.stringify(header, null, 2);
        
        // Header info
        const algo = header.alg || 'Unknown';
        const type = header.typ || 'Unknown';
        headerInfo.innerHTML = `
            <div class="flex gap-4">
                <div><strong>Algorithm:</strong> ${algo}</div>
                <div><strong>Type:</strong> ${type}</div>
            </div>
        `;

        // Payload
        payloadContent.textContent = JSON.stringify(payload, null, 2);

        // Signature
        signatureContent.textContent = signature;

        // Claims
        displayClaims(payload);

    } catch (error) {
        emptyState.classList.add('hidden');
        decodedContent.classList.add('hidden');
        tokenError.classList.remove('hidden');
        tokenError.querySelector('p').textContent = error.message;
    }
}

// Display claims explanation
function displayClaims(payload) {
    const knownClaims = {
        iss: { name: 'Issuer', desc: 'Who created and signed the token' },
        sub: { name: 'Subject', desc: 'The subject of the token (often user ID)' },
        aud: { name: 'Audience', desc: 'Who the token is intended for' },
        exp: { name: 'Expiration Time', desc: 'When the token expires', isTimestamp: true },
        nbf: { name: 'Not Before', desc: 'Token is not valid before this time', isTimestamp: true },
        iat: { name: 'Issued At', desc: 'When the token was issued', isTimestamp: true },
        jti: { name: 'JWT ID', desc: 'Unique identifier for this token' }
    };

    const claims = [];
    
    for (const [key, value] of Object.entries(payload)) {
        if (knownClaims[key]) {
            const claim = knownClaims[key];
            let displayValue = value;

            if (claim.isTimestamp) {
                const date = new Date(value * 1000);
                const now = new Date();
                const diff = date - now;
                const diffText = formatTimeDiff(diff);
                
                displayValue = `${date.toLocaleString()} (${diffText})`;
            }

            claims.push({
                key,
                name: claim.name,
                desc: claim.desc,
                value: displayValue,
                isExpiry: key === 'exp'
            });
        }
    }

    if (claims.length > 0) {
        claimsSection.classList.remove('hidden');
        claimsList.innerHTML = claims.map(claim => {
            let statusClass = '';
            let statusIcon = '';
            
            if (claim.isExpiry) {
                const expTimestamp = payload.exp * 1000;
                if (expTimestamp < Date.now()) {
                    statusClass = 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800';
                    statusIcon = '<span class="text-red-600">❌ Expired</span>';
                } else {
                    statusClass = 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800';
                    statusIcon = '<span class="text-green-600">✓ Valid</span>';
                }
            }
            
            return `
                <div class="p-3 bg-white dark:bg-slate-900 rounded-lg border ${statusClass || 'border-gray-200 dark:border-slate-700'}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="font-semibold">${claim.name} (<code class="text-xs">${claim.key}</code>)</div>
                            <div class="text-gray-600 dark:text-gray-400 mt-1">${claim.desc}</div>
                            <div class="mt-2 font-mono text-xs bg-gray-50 dark:bg-slate-800 px-2 py-1 rounded">${escapeHtml(String(claim.value))}</div>
                        </div>
                        ${statusIcon ? `<div class="ml-2">${statusIcon}</div>` : ''}
                    </div>
                </div>
            `;
        }).join('');
    } else {
        claimsSection.classList.add('hidden');
    }
}

function formatTimeDiff(ms) {
    const abs = Math.abs(ms);
    const prefix = ms < 0 ? 'Expired' : 'Expires in';
    
    const seconds = Math.floor(abs / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);

    if (days > 0) return `${prefix} ${days} day${days > 1 ? 's' : ''}`;
    if (hours > 0) return `${prefix} ${hours} hour${hours > 1 ? 's' : ''}`;
    if (minutes > 0) return `${prefix} ${minutes} minute${minutes > 1 ? 's' : ''}`;
    return `${prefix} ${seconds} second${seconds !== 1 ? 's' : ''}`;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Copy functions
copyHeaderBtn.addEventListener('click', async () => {
    if (!currentHeader) return;
    await copyToClipboard(JSON.stringify(currentHeader, null, 2), copyHeaderBtn);
});

copyPayloadBtn.addEventListener('click', async () => {
    if (!currentPayload) return;
    await copyToClipboard(JSON.stringify(currentPayload, null, 2), copyPayloadBtn);
});

async function copyToClipboard(text, button) {
    try {
        await navigator.clipboard.writeText(text);
        const originalText = button.textContent;
        button.textContent = '✓ Copied!';
        setTimeout(() => {
            button.textContent = originalText;
        }, 2000);
    } catch (error) {
        alert('Failed to copy: ' + error.message);
    }
}

// Auto-decode
let debounceTimer;
jwtInput.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(decodeJWT, 300);
});

// Sample JWT (with a future expiry)
const futureTimestamp = Math.floor(Date.now() / 1000) + 3600; // 1 hour from now
const sampleHeader = btoa(JSON.stringify({ alg: "HS256", typ: "JWT" }));
const samplePayload = btoa(JSON.stringify({
    sub: "1234567890",
    name: "John Doe",
    email: "john@example.com",
    iat: Math.floor(Date.now() / 1000),
    exp: futureTimestamp,
    iss: "https://example.com",
    aud: "https://api.example.com"
}));
const sampleSignature = "SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c";

jwtInput.value = `${sampleHeader}.${samplePayload}.${sampleSignature}`;
decodeJWT();
</script>
