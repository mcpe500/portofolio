<?php
/**
 * CoffeeScript Playground (Monaco + CoffeeScript Compiler)
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">coffee</span>
            CoffeeScript Playground
        </h1>
        <button id="run-btn" 
                class="px-4 py-1.5 text-sm font-medium rounded bg-primary text-white hover:bg-primary/90 transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">play_arrow</span>
            Compile & Run
        </button>
    </div>

    <!-- Editor & Console -->
    <div class="flex flex-1 overflow-hidden">
        <!-- Editor -->
        <div class="w-1/2 border-r border-gray-200 dark:border-slate-700 flex flex-col">
            <div id="editor-container" 
                 class="flex-1 w-full h-full text-sm font-mono bg-gray-50 dark:bg-slate-900 text-gray-800 dark:text-gray-200">
            </div>
        </div>

        <!-- Console -->
        <div class="w-1/2 bg-slate-900 text-slate-200 overflow-hidden flex flex-col font-mono text-sm">
            <div class="px-4 py-2 bg-slate-800 border-b border-slate-700 text-xs uppercase tracking-wider font-semibold text-slate-400 flex justify-between">
                <span>Console Output</span>
                <button id="clear-btn" class="hover:text-white transition-colors">Clear</button>
            </div>
            <div id="console-output" class="flex-1 p-4 overflow-auto whitespace-pre-wrap"></div>
        </div>
    </div>
</div>

<!-- Load CoffeeScript Compiler -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/coffeescript/2.7.0/coffeescript.min.js"></script>

<!-- Monaco Editor loader -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs/loader.min.js"
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

<script>
    const editorContainer = document.getElementById('editor-container');
    const runBtn = document.getElementById('run-btn');
    const clearBtn = document.getElementById('clear-btn');
    const consoleOutput = document.getElementById('console-output');
    let editorInstance;

    const initialCode = `# Assignment:
number   = 42
opposite = true

# Conditions:
number = -42 if opposite

# Functions:
square = (x) -> x * x

# Arrays:
list = [1, 2, 3, 4, 5]

# Objects:
math =
  root:   Math.sqrt
  square: square
  cube:   (x) -> x * square x

# Splats:
race = (winner, runners...) ->
  console.log "Winner: " + winner
  console.log "Runners: " + runners

console.log "Number: " + number
console.log "Square of 5: " + math.square(5)
race "Alice", "Bob", "Charlie", "Dave"`;

    // Console Emulation
    function appendToConsole(type, args) {
        const line = document.createElement('div');
        line.className = `mb-1 ${type === 'error' ? 'text-red-400' : type === 'warn' ? 'text-amber-400' : 'text-slate-200'}`;
        
        const content = args.map(arg => {
            if (typeof arg === 'object') {
                try {
                    return JSON.stringify(arg, null, 2);
                } catch (e) {
                    return String(arg);
                }
            }
            return String(arg);
        }).join(' ');

        line.textContent = `> ${content}`;
        consoleOutput.appendChild(line);
        consoleOutput.scrollTop = consoleOutput.scrollHeight;
    }

    function runCode() {
        if (!editorInstance || !window.CoffeeScript) return;
        const coffeeCode = editorInstance.getValue();
        
        appendToConsole('info', ['--- Compiling & Running ---']);

        // Hijack console
        const hijackedConsole = {
            log: (...args) => appendToConsole('log', args),
            error: (...args) => appendToConsole('error', args),
            warn: (...args) => appendToConsole('warn', args),
            info: (...args) => appendToConsole('info', args)
        };

        try {
            // Compile
            const jsCode = CoffeeScript.compile(coffeeCode);

            // Execute
            const func = new Function('console', jsCode);
            func(hijackedConsole);
        } catch (error) {
            appendToConsole('error', [error]);
        }
    }

    runBtn.addEventListener('click', runCode);
    
    clearBtn.addEventListener('click', () => {
        consoleOutput.innerHTML = '';
    });

    // Configure Monaco loader
    require.config({
        paths: {
            'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs'
        }
    });

    // Worker config
    window.MonacoEnvironment = {
        getWorkerUrl: function () {
            const base = 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/';
            const code = `
                self.MonacoEnvironment = { baseUrl: '${base}' };
                importScripts('${base}vs/base/worker/workerMain.min.js');
            `;
            return 'data:application/javascript;charset=utf-8,' + encodeURIComponent(code);
        }
    };

    // Initialize Monaco
    require(['vs/editor/editor.main'], function () {
        const isDark = document.documentElement.classList.contains('dark');

        editorInstance = monaco.editor.create(editorContainer, {
            value: initialCode,
            language: 'coffeescript',
            automaticLayout: true,
            minimap: { enabled: false },
            wordWrap: 'on',
            fontSize: 14,
            theme: isDark ? 'vs-dark' : 'vs'
        });

        // Initial Run
        setTimeout(runCode, 500);
    });
</script>
