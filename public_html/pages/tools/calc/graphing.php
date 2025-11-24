<?php
/**
 * Graphing Calculator (function-plot)
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)] bg-gray-50 dark:bg-slate-900">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">monitoring</span>
            Graphing Calculator
        </h1>
    </div>

    <div class="flex flex-1 overflow-hidden">
        <!-- Sidebar (Controls) -->
        <div class="w-80 bg-white dark:bg-slate-800 border-r border-gray-200 dark:border-slate-700 flex flex-col z-10 shadow-lg">
            <div class="p-4 border-b border-gray-200 dark:border-slate-700">
                <h2 class="font-bold text-gray-900 dark:text-white mb-2">Functions</h2>
                <button onclick="addFunction()" class="w-full py-2 px-4 bg-primary/10 text-primary hover:bg-primary/20 rounded-lg text-sm font-medium transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">add</span> Add Function
                </button>
            </div>
            
            <div id="functions-list" class="flex-1 overflow-y-auto p-4 space-y-4">
                <!-- Function Item Template -->
                <div class="function-item group relative">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400">f(x) =</label>
                    </div>
                    <input type="text" value="x^2" 
                           class="fn-input w-full px-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg text-sm font-mono focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                           placeholder="e.g. sin(x)">
                    <button onclick="removeFunction(this)" class="absolute top-0 right-0 p-1 text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                </div>
            </div>

            <div class="p-4 border-t border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50">
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">X Min</label>
                        <input type="number" id="x-min" value="-10" class="w-full px-2 py-1 rounded border dark:bg-slate-800 dark:border-slate-700">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">X Max</label>
                        <input type="number" id="x-max" value="10" class="w-full px-2 py-1 rounded border dark:bg-slate-800 dark:border-slate-700">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Y Min</label>
                        <input type="number" id="y-min" value="-10" class="w-full px-2 py-1 rounded border dark:bg-slate-800 dark:border-slate-700">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Y Max</label>
                        <input type="number" id="y-max" value="10" class="w-full px-2 py-1 rounded border dark:bg-slate-800 dark:border-slate-700">
                    </div>
                </div>
                <button onclick="updatePlot()" class="w-full mt-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary/90 transition-colors">
                    Update Graph
                </button>
            </div>
        </div>

        <!-- Graph Area -->
        <div class="flex-1 bg-white dark:bg-slate-900 relative overflow-hidden" id="plot-container">
            <div id="plot" class="w-full h-full"></div>
        </div>
    </div>
</div>

<!-- Load D3 and Function Plot -->
<script src="https://unpkg.com/d3@3/d3.min.js"></script>
<script src="https://unpkg.com/function-plot@1/dist/function-plot.js"></script>

<script>
    const colors = ['#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899'];
    let colorIndex = 1;

    function init() {
        updatePlot();
        
        // Add event listeners to initial inputs
        document.querySelectorAll('.fn-input').forEach(input => {
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') updatePlot();
            });
        });
    }

    function addFunction() {
        const container = document.getElementById('functions-list');
        const color = colors[colorIndex % colors.length];
        colorIndex++;

        const div = document.createElement('div');
        div.className = 'function-item group relative';
        div.innerHTML = `
            <div class="flex items-center gap-2 mb-1">
                <span class="w-3 h-3 rounded-full" style="background-color: ${color}"></span>
                <label class="text-xs font-medium text-gray-500 dark:text-gray-400">f(x) =</label>
            </div>
            <input type="text" value="" 
                   class="fn-input w-full px-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg text-sm font-mono focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                   placeholder="e.g. cos(x)">
            <button onclick="removeFunction(this)" class="absolute top-0 right-0 p-1 text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        `;
        
        container.appendChild(div);
        
        const input = div.querySelector('input');
        input.focus();
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') updatePlot();
        });
    }

    function removeFunction(btn) {
        btn.closest('.function-item').remove();
        updatePlot();
    }

    function updatePlot() {
        const inputs = document.querySelectorAll('.fn-input');
        const data = [];
        
        inputs.forEach((input, index) => {
            const val = input.value.trim();
            if (val) {
                data.push({
                    fn: val,
                    color: index === 0 ? colors[0] : colors[(index) % colors.length]
                });
            }
        });

        const width = document.getElementById('plot-container').clientWidth;
        const height = document.getElementById('plot-container').clientHeight;
        
        const xMin = parseFloat(document.getElementById('x-min').value);
        const xMax = parseFloat(document.getElementById('x-max').value);
        const yMin = parseFloat(document.getElementById('y-min').value);
        const yMax = parseFloat(document.getElementById('y-max').value);

        try {
            document.getElementById('plot').innerHTML = '';
            functionPlot({
                target: '#plot',
                width: width,
                height: height,
                yAxis: { domain: [yMin, yMax] },
                xAxis: { domain: [xMin, xMax] },
                grid: true,
                data: data
            });
        } catch (e) {
            console.error(e);
        }
    }

    // Handle resize
    window.addEventListener('resize', () => {
        updatePlot();
    });

    // Initial load
    setTimeout(init, 100);
</script>
