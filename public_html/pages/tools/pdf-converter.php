<?php
/**
 * Universal File Converter
 * Convert between PDF, Images, HTML, DOCX, and ODF
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">swap_horiz</span>
            Universal Converter
        </h1>
        
        <!-- Conversion Selector -->
        <div class="flex items-center gap-2 bg-gray-100 dark:bg-slate-700 p-1 rounded-lg">
            <div class="relative">
                <select id="from-format" class="appearance-none bg-white dark:bg-slate-600 border border-gray-200 dark:border-slate-500 text-gray-700 dark:text-white py-1 pl-3 pr-8 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer">
                    <optgroup label="Documents">
                        <option value="pdf">PDF</option>
                        <option value="docx">DOCX</option>
                        <option value="odf">ODF (OpenDocument)</option>
                        <option value="html">HTML</option>
                    </optgroup>
                    <optgroup label="Images">
                        <option value="image">Images (JPG/PNG)</option>
                    </optgroup>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500 dark:text-gray-400">
                    <span class="material-symbols-outlined text-xs">expand_more</span>
                </div>
            </div>

            <span class="material-symbols-outlined text-gray-400">arrow_forward</span>

            <div class="relative">
                <select id="to-format" class="appearance-none bg-white dark:bg-slate-600 border border-gray-200 dark:border-slate-500 text-gray-700 dark:text-white py-1 pl-3 pr-8 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer">
                    <!-- Populated dynamically -->
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500 dark:text-gray-400">
                    <span class="material-symbols-outlined text-xs">expand_more</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="flex-1 overflow-hidden relative bg-gray-50 dark:bg-slate-900 p-6">
        <div class="max-w-4xl mx-auto w-full h-full flex flex-col">
            
            <!-- Upload Area -->
            <div id="upload-area" class="bg-white dark:bg-slate-800 rounded-xl border-2 border-dashed border-gray-300 dark:border-slate-600 p-8 text-center hover:border-primary transition-colors cursor-pointer flex-shrink-0 mb-6">
                <input type="file" id="file-input" class="hidden">
                <div class="space-y-2 pointer-events-none">
                    <span class="material-symbols-outlined text-4xl text-gray-400" id="upload-icon">cloud_upload</span>
                    <p class="text-gray-600 dark:text-gray-300 font-medium" id="upload-text">Upload file</p>
                    <p class="text-sm text-gray-500" id="upload-hint">Supports various formats</p>
                </div>
            </div>

            <!-- HTML Input Area (Special case for HTML -> PDF) -->
            <div id="html-input-area" class="hidden flex-1 bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 flex-col mb-6">
                <textarea id="html-text" class="flex-1 w-full p-4 font-mono text-sm focus:outline-none bg-transparent resize-none" placeholder="Paste your HTML code here..."></textarea>
            </div>

            <!-- Preview / Status Area -->
            <div id="preview-area" class="hidden flex-1 bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden flex flex-col">
                <div class="p-4 border-b border-gray-200 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-900">
                    <h3 class="font-medium text-gray-700 dark:text-gray-300">Preview / Result</h3>
                    <div class="flex gap-2">
                        <button id="clear-btn" class="px-3 py-1 text-xs font-medium rounded bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-slate-600">Clear</button>
                        <button id="convert-btn" class="px-3 py-1 text-xs font-medium rounded bg-primary text-white hover:bg-primary/90">Convert & Download</button>
                    </div>
                </div>
                <div class="flex-1 overflow-auto p-4" id="preview-content">
                    <!-- Dynamic content -->
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.6.0/mammoth.browser.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
// Configure PDF.js worker
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

// Elements
const fromFormat = document.getElementById('from-format');
const toFormat = document.getElementById('to-format');
const uploadArea = document.getElementById('upload-area');
const fileInput = document.getElementById('file-input');
const uploadText = document.getElementById('upload-text');
const uploadHint = document.getElementById('upload-hint');
const uploadIcon = document.getElementById('upload-icon');
const previewArea = document.getElementById('preview-area');
const previewContent = document.getElementById('preview-content');
const convertBtn = document.getElementById('convert-btn');
const clearBtn = document.getElementById('clear-btn');
const htmlInputArea = document.getElementById('html-input-area');
const htmlText = document.getElementById('html-text');

// State
let currentFiles = [];
let conversionSource = null; // 'file' or 'html'

// Format Mappings
const formats = {
    pdf: { label: 'PDF', to: ['image', 'text'] },
    docx: { label: 'DOCX', to: ['pdf', 'html'] },
    odf: { label: 'ODF', to: ['pdf', 'html'] },
    html: { label: 'HTML', to: ['pdf'] },
    image: { label: 'Images', to: ['pdf'] }
};

// Initialize
function updateToOptions() {
    const from = fromFormat.value;
    const options = formats[from].to;
    
    toFormat.innerHTML = options.map(opt => {
        let label = '';
        switch(opt) {
            case 'pdf': label = 'PDF'; break;
            case 'image': label = 'Images (JPG)'; break;
            case 'text': label = 'Text (TXT)'; break;
            case 'html': label = 'HTML'; break;
        }
        return `<option value="${opt}">${label}</option>`;
    }).join('');

    // Update UI based on selection
    if (from === 'html') {
        uploadArea.classList.add('hidden');
        htmlInputArea.classList.remove('hidden');
        previewArea.classList.remove('hidden'); // Always show preview for HTML
        conversionSource = 'html';
    } else {
        uploadArea.classList.remove('hidden');
        htmlInputArea.classList.add('hidden');
        previewArea.classList.add('hidden');
        conversionSource = 'file';
        
        // Update upload hint
        switch(from) {
            case 'image':
                fileInput.accept = 'image/*';
                fileInput.multiple = true;
                uploadText.textContent = 'Upload Images';
                uploadHint.textContent = 'JPG, PNG, WEBP supported';
                uploadIcon.textContent = 'add_photo_alternate';
                break;
            case 'pdf':
                fileInput.accept = '.pdf';
                fileInput.multiple = false;
                uploadText.textContent = 'Upload PDF';
                uploadHint.textContent = '.pdf files';
                uploadIcon.textContent = 'picture_as_pdf';
                break;
            case 'docx':
                fileInput.accept = '.docx';
                fileInput.multiple = false;
                uploadText.textContent = 'Upload DOCX';
                uploadHint.textContent = '.docx files';
                uploadIcon.textContent = 'description';
                break;
            case 'odf':
                fileInput.accept = '.odt';
                fileInput.multiple = false;
                uploadText.textContent = 'Upload ODF';
                uploadHint.textContent = '.odt files';
                uploadIcon.textContent = 'description';
                break;
        }
    }
}

fromFormat.addEventListener('change', updateToOptions);
updateToOptions(); // Initial call

// File Handling
uploadArea.addEventListener('click', () => fileInput.click());
uploadArea.addEventListener('dragover', (e) => { e.preventDefault(); uploadArea.classList.add('border-primary'); });
uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('border-primary'));
uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('border-primary');
    handleFiles(e.dataTransfer.files);
});
fileInput.addEventListener('change', (e) => handleFiles(e.target.files));

function handleFiles(files) {
    if (files.length === 0) return;
    currentFiles = Array.from(files);
    
    previewArea.classList.remove('hidden');
    previewContent.innerHTML = '';

    const from = fromFormat.value;

    if (from === 'image') {
        const grid = document.createElement('div');
        grid.className = 'grid grid-cols-3 gap-4';
        
        currentFiles.forEach(file => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-32 object-cover rounded-lg border border-gray-200';
                grid.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
        previewContent.appendChild(grid);
    } else {
        previewContent.innerHTML = `
            <div class="flex items-center gap-3 p-4 bg-gray-100 dark:bg-slate-700 rounded-lg">
                <span class="material-symbols-outlined text-3xl text-gray-500">description</span>
                <div>
                    <div class="font-medium text-gray-900 dark:text-white">${currentFiles[0].name}</div>
                    <div class="text-xs text-gray-500">${(currentFiles[0].size / 1024).toFixed(1)} KB</div>
                </div>
            </div>
        `;
    }
}

clearBtn.addEventListener('click', () => {
    currentFiles = [];
    fileInput.value = '';
    previewArea.classList.add('hidden');
    previewContent.innerHTML = '';
    htmlText.value = '';
});

// Conversion Logic
convertBtn.addEventListener('click', async () => {
    const from = fromFormat.value;
    const to = toFormat.value;

    convertBtn.disabled = true;
    convertBtn.textContent = 'Converting...';

    try {
        if (from === 'image' && to === 'pdf') {
            await convertImagesToPdf();
        } else if (from === 'html' && to === 'pdf') {
            await convertHtmlToPdf();
        } else if (from === 'docx' && to === 'pdf') {
            await convertDocxToPdf();
        } else if (from === 'odf' && to === 'pdf') {
            await convertOdfToPdf();
        } else if (from === 'pdf' && to === 'image') {
            await convertPdfToImages();
        } else if (from === 'pdf' && to === 'text') {
            await convertPdfToText();
        } else if ((from === 'docx' || from === 'odf') && to === 'html') {
            await convertDocToHtml();
        }
    } catch (error) {
        alert('Conversion failed: ' + error.message);
        console.error(error);
    } finally {
        convertBtn.disabled = false;
        convertBtn.textContent = 'Convert & Download';
    }
});

// --- Converters ---

async function convertImagesToPdf() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    for (let i = 0; i < currentFiles.length; i++) {
        if (i > 0) doc.addPage();
        const dataUrl = await readFileAsDataURL(currentFiles[i]);
        
        const imgProps = doc.getImageProperties(dataUrl);
        const pdfWidth = doc.internal.pageSize.getWidth();
        const pdfHeight = doc.internal.pageSize.getHeight();
        const ratio = imgProps.width / imgProps.height;
        let w = pdfWidth;
        let h = w / ratio;
        if (h > pdfHeight) { h = pdfHeight; w = h * ratio; }
        
        doc.addImage(dataUrl, 'JPEG', (pdfWidth - w)/2, (pdfHeight - h)/2, w, h);
    }
    doc.save('converted.pdf');
}

async function convertHtmlToPdf() {
    const element = document.createElement('div');
    element.innerHTML = htmlText.value;
    document.body.appendChild(element); // Needs to be in DOM
    
    const opt = {
        margin: 10,
        filename: 'converted.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };

    await html2pdf().set(opt).from(element).save();
    document.body.removeChild(element);
}

async function convertDocxToPdf() {
    const arrayBuffer = await readFileAsArrayBuffer(currentFiles[0]);
    const result = await mammoth.convertToHtml({ arrayBuffer: arrayBuffer });
    const html = result.value;
    
    const element = document.createElement('div');
    element.innerHTML = html;
    element.style.width = '210mm';
    element.style.padding = '20mm';
    document.body.appendChild(element);

    const opt = {
        margin: 10,
        filename: 'converted.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };

    await html2pdf().set(opt).from(element).save();
    document.body.removeChild(element);
}

async function convertOdfToPdf() {
    const file = currentFiles[0];
    const zip = await JSZip.loadAsync(file);
    const contentXml = await zip.file("content.xml").async("string");
    
    // Basic ODF parsing (extract text paragraphs)
    const parser = new DOMParser();
    const xmlDoc = parser.parseFromString(contentXml, "text/xml");
    const paragraphs = xmlDoc.getElementsByTagName("text:p");
    
    let html = '<div style="font-family: sans-serif; line-height: 1.5;">';
    for (let p of paragraphs) {
        html += `<p>${p.textContent}</p>`;
    }
    html += '</div>';

    const element = document.createElement('div');
    element.innerHTML = html;
    element.style.width = '210mm';
    element.style.padding = '20mm';
    document.body.appendChild(element);

    const opt = {
        margin: 10,
        filename: 'converted.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };

    await html2pdf().set(opt).from(element).save();
    document.body.removeChild(element);
}

async function convertPdfToImages() {
    const arrayBuffer = await readFileAsArrayBuffer(currentFiles[0]);
    const pdf = await pdfjsLib.getDocument(arrayBuffer).promise;
    const zip = new JSZip();

    for (let i = 1; i <= pdf.numPages; i++) {
        const page = await pdf.getPage(i);
        const viewport = page.getViewport({ scale: 2.0 });
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        await page.render({ canvasContext: context, viewport: viewport }).promise;
        
        const imgData = canvas.toDataURL('image/jpeg').split(',')[1];
        zip.file(`page-${i}.jpg`, imgData, {base64: true});
    }

    const content = await zip.generateAsync({type: "blob"});
    const link = document.createElement('a');
    link.href = URL.createObjectURL(content);
    link.download = "pdf-images.zip";
    link.click();
}

async function convertPdfToText() {
    const arrayBuffer = await readFileAsArrayBuffer(currentFiles[0]);
    const pdf = await pdfjsLib.getDocument(arrayBuffer).promise;
    let fullText = '';

    for (let i = 1; i <= pdf.numPages; i++) {
        const page = await pdf.getPage(i);
        const textContent = await page.getTextContent();
        const pageText = textContent.items.map(item => item.str).join(' ');
        fullText += `--- Page ${i} ---\n\n${pageText}\n\n`;
    }

    const blob = new Blob([fullText], {type: 'text/plain'});
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = "extracted-text.txt";
    link.click();
}

async function convertDocToHtml() {
    // Re-use logic from PDF conversion but save as HTML file
    let html = '';
    if (fromFormat.value === 'docx') {
        const arrayBuffer = await readFileAsArrayBuffer(currentFiles[0]);
        const result = await mammoth.convertToHtml({ arrayBuffer: arrayBuffer });
        html = result.value;
    } else {
        // ODF
        const file = currentFiles[0];
        const zip = await JSZip.loadAsync(file);
        const contentXml = await zip.file("content.xml").async("string");
        const parser = new DOMParser();
        const xmlDoc = parser.parseFromString(contentXml, "text/xml");
        const paragraphs = xmlDoc.getElementsByTagName("text:p");
        html = '<div>';
        for (let p of paragraphs) {
            html += `<p>${p.textContent}</p>`;
        }
        html += '</div>';
    }

    const blob = new Blob([html], {type: 'text/html'});
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = "converted.html";
    link.click();
}

// Helpers
function readFileAsDataURL(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result);
        reader.onerror = reject;
        reader.readAsDataURL(file);
    });
}

function readFileAsArrayBuffer(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result);
        reader.onerror = reject;
        reader.readAsArrayBuffer(file);
    });
}
</script>
