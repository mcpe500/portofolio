<?php
/**
 * Programmer Calculator (Hex/Bin/Oct/Dec)
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)] bg-gray-50 dark:bg-slate-900">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">terminal</span>
            Programmer Calculator
        </h1>
    </div>

    <div class="flex flex-1 overflow-hidden p-4">
        <div class="w-full max-w-4xl mx-auto bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-gray-200 dark:border-slate-700 p-6 flex flex-col">
            
            <!-- Display Area -->
            <div class="mb-6 space-y-2">
                <!-- Main Display -->
                <div class="text-right">
                    <div id="main-display" class="text-5xl font-mono font-bold text-gray-900 dark:text-white tracking-wider">0</div>
                </div>
                
                <!-- Base Conversions -->
                <div class="grid grid-cols-[auto,1fr] gap-x-4 gap-y-2 text-sm font-mono mt-4 p-4 bg-gray-50 dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-700">
                    <div class="text-gray-500 dark:text-gray-400">HEX</div>
                    <div id="hex-display" class="text-primary font-bold cursor-pointer hover:bg-gray-200 dark:hover:bg-slate-800 px-2 rounded">0</div>
                    
                    <div class="text-gray-500 dark:text-gray-400">DEC</div>
                    <div id="dec-display" class="text-gray-900 dark:text-white font-bold cursor-pointer hover:bg-gray-200 dark:hover:bg-slate-800 px-2 rounded">0</div>
                    
                    <div class="text-gray-500 dark:text-gray-400">OCT</div>
                    <div id="oct-display" class="text-gray-900 dark:text-white font-bold cursor-pointer hover:bg-gray-200 dark:hover:bg-slate-800 px-2 rounded">0</div>
                    
                    <div class="text-gray-500 dark:text-gray-400">BIN</div>
                    <div id="bin-display" class="text-gray-900 dark:text-white font-bold break-all cursor-pointer hover:bg-gray-200 dark:hover:bg-slate-800 px-2 rounded">0</div>
                </div>
            </div>

            <!-- Keypad -->
            <div class="grid grid-cols-4 gap-2 flex-1">
                <!-- Row 1 -->
                <button class="prog-btn hex" data-val="A">A</button>
                <button class="prog-btn hex" data-val="B">B</button>
                <button class="prog-btn hex" data-val="C">C</button>
                <button class="prog-btn op bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400" onclick="clearAll()">AC</button>

                <!-- Row 2 -->
                <button class="prog-btn hex" data-val="D">D</button>
                <button class="prog-btn hex" data-val="E">E</button>
                <button class="prog-btn hex" data-val="F">F</button>
                <button class="prog-btn op" data-val="/">÷</button>

                <!-- Row 3 -->
                <button class="prog-btn num" data-val="7">7</button>
                <button class="prog-btn num" data-val="8">8</button>
                <button class="prog-btn num" data-val="9">9</button>
                <button class="prog-btn op" data-val="*">×</button>

                <!-- Row 4 -->
                <button class="prog-btn num" data-val="4">4</button>
                <button class="prog-btn num" data-val="5">5</button>
                <button class="prog-btn num" data-val="6">6</button>
                <button class="prog-btn op" data-val="-">−</button>

                <!-- Row 5 -->
                <button class="prog-btn num" data-val="1">1</button>
                <button class="prog-btn num" data-val="2">2</button>
                <button class="prog-btn num" data-val="3">3</button>
                <button class="prog-btn op" data-val="+">+</button>

                <!-- Row 6 -->
                <button class="prog-btn num col-span-2" data-val="0">0</button>
                <button class="prog-btn action" onclick="backspace()">⌫</button>
                <button class="prog-btn action bg-primary text-white hover:bg-primary/90" onclick="calculate()">=</button>
            </div>
            
            <!-- Bitwise Ops (Bottom) -->
            <div class="grid grid-cols-4 gap-2 mt-2">
                <button class="prog-btn bit" onclick="bitwise('AND')">AND</button>
                <button class="prog-btn bit" onclick="bitwise('OR')">OR</button>
                <button class="prog-btn bit" onclick="bitwise('XOR')">XOR</button>
                <button class="prog-btn bit" onclick="bitwise('NOT')">NOT</button>
            </div>

        </div>
    </div>
</div>

<style>
    .prog-btn {
        @apply rounded-xl text-lg font-medium transition-all active:scale-95 flex items-center justify-center shadow-sm border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-slate-700 h-12;
    }
    .prog-btn.hex {
        @apply text-gray-400 dark:text-gray-500 text-sm;
    }
    .prog-btn.op {
        @apply text-xl text-primary dark:text-blue-400 bg-blue-50 dark:bg-blue-900/10;
    }
    .prog-btn.bit {
        @apply text-xs font-bold uppercase tracking-wider bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300;
    }
</style>

<script>
    let currentVal = '0';
    let currentOp = null;
    let prevVal = null;
    let isNewInput = false;

    const mainDisplay = document.getElementById('main-display');
    const hexDisplay = document.getElementById('hex-display');
    const decDisplay = document.getElementById('dec-display');
    const octDisplay = document.getElementById('oct-display');
    const binDisplay = document.getElementById('bin-display');

    document.querySelectorAll('.prog-btn[data-val]').forEach(btn => {
        btn.addEventListener('click', () => input(btn.dataset.val));
    });

    document.querySelectorAll('.prog-btn.op[data-val]').forEach(btn => {
        btn.addEventListener('click', () => setOp(btn.dataset.val));
    });

    function updateDisplays() {
        // Parse current value as integer (assuming input is HEX for now if letters, else DEC? Let's stick to DEC input logic for simplicity or handle HEX input)
        // Actually, programmer calcs usually have a "mode". Let's assume DEC mode for input, but allow A-F if we switch modes.
        // For MVP, let's treat input as HEX if it contains letters, otherwise DEC.
        
        let val = parseInt(currentVal, 16); // Treat internal state as Hex string or just number?
        // Let's store currentVal as a STRING representing the number in the CURRENT BASE.
        // For simplicity, let's assume standard calculator behavior: Input is Decimal unless A-F used?
        // Let's enforce Hex mode for simplicity since we have A-F buttons.
        
        // REVISION: Let's store the value as a BigInt or Number.
        // Let's assume input is Hexadecimal for maximum flexibility.
        
        let num = parseInt(currentVal, 16);
        if (isNaN(num)) num = 0;

        mainDisplay.textContent = currentVal.toUpperCase();
        
        hexDisplay.textContent = num.toString(16).toUpperCase();
        decDisplay.textContent = num.toString(10);
        octDisplay.textContent = num.toString(8);
        binDisplay.textContent = num.toString(2);
    }

    function input(val) {
        if (currentVal === '0' || isNewInput) {
            currentVal = val;
            isNewInput = false;
        } else {
            currentVal += val;
        }
        updateDisplays();
    }

    function clearAll() {
        currentVal = '0';
        prevVal = null;
        currentOp = null;
        updateDisplays();
    }

    function backspace() {
        if (currentVal.length > 1) {
            currentVal = currentVal.slice(0, -1);
        } else {
            currentVal = '0';
        }
        updateDisplays();
    }

    function setOp(op) {
        prevVal = parseInt(currentVal, 16);
        currentOp = op;
        isNewInput = true;
    }

    function calculate() {
        if (currentOp && prevVal !== null) {
            const curr = parseInt(currentVal, 16);
            let result = 0;
            switch (currentOp) {
                case '+': result = prevVal + curr; break;
                case '-': result = prevVal - curr; break;
                case '*': result = prevVal * curr; break;
                case '/': result = Math.floor(prevVal / curr); break;
            }
            currentVal = result.toString(16).toUpperCase();
            currentOp = null;
            prevVal = null;
            isNewInput = true;
            updateDisplays();
        }
    }

    function bitwise(op) {
        const curr = parseInt(currentVal, 16);
        let result = 0;
        switch (op) {
            case 'NOT': result = ~curr; break;
            // For binary ops, we need a previous value. This is a simplification.
            // Let's make AND/OR/XOR act as operators.
        }
        
        if (op === 'NOT') {
            currentVal = (result >>> 0).toString(16).toUpperCase(); // Unsigned shift for 32-bit
            updateDisplays();
            isNewInput = true;
        } else {
            // Set op
             prevVal = parseInt(currentVal, 16);
             currentOp = op; // Custom handling needed in calculate
             isNewInput = true;
        }
    }
    
    // Override calculate for bitwise
    const oldCalculate = calculate;
    calculate = function() {
        if (['AND', 'OR', 'XOR'].includes(currentOp)) {
            const curr = parseInt(currentVal, 16);
            let result = 0;
            switch (currentOp) {
                case 'AND': result = prevVal & curr; break;
                case 'OR': result = prevVal | curr; break;
                case 'XOR': result = prevVal ^ curr; break;
            }
            currentVal = (result >>> 0).toString(16).toUpperCase();
            currentOp = null;
            prevVal = null;
            isNewInput = true;
            updateDisplays();
        } else {
            oldCalculate();
        }
    }
</script>
