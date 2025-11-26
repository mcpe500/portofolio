<?php
/**
 * Diff Checker
 * Compare text and files with advanced diff visualization
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">difference</span>
            Diff Checker
        </h1>
        <div class="flex items-center gap-2 text-xs">
            <label class="flex items-center gap-1">
                <input type="checkbox" id="ignore-whitespace" class="rounded">
                <span>Ignore Whitespace</span>
            </label>
            <label class="flex items-center gap-1">
                <input type="checkbox" id="ignore-case" class="rounded">
                <span>Ignore Case</span>
            </label>
            <select id="view-mode" class="px-2 py-1 rounded border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800">
                <option value="split">Side by Side</option>
                <option value="unified">Unified</option>
            </select>
        </div>
    </div>

    <!-- Content -->
    <div class="flex-1 flex overflow-hidden">
        <!-- Split View (default) -->
        <div id="split-view" class="flex-1 flex">
            <!-- Original (Left) -->
            <div class="w-1/2 border-r border-gray-200 dark:border-slate-700 flex flex-col bg-white dark:bg-slate-900">
                <div class="p-3 border-b border-gray-200 dark:border-slate-700 flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Original</span>
                    <label class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 rounded cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600">
                        Upload File
                        <input type="file" id="upload-original" class="hidden" accept=".txt,.json,.js,.css,.html,.xml,.md,.yaml,.yml">
                    </label>
                </div>
                <div class="flex-1 overflow-auto">
                    <div id="original-display" class="font-mono text-sm"></div>
                    <textarea id="text-original" class="hidden" placeholder="Paste original text or upload file..."></textarea>
                </div>
            </div>

            <!-- Modified (Right) -->
            <div class="w-1/2 flex flex-col bg-white dark:bg-slate-900">
                <div class="p-3 border-b border-gray-200 dark:border-slate-700 flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Modified</span>
                    <label class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 rounded cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600">
                        Upload File
                        <input type="file" id="upload-modified" class="hidden" accept=".txt,.json,.js,.css,.html,.xml,.md,.yaml,.yml">
                    </label>
                </div>
                <div class="flex-1 overflow-auto">
                    <div id="modified-display" class="font-mono text-sm"></div>
                    <textarea id="text-modified" class="hidden" placeholder="Paste modified text or upload file..."></textarea>
                </div>
            </div>
        </div>

        <!-- Unified View -->
        <div id="unified-view" class="hidden flex-1 flex flex-col bg-white dark:bg-slate-900">
            <div class="flex-1 p-4 overflow-auto">
                <div id="unified-output" class="font-mono text-sm"></div>
            </div>
        </div>
    </div>

    <!-- Diff Stats -->
    <div class="px-4 py-2 bg-gray-100 dark:bg-slate-700 border-t border-gray-200 dark:border-slate-600 flex items-center justify-between text-xs">
        <div id="diff-stats" class="flex items-center gap-4">
            <span class="text-gray-600 dark:text-gray-300">Ready to compare</span>
        </div>
        <div class="flex items-center gap-2">
            <button id="compare-btn" class="px-3 py-1 rounded bg-primary text-white font-medium hover:bg-primary/90">
                Compare
            </button>
            <button id="copy-diff-btn" class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500">
                Copy Diff
            </button>
        </div>
    </div>
</div>

<!-- jsdiff library -->
<script src="https://cdn.jsdelivr.net/npm/diff@5.1.0/dist/diff.min.js"></script>

<script>
// Elements
const textOriginal = document.getElementById('text-original');
const textModified = document.getElementById('text-modified');
const originalDisplay = document.getElementById('original-display');
const modifiedDisplay = document.getElementById('modified-display');
const uploadOriginal = document.getElementById('upload-original');
const uploadModified = document.getElementById('upload-modified');
const ignoreWhitespace = document.getElementById('ignore-whitespace');
const ignoreCase = document.getElementById('ignore-case');
const viewMode = document.getElementById('view-mode');
const splitView = document.getElementById('split-view');
const unifiedView = document.getElementById('unified-view');
const unifiedOutput = document.getElementById('unified-output');
const diffStats = document.getElementById('diff-stats');
const compareBtn = document.getElementById('compare-btn');
const copyDiffBtn = document.getElementById('copy-diff-btn');

let originalText = `function greeting(name) {
  console.log("Hello, " + name);
  return true;
}

const user = "World";
greeting(user);`;

let modifiedText = `function greeting(name) {
  console.log(\`Hello, \${name}!\`);
  return name.length > 0;
}

const user = "Developer";
const result = greeting(user);
console.log("Result:", result);`;

let currentDiff = null;

// File Upload
uploadOriginal.addEventListener('change', (e) => loadFile(e.target.files[0], 'original'));
uploadModified.addEventListener('change', (e) => loadFile(e.target.files[0], 'modified'));

function loadFile(file, target) {
    if (!file) return;
    
    const reader = new FileReader();
    reader.onload = (e) => {
        if (target === 'original') {
            originalText = e.target.result;
            textOriginal.value = originalText;
        } else {
            modifiedText = e.target.result;
            textModified.value = modifiedText;
        }
        compareDiff();
    };
    reader.readAsText(file);
}

// Editable text areas (using contenteditable on displays)
originalDisplay.contentEditable = true;
modifiedDisplay.contentEditable = true;
originalDisplay.spellcheck = false;
modifiedDisplay.spellcheck = false;

originalDisplay.addEventListener('input', () => {
    originalText = originalDisplay.textContent;
    textOriginal.value = originalText;
});

modifiedDisplay.addEventListener('input', () => {
    modifiedText = modifiedDisplay.textContent;
    textModified.value = modifiedText;
});

// View Mode Toggle
viewMode.addEventListener('change', () => {
    const mode = viewMode.value;
    if (mode === 'split') {
        splitView.classList.remove('hidden');
        unifiedView.classList.add('hidden');
        renderSplitView();
    } else {
        splitView.classList.add('hidden');
        unifiedView.classList.remove('hidden');
        renderUnifiedView();
    }
});

// Compare
compareBtn.addEventListener('click', compareDiff);
ignoreWhitespace.addEventListener('change', compareDiff);
ignoreCase.addEventListener('change', compareDiff);

function compareDiff() {
    let original = originalText;
    let modified = modifiedText;

    if (!original && !modified) {
        diffStats.innerHTML = '<span class="text-gray-600 dark:text-gray-300">Ready to compare</span>';
        return;
    }

    // Apply options
    if (ignoreWhitespace.checked) {
        original = original.replace(/\s+/g, ' ').trim();
        modified = modified.replace(/\s+/g, ' ').trim();
    }

    if (ignoreCase.checked) {
        original = original.toLowerCase();
        modified = modified.toLowerCase();
    }

    // Calculate diff
    currentDiff = Diff.diffLines(original, modified);

    // Render based on view mode
    if (viewMode.value === 'split') {
        renderSplitView();
    } else {
        renderUnifiedView();
    }

    // Update stats
    updateStats();
}

function renderSplitView() {
    if (!currentDiff) {
        originalDisplay.textContent = originalText;
        modifiedDisplay.textContent = modifiedText;
        return;
    }

    let originalHtml = '';
    let modifiedHtml = '';
    let originalLineNum = 1;
    let modifiedLineNum = 1;

    currentDiff.forEach((part) => {
        const lines = part.value.split('\n').filter(l => l || part.value.endsWith('\n'));
        
        if (part.removed) {
            // Show in original with red background
            lines.forEach(line => {
                originalHtml += `<div class="flex hover:bg-red-100 dark:hover:bg-red-900/40">
                    <span class="inline-block w-10 text-right pr-2 text-gray-400 select-none">${originalLineNum++}</span>
                    <span class="flex-1 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 px-2">${escapeHtml(line) || '&nbsp;'}</span>
                </div>`;
            });
        } else if (part.added) {
            // Show in modified with green background
            lines.forEach(line => {
                modifiedHtml += `<div class="flex hover:bg-green-100 dark:hover:bg-green-900/40">
                    <span class="inline-block w-10 text-right pr-2 text-gray-400 select-none">${modifiedLineNum++}</span>
                    <span class="flex-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 px-2">${escapeHtml(line) || '&nbsp;'}</span>
                </div>`;
            });
        } else {
            // Unchanged - show in both
            lines.forEach(line => {
                originalHtml += `<div class="flex hover:bg-gray-50 dark:hover:bg-slate-800">
                    <span class="inline-block w-10 text-right pr-2 text-gray-400 select-none">${originalLineNum++}</span>
                    <span class="flex-1 text-gray-700 dark:text-gray-300 px-2">${escapeHtml(line) || '&nbsp;'}</span>
                </div>`;
                
                modifiedHtml += `<div class="flex hover:bg-gray-50 dark:hover:bg-slate-800">
                    <span class="inline-block w-10 text-right pr-2 text-gray-400 select-none">${modifiedLineNum++}</span>
                    <span class="flex-1 text-gray-700 dark:text-gray-300 px-2">${escapeHtml(line) || '&nbsp;'}</span>
                </div>`;
            });
        }
    });

    originalDisplay.innerHTML = originalHtml;
    modifiedDisplay.innerHTML = modifiedHtml;
    originalDisplay.contentEditable = false;
    modifiedDisplay.contentEditable = false;
}

function renderUnifiedView() {
    if (!currentDiff) return;

    let html = '';
    let lineNum = 1;

    currentDiff.forEach((part) => {
        const lines = part.value.split('\n').filter(l => l);
        
        lines.forEach(line => {
            if (part.added) {
                html += `<div class="flex hover:bg-green-100 dark:hover:bg-green-900/40 bg-green-50 dark:bg-green-900/20">
                    <span class="inline-block w-10 text-right pr-2 text-green-600 dark:text-green-400 font-bold select-none">+</span>
                    <span class="flex-1 text-green-800 dark:text-green-200 px-2">${escapeHtml(line)}</span>
                </div>`;
            } else if (part.removed) {
                html += `<div class="flex hover:bg-red-100 dark:hover:bg-red-900/40 bg-red-50 dark:bg-red-900/20">
                    <span class="inline-block w-10 text-right pr-2 text-red-600 dark:text-red-400 font-bold select-none">-</span>
                    <span class="flex-1 text-red-800 dark:text-red-200 px-2">${escapeHtml(line)}</span>
                </div>`;
            } else {
                html += `<div class="flex hover:bg-gray-50 dark:hover:bg-slate-800">
                    <span class="inline-block w-10 text-right pr-2 text-gray-400 select-none">${lineNum++}</span>
                    <span class="flex-1 text-gray-700 dark:text-gray-300 px-2">${escapeHtml(line)}</span>
                </div>`;
            }
        });
    });

    unifiedOutput.innerHTML = html;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function updateStats() {
    if (!currentDiff) return;

    let additions = 0, deletions = 0, unchanged = 0;
    
    currentDiff.forEach(part => {
        const lines = part.value.split('\n').filter(l => l).length;
        if (part.added) additions += lines;
        else if (part.removed) deletions += lines;
        else unchanged += lines;
    });

    diffStats.innerHTML = `
        <span class="text-green-600 dark:text-green-400 font-semibold">+${additions} additions</span>
        <span class="text-red-600 dark:text-red-400 font-semibold">-${deletions} deletions</span>
        <span class="text-gray-600 dark:text-gray-300">${unchanged} unchanged</span>
    `;
}

// Copy Diff
copyDiffBtn.addEventListener('click', async () => {
    if (!currentDiff) {
        alert('Please compare first');
        return;
    }

    let output = '';
    currentDiff.forEach((part) => {
        const prefix = part.added ? '+ ' : part.removed ? '- ' : '  ';
        part.value.split('\n').forEach(line => {
            if (line) output += prefix + line + '\n';
        });
    });

    try {
        await navigator.clipboard.writeText(output);
        const originalText = copyDiffBtn.textContent;
        copyDiffBtn.textContent = '✓ Copied!';
        setTimeout(() => {
            copyDiffBtn.textContent = originalText;
        }, 2000);
    } catch (error) {
        alert('Failed to copy: ' + error.message);
    }
});

// Initialize with sample data
textOriginal.value = originalText;
textModified.value = modifiedText;
compareDiff();
</script>
