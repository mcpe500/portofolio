<?php
/**
 * Date Formatter
 * Convert dates between various formats
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">calendar_today</span>
            Date Formatter
        </h1>
        <div class="flex items-center gap-2">
            <button id="now-btn" class="px-3 py-1 text-xs font-medium rounded bg-gray-100 dark:bg-gray-700 hover:bg-primary hover:text-white transition-colors">
                Use Current Time
            </button>
            <button id="copy-all-btn" class="px-3 py-1 text-xs font-medium rounded bg-gray-100 dark:bg-gray-700 hover:bg-primary hover:text-white transition-colors">
                Copy All Formats
            </button>
        </div>
    </div>

    <div class="flex flex-1 overflow-hidden">
        <!-- Input Section (Left) -->
        <div class="w-1/2 border-r border-gray-200 dark:border-slate-700 flex flex-col bg-white dark:bg-slate-900">
            <div class="p-4 space-y-4">
                <!-- Date Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Input Date
                    </label>
                    <input id="date-input" type="text" placeholder="2024-03-15 14:30:00 or Unix timestamp or any date format" 
                        class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" />
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Auto-detects: ISO, Unix timestamp, natural language, etc.</p>
                </div>

                <!-- Timezone Selector -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Timezone
                    </label>
                    <select id="timezone-select" class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="local">Local (Browser Timezone)</option>
                        <option value="UTC">UTC</option>
                        <option value="America/New_York">America/New_York (EST/EDT)</option>
                        <option value="America/Los_Angeles">America/Los_Angeles (PST/PDT)</option>
                        <option value="America/Chicago">America/Chicago (CST/CDT)</option>
                        <option value="Europe/London">Europe/London (GMT/BST)</option>
                        <option value="Europe/Paris">Europe/Paris (CET/CEST)</option>
                        <option value="Asia/Tokyo">Asia/Tokyo (JST)</option>
                        <option value="Asia/Shanghai">Asia/Shanghai (CST)</option>
                        <option value="Asia/Dubai">Asia/Dubai (GST)</option>
                        <option value="Asia/Kolkata">Asia/Kolkata (IST)</option>
                        <option value="Australia/Sydney">Australia/Sydney (AEDT/AEST)</option>
                    </select>
                </div>

                <!-- Quick Presets -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Quick Presets
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <button class="preset-btn px-3 py-2 text-xs rounded bg-gray-100 dark:bg-gray-700 hover:bg-primary hover:text-white transition-colors" data-preset="now">
                            Now
                        </button>
                        <button class="preset-btn px-3 py-2 text-xs rounded bg-gray-100 dark:bg-gray-700 hover:bg-primary hover:text-white transition-colors" data-preset="tomorrow">
                            Tomorrow
                        </button>
                        <button class="preset-btn px-3 py-2 text-xs rounded bg-gray-100 dark:bg-gray-700 hover:bg-primary hover:text-white transition-colors" data-preset="yesterday">
                            Yesterday
                        </button>
                        <button class="preset-btn px-3 py-2 text-xs rounded bg-gray-100 dark:bg-gray-700 hover:bg-primary hover:text-white transition-colors" data-preset="week">
                            1 Week Ago
                        </button>
                    </div>
                </div>

                <!-- Error Display -->
                <div id="input-error" class="hidden p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <p class="text-xs text-red-600 dark:text-red-400"></p>
                </div>
            </div>
        </div>

        <!-- Output Formats (Right) -->
        <div class="w-1/2 flex flex-col bg-gray-50 dark:bg-slate-800 overflow-auto">
            <div class="p-4 space-y-3">
                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Output Formats</h3>

                <!-- Format Output Grid -->
                <div id="formats-output" class="space-y-2">
                    <!-- Dynamically populated -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Elements
const dateInput = document.getElementById('date-input');
const timezoneSelect = document.getElementById('timezone-select');
const formatsOutput = document.getElementById('formats-output');
const inputError = document.getElementById('input-error');
const nowBtn = document.getElementById('now-btn');
const copyAllBtn = document.getElementById('copy-all-btn');

let currentDate = new Date();

// Date Format Definitions
const formats = [
    { name: 'ISO 8601', key: 'iso8601', desc: 'International standard' },
    { name: 'ISO 8601 (Extended)', key: 'iso8601Extended', desc: 'With milliseconds' },
    { name: 'RFC 3339', key: 'rfc3339', desc: 'Internet timestamp' },
    { name: 'RFC 2822', key: 'rfc2822', desc: 'Email timestamp' },
    { name: 'Unix Timestamp', key: 'unix', desc: 'Seconds since epoch' },
    { name: 'Unix Timestamp (ms)', key: 'unixMs', desc: 'Milliseconds since epoch' },
    { name: 'DD/MM/YYYY', key: 'ddmmyyyy', desc: 'Day/Month/Year' },
    { name: 'MM/DD/YYYY', key: 'mmddyyyy', desc: 'Month/Day/Year (US)' },
    { name: 'YYYY-MM-DD', key: 'yyyymmdd', desc: 'Year-Month-Day' },
    { name: 'DD-MM-YYYY HH:mm:ss', key: 'ddmmyyyyTime', desc: 'European with time' },
    { name: 'MM-DD-YYYY HH:mm:ss', key: 'mmddyyyyTime', desc: 'US with time' },
    { name: 'YYYY-MM-DD HH:mm:ss', key: 'yyyymmddTime', desc: 'ISO-like with time' },
    { name: 'DD MMM YYYY', key: 'ddMMMMyyyy', desc: '15 Mar 2024' },
    { name: 'MMMM DD, YYYY', key: 'MMMMddyyyy', desc: 'March 15, 2024' },
    { name: 'HH:mm:ss', key: 'time24', desc: '24-hour time' },
    { name: 'hh:mm:ss A', key: 'time12', desc: '12-hour time with AM/PM' },
    { name: 'Day of Week', key: 'dayOfWeek', desc: 'Monday, Tuesday, etc.' },
    { name: 'Day of Year', key: 'dayOfYear', desc: 'Day number (1-365)' },
    { name: 'Week of Year', key: 'weekOfYear', desc: 'Week number' },
    { name: 'Relative Time', key: 'relative', desc: '2 hours ago, in 3 days' },
    { name: 'Full Locale String', key: 'fullLocale', desc: 'Browser locale format' },
    { name: 'UTC String', key: 'utcString', desc: 'UTC format string' },
];

// Format date based on key
function formatDate(date, formatKey, timezone) {
    const tz = timezone === 'local' ? undefined : timezone;
    
    try {
        switch (formatKey) {
            case 'iso8601':
                return date.toISOString().slice(0, 19) + 'Z';
            
            case 'iso8601Extended':
                return date.toISOString();
            
            case 'rfc3339':
                return date.toISOString();
            
            case 'rfc2822':
                return date.toUTCString();
            
            case 'unix':
                return Math.floor(date.getTime() / 1000).toString();
            
            case 'unixMs':
                return date.getTime().toString();
            
            case 'ddmmyyyy':
                return formatWithIntl(date, { day: '2-digit', month: '2-digit', year: 'numeric' }, tz).replace(/\//g, '/');
            
            case 'mmddyyyy':
                const parts = formatWithIntl(date, { month: '2-digit', day: '2-digit', year: 'numeric' }, tz).split('/');
                return `${parts[0]}/${parts[1]}/${parts[2]}`;
            
            case 'yyyymmdd':
return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
            
            case 'ddmmyyyyTime':
                return `${pad(date.getDate())}-${pad(date.getMonth() + 1)}-${date.getFullYear()} ${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`;
            
            case 'mmddyyyyTime':
                return `${pad(date.getMonth() + 1)}-${pad(date.getDate())}-${date.getFullYear()} ${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`;
            
            case 'yyyymmddTime':
                return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())} ${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`;
            
            case 'ddMMMMyyyy':
                return formatWithIntl(date, { day: '2-digit', month: 'short', year: 'numeric' }, tz);
            
            case 'MMMMddyyyy':
                return formatWithIntl(date, { month: 'long', day: '2-digit', year: 'numeric' }, tz);
            
            case 'time24':
                return `${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`;
            
            case 'time12':
                const hours = date.getHours();
                const ampm = hours >= 12 ? 'PM' : 'AM';
                const hours12 = hours % 12 || 12;
                return `${pad(hours12)}:${pad(date.getMinutes())}:${pad(date.getSeconds())} ${ampm}`;
            
            case 'dayOfWeek':
                return formatWithIntl(date, { weekday: 'long' }, tz);
            
            case 'dayOfYear':
                const start = new Date(date.getFullYear(), 0, 0);
                const diff = date - start;
                const oneDay = 1000 * 60 * 60 * 24;
                return Math.floor(diff / oneDay).toString();
            
            case 'weekOfYear':
                const startOfYear = new Date(date.getFullYear(), 0, 1);
                const days = Math.floor((date - startOfYear) / (24 * 60 * 60 * 1000));
                return Math.ceil((days + startOfYear.getDay() + 1) / 7).toString();
            
            case 'relative':
                return getRelativeTime(date);
            
            case 'fullLocale':
                return date.toLocaleString(undefined, { timeZone: tz });
            
            case 'utcString':
                return date.toUTCString();
            
            default:
                return 'Format not supported';
        }
    } catch (error) {
        return `Error: ${error.message}`;
    }
}

function formatWithIntl(date, options, timezone) {
    return new Intl.DateTimeFormat('en-US', {
        ...options,
        timeZone: timezone
    }).format(date);
}

function pad(num) {
    return num.toString().padStart(2, '0');
}

function getRelativeTime(date) {
    const now = new Date();
    const diff = date - now;
    const absDiff = Math.abs(diff);
    
    const seconds = Math.floor(absDiff / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);
    const weeks = Math.floor(days / 7);
    const months = Math.floor(days / 30);
    const years = Math.floor(days / 365);
    
    const isPast = diff < 0;
    
    if (years > 0) return `${isPast ? '' : 'in '}${years} year${years > 1 ? 's' : ''}${isPast ? ' ago' : ''}`;
    if (months > 0) return `${isPast ? '' : 'in '}${months} month${months > 1 ? 's' : ''}${isPast ? ' ago' : ''}`;
    if (weeks > 0) return `${isPast ? '' : 'in '}${weeks} week${weeks > 1 ? 's' : ''}${isPast ? ' ago' : ''}`;
    if (days > 0) return `${isPast ? '' : 'in '}${days} day${days > 1 ? 's' : ''}${isPast ? ' ago' : ''}`;
    if (hours > 0) return `${isPast ? '' : 'in '}${hours} hour${hours > 1 ? 's' : ''}${isPast ? ' ago' : ''}`;
    if (minutes > 0) return `${isPast ? '' : 'in '}${minutes} minute${minutes > 1 ? 's' : ''}${isPast ? ' ago' : ''}`;
    if (seconds > 0) return `${isPast ? '' : 'in '}${seconds} second${seconds > 1 ? 's' : ''}${isPast ? ' ago' : ''}`;
    
    return 'just now';
}

// Parse input date
function parseInputDate(input) {
    if (!input) return null;
    
    // Try Unix timestamp (seconds)
    if (/^\d{10}$/.test(input)) {
        return new Date(parseInt(input) * 1000);
    }
    
    // Try Unix timestamp (milliseconds)
    if (/^\d{13}$/.test(input)) {
        return new Date(parseInt(input));
    }
    
    // Try standard Date parsing
    const date = new Date(input);
    if (!isNaN(date.getTime())) {
        return date;
    }
    
    return null;
}

// Render all formats
function renderFormats() {
    const input = dateInput.value.trim();
    
    if (!input) {
        formatsOutput.innerHTML = '<p class="text-xs text-gray-500 dark:text-gray-400">Enter a date to see all formats</p>';
        return;
    }
    
    const date = parseInputDate(input);
    
    if (!date || isNaN(date.getTime())) {
        inputError.classList.remove('hidden');
        inputError.querySelector('p').textContent = 'Invalid date format. Try: "2024-03-15", "1234567890", or "March 15, 2024"';
        formatsOutput.innerHTML = '';
        return;
    }
    
    inputError.classList.add('hidden');
    currentDate = date;
    
    const timezone = timezoneSelect.value;
    
    let html = '';
    formats.forEach(format => {
        const value = formatDate(date, format.key, timezone);
        html += `
            <div class="group p-3 bg-white dark:bg-slate-900 rounded-lg border border-gray-200 dark:border-slate-700 hover:border-primary hover:shadow-sm transition-all">
                <div class="flex items-start justify-between mb-1">
                    <div class="flex-1">
                        <div class="text-xs font-semibold text-gray-700 dark:text-gray-300">${format.name}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">${format.desc}</div>
                    </div>
                    <button onclick="copyFormat('${value.replace(/'/g, "\\'")}', this)" 
                        class="opacity-0 group-hover:opacity-100 px-2 py-1 text-xs rounded bg-gray-100 dark:bg-gray-700 hover:bg-primary hover:text-white transition-all">
                        Copy
                    </button>
                </div>
                <div class="font-mono text-sm text-gray-900 dark:text-white break-all">${escapeHtml(value)}</div>
            </div>
        `;
    });
    
    formatsOutput.innerHTML = html;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Copy single format
window.copyFormat = async (value, button) => {
    try {
        await navigator.clipboard.writeText(value);
        const originalText = button.textContent;
        button.textContent = '✓';
        button.classList.add('bg-green-500', 'text-white');
        setTimeout(() => {
            button.textContent = originalText;
            button.classList.remove('bg-green-500', 'text-white');
        }, 1500);
    } catch (error) {
        alert('Failed to copy: ' + error.message);
    }
};

// Copy all formats
copyAllBtn.addEventListener('click', async () => {
    if (!currentDate || isNaN(currentDate.getTime())) {
        alert('Please enter a valid date first');
        return;
    }
    
    const timezone = timezoneSelect.value;
    let output = `Date Formats (${currentDate.toISOString()})\n\n`;
    
    formats.forEach(format => {
        const value = formatDate(currentDate, format.key, timezone);
        output += `${format.name}: ${value}\n`;
    });
    
    try {
        await navigator.clipboard.writeText(output);
        const originalText = copyAllBtn.textContent;
        copyAllBtn.textContent = '✓ Copied!';
        setTimeout(() => {
            copyAllBtn.textContent = originalText;
        }, 2000);
    } catch (error) {
        alert('Failed to copy: ' + error.message);
    }
});

// Use current time
nowBtn.addEventListener('click', () => {
    dateInput.value = new Date().toISOString();
    renderFormats();
});

// Preset buttons
document.querySelectorAll('.preset-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const preset = btn.dataset.preset;
        const now = new Date();
        
        switch (preset) {
            case 'now':
                dateInput.value = now.toISOString();
                break;
            case 'tomorrow':
                now.setDate(now.getDate() + 1);
                dateInput.value = now.toISOString();
                break;
            case 'yesterday':
                now.setDate(now.getDate() - 1);
                dateInput.value = now.toISOString();
                break;
            case 'week':
                now.setDate(now.getDate() - 7);
                dateInput.value = now.toISOString();
                break;
        }
        
        renderFormats();
    });
});

// Auto-update on input
let debounceTimer;
dateInput.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(renderFormats, 300);
});

timezoneSelect.addEventListener('change', renderFormats);

// Initialize with current time
dateInput.value = new Date().toISOString();
renderFormats();
</script>
