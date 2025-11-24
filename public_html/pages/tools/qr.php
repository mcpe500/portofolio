<?php
/**
 * QR Code Generator Tool
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">qr_code_2</span>
            QR Code Generator
        </h1>
        <div class="text-xs md:text-sm text-gray-500 dark:text-gray-400">
            Type text or URL, then generate & download.
        </div>
    </div>

    <div class="flex flex-1 flex-col md:flex-row overflow-hidden">
        <!-- Controls -->
        <div class="w-full md:w-1/3 border-b md:border-b-0 md:border-r border-gray-200 dark:border-slate-700 p-4 space-y-4 bg-white dark:bg-slate-900">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Text / URL
                </label>
                <textarea
                    id="qr-text"
                    class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                    rows="4"
                    placeholder="https://example.com or any text..."
                >https://example.com</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Size
                    </label>
                    <input
                        id="qr-size"
                        type="number"
                        min="64"
                        max="1024"
                        step="16"
                        value="256"
                        class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Error Level
                    </label>
                    <select
                        id="qr-ecc"
                        class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                    >
                        <option value="L">L (Low)</option>
                        <option value="M" selected>M (Medium)</option>
                        <option value="Q">Q (Quartile)</option>
                        <option value="H">H (High)</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 pt-2">
                <button
                    id="qr-generate"
                    class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary"
                >
                    <span class="material-symbols-outlined text-sm">play_arrow</span>
                    Generate
                </button>

                <button
                    id="qr-download"
                    class="inline-flex items-center gap-2 rounded-lg bg-gray-100 dark:bg-slate-800 px-4 py-2 text-sm font-medium text-gray-800 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-primary"
                >
                    <span class="material-symbols-outlined text-sm">download</span>
                    Download PNG
                </button>
            </div>

            <p class="text-xs text-gray-500 dark:text-gray-400">
                Uses client-side generation; your content is not sent to any server.
            </p>
        </div>

        <!-- Preview -->
        <div class="flex-1 flex items-center justify-center bg-gray-50 dark:bg-slate-900">
            <div class="flex flex-col items-center gap-4">
                <div
                    id="qr-output"
                    class="flex items-center justify-center bg-white dark:bg-slate-800 rounded-xl shadow p-4 min-h-[260px] min-w-[260px]"
                ></div>
                <p class="text-xs text-gray-500 dark:text-gray-400 text-center max-w-xs">
                    Tip: increase size for printing, smaller size for embedding in UIs.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- QRCode.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    const qrContainer = document.getElementById('qr-output');
    const qrText = document.getElementById('qr-text');
    const qrSize = document.getElementById('qr-size');
    const qrECC = document.getElementById('qr-ecc');
    const qrGenerateBtn = document.getElementById('qr-generate');
    const qrDownloadBtn = document.getElementById('qr-download');

    let qrInstance = null;

    function generateQR() {
        const text = qrText.value.trim() || ' ';
        const size = Math.max(64, Math.min(1024, parseInt(qrSize.value || '256', 10)));
        const ecc = qrECC.value || 'M';

        qrContainer.innerHTML = '';

        qrInstance = new QRCode(qrContainer, {
            text,
            width: size,
            height: size,
            correctLevel: QRCode.CorrectLevel[ecc] || QRCode.CorrectLevel.M
        });
    }

    function downloadQR() {
        if (!qrInstance) {
            generateQR();
        }

        const img = qrContainer.querySelector('img');
        const canvas = qrContainer.querySelector('canvas');

        let dataUrl = null;
        if (canvas) {
            dataUrl = canvas.toDataURL('image/png');
        } else if (img && img.src) {
            dataUrl = img.src;
        }

        if (!dataUrl) return;

        const link = document.createElement('a');
        link.href = dataUrl;
        link.download = 'qr-code.png';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    qrGenerateBtn.addEventListener('click', generateQR);
    qrDownloadBtn.addEventListener('click', downloadQR);

    // Initial QR
    generateQR();
</script>
