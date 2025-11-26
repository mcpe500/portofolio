<?php
/**
 * Data Converter
 * Convert between JSON, YAML, TOML, TOON, XML formats
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">transform</span>
            Data Converter
        </h1>
        <div class="flex items-center gap-2">
            <button id="format-btn" class="px-3 py-1 text-xs font-medium rounded bg-gray-100 dark:bg-gray-700 hover:bg-primary hover:text-white transition-colors">
                Format / Pretty Print
            </button>
            <button id="minify-btn" class="px-3 py-1 text-xs font-medium rounded bg-gray-100 dark:bg-gray-700 hover:bg-primary hover:text-white transition-colors">
                Minify
            </button>
            <button id="copy-btn" class="px-3 py-1 text-xs font-medium rounded bg-gray-100 dark:bg-gray-700 hover:bg-primary hover:text-white transition-colors">
                Copy Output
            </button>
        </div>
    </div>

    <div class="flex flex-1 overflow-hidden">
        <!-- Input (Left) -->
        <div class="w-1/2 border-r border-gray-200 dark:border-slate-700 flex flex-col bg-white dark:bg-slate-900">
            <div class="p-3 border-b border-gray-200 dark:border-slate-700 flex items-center justify-between">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Input Format</label>
                <select id="input-format" class="rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="json">JSON</option>
                    <option value="yaml">YAML</option>
                    <option value="toml">TOML</option>
                    <option value="toon">TOON</option>
                    <option value="xml">XML</option>
                </select>
            </div>
            <div class="flex-1 p-4">
                <textarea id="input-text" placeholder='{"name": "John", "age": 30}' 
                    class="w-full h-full font-mono text-sm rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary resize-none"></textarea>
            </div>
            <div id="input-error" class="hidden px-4 py-2 bg-red-50 dark:bg-red-900/20 border-t border-red-200 dark:border-red-800">
                <p class="text-xs text-red-600 dark:text-red-400"></p>
            </div>
        </div>

        <!-- Output (Right) -->
        <div class="w-1/2 flex flex-col bg-gray-50 dark:bg-slate-800">
            <div class="p-3 border-b border-gray-200 dark:border-slate-700 flex items-center justify-between">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Output Format</label>
                <select id="output-format" class="rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="json">JSON</option>
                    <option value="yaml">YAML</option>
                    <option value="toml">TOML</option>
                    <option value="toon">TOON</option>
                    <option value="xml">XML</option>
                </select>
            </div>
            <div class="flex-1 p-4">
                <pre id="output-text" class="w-full h-full font-mono text-sm rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 overflow-auto"></pre>
            </div>
            <div id="output-info" class="px-4 py-2 bg-blue-50 dark:bg-blue-900/20 border-t border-blue-200 dark:border-blue-800">
                <p class="text-xs text-blue-600 dark:text-blue-400" id="output-stats"></p>
            </div>
        </div>
    </div>
</div>

<!-- Libraries -->
<script src="https://cdn.jsdelivr.net/npm/js-yaml@4.1.0/dist/js-yaml.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/smol-toml@1.3.1/dist/index.min.js"></script>

<script>
// TOON Parser (Token Oriented Object Notation - simplified format)
const TOONParser = {
    parse(toonString) {
        const lines = toonString.trim().split('\n');
        const result = {};
        let currentKey = null;
        let currentArray = null;
        let indent = 0;

        for (const line of lines) {
            const trimmed = line.trim();
            if (!trimmed || trimmed.startsWith('#')) continue;

            // Detect key-value pairs
            if (trimmed.includes(':')) {
                const [key, ...valueParts] = trimmed.split(':');
                const value = valueParts.join(':').trim();
                
                if (value.startsWith('[') && value.endsWith(']')) {
                    // Array value
                    result[key.trim()] = value.slice(1, -1).split(',').map(v => v.trim().replace(/['"]/g, ''));
                } else if (value === '{') {
                    // Object start (not implemented in simple version)
                    currentKey = key.trim();
                } else if (!isNaN(value)) {
                    // Number
                    result[key.trim()] = parseFloat(value);
                } else if (value === 'true' || value === 'false') {
                    // Boolean
                    result[key.trim()] = value === 'true';
                } else {
                    // String
                    result[key.trim()] = value.replace(/['"]/g, '');
                }
            }
        }

        return result;
    },

    stringify(obj, indent = 0) {
        let output = '';
        const spaces = '  '.repeat(indent);

        for (const [key, value] of Object.entries(obj)) {
            if (Array.isArray(value)) {
                output += `${spaces}${key}: [${value.map(v => typeof v === 'string' ? `"${v}"` : v).join(', ')}]\n`;
            } else if (typeof value === 'object' && value !== null) {
                output += `${spaces}${key}:\n`;
                output += TOONParser.stringify(value, indent + 1);
            } else if (typeof value === 'string') {
                output += `${spaces}${key}: "${value}"\n`;
            } else {
                output += `${spaces}${key}: ${value}\n`;
            }
        }

        return output;
    }
};

// XML Parser/Builder
const XMLParser = {
    parse(xmlString) {
        const parser = new DOMParser();
        const doc = parser.parseFromString(xmlString, 'text/xml');
        
        if (doc.querySelector('parsererror')) {
            throw new Error('Invalid XML');
        }
        
        function xmlToJson(xml) {
            const obj = {};
            
            if (xml.nodeType === 1) {
                if (xml.attributes.length > 0) {
                    obj['@attributes'] = {};
                    for (let j = 0; j < xml.attributes.length; j++) {
                        const attribute = xml.attributes.item(j);
                        obj['@attributes'][attribute.nodeName] = attribute.nodeValue;
                    }
                }
            } else if (xml.nodeType === 3) {
                return xml.nodeValue.trim();
            }
            
            if (xml.hasChildNodes()) {
                for (let i = 0; i < xml.childNodes.length; i++) {
                    const item = xml.childNodes.item(i);
                    const nodeName = item.nodeName;
                    
                    if (typeof obj[nodeName] === 'undefined') {
                        const converted = xmlToJson(item);
                        if (converted !== '') {
                            obj[nodeName] = converted;
                        }
                    } else {
                        if (typeof obj[nodeName].push === 'undefined') {
                            const old = obj[nodeName];
                            obj[nodeName] = [];
                            obj[nodeName].push(old);
                        }
                        const converted = xmlToJson(item);
                        if (converted !== '') {
                            obj[nodeName].push(converted);
                        }
                    }
                }
            }
            
            return obj;
        }
        
        return xmlToJson(doc);
    },
    
    stringify(obj, rootName = 'root') {
        function jsonToXml(obj, name) {
            let xml = '';
            
            if (typeof obj === 'object' && obj !== null) {
                if (Array.isArray(obj)) {
                    obj.forEach(item => {
                        xml += jsonToXml(item, name);
                    });
                } else {
                    xml += `<${name}`;
                    
                    if (obj['@attributes']) {
                        for (const [key, value] of Object.entries(obj['@attributes'])) {
                            xml += ` ${key}="${value}"`;
                        }
                    }
                    
                    xml += '>';
                    
                    for (const [key, value] of Object.entries(obj)) {
                        if (key !== '@attributes') {
                            xml += jsonToXml(value, key);
                        }
                    }
                    
                    xml += `</${name}>`;
                }
            } else {
                xml += `<${name}>${obj}</${name}>`;
            }
            
            return xml;
        }
        
        return '<?xml version="1.0" encoding="UTF-8"?>\n' + jsonToXml(obj, rootName);
    }
};

// Elements
const inputText = document.getElementById('input-text');
const outputText = document.getElementById('output-text');
const inputFormat = document.getElementById('input-format');
const outputFormat = document.getElementById('output-format');
const inputError = document.getElementById('input-error');
const outputStats = document.getElementById('output-stats');
const formatBtn = document.getElementById('format-btn');
const minifyBtn = document.getElementById('minify-btn');
const copyBtn = document.getElementById('copy-btn');

let currentData = null;

// Convert function
async function convert() {
    const input = inputText.value.trim();
    if (!input) {
        outputText.textContent = '';
        inputError.classList.add('hidden');
        return;
    }

    try {
        // Parse input
        const inFormat = inputFormat.value;
        let data;

        switch (inFormat) {
            case 'json':
                data = JSON.parse(input);
                break;
            case 'yaml':
                data = jsyaml.load(input);
                break;
            case 'toml':
                data = TOML.parse(input);
                break;
            case 'toon':
                data = TOONParser.parse(input);
                break;
            case 'xml':
                data = XMLParser.parse(input);
                break;
        }

        currentData = data;
        inputError.classList.add('hidden');

        // Convert to output
        const outFormat = outputFormat.value;
        let output;

        switch (outFormat) {
            case 'json':
                output = JSON.stringify(data, null, 2);
                break;
            case 'yaml':
                output = jsyaml.dump(data, { indent: 2, lineWidth: -1 });
                break;
            case 'toml':
                output = TOML.stringify(data);
                break;
            case 'toon':
                output = TOONParser.stringify(data);
                break;
            case 'xml':
                output = XMLParser.stringify(data);
                break;
        }

        outputText.textContent = output;
        
        // Stats
        const lines = output.split('\n').length;
        const chars = output.length;
        const bytes = new Blob([output]).size;
        outputStats.textContent = `${lines} lines · ${chars} characters · ${bytes} bytes`;

    } catch (error) {
        inputError.classList.remove('hidden');
        inputError.querySelector('p').textContent = `Parse Error: ${error.message}`;
        outputText.textContent = '';
        outputStats.textContent = '';
    }
}

// Format / Pretty Print
formatBtn.addEventListener('click', () => {
    if (!currentData) return;
    
    const outFormat = outputFormat.value;
    let output;

    try {
        switch (outFormat) {
            case 'json':
                output = JSON.stringify(currentData, null, 2);
                break;
            case 'yaml':
                output = jsyaml.dump(currentData, { indent: 2, lineWidth: -1 });
                break;
            case 'toml':
                output = TOML.stringify(currentData);
                break;
            case 'toon':
                output = TOONParser.stringify(currentData);
                break;
            case 'xml':
                output = XMLParser.stringify(currentData);
                const parser = new DOMParser();
                const xmlDoc = parser.parseFromString(output, 'text/xml');
                const serializer = new XMLSerializer();
                output = serializer.serializeToString(xmlDoc);
                break;
        }
        
        outputText.textContent = output;
    } catch (error) {
        alert('Format error: ' + error.message);
    }
});

// Minify
minifyBtn.addEventListener('click', () => {
    if (!currentData) return;
    
    const outFormat = outputFormat.value;
    let output;

    try {
        switch (outFormat) {
            case 'json':
                output = JSON.stringify(currentData);
                break;
            case 'yaml':
                output = jsyaml.dump(currentData, { flowLevel: 0 }).replace(/\n/g, '');
                break;
            case 'toml':
                output = TOML.stringify(currentData).replace(/\n\n/g, '\n');
                break;
            case 'toon':
                output = TOONParser.stringify(currentData).replace(/\n\n/g, '\n');
                break;
            case 'xml':
                output = XMLParser.stringify(currentData).replace(/>\s+</g, '><');
                break;
        }
        
        outputText.textContent = output;
        
        // Update stats
        const chars = output.length;
        const bytes = new Blob([output]).size;
        outputStats.textContent = `Minified: ${chars} characters · ${bytes} bytes`;
    } catch (error) {
        alert('Minify error: ' + error.message);
    }
});

// Copy
copyBtn.addEventListener('click', async () => {
    const text = outputText.textContent;
    if (!text) return;
    
    try {
        await navigator.clipboard.writeText(text);
        const originalText = copyBtn.textContent;
        copyBtn.textContent = '✓ Copied!';
        setTimeout(() => {
            copyBtn.textContent = originalText;
        }, 2000);
    } catch (error) {
        alert('Failed to copy: ' + error.message);
    }
});

// Auto-convert on input
let debounceTimer;
inputText.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(convert, 300);
});

inputFormat.addEventListener('change', convert);
outputFormat.addEventListener('change', convert);

// Initial sample
inputText.value = `{
  "name": "John Doe",
  "age": 30,
  "email": "john@example.com",
  "address": {
    "street": "123 Main St",
    "city": "New York",
    "country": "USA"
  },
  "hobbies": ["reading", "coding", "traveling"]
}`;
convert();
</script>
