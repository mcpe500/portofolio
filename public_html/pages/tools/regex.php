<?php
/**
 * Regex Tester
 * Test regular expressions with live highlighting
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">regular_expression</span>
            Regex Tester
        </h1>
        <button id="toggle-cheatsheet" class="px-3 py-1 text-xs font-medium rounded bg-gray-100 dark:bg-gray-700 hover:bg-primary hover:text-white transition-colors">
            Show Cheatsheet
        </button>
    </div>

    <div class="flex flex-1 overflow-hidden">
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Pattern Input -->
            <div class="p-4 bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-700">
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium mb-1">Regular Expression</label>
                        <div class="flex gap-2 items-center">
                            <span class="text-lg font-mono text-gray-600 dark:text-gray-400">/</span>
                            <input id="regex-pattern" type="text" placeholder="[A-Za-z0-9]+" value="(\d{3})-(\d{3})-(\d{4})"
                                class="flex-1 font-mono rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" />
                            <span class="text-lg font-mono text-gray-600 dark:text-gray-400">/</span>
                            <div class="flex items-center gap-1">
                                <label class="flex items-center gap-1 text-xs">
                                    <input type="checkbox" id="flag-g" class="rounded" checked>
                                    <span class="font-mono">g</span>
                                </label>
                                <label class="flex items-center gap-1 text-xs">
                                    <input type="checkbox" id="flag-i" class="rounded">
                                    <span class="font-mono">i</span>
                                </label>
                                <label class="flex items-center gap-1 text-xs">
                                    <input type="checkbox" id="flag-m" class="rounded">
                                    <span class="font-mono">m</span>
                                </label>
                                <label class="flex items-center gap-1 text-xs">
                                    <input type="checkbox" id="flag-s" class="rounded">
                                    <span class="font-mono">s</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="pattern-error" class="hidden text-xs text-red-600 dark:text-red-400"></div>
                </div>
            </div>

            <!-- Test String & Results -->
            <div class="flex-1 flex overflow-hidden">
                <!-- Test String (Left) -->
                <div class="w-1/2 border-r border-gray-200 dark:border-slate-700 flex flex-col bg-gray-50 dark:bg-slate-800">
                    <div class="p-3 border-b border-gray-200 dark:border-slate-700">
                        <label class="text-sm font-medium">Test String</label>
                    </div>
                    <div class="flex-1 p-4 overflow-auto">
                        <div id="highlighted-text" class="font-mono text-sm whitespace-pre-wrap"></div>
                        <textarea id="test-string" 
                            class="absolute opacity-0 pointer-events-none"
                            placeholder="Enter text to test..."></textarea>
                    </div>
                </div>

                <!-- Matches & Groups (Right) -->
                <div class="w-1/2 flex flex-col bg-white dark:bg-slate-900">
                    <div class="flex border-b border-gray-200 dark:border-slate-700">
                        <button class="result-tab active flex-1 px-4 py-2 text-sm font-medium border-b-2 border-primary" data-tab="matches">
                            Matches (<span id="match-count">0</span>)
                        </button>
                        <button class="result-tab flex-1 px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-600" data-tab="groups">
                            Capture Groups
                        </button>
                        <button class="result-tab flex-1 px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-600" data-tab="replace">
                            Replace
                        </button>
                    </div>

                    <!-- Matches Tab -->
                    <div id="tab-matches" class="result-content flex-1 p-4 overflow-auto">
                        <div id="matches-list" class="space-y-2"></div>
                    </div>

                    <!-- Groups Tab -->
                    <div id="tab-groups" class="result-content hidden flex-1 p-4 overflow-auto">
                        <div id="groups-list" class="space-y-2"></div>
                    </div>

                    <!-- Replace Tab -->
                    <div id="tab-replace" class="result-content hidden flex-1 p-4 overflow-auto space-y-3">
                        <div>
                            <label class="block text-sm font-medium mb-1">Replacement</label>
                            <input id="replacement-text" type="text" placeholder="$1-$2-$3" value="($1) $2-$3"
                                class="w-full font-mono rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Result</label>
                            <pre id="replace-result" class="w-full font-mono text-sm rounded-lg border border-gray-300 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 px-3 py-2 overflow-auto max-h-64"></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cheatsheet Sidebar -->
        <div id="cheatsheet" class="hidden w-80 bg-white dark:bg-slate-900 border-l border-gray-200 dark:border-slate-700 overflow-auto p-4 text-xs">
            <h3 class="font-bold mb-3">Regex Cheatsheet</h3>
            
            <div class="space-y-3">
                <div>
                    <h4 class="font-semibold mb-1">Character Classes</h4>
                    <div class="space-y-1 text-gray-600 dark:text-gray-400">
                        <div><code class="bg-gray-100 dark:bg-slate-800 px-1 rounded">.</code> any character</div>
                        <div><code class="bg-gray-100 dark:bg-slate-800 px-1 rounded">\d</code> digit [0-9]</div>
                        <div><code class="bg-gray-100 dark:bg-slate-800 px-1 rounded">\w</code> word [A-Za-z0-9_]</div>
                        <div><code class="bg-gray-100 dark:bg-slate-800 px-1 rounded">\s</code> whitespace</div>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold mb-1">Quantifiers</h4>
                    <div class="space-y-1 text-gray-600 dark:text-gray-400">
                        <div><code class="bg-gray-100 dark:bg-slate-800 px-1 rounded">*</code> 0 or more</div>
                        <div><code class="bg-gray-100 dark:bg-slate-800 px-1 rounded">+</code> 1 or more</div>
                        <div><code class="bg-gray-100 dark:bg-slate-800 px-1 rounded">?</code> 0 or 1</div>
                        <div><code class="bg-gray-100 dark:bg-slate-800 px-1 rounded">{3}</code> exactly 3</div>
                        <div><code class="bg-gray-100 dark:bg-slate-800 px-1 rounded">{3,5}</code> 3 to 5</div>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold mb-1">Anchors</h4>
                    <div class="space-y-1 text-gray-600 dark:text-gray-400">
                        <div><code class="bg-gray-100 dark:bg-slate-800 px-1 rounded">^</code> start of string</div>
                        <div><code class="bg-gray-100 dark:bg-slate-800 px-1 rounded">$</code> end of string</div>
                        <div><code class="bg-gray-100 dark:bg-slate-800 px-1 rounded">\b</code> word boundary</div>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold mb-1">Groups</h4>
                    <div class="space-y-1 text-gray-600 dark:text-gray-400">
                        <div><code class="bg-gray-100 dark:bg-slate-800 px-1 rounded">(abc)</code> capture group</div>
                        <div><code class="bg-gray-100 dark:bg-slate-800 px-1 rounded">(?:abc)</code> non-capturing</div>
                        <div><code class="bg-gray-100 dark:bg-slate-800 px-1 rounded">a|b</code> alternation</div>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold mb-1">Common Patterns</h4>
                    <div class="space-y-1">
                        <button class="w-full text-left px-2 py-1 rounded hover:bg-gray-100 dark:hover:bg-slate-800" onclick="loadPattern('[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}', 'test@example.com')">
                            Email
                        </button>
                        <button class="w-full text-left px-2 py-1 rounded hover:bg-gray-100 dark:hover:bg-slate-800" onclick="loadPattern('https?:\\/\\/(www\\.)?[-a-zA-Z0-9@:%._\\+~#=]{1,256}\\.[a-zA-Z0-9()]{1,6}\\b([-a-zA-Z0-9()@:%_\\+.~#?&//=]*)', 'https://example.com')">
                            URL
                        </button>
                        <button class="w-full text-left px-2 py-1 rounded hover:bg-gray-100 dark:hover:bg-slate-800" onclick="loadPattern('(\\+\\d{1,3})?[\\s.-]?\\(?(\\d{3})\\)?[\\s.-]?(\\d{3})[\\s.-]?(\\d{4})', '+1 (555) 123-4567')">
                            Phone
                        </button>
                        <button class="w-full text-left px-2 py-1 rounded hover:bg-gray-100 dark:hover:bg-slate-800" onclick="loadPattern('\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}', '192.168.1.1')">
                            IP Address
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Elements
const patternInput = document.getElementById('regex-pattern');
const testString = document.getElementById('test-string');
const highlightedText = document.getElementById('highlighted-text');
const patternError = document.getElementById('pattern-error');
const matchesList = document.getElementById('matches-list');
const groupsList = document.getElementById('groups-list');
const matchCount = document.getElementById('match-count');
const toggleCheatsheet = document.getElementById('toggle-cheatsheet');
const cheatsheet = document.getElementById('cheatsheet');
const replacementText = document.getElementById('replacement-text');
const replaceResult = document.getElementById('replace-result');

// Flags
const flags = {
    g: document.getElementById('flag-g'),
    i: document.getElementById('flag-i'),
    m: document.getElementById('flag-m'),
    s: document.getElementById('flag-s')
};

// Tab switching
document.querySelectorAll('.result-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        const tab = btn.dataset.tab;
        document.querySelectorAll('.result-tab').forEach(b => {
            b.classList.remove('active', 'border-primary', 'text-gray-900', 'dark:text-white');
            b.classList.add('border-transparent', 'text-gray-600');
        });
        btn.classList.add('active', 'border-primary', 'text-gray-900', 'dark:text-white');
        btn.classList.remove('border-transparent', 'text-gray-600');
        
        document.querySelectorAll('.result-content').forEach(c => c.classList.add('hidden'));
        document.getElementById(`tab-${tab}`).classList.remove('hidden');
    });
});

// Toggle cheatsheet
toggleCheatsheet.addEventListener('click', () => {
    cheatsheet.classList.toggle('hidden');
    toggleCheatsheet.textContent = cheatsheet.classList.contains('hidden') ? 'Show Cheatsheet' : 'Hide Cheatsheet';
});

// Test regex
function testRegex() {
    const pattern = patternInput.value;
    const text = highlightedText.textContent;

    if (!pattern || !text) {
        highlightedText.innerHTML = escapeHtml(text);
        matchesList.innerHTML = '<p class="text-xs text-gray-500">No matches</p>';
        groupsList.innerHTML = '<p class="text-xs text-gray-500">No groups</p>';
        matchCount.textContent = '0';
        return;
    }

    try {
        // Build flags
        let flagStr = '';
        if (flags.g.checked) flagStr += 'g';
        if (flags.i.checked) flagStr += 'i';
        if (flags.m.checked) flagStr += 'm';
        if (flags.s.checked) flagStr += 's';

        const regex = new RegExp(pattern, flagStr);
        patternError.classList.add('hidden');

        // Find matches
        const matches = [...text.matchAll(new RegExp(pattern, flagStr + (flagStr.includes('g') ? '' : 'g')))];
        matchCount.textContent = matches.length;

        // Highlight text
        if (matches.length > 0) {
            let html = '';
            let lastIndex = 0;

            matches.forEach((match, i) => {
                html += escapeHtml(text.slice(lastIndex, match.index));
                html += `<span class="bg-yellow-200 dark:bg-yellow-600/40 font-bold" title="Match ${i + 1}">${escapeHtml(match[0])}</span>`;
                lastIndex = match.index + match[0].length;
            });
            html += escapeHtml(text.slice(lastIndex));
            highlightedText.innerHTML = html;
        } else {
            highlightedText.innerHTML = escapeHtml(text);
        }

        // Show matches
        if (matches.length > 0) {
            matchesList.innerHTML = matches.map((match, i) => `
                <div class="p-2 bg-gray-50 dark:bg-slate-800 rounded text-xs">
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-semibold">Match ${i + 1}</span>
                        <span class="text-gray-500">Index: ${match.index}</span>
                    </div>
                    <code class="block bg-white dark:bg-slate-900 px-2 py-1 rounded font-mono">${escapeHtml(match[0])}</code>
                </div>
            `).join('');
        } else {
            matchesList.innerHTML = '<p class="text-xs text-gray-500">No matches found</p>';
        }

        // Show groups
        if (matches.length > 0 && matches[0].length > 1) {
            groupsList.innerHTML = matches.map((match, matchIdx) => `
                <div class="space-y-1">
                    <div class="font-semibold text-xs">Match ${matchIdx + 1} Groups:</div>
                    ${match.slice(1).map((group, groupIdx) => `
                        <div class="p-2 bg-gray-50 dark:bg-slate-800 rounded text-xs">
                            <div class="text-gray-500 mb-1">Group ${groupIdx + 1}</div>
                            <code class="block bg-white dark:bg-slate-900 px-2 py-1 rounded font-mono">${escapeHtml(group || '')}</code>
                        </div>
                    `).join('')}
                </div>
            `).join('<hr class="my-2 border-gray-200 dark:border-slate-700">');
        } else {
            groupsList.innerHTML = '<p class="text-xs text-gray-500">No capture groups</p>';
        }

        // Replace
        try {
            const replaced = text.replace(regex, replacementText.value);
            replaceResult.textContent = replaced;
        } catch (e) {
            replaceResult.textContent = 'Error: ' + e.message;
        }

    } catch (error) {
        patternError.textContent = 'Invalid regex: ' + error.message;
        patternError.classList.remove('hidden');
        highlightedText.innerHTML = escapeHtml(text);
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Editable highlighted text
highlightedText.contentEditable = true;
highlightedText.addEventListener('input', () => {
    testRegex();
});

// Event listeners
let debounceTimer;
[patternInput, replacementText].forEach(el => {
    el.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(testRegex, 300);
    });
});

Object.values(flags).forEach(flag => {
    flag.addEventListener('change', testRegex);
});

// Load pattern helper
window.loadPattern = (pattern, sample) => {
    patternInput.value = pattern;
    highlightedText.textContent = sample;
    testRegex();
};

// Initial sample
highlightedText.textContent = `Call me at 555-123-4567 or 555-987-6543
My other number is 555-555-5555`;
testRegex();
</script>
