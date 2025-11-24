<?php
/**
 * Unit Converter
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)] bg-gray-50 dark:bg-slate-900">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">straighten</span>
            Unit Converter
        </h1>
    </div>

    <div class="flex flex-1 items-center justify-center p-4">
        <div class="w-full max-w-2xl bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-gray-200 dark:border-slate-700 p-6 md:p-8">
            
            <!-- Category Tabs -->
            <div class="flex space-x-2 overflow-x-auto pb-4 mb-6 border-b border-gray-100 dark:border-slate-700 no-scrollbar">
                <button class="tab-btn active" data-cat="length">Length</button>
                <button class="tab-btn" data-cat="mass">Mass</button>
                <button class="tab-btn" data-cat="temperature">Temperature</button>
                <button class="tab-btn" data-cat="volume">Volume</button>
                <button class="tab-btn" data-cat="data">Data</button>
            </div>

            <!-- Converter UI -->
            <div class="grid grid-cols-1 md:grid-cols-[1fr,auto,1fr] gap-4 items-center">
                <!-- From -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">From</label>
                    <input
                        type="number"
                        id="input-val"
                        value="1"
                        step="any"
                        class="w-full p-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-lg focus:ring-2 focus:ring-primary outline-none transition-all">
                    <select id="from-unit" class="w-full p-2 bg-transparent border-none text-gray-700 dark:text-gray-300 font-medium focus:ring-0 cursor-pointer"></select>
                </div>

                <!-- Swap Icon -->
                <div class="flex justify-center">
                    <button onclick="swapUnits()" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-slate-700 text-gray-400 transition-colors">
                        <span class="material-symbols-outlined">sync_alt</span>
                    </button>
                </div>

                <!-- To -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">To</label>
                    <div id="output-val" class="w-full p-3 bg-gray-100 dark:bg-slate-700/50 border border-transparent rounded-xl text-lg font-bold text-gray-900 dark:text-white truncate">
                        ---
                    </div>
                    <select id="to-unit" class="w-full p-2 bg-transparent border-none text-gray-700 dark:text-gray-300 font-medium focus:ring-0 cursor-pointer"></select>
                </div>
            </div>

            <!-- Formula Display -->
            <div class="mt-8 p-4 bg-blue-50 dark:bg-blue-900/10 rounded-xl text-center">
                <p class="text-sm text-blue-600 dark:text-blue-400" id="formula-display"></p>
            </div>

        </div>
    </div>
</div>

<style>
    /* Hide scrollbar helper (optional) */
    .no-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;     /* Firefox */
    }
    .no-scrollbar::-webkit-scrollbar {
        display: none;             /* Chrome, Safari */
    }

    /* Replacing Tailwind @apply with plain CSS */
    .tab-btn {
        padding: 0.5rem 1rem;           /* px-4 py-2 */
        border-radius: 0.5rem;          /* rounded-lg */
        font-size: 0.875rem;            /* text-sm */
        font-weight: 500;               /* font-medium */
        color: #6b7280;                 /* gray-500 */
        white-space: nowrap;
        background: transparent;
        border: none;
        cursor: pointer;
        transition:
            background-color 150ms ease,
            color 150ms ease,
            box-shadow 150ms ease;
    }
    .dark .tab-btn {
        color: #9ca3af;                 /* gray-400 */
    }
    .tab-btn:hover {
        background-color: #f3f4f6;      /* gray-100 */
    }
    .dark .tab-btn:hover {
        background-color: #374151;      /* slate-700-ish */
    }
    .tab-btn.active {
        background-color: #3b82f6;      /* primary-ish */
        color: #ffffff;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .tab-btn.active:hover {
        background-color: #2563eb;      /* slightly darker */
    }
</style>

<script>
    const units = {
        length: {
            base: 'm',
            rates: {
                'nm': 1e-9, 'um': 1e-6, 'mm': 0.001, 'cm': 0.01, 'm': 1, 'km': 1000,
                'in': 0.0254, 'ft': 0.3048, 'yd': 0.9144, 'mi': 1609.34
            }
        },
        mass: {
            base: 'kg',
            rates: {
                'mg': 1e-6, 'g': 0.001, 'kg': 1, 't': 1000,
                'oz': 0.0283495, 'lb': 0.453592
            }
        },
        volume: {
            base: 'l',
            rates: {
                'ml': 0.001, 'l': 1, 'm3': 1000,
                'tsp': 0.00492892, 'tbsp': 0.0147868, 'fl oz': 0.0295735,
                'cup': 0.236588, 'pt': 0.473176, 'qt': 0.946353, 'gal': 3.78541
            }
        },
        data: {
            base: 'b',
            rates: {
                'b': 1,
                'B': 8,
                'KB': 8192,
                'MB': 8388608,
                'GB': 8589934592,
                'TB': 8796093022208
            }
        },
        temperature: {
            special: true // Handled separately
        }
    };

    let currentCat = 'length';

    // Elements
    const inputVal = document.getElementById('input-val');
    const outputVal = document.getElementById('output-val');
    const fromUnit = document.getElementById('from-unit');
    const toUnit = document.getElementById('to-unit');
    const formulaDisplay = document.getElementById('formula-display');

    // Init tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentCat = btn.dataset.cat;
            populateUnits();
            convert();
        });
    });

    inputVal.addEventListener('input', convert);
    fromUnit.addEventListener('change', convert);
    toUnit.addEventListener('change', convert);

    function populateUnits() {
        fromUnit.innerHTML = '';
        toUnit.innerHTML = '';
        
        let keys;
        if (currentCat === 'temperature') {
            keys = ['Celsius', 'Fahrenheit', 'Kelvin'];
        } else {
            keys = Object.keys(units[currentCat].rates);
        }

        keys.forEach(key => {
            fromUnit.add(new Option(key, key));
            toUnit.add(new Option(key, key));
        });

        // Set defaults
        if (currentCat === 'length') {
            toUnit.value = 'km';
        }
        if (currentCat === 'temperature') {
            toUnit.value = 'Fahrenheit';
        }
    }

    function convert() {
        const val = parseFloat(inputVal.value);
        if (isNaN(val)) {
            outputVal.textContent = '---';
            formulaDisplay.textContent = '';
            return;
        }

        const from = fromUnit.value;
        const to = toUnit.value;
        let result;

        if (currentCat === 'temperature') {
            result = convertTemp(val, from, to);
            formulaDisplay.textContent = getTempFormula(from, to);
        } else {
            const rates = units[currentCat].rates;
            const baseVal = val * rates[from];      // convert to base unit
            result = baseVal / rates[to];           // convert from base to target
            formulaDisplay.textContent =
                `1 ${from} = ${(rates[from] / rates[to]).toPrecision(4)} ${to}`;
        }

        outputVal.textContent = result.toLocaleString(undefined, {
            maximumFractionDigits: 6
        });
    }

    function convertTemp(val, from, to) {
        if (from === to) return val;
        let c;
        // To Celsius
        if (from === 'Celsius') c = val;
        else if (from === 'Fahrenheit') c = (val - 32) * 5 / 9;
        else if (from === 'Kelvin') c = val - 273.15;

        // From Celsius
        if (to === 'Celsius') return c;
        if (to === 'Fahrenheit') return c * 9 / 5 + 32;
        if (to === 'Kelvin') return c + 273.15;
    }

    function getTempFormula(from, to) {
        if (from === to) return '';
        return `Conversion from ${from} to ${to}`;
    }

    function swapUnits() {
        const temp = fromUnit.value;
        fromUnit.value = toUnit.value;
        toUnit.value = temp;
        convert();
    }

    // Start
    populateUnits();
    convert();
</script>
