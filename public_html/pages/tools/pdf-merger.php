<?php
/**
 * PDF Merger
 * Merge multiple PDF files into a single document
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">picture_as_pdf</span>
            PDF Merger
        </h1>
        <div class="flex items-center gap-2 text-xs">
            <button id="add-files-btn" class="flex items-center gap-1 px-3 py-1 rounded bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                <span class="material-symbols-outlined text-sm">add</span>
                Add Files
            </button>
            <button id="clear-all-btn" class="hidden flex items-center gap-1 px-3 py-1 rounded bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors">
                <span class="material-symbols-outlined text-sm">delete_sweep</span>
                Clear All
            </button>
            <input type="file" id="file-input" class="hidden" accept="application/pdf" multiple>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex overflow-hidden">
        <!-- File List (Left/Top) -->
        <div class="w-full md:w-1/2 lg:w-2/5 border-r border-gray-200 dark:border-slate-700 flex flex-col bg-white dark:bg-slate-900">
            <div class="p-4 border-b border-gray-200 dark:border-slate-700 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Selected Files</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Drag to reorder</p>
                </div>
                <div id="file-count" class="text-xs font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">0 files</div>
            </div>
            
            <!-- Empty State -->
            <div id="empty-state" class="flex-1 flex flex-col items-center justify-center p-8 text-center">
                <div class="w-16 h-16 bg-gray-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-3xl text-gray-400">upload_file</span>
                </div>
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">No PDFs selected</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 max-w-xs mb-4">Add PDF files to combine them into a single document.</p>
                <button onclick="document.getElementById('file-input').click()" class="px-4 py-2 bg-primary text-white text-sm rounded-lg hover:bg-primary/90 transition-all shadow-sm">
                    Select PDFs
                </button>
            </div>

            <!-- List Container -->
            <div id="file-list-container" class="hidden flex-1 overflow-y-auto p-4 space-y-2">
                <!-- Items will be injected here -->
            </div>

            <!-- Action Area -->
            <div id="action-area" class="hidden p-4 border-t border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50">
                <button id="merge-btn" class="w-full flex items-center justify-center gap-2 w-full py-3 bg-primary text-white rounded-xl font-medium shadow-lg shadow-primary/25 hover:shadow-primary/40 hover:-translate-y-0.5 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    <span class="material-symbols-outlined">call_merge</span>
                    Merge PDFs
                </button>
            </div>
        </div>

        <!-- Preview/Result (Right/Bottom) -->
        <div class="hidden md:flex flex-1 flex-col bg-gray-50 dark:bg-black/20 overflow-hidden relative">
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-10">
                <span class="material-symbols-outlined text-[120px]">picture_as_pdf</span>
            </div>
            
            <div id="success-state" class="hidden flex-col items-center justify-center h-full z-10 p-8 text-center">
                <div class="w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mb-6 text-green-600 dark:text-green-400 animate-bounce-short">
                    <span class="material-symbols-outlined text-4xl">check</span>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Merge Complete!</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md">Your PDFs have been successfully combined into a single document.</p>
                
                <div class="flex gap-4">
                    <button id="preview-btn" class="px-6 py-2.5 rounded-lg border border-gray-300 dark:border-slate-600 font-medium hover:bg-white dark:hover:bg-slate-800 transition-colors">
                        Preview
                    </button>
                    <button id="download-btn" class="px-6 py-2.5 rounded-lg bg-primary text-white font-medium shadow-lg shadow-primary/25 hover:bg-primary/90 transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">download</span>
                        Download
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Libraries -->
<script src="https://unpkg.com/pdf-lib@1.17.1/dist/pdf-lib.min.js"></script>
<script src="https://unpkg.com/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
    // State
    let files = []; // Array of file objects with id
    
    // Elements
    const fileInput = document.getElementById('file-input');
    const addFilesBtn = document.getElementById('add-files-btn');
    const clearAllBtn = document.getElementById('clear-all-btn');
    const fileListContainer = document.getElementById('file-list-container');
    const emptyState = document.getElementById('empty-state');
    const actionArea = document.getElementById('action-area');
    const fileCount = document.getElementById('file-count');
    const mergeBtn = document.getElementById('merge-btn');
    const successState = document.getElementById('success-state');
    const downloadBtn = document.getElementById('download-btn');
    const previewBtn = document.getElementById('preview-btn');
    
    let mergedPdfBytes = null;

    // Event Listeners
    addFilesBtn.addEventListener('click', () => fileInput.click());
    
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFiles(Array.from(e.target.files));
            fileInput.value = ''; // Reset to allow adding same files again
        }
    });

    clearAllBtn.addEventListener('click', () => {
        files = [];
        updateUI();
        successState.classList.add('hidden');
    });

    mergeBtn.addEventListener('click', async () => {
        if (files.length < 2) {
            alert('Please select at least 2 PDF files to merge.');
            return;
        }

        try {
            setLoading(true);
            await mergePDFs();
            setLoading(false);
            successState.classList.remove('hidden');
            successState.classList.add('flex');
        } catch (error) {
            console.error(error);
            alert('Error merging PDFs: ' + error.message);
            setLoading(false);
        }
    });

    downloadBtn.addEventListener('click', () => {
        if (mergedPdfBytes) {
            const blob = new Blob([mergedPdfBytes], { type: 'application/pdf' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `merged_${new Date().getTime()}.pdf`;
            link.click();
        }
    });

    previewBtn.addEventListener('click', () => {
        if (mergedPdfBytes) {
            const blob = new Blob([mergedPdfBytes], { type: 'application/pdf' });
            const url = URL.createObjectURL(blob);
            window.open(url, '_blank');
        }
    });

    // Sortable
    new Sortable(fileListContainer, {
        animation: 150,
        ghostClass: 'bg-gray-100',
        onEnd: (evt) => {
            const item = files.splice(evt.oldIndex, 1)[0];
            files.splice(evt.newIndex, 0, item);
        }
    });

    // Functions
    function handleFiles(newFiles) {
        newFiles.forEach(file => {
            if (file.type === 'application/pdf') {
                files.push({
                    id: Math.random().toString(36).substr(2, 9),
                    file: file,
                    name: file.name,
                    size: formatSize(file.size)
                });
            }
        });
        updateUI();
        successState.classList.add('hidden');
        successState.classList.remove('flex');
    }

    function removeFile(id) {
        files = files.filter(f => f.id !== id);
        updateUI();
        successState.classList.add('hidden');
        successState.classList.remove('flex');
    }

    function updateUI() {
        // Toggle states
        if (files.length === 0) {
            emptyState.classList.remove('hidden');
            fileListContainer.classList.add('hidden');
            actionArea.classList.add('hidden');
            clearAllBtn.classList.add('hidden');
        } else {
            emptyState.classList.add('hidden');
            fileListContainer.classList.remove('hidden');
            actionArea.classList.remove('hidden');
            clearAllBtn.classList.remove('hidden');
        }

        // Update counts
        fileCount.textContent = `${files.length} file${files.length !== 1 ? 's' : ''}`;
        
        // Render list
        fileListContainer.innerHTML = '';
        files.forEach((item, index) => {
            const el = document.createElement('div');
            el.className = 'group flex items-center justify-between p-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg shadow-sm cursor-move hover:border-primary/50 transition-colors';
            el.innerHTML = `
                <div class="flex items-center gap-3 overflow-hidden">
                    <span class="material-symbols-outlined text-gray-400 group-hover:text-primary">drag_indicator</span>
                    <div class="flex items-center justify-center w-8 h-8 rounded bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400">
                        <span class="material-symbols-outlined text-lg">picture_as_pdf</span>
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="text-sm font-medium text-gray-900 dark:text-white truncate" title="${item.name}">${item.name}</span>
                        <span class="text-xs text-gray-500">${item.size}</span>
                    </div>
                </div>
                <button onclick="removeFile('${item.id}')" class="p-1.5 rounded text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            `;
            fileListContainer.appendChild(el);
        });
    }

    async function mergePDFs() {
        const { PDFDocument } = PDFLib;
        const resultDoc = await PDFDocument.create();

        for (const item of files) {
            const arrayBuffer = await item.file.arrayBuffer();
            const srcDoc = await PDFDocument.load(arrayBuffer);
            const copiedPages = await resultDoc.copyPages(srcDoc, srcDoc.getPageIndices());
            copiedPages.forEach((page) => resultDoc.addPage(page));
        }

        mergedPdfBytes = await resultDoc.save();
    }

    function setLoading(isLoading) {
        if (isLoading) {
            mergeBtn.disabled = true;
            mergeBtn.innerHTML = `
                <span class="animate-spin w-5 h-5 border-2 border-white/20 border-t-white rounded-full"></span>
                Merging...
            `;
        } else {
            mergeBtn.disabled = false;
            mergeBtn.innerHTML = `
                <span class="material-symbols-outlined">call_merge</span>
                Merge PDFs
            `;
        }
    }

    function formatSize(bytes) {
        if (bytes === 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }
</script>
