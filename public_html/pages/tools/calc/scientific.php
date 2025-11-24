<?php
/**
 * Scientific Calculator (Math.js)
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)] bg-gray-50 dark:bg-slate-900">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">calculate</span>
            Scientific Calculator
        </h1>
    </div>

    <div class="flex flex-1 overflow-hidden">
        <!-- Calculator UI -->
        <div class="flex-1 flex flex-col max-w-3xl mx-auto w-full p-4">
            
            <!-- Display -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-gray-200 dark:border-slate-700 mb-4 overflow-hidden flex flex-col">
                <!-- History -->
                <div id="history-display" class="h-24 p-4 text-right text-gray-500 dark:text-gray-400 text-sm overflow-y-auto font-mono"></div>
                <!-- Current Input -->
                <input type="text" id="calc-display" 
                       class="w-full p-4 text-right text-3xl font-mono bg-transparent border-none focus:ring-0 text-gray-900 dark:text-white placeholder-gray-300 dark:placeholder-slate-600" 
                       placeholder="0" autofocus>
                <!-- Result Preview -->
                <div id="result-preview" class="h-8 px-4 text-right text-gray-400 dark:text-gray-500 text-lg font-mono"></div>
            </div>

            <!-- Keypad -->
            <div class="grid grid-cols-5 gap-2 flex-1">
                <!-- Row 1: Scientific Functions -->
                <button class="calc-btn fn" data-val="sin(">sin</button>
                <button class="calc-btn fn" data-val="cos(">cos</button>
                <button class="calc-btn fn" data-val="tan(">tan</button>
                <button class="calc-btn fn" data-val="log(">log</button>
                <button class="calc-btn fn" data-val="ln(">ln</button>

                <!-- Row 2 -->
                <button class="calc-btn fn" data-val="sqrt(">√</button>
                <button class="calc-btn fn" data-val="^">^</button>
                <button class="calc-btn fn" data-val="(">(</button>
                <button class="calc-btn fn" data-val=")">)</button>
                <button class="calc-btn op" data-val="/">÷</button>

                <!-- Row 3 -->
                <button class="calc-btn fn" data-val="pi">π</button>
                <button class="calc-btn num" data-val="7">7</button>
                <button class="calc-btn num" data-val="8">8</button>
                <button class="calc-btn num" data-val="9">9</button>
                <button class="calc-btn op" data-val="*">×</button>

                <!-- Row 4 -->
                <button class="calc-btn fn" data-val="e">e</button>
                <button class="calc-btn num" data-val="4">4</button>
                <button class="calc-btn num" data-val="5">5</button>
                <button class="calc-btn num" data-val="6">6</button>
                <button class="calc-btn op" data-val="-">−</button>

                <!-- Row 5 -->
                <button class="calc-btn fn" data-val="abs(">abs</button>
                <button class="calc-btn num" data-val="1">1</button>
                <button class="calc-btn num" data-val="2">2</button>
                <button class="calc-btn num" data-val="3">3</button>
                <button class="calc-btn op" data-val="+">+</button>

                <!-- Row 6 -->
                <button class="calc-btn action bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400" onclick="clearAll()">AC</button>
                <button class="calc-btn action" onclick="backspace()">⌫</button>
                <button class="calc-btn num" data-val="0">0</button>
                <button class="calc-btn num" data-val=".">.</button>
                <button class="calc-btn action bg-primary text-white hover:bg-primary/90" onclick="calculate()">=</button>
            </div>
        </div>

        <!-- Sidebar (Variables/Help) -->
        <div class="hidden lg:flex w-80 border-l border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 flex-col">
            <div class="p-4 border-b border-gray-200 dark:border-slate-700">
                <h2 class="font-bold text-gray-900 dark:text-white">Variables & Constants</h2>
            </div>
            <div class="p-4 overflow-y-auto text-sm text-gray-600 dark:text-gray-400 space-y-2">
                <p><code class="bg-gray-100 dark:bg-slate-700 px-1 rounded">ans</code> : Last result</p>
                <p><code class="bg-gray-100 dark:bg-slate-700 px-1 rounded">pi</code> : 3.14159...</p>
                <p><code class="bg-gray-100 dark:bg-slate-700 px-1 rounded">e</code> : 2.71828...</p>
                <hr class="my-2 border-gray-200 dark:border-slate-700">
                <h3 class="font-semibold mb-2">Examples</h3>
                <button class="block w-full text-left hover:bg-gray-50 dark:hover:bg-slate-700 p-1 rounded" onclick="insert('sin(45 deg)')">sin(45 deg)</button>
                <button class="block w-full text-left hover:bg-gray-50 dark:hover:bg-slate-700 p-1 rounded" onclick="insert('sqrt(16) + 2^3')">sqrt(16) + 2^3</button>
                <button class="block w-full text-left hover:bg-gray-50 dark:hover:bg-slate-700 p-1 rounded" onclick="insert('det([-1, 2; 3, 1])')">det([-1, 2; 3, 1])</button>
            </div>
        </div>
    </div>
</div>

<style>
    .calc-btn {
        @apply rounded-xl text-lg font-medium transition-all active:scale-95 flex items-center justify-center shadow-sm border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-slate-700 h-14;
    }
    .calc-btn.fn {
        @apply text-sm font-bold text-primary dark:text-blue-400 bg-blue-50 dark:bg-blue-900/10;
    }
    .calc-btn.op {
        @apply text-xl text-primary dark:text-blue-400 bg-blue-50 dark:bg-blue-900/10;
    }
</style>

<!-- Load Math.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/12.4.0/math.js"></script>

<script>
    const display = document.getElementById('calc-display');
    const historyDisplay = document.getElementById('history-display');
    const previewDisplay = document.getElementById('result-preview');
    let lastAns = 0;

    // Initialize Math.js
    const parser = math.parser();

    // Button Click Handlers
    document.querySelectorAll('.calc-btn[data-val]').forEach(btn => {
        btn.addEventListener('click', () => {
            insert(btn.dataset.val);
        });
    });

    display.addEventListener('input', () => {
        updatePreview();
    });

    display.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            calculate();
        }
    });

    function insert(val) {
        const start = display.selectionStart;
        const end = display.selectionEnd;
        const text = display.value;
        display.value = text.substring(0, start) + val + text.substring(end);
        display.selectionStart = display.selectionEnd = start + val.length;
        display.focus();
        updatePreview();
    }

    function clearAll() {
        display.value = '';
        previewDisplay.textContent = '';
        display.focus();
    }

    function backspace() {
        const start = display.selectionStart;
        const end = display.selectionEnd;
        const text = display.value;
        if (start === end && start > 0) {
            display.value = text.substring(0, start - 1) + text.substring(end);
            display.selectionStart = display.selectionEnd = start - 1;
        } else {
            display.value = text.substring(0, start) + text.substring(end);
            display.selectionStart = display.selectionEnd = start;
        }
        display.focus();
        updatePreview();
    }

    function updatePreview() {
        const expr = display.value.trim();
        if (!expr) {
            previewDisplay.textContent = '';
            return;
        }
        try {
            // Use a temporary parser for preview to avoid side effects
            const res = math.evaluate(expr, { ans: lastAns });
            if (res !== undefined && typeof res !== 'function') {
                previewDisplay.textContent = math.format(res, { precision: 14 });
            } else {
                previewDisplay.textContent = '';
            }
        } catch (e) {
            previewDisplay.textContent = '';
        }
    }

    function calculate() {
        const expr = display.value.trim();
        if (!expr) return;

        try {
            const result = parser.evaluate(expr);
            lastAns = result;
            
            // Add to history
            const historyItem = document.createElement('div');
            historyItem.className = 'mb-1 hover:bg-gray-100 dark:hover:bg-slate-700 p-1 rounded cursor-pointer';
            historyItem.innerHTML = `<span class="text-gray-400">${expr} =</span> <span class="font-bold text-gray-800 dark:text-gray-200">${math.format(result, { precision: 14 })}</span>`;
            historyItem.onclick = () => {
                display.value = expr;
                updatePreview();
            };
            historyDisplay.appendChild(historyItem);
            historyDisplay.scrollTop = historyDisplay.scrollHeight;

            // Update display
            display.value = math.format(result, { precision: 14 });
            previewDisplay.textContent = '';
        } catch (e) {
            previewDisplay.textContent = 'Error';
            previewDisplay.classList.add('text-red-500');
            setTimeout(() => previewDisplay.classList.remove('text-red-500'), 2000);
        }
    }
</script>
