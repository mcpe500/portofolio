<?php
/**
 * Date Formatter
 * Convert dates between various formats with timezone support
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
        <div class="w-1/2 border-r border-gray-200 dark:border-slate-700 flex flex-col bg-white dark:bg-slate-900 overflow-auto">
            <div class="p-4 space-y-4">
                <!-- Date Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Input Date
                    </label>
                    <input id="date-input" type="text" placeholder="2024-03-15 14:30:00 or Unix timestamp" 
                        class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" />
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Auto-detects: ISO, Unix, DD/MM/YYYY, MM/DD/YYYY, etc.</p>
                </div>

                <!-- Timezone Selector -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Display Timezone
                    </label>
                    <select id="timezone-select" class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="local">🌍 Local (Browser Timezone)</option>
                        <option value="UTC">🌐 UTC (GMT+0)</option>
                        <optgroup label="Americas">
                            <option value="America/New_York">🇺🇸 New York (UTC-5/-4)</option>
                            <option value="America/Los_Angeles">🇺🇸 Los Angeles (UTC-8/-7)</option>
                            <option value="America/Chicago">🇺🇸 Chicago (UTC-6/-5)</option>
                            <option value="America/Denver">🇺🇸 Denver (UTC-7/-6)</option>
                            <option value="America/Phoenix">🇺🇸 Phoenix (UTC-7)</option>
                            <option value="America/Toronto">🇨🇦 Toronto (UTC-5/-4)</option>
                            <option value="America/Mexico_City">🇲🇽 Mexico City (UTC-6/-5)</option>
                            <option value="America/Sao_Paulo">🇧🇷 São Paulo (UTC-3)</option>
                            <option value="America/Argentina/Buenos_Aires">🇦🇷 Buenos Aires (UTC-3)</option>
                        </optgroup>
                        <optgroup label="Europe">
                            <option value="Europe/London">🇬🇧 London (UTC+0/+1)</option>
                            <option value="Europe/Paris">🇫🇷 Paris (UTC+1/+2)</option>
                            <option value="Europe/Berlin">🇩🇪 Berlin (UTC+1/+2)</option>
                            <option value="Europe/Rome">🇮🇹 Rome (UTC+1/+2)</option>
                            <option value="Europe/Madrid">🇪🇸 Madrid (UTC+1/+2)</option>
                            <option value="Europe/Amsterdam">🇳🇱 Amsterdam (UTC+1/+2)</option>
                            <option value="Europe/Athens">🇬🇷 Athens (UTC+2/+3)</option>
                            <option value="Europe/Moscow">🇷🇺 Moscow (UTC+3)</option>
                        </optgroup>
                        <optgroup label="Asia">
                            <option value="Asia/Dubai">🇦🇪 Dubai (UTC+4)</option>
                            <option value="Asia/Karachi">🇵🇰 Karachi (UTC+5)</option>
                            <option value="Asia/Kolkata">🇮🇳 Mumbai/Delhi (UTC+5:30)</option>
                            <option value="Asia/Dhaka">🇧🇩 Dhaka (UTC+6)</option>
                            <option value="Asia/Bangkok">🇹🇭 Bangkok (UTC+7)</option>
                            <option value="Asia/Singapore">🇸🇬 Singapore (UTC+8)</option>
                            <option value="Asia/Hong_Kong">🇭🇰 Hong Kong (UTC+8)</option>
                            <option value="Asia/Shanghai">🇨🇳 Shanghai (UTC+8)</option>
                            <option value="Asia/Tokyo">🇯🇵 Tokyo (UTC+9)</option>
                            <option value="Asia/Seoul">🇰🇷 Seoul (UTC+9)</option>
                        </optgroup>
                        <optgroup label="Pacific">
                            <option value="Australia/Sydney">🇦🇺 Sydney (UTC+10/+11)</option>
                            <option value="Australia/Melbourne">🇦🇺 Melbourne (UTC+10/+11)</option>
                            <option value="Australia/Perth">🇦🇺 Perth (UTC+8)</option>
                            <option value="Pacific/Auckland">🇳🇿 Auckland (UTC+12/+13)</option>
                            <option value="Pacific/Fiji">🇫🇯 Fiji (UTC+12)</option>
                        </optgroup>
                        <optgroup label="Africa & Middle East">
                            <option value="Africa/Cairo">🇪🇬 Cairo (UTC+2)</option>
                            <option value="Africa/Johannesburg">🇿🇦 Johannesburg (UTC+2)</option>
                            <option value="Africa/Lagos">🇳🇬 Lagos (UTC+1)</option>
                            <option value="Asia/Jerusalem">🇮🇱 Jerusalem (UTC+2/+3)</option>
                            <option value="Asia/Riyadh">🇸🇦 Riyadh (UTC+3)</option>
                        </optgroup>
                    </select>
                    <div id="timezone-info" class="mt-2 text-xs text-gray-600 dark:text-gray-400"></div>
                </div>

                <!-- Quick Presets -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Quick Presets
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        <button class="preset-btn px-2 py-2 text-xs rounded bg-gray-100 dark:bg-gray-700 hover:bg-primary hover:text-white transition-colors" data-preset="now">
                            Now
                        </button>
                        <button class="preset-btn px-2 py-2 text-xs rounded bg-gray-100 dark:bg-gray-700 hover:bg-primary hover:text-white transition-colors" data-preset="today">
                            Today 00:00
                        </button>
                        <button class="preset-btn px-2 py-2 text-xs rounded bg-gray-100 dark:bg-gray-700 hover:bg-primary hover:text-white transition-colors" data-preset="tomorrow">
                            Tomorrow
                        </button>
                        <button class="preset-btn px-2 py-2 text-xs rounded bg-gray-100 dark:bg-gray-700 hover:bg-primary hover:text-white transition-colors" data-preset="yesterday">
                            Yesterday
                        </button>
                        <button class="preset-btn px-2 py-2 text-xs rounded bg-gray-100 dark:bg-gray-700 hover:bg-primary hover:text-white transition-colors" data-preset="week">
                            1 Week Ago
                        </button>
                        <button class="preset-btn px-2 py-2 text-xs rounded bg-gray-100 dark:bg-gray-700 hover:bg-primary hover:text-white transition-colors" data-preset="month">
                            1 Month Ago
                        </button>
                    </div>
                </div>

                <!-- Date Arithmetic -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Date Arithmetic
                    </label>
                    <div class="flex gap-2">
                        <input id="arithmetic-value" type="number" value="1" class="w-20 rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-2 py-1 text-sm" />
                        <select id="arithmetic-unit" class="flex-1 rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-2 py-1 text-sm">
                            <option value="days">Days</option>
                            <option value="hours">Hours</option>
                            <option value="minutes">Minutes</option>
                            <option value="weeks">Weeks</option>
                            <option value="months">Months</option>
                            <option value="years">Years</option>
                        </select>
                        <button id="add-time" class="px-3 py-1 text-xs rounded bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 hover:bg-green-200 dark:hover:bg-green-900/50">
                            + Add
                        </button>
                        <button id="subtract-time" class="px-3 py-1 text-xs rounded bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 hover:bg-red-200 dark:hover:bg-red-900/50">
                            - Subtract
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

                <!-- UTC Offset Display -->
                <div id="utc-offset-display" class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <div class="text-xs font-semibold text-blue-800 dark:text-blue-300 mb-1">Timezone Information</div>
                    <div id="utc-offset-text" class="text-sm font-mono text-blue-900 dark:text-blue-100"></div>
                </div>

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
const timezoneInfo = document.getElementById('timezone-info');
const utcOffsetDisplay = document.getElementById('utc-offset-display');
const utcOffsetText = document.getElementById('utc-offset-text');
const formatsOutput = document.getElementById('formats-output');
const inputError = document.getElementById('input-error');
const nowBtn = document.getElementById('now-btn');
const copyAllBtn = document.getElementById('copy-all-btn');
const arithmeticValue = document.getElementById('arithmetic-value');
const arithmeticUnit = document.getElementById('arithmetic-unit');
const addTimeBtn = document.getElementById('add-time');
const subtractTimeBtn = document.getElementById('subtract-time');

let currentDate = new Date();

// Enhanced Date Format Definitions
const formats = [
    { name: 'ISO 8601', key: 'iso8601', desc: 'YYYY-MM-DDTHH:mm:ssZ' },
    { name: 'ISO 8601 (Extended)', key: 'iso8601Extended', desc: 'With milliseconds' },
    { name: 'ISO 8601 (Local)', key: 'iso8601Local', desc: 'With timezone offset' },
    { name: 'RFC 3339', key: 'rfc3339', desc: 'Internet timestamp' },
    { name: 'RFC 2822', key: 'rfc2822', desc: 'Email timestamp' },
    { name: 'Unix Timestamp', key: 'unix', desc: 'Seconds since epoch' },
    { name: 'Unix Timestamp (ms)', key: 'unixMs', desc: 'Milliseconds since epoch' },
    { name: 'UTC Date/Time', key: 'utcDateTime', desc: 'YYYY-MM-DD HH:mm:ss UTC' },
    { name: 'UTC with Offset', key: 'utcWithOffset', desc: 'Shows UTC±X format' },
    { name: 'DD/MM/YYYY', key: 'ddmmyyyy', desc: 'European format' },
    { name: 'MM/DD/YYYY', key: 'mmddyyyy', desc: 'US format' },
    { name: 'YYYY-MM-DD', key: 'yyyymmdd', desc: 'ISO date only' },
    { name: 'DD-MM-YYYY HH:mm:ss', key: 'ddmmyyyyTime', desc: 'European with time' },
    { name: 'MM-DD-YYYY HH:mm:ss', key: 'mmddyyyyTime', desc: 'US with time' },
    { name: 'YYYY-MM-DD HH:mm:ss', key: 'yyyymmddTime', desc: 'ISO with time' },
    { name: 'DD MMM YYYY', key: 'ddMMMMyyyy', desc: '15 Mar 2024' },
    { name: 'DD MMMM YYYY', key: 'ddMMMMyyyyFull', desc: '15 March 2024' },
    { name: 'MMMM DD, YYYY', key: 'MMMMddyyyy', desc: 'March 15, 2024' },
    { name: 'MMMM DD, YYYY HH:mm', key: 'MMMMddyyyyTime', desc: 'March 15, 2024 14:30' },
    { name: 'HH:mm:ss', key: 'time24', desc: '24-hour time' },
    { name: 'hh:mm:ss A', key: 'time12', desc: '12-hour time with AM/PM' },
    { name: 'HH:mm:ss.SSS', key: 'timeMs', desc: '24-hour with milliseconds' },
    { name: 'Day of Week', key: 'dayOfWeek', desc: 'Monday, Tuesday, etc.' },
    { name: 'Short Day of Week', key: 'shortDayOfWeek', desc: 'Mon, Tue, etc.' },
    { name: 'Day of Year', key: 'dayOfYear', desc: 'Day number (1-365/366)' },
    { name: 'Week of Year', key: 'weekOfYear', desc: 'Week number (ISO)' },
    { name: 'Quarter', key: 'quarter', desc: 'Q1, Q2, Q3, or Q4' },
    { name: 'Relative Time', key: 'relative', desc: '2 hours ago, in 3 days' },
    { name: 'Relative (Precise)', key: 'relativePrecise', desc: '2h 30m 15s ago' },
    { name: 'Full Locale String', key: 'fullLocale', desc: 'Browser locale format' },
    { name: 'Cookie Expires', key: 'cookieExpires', desc: 'HTTP cookie format' },
    { name: 'SQL DateTime', key: 'sqlDateTime', desc: 'Database format' },
    { name: 'Excel Serial', key: 'excelSerial', desc: 'Excel date number' },
];

// Get UTC offset for a timezone
function getUTCOffset(date, timezone) {
    if (timezone === 'local') {
        const offset = -date.getTimezoneOffset();
        const hours = Math.floor(Math.abs(offset) / 60);
        const minutes = Math.abs(offset) % 60;
        const sign = offset >= 0 ? '+' : '-';
        return `UTC${sign}${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
    } else if (timezone === 'UTC') {
        return 'UTC+00:00';
    } else {
        try {
            const formatter = new Intl.DateTimeFormat('en-US', {
                timeZone: timezone,
                timeZoneName: 'longOffset'
            });
            const parts = formatter.formatToParts(date);
            const offsetPart = parts.find(p => p.type === 'timeZoneName');
            if (offsetPart) {
                return offsetPart.value.replace('GMT', 'UTC');
            }
        } catch (e) {
            // Fallback
        }
    }
    return 'UTC';
}

// Format date based on key
function formatDate(date, formatKey, timezone) {
    const tz = timezone === 'local' ? undefined : timezone;
    
    try {
        switch (formatKey) {
            case 'iso8601':
                return date.toISOString().slice(0, 19) + 'Z';
            
            case 'iso8601Extended':
                return date.toISOString();
            
            case 'iso8601Local':
                const offset = -date.getTimezoneOffset();
                const offsetHours = Math.floor(Math.abs(offset) / 60);
                const offsetMinutes = Math.abs(offset) % 60;
                const offsetSign = offset >= 0 ? '+' : '-';
                return date.getFullYear() + '-' + 
                    pad(date.getMonth() + 1) + '-' +
                    pad(date.getDate()) + 'T' +
                    pad(date.getHours()) + ':' +
                    pad(date.getMinutes()) + ':' +
                    pad(date.getSeconds()) +
                    offsetSign + pad(offsetHours) + ':' + pad(offsetMinutes);
            
            case 'rfc3339':
                return date.toISOString();
            
            case 'rfc2822':
                return date.toUTCString();
            
            case 'unix':
                return Math.floor(date.getTime() / 1000).toString();
            
            case 'unixMs':
                return date.getTime().toString();
            
            case 'utcDateTime':
                return date.toISOString().replace('T', ' ').slice(0, 19) + ' UTC';
            
            case 'utcWithOffset':
                return date.toISOString().slice(0, 19) + ' ' + getUTCOffset(date, timezone);
            
            case 'ddmmyyyy':
                return `${pad(date.getDate())}/${pad(date.getMonth() + 1)}/${date.getFullYear()}`;
            
            case 'mmddyyyy':
                return `${pad(date.getMonth() + 1)}/${pad(date.getDate())}/${date.getFullYear()}`;
            
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
            
            case 'ddMMMMyyyyFull':
                return formatWithIntl(date, { day: '2-digit', month: 'long', year: 'numeric' }, tz);
            
            case 'MMMMddyyyy':
                return formatWithIntl(date, { month: 'long', day: '2-digit', year: 'numeric' }, tz);
            
            case 'MMMMddyyyyTime':
                return formatWithIntl(date, { month: 'long', day: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }, tz);
            
            case 'time24':
                return `${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`;
            
            case 'time12':
                const hours24 = date.getHours();
                const ampm = hours24 >= 12 ? 'PM' : 'AM';
                const hours12 = hours24 % 12 || 12;
                return `${pad(hours12)}:${pad(date.getMinutes())}:${pad(date.getSeconds())} ${ampm}`;
            
            case 'timeMs':
                return `${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}.${String(date.getMilliseconds()).padStart(3, '0')}`;
            
            case 'dayOfWeek':
                return formatWithIntl(date, { weekday: 'long' }, tz);
            
            case 'shortDayOfWeek':
                return formatWithIntl(date, { weekday: 'short' }, tz);
            
            case 'dayOfYear':
                const start = new Date(date.getFullYear(), 0, 0);
                const diff = date - start;
                const oneDay = 1000 * 60 * 60 * 24;
                return Math.floor(diff / oneDay).toString();
            
            case 'weekOfYear':
                const startOfYear = new Date(date.getFullYear(), 0, 1);
                const days = Math.floor((date - startOfYear) / (24 * 60 * 60 * 1000));
                return Math.ceil((days + startOfYear.getDay() + 1) / 7).toString();
            
            case 'quarter':
                const quarter = Math.floor(date.getMonth() / 3) + 1;
                return `Q${quarter} ${date.getFullYear()}`;
            
            case 'relative':
                return getRelativeTime(date);
            
            case 'relativePrecise':
                return getRelativeTimePrecise(date);
            
            case 'fullLocale':
                return date.toLocaleString(undefined, { timeZone: tz });
            
            case 'cookieExpires':
                return date.toUTCString();
            
            case 'sqlDateTime':
                return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())} ${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`;
            
            case 'excelSerial':
                const excelEpoch = new Date(1899, 11, 30);
                const daysSinceEpoch = (date - excelEpoch) / (24 * 60 * 60 * 1000);
                return daysSinceEpoch.toFixed(5);
            
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

function getRelativeTimePrecise(date) {
    const now = new Date();
    const diff = date - now;
    const absDiff = Math.abs(diff);
    
    const seconds = Math.floor(absDiff / 1000) % 60;
    const minutes = Math.floor(absDiff / (1000 * 60)) % 60;
    const hours = Math.floor(absDiff / (1000 * 60 * 60)) % 24;
    const days = Math.floor(absDiff / (1000 * 60 * 60 * 24));
    
    const isPast = diff < 0;
    let parts = [];
    
    if (days > 0) parts.push(`${days}d`);
    if (hours > 0) parts.push(`${hours}h`);
    if (minutes > 0) parts.push(`${minutes}m`);
    if (seconds > 0 || parts.length === 0) parts.push(`${seconds}s`);
    
    return `${parts.join(' ')}${isPast ? ' ago' : ' from now'}`;
}

// Parse input date (enhanced)
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
    
    // Try DD/MM/YYYY or DD-MM-YYYY
    const ddmmyyyyMatch = input.match(/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})(?:\s+(\d{1,2}):(\d{1,2})(?::(\d{1,2}))?)?$/);
    if (ddmmyyyyMatch) {
        const [_, day, month, year, hours = 0, minutes = 0, seconds = 0] = ddmmyyyyMatch;
        return new Date(year, month - 1, day, hours, minutes, seconds);
    }
    
    // Try MM/DD/YYYY or MM-DD-YYYY (ambiguous, assume US format if month > 12)
    const mmddyyyyMatch = input.match(/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})(?:\s+(\d{1,2}):(\d{1,2})(?::(\d{1,2}))?)?$/);
    if (mmddyyyyMatch) {
        const [_, first, second, year, hours = 0, minutes = 0, seconds = 0] = mmddyyyyMatch;
        if (parseInt(first) > 12) {
            // Must be DD/MM/YYYY
            return new Date(year, second - 1, first, hours, minutes, seconds);
        }
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
        inputError.querySelector('p').textContent = 'Invalid date format. Try: "2024-03-15", "15/03/2024", "1234567890", or "March 15, 2024"';
        formatsOutput.innerHTML = '';
        return;
    }
    
    inputError.classList.add('hidden');
    currentDate = date;
    
    const timezone = timezoneSelect.value;
    
    // Update UTC offset display
    const offset = getUTCOffset(date, timezone);
    const selectedOption = timezoneSelect.options[timezoneSelect.selectedIndex];
    utcOffsetText.textContent = `${selectedOption.text.replace(/🌍|🌐|🇺🇸|🇨🇦|🇲🇽|🇧🇷|🇦🇷|🇬🇧|🇫🇷|🇩🇪|🇮🇹|🇪🇸|🇳🇱|🇬🇷|🇷🇺|🇦🇪|🇵🇰|🇮🇳|🇧🇩|🇹🇭|🇸🇬|🇭🇰|🇨🇳|🇯🇵|🇰🇷|🇦🇺|🇳🇿|🇫🇯|🇪🇬|🇿🇦|🇳🇬|🇮🇱|🇸🇦/g, '').trim()} - ${offset}`;
    timezoneInfo.textContent = `Currently displaying times in ${offset}`;
    
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
    const offset = getUTCOffset(currentDate, timezone);
    let output = `Date Formats (${currentDate.toISOString()}) - ${offset}\n\n`;
    
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
            case 'today':
                now.setHours(0, 0, 0, 0);
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
            case 'month':
                now.setMonth(now.getMonth() - 1);
                dateInput.value = now.toISOString();
                break;
        }
        
        renderFormats();
    });
});

// Date arithmetic
function performArithmetic(operation) {
    if (!currentDate || isNaN(currentDate.getTime())) {
        alert('Please enter a valid date first');
        return;
    }
    
    const value = parseInt(arithmeticValue.value);
    const unit = arithmeticUnit.value;
    const newDate = new Date(currentDate);
    
    const multiplier = operation === 'add' ? 1 : -1;
    
    switch (unit) {
        case 'minutes':
            newDate.setMinutes(newDate.getMinutes() + (value * multiplier));
            break;
        case 'hours':
            newDate.setHours(newDate.getHours() + (value * multiplier));
            break;
        case 'days':
            newDate.setDate(newDate.getDate() + (value * multiplier));
            break;
        case 'weeks':
            newDate.setDate(newDate.getDate() + (value * 7 * multiplier));
            break;
        case 'months':
            newDate.setMonth(newDate.getMonth() + (value * multiplier));
            break;
        case 'years':
            newDate.setFullYear(newDate.getFullYear() + (value * multiplier));
            break;
    }
    
    dateInput.value = newDate.toISOString();
    renderFormats();
}

addTimeBtn.addEventListener('click', () => performArithmetic('add'));
subtractTimeBtn.addEventListener('click', () => performArithmetic('subtract'));

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
