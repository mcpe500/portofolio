<?php
/**
 * TypeScript Playground (Monaco + TS Compiler)
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">code</span>
            TypeScript Playground
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

<!-- Load TypeScript Compiler -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/typescript/5.3.3/typescript.min.js"></script>

<!-- Monaco Editor loader -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.53.0/min/vs/loader.min.js"
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

<script>
    const editorContainer = document.getElementById('editor-container');
    const runBtn = document.getElementById('run-btn');
    const clearBtn = document.getElementById('clear-btn');
    const consoleOutput = document.getElementById('console-output');
    let editorInstance;

    const initialCode = `interface User {
    id: number;
    name: string;
    role: 'admin' | 'user';
}

class UserManager {
    private users: User[] = [];

    addUser(user: User): void {
        this.users.push(user);
        console.log(\`User added: \${user.name} (\${user.role})\`);
    }

    getAdmins(): User[] {
        return this.users.filter(u => u.role === 'admin');
    }
}

const manager = new UserManager();

manager.addUser({ id: 1, name: 'Alice', role: 'admin' });
manager.addUser({ id: 2, name: 'Bob', role: 'user' });

const admins = manager.getAdmins();
console.log('Admins:', admins);`;

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
        if (!editorInstance || !window.ts) return;
        const tsCode = editorInstance.getValue();
        
        appendToConsole('info', ['--- Compiling & Running ---']);

        // Hijack console
        const hijackedConsole = {
            log: (...args) => appendToConsole('log', args),
            error: (...args) => appendToConsole('error', args),
            warn: (...args) => appendToConsole('warn', args),
            info: (...args) => appendToConsole('info', args)
        };

        try {
            // Transpile
            const jsCode = ts.transpile(tsCode, { 
                target: ts.ScriptTarget.ES2015,
                module: ts.ModuleKind.None 
            });

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
            'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.53.0/min/vs'
        }
    });

    // Worker config
    window.MonacoEnvironment = {
        getWorkerUrl: function () {
            const base = 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.53.0/min/';
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

        // Configure TypeScript compiler options for Monaco
        monaco.languages.typescript.typescriptDefaults.setCompilerOptions({
            target: monaco.languages.typescript.ScriptTarget.ES2015,
            allowNonTsExtensions: true,
            moduleResolution: monaco.languages.typescript.ModuleResolutionKind.NodeJs,
            module: monaco.languages.typescript.ModuleKind.CommonJS,
            noEmit: true,
            typeRoots: ["node_modules/@types"]
        });

        editorInstance = monaco.editor.create(editorContainer, {
            value: initialCode,
            language: 'typescript',
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
