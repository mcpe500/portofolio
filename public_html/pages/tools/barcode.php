<?php
/**
 * Barcode Generator Tool
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">barcode</span>
            Barcode Generator
        </h1>
        <div class="text-xs md:text-sm text-gray-500 dark:text-gray-400">
            Supports CODE128, EAN-13, UPC, ITF.
        </div>
    </div>

    <div class="flex flex-1 flex-col md:flex-row overflow-hidden">
        <!-- Controls -->
        <div class="w-full md:w-1/3 border-b md:border-b-0 md:border-r border-gray-200 dark:border-slate-700 p-4 space-y-4 bg-white dark:bg-slate-900">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Barcode Value
                </label>
                <input
                    id="barcode-value"
                    type="text"
                    value="123456789012"
                    class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                    placeholder="123456789012"
                />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Make sure the value is valid for the selected format (e.g., length & checksum).
                </p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Format
                    </label>
                    <select
                        id="barcode-format"
                        class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                    >
                        <option value="CODE128">CODE128</option>
                        <option value="EAN13">EAN-13</option>
                        <option value="UPC">UPC-A</option>
                        <option value="ITF">ITF</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Height
                    </label>
                    <input
                        id="barcode-height"
                        type="number"
                        value="80"
                        min="40"
                        max="200"
                        class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                    />
                </div>
            </div>

            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input id="barcode-display" type="checkbox" class="rounded border-gray-300 text-primary focus:ring-primary" checked />
                    Show value under barcode
                </label>
            </div>

            <div class="flex flex-wrap gap-3 pt-2">
                <button
                    id="barcode-generate"
                    class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary"
                >
                    <span class="material-symbols-outlined text-sm">play_arrow</span>
                    Generate
                </button>
            </div>
        </div>

        <!-- Preview -->
        <div class="flex-1 flex items-center justify-center bg-gray-50 dark:bg-slate-900">
            <div class="flex flex-col items-center gap-4">
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow p-6 min-w-[260px]">
                    <svg id="barcode-output"></svg>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 text-center max-w-xs">
                    You can right-click and save the SVG for printing or use it directly in your UI.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- JsBarcode -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js" crossorigin="anonymous"></script>

<script>
    const barcodeValue = document.getElementById('barcode-value');
    const barcodeFormat = document.getElementById('barcode-format');
    const barcodeHeight = document.getElementById('barcode-height');
    const barcodeDisplay = document.getElementById('barcode-display');
    const barcodeGenerateBtn = document.getElementById('barcode-generate');
    const barcodeOutput = document.getElementById('barcode-output');

    function generateBarcode() {
        const value = barcodeValue.value.trim();
        if (!value) return;

        const format = barcodeFormat.value || 'CODE128';
        const height = Math.max(40, Math.min(200, parseInt(barcodeHeight.value || '80', 10)));
        const displayValue = barcodeDisplay.checked;

        try {
            JsBarcode(barcodeOutput, value, {
                format,
                lineColor: "#111111",
                width: 2,
                height,
                displayValue,
                fontSize: 14,
                margin: 10
            });
        } catch (e) {
            alert('Error generating barcode: ' + e.message);
        }
    }

    barcodeGenerateBtn.addEventListener('click', generateBarcode);

    // Initial render
    generateBarcode();
</script>
