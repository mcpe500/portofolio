<?php
/**
 * Quantum Visualizer
 * Interactive Quantum Circuit Simulator with Math & Physics Insights
 */
?>
<style>
    .gate-slot {
        width: 48px;
        height: 48px;
        border: 1px dashed #cbd5e1; /* slate-300 */
        border-radius: 4px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .dark .gate-slot {
        border-color: #334155; /* slate-700 */
    }
    .gate-slot:hover {
        background-color: rgba(59, 130, 246, 0.1);
        border-color: #3b82f6;
    }
    .gate-slot.drag-over {
        background-color: rgba(59, 130, 246, 0.2);
        box-shadow: inset 0 0 0 2px #3b82f6;
        border-style: solid;
    }

    .gate {
        width: 40px;
        height: 40px;
        background-color: white;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        cursor: grab;
        user-select: none;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        font-family: 'JetBrains Mono', monospace;
        z-index: 10;
    }
    .dark .gate {
        background-color: #1e293b;
        border-color: #475569;
        color: white;
    }
    .gate.dragging {
        opacity: 0.5;
        cursor: grabbing;
    }

    /* Gate Types */
    .gate-x, .gate-y, .gate-z { background-color: #dcfce7; color: #15803d; border-color: #86efac; } /* Green */
    .dark .gate-x, .dark .gate-y, .dark .gate-z { background-color: #052e16; color: #4ade80; border-color: #166534; }

    .gate-h { background-color: #ffedd5; color: #c2410c; border-color: #fdba74; } /* Orange */
    .dark .gate-h { background-color: #431407; color: #fb923c; border-color: #9a3412; }

    .gate-c { background-color: #dbeafe; color: #1d4ed8; border-color: #93c5fd; } /* Blue (CNOT Control) */
    .dark .gate-c { background-color: #172554; color: #60a5fa; border-color: #1e40af; }
    .gate-c-target { border-radius: 50%; width: 32px; height: 32px; border: 2px solid #3b82f6; } /* CNOT Target */

    .wire-line {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background-color: #94a3b8;
        z-index: 0;
    }

    .math-matrix {
        font-family: 'JetBrains Mono', monospace;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4px;
        text-align: center;
        position: relative;
    }
    .math-matrix::before, .math-matrix::after {
        content: "";
        position: absolute;
        top: 0;
        bottom: 0;
        width: 6px;
        border: 1px solid currentColor;
    }
    .math-matrix::before { left: -4px; border-right: none; }
    .math-matrix::after { right: -4px; border-left: none; }
</style>

<div class="flex flex-col h-[calc(100vh-64px)] max-w-[1920px] mx-auto">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-6 py-3 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">science</span>
                Quantum Visualizer
                <span class="text-xs font-normal px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">Experimental</span>
            </h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Interactive 3-Qubit Circuit Simulator & Physics Explainer</p>
        </div>
        <div class="flex items-center gap-4">
            <button id="reset-circuit" class="flex items-center gap-1 px-3 py-1.5 text-sm rounded hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors text-red-600 dark:text-red-400">
                <span class="material-symbols-outlined text-sm">restart_alt</span> Reset
            </button>
            <div class="h-6 w-px bg-gray-300 dark:bg-slate-600"></div>
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                <span id="qubit-count-display">3 Qubits</span>
            </div>
        </div>
    </div>

    <div class="flex-1 flex overflow-hidden">
        <!-- LEFT: Toolbox & Circuit -->
        <div class="w-1/2 flex flex-col border-r border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50">
            <!-- Gate Toolbox -->
            <div class="p-4 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700 shadow-sm z-10">
                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Logic Gates</h3>
                <div class="flex flex-wrap gap-4">
                    <div class="gate gate-h" draggable="true" data-type="H" title="Hadamard Gate" data-math="Superposition">H</div>
                    <div class="gate gate-x" draggable="true" data-type="X" title="Pauli-X (NOT) Gate" data-math="Bit Flip">X</div>
                    <div class="gate gate-y" draggable="true" data-type="Y" title="Pauli-Y Gate" data-math="Y-Rotation">Y</div>
                    <div class="gate gate-z" draggable="true" data-type="Z" title="Pauli-Z Gate" data-math="Phase Flip">Z</div>
                    <div class="gate gate-c" draggable="true" data-type="CNOT_C" title="CNOT Control (Drag first)" data-math="Entangler">●</div>
                    <!-- Target is auto-handled, simpler for now to drag Control then specific target -->
                </div>
                <p class="mt-2 text-xs text-gray-400">Drag gates onto the circuit lines below.</p>
            </div>

            <!-- Circuit Grid -->
            <div class="flex-1 p-6 overflow-auto bg-grid-pattern relative">
                <div id="circuit-container" class="space-y-8 min-w-[600px] inline-block">
                    <!-- Lines injected by JS -->
                </div>
            </div>
            
            <!-- Context Info (Bottom Left) -->
            <div id="step-explainer" class="p-4 bg-blue-50 dark:bg-blue-900/10 border-t border-blue-100 dark:border-blue-900/30 min-h-[100px]">
                <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-100 flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">info</span>
                    Physics Insight
                </h4>
                <p id="physics-text" class="text-sm text-blue-800 dark:text-blue-200 mt-1">
                    Start by dragging a Hadamard (H) gate to Qubit 0 to create a superposition.
                </p>
            </div>
        </div>

        <!-- RIGHT: Visualization & Math -->
        <div class="w-1/2 flex flex-col bg-white dark:bg-slate-900 overflow-y-auto">
            <!-- Top: Output Probabilities -->
            <div class="p-6 border-b border-gray-200 dark:border-slate-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4 flex items-center justify-between">
                    <span>Measurement Probabilities</span>
                    <span class="text-xs text-gray-500 font-normal">Theoretical Distribution</span>
                </h3>
                <div id="probability-chart" class="h-48 flex items-end justify-between gap-1">
                    <!-- Bars injected by JS -->
                </div>
                <div id="probability-labels" class="flex justify-between mt-2 px-1 text-xs font-mono text-gray-500">
                    <!-- Labels |000> etc encoded by JS -->
                </div>
            </div>

            <!-- Middle: The Math (State Vector) -->
            <div class="p-6 border-b border-gray-200 dark:border-slate-700 flex-1">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">function</span>
                    Quantum State Vector |ψ⟩
                </h3>
                
                <div class="p-4 bg-gray-900 rounded-lg text-gray-200 font-mono text-sm overflow-x-auto shadow-inner">
                    <div id="state-vector-display" class="leading-relaxed">
                        |ψ⟩ = 1.00|000⟩
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Active Gate Math</h3>
                    <div id="matrix-display" class="p-4 border border-gray-200 dark:border-slate-700 rounded-lg bg-gray-50 dark:bg-slate-800 min-h-[120px] flex items-center justify-center text-sm text-gray-600 dark:text-gray-400">
                        Hover over a gate in the circuit to see its Linear Algebra representation.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Quantum Engine (Minimal JS Implementation)
 */
class Complex {
    constructor(re, im) { this.re = re; this.im = im; }
    add(c) { return new Complex(this.re + c.re, this.im + c.im); }
    mul(c) { return new Complex(this.re * c.re - this.im * c.im, this.re * c.im + this.im * c.re); }
    absSq() { return this.re * this.re + this.im * this.im; }
    toString() {
        if (Math.abs(this.re) < 0.0001 && Math.abs(this.im) < 0.0001) return "0";
        let s = "";
        if (Math.abs(this.re) > 0.0001) s += this.re.toFixed(2);
        if (Math.abs(this.im) > 0.0001) {
            s += (this.im >= 0 ? (s ? "+" : "") : "") + this.im.toFixed(2) + "i";
        }
        return s;
    }
}

const GATES = {
    H: [[new Complex(1/Math.sqrt(2),0), new Complex(1/Math.sqrt(2),0)], [new Complex(1/Math.sqrt(2),0), new Complex(-1/Math.sqrt(2),0)]],
    X: [[new Complex(0,0), new Complex(1,0)], [new Complex(1,0), new Complex(0,0)]],
    Y: [[new Complex(0,0), new Complex(0,-1)], [new Complex(0,1), new Complex(0,0)]],
    Z: [[new Complex(1,0), new Complex(0,0)], [new Complex(0,0), new Complex(-1,0)]],
    I: [[new Complex(1,0), new Complex(0,0)], [new Complex(0,1), new Complex(0,0)]]
};

// UI & Logic
const NUM_QUBITS = 3;
const NUM_STEPS = 8;
const circuitData = Array(NUM_QUBITS).fill().map(() => Array(NUM_STEPS).fill(null));

// DOM Elements
const circuitContainer = document.getElementById('circuit-container');
const probChart = document.getElementById('probability-chart');
const probLabels = document.getElementById('probability-labels');
const stateVectorDisplay = document.getElementById('state-vector-display');
const matrixDisplay = document.getElementById('matrix-display');
const physicsText = document.getElementById('physics-text');
const resetBtn = document.getElementById('reset-circuit');

// Initialization
function initGrid() {
    circuitContainer.innerHTML = '';
    for (let q = 0; q < NUM_QUBITS; q++) {
        const row = document.createElement('div');
        row.className = 'relative flex items-center h-16';
        
        // Qubit Label
        const label = document.createElement('div');
        label.className = 'w-12 text-sm font-bold text-gray-500 dark:text-gray-400 select-none';
        label.innerText = `q${q}`;
        row.appendChild(label);

        // Wire
        const wire = document.createElement('div');
        wire.className = 'wire-line left-12 right-0';
        row.appendChild(wire);

        // Slots
        for (let s = 0; s < NUM_STEPS; s++) {
            const slot = document.createElement('div');
            slot.className = 'gate-slot z-10 ml-2 bg-white/50 dark:bg-slate-900/50 backdrop-blur-[1px]';
            slot.dataset.qubit = q;
            slot.dataset.step = s;
            
            // Drag Events
            slot.addEventListener('dragover', e => {
                e.preventDefault();
                slot.classList.add('drag-over');
            });
            slot.addEventListener('dragleave', () => slot.classList.remove('drag-over'));
            slot.addEventListener('drop', handleDrop);
            
            // Interaction: Remove on click if occupied
            slot.addEventListener('click', (e) => {
                // If it has a gate, remove it
                if (circuitData[q][s]) {
                    // special handling for CNOT later
                    circuitData[q][s] = null;
                    renderGrid();
                    simulate();
                } else if(selectedGateType === 'CNOT_T') {
                    // CNOT Target placement logic could go here if we used click-to-place
                }
            });

            // If gate exists in data, render it
            const gateInfo = circuitData[q][s];
            if (gateInfo) {
                const gateEl = document.createElement('div');
                gateEl.className = `gate gate-${gateInfo.type.toLowerCase()}`;
                if (gateInfo.type === 'CNOT_C') gateEl.textContent = '●';
                else if (gateInfo.type === 'CNOT_T') {
                    gateEl.textContent = '+';
                    gateEl.classList.add('gate-c-target');
                    gateEl.classList.remove('gate'); // shape override
                    gateEl.classList.add('flex', 'items-center', 'justify-center', 'rounded-full', 'bg-white', 'dark:bg-slate-900', 'z-10');
                }
                else gateEl.textContent = gateInfo.type;

                // Hover for Matrix
                gateEl.addEventListener('mouseenter', () => showGateMath(gateInfo.type));
                gateEl.addEventListener('mouseleave', () => showGateMath(null));
                
                slot.appendChild(gateEl);
            }

            row.appendChild(slot);
        }
        circuitContainer.appendChild(row);
    }
}

// Drag & Drop
let draggedType = null;

document.querySelectorAll('[draggable="true"]').forEach(el => {
    el.addEventListener('dragstart', e => {
        draggedType = e.target.dataset.type;
        e.target.classList.add('dragging');
    });
    el.addEventListener('dragend', e => {
        e.target.classList.remove('dragging');
        draggedType = null;
    });
});

function handleDrop(e) {
    e.preventDefault();
    e.target.classList.remove('drag-over');
    if (!draggedType) return;

    const q = parseInt(e.target.dataset.qubit);
    const s = parseInt(e.target.dataset.step);

    if (draggedType === 'CNOT_C') {
        // CNOT Logic: Must place control, then ask for target or default next qubit
        // For simplicity: Control at Q, Target at Q+1 (if exists)
        if (q + 1 < NUM_QUBITS) {
            circuitData[q][s] = { type: 'CNOT_C', target: q + 1 };
            circuitData[q + 1][s] = { type: 'CNOT_T', control: q };
        } else {
            alert("CNOT Control requires a qubit below it for the target in this version.");
        }
    } else {
        circuitData[q][s] = { type: draggedType };
    }
    
    renderGrid();
    simulate();
}

function renderGrid() {
    initGrid();
}

// Simulation Engine
function simulate() {
    // Start state: |000> -> vector [1, 0, 0, ... 0]
    let numStates = Math.pow(2, NUM_QUBITS);
    let state = Array(numStates).fill(null).map(() => new Complex(0,0));
    state[0] = new Complex(1,0); // |000> is 100%

    // Step by step
    for (let s = 0; s < NUM_STEPS; s++) {
        // 1. Build layer matrix (tensor product of all gates at this step)
        // If no gate, Identity (I)
        
        // Group CNOTs (skip target processing as it's handled with control)
        // But for simulation, we need a full matrix for the column. 
        // JS Simulation shortcut: Apply Gates sequentially if they don't overlap.
        // Actually, valid quantum simulation requires Tensor Product of (Gate x Gate x Gate) for the step.
        // Or, since our gates are mostly single qubit, we can apply single qubit gates to the state vector mathematically one by one?
        // No, tensor product is safer. 
        
        /** 
         * Simplified Simulation: 
         * Iterate Qubits. If Gate found, Apply it.
         * For CNOT (Control Q_c, Target Q_t), apply CNOT logic to the state vector indices.
         */
        
        let operations = [];
        for(let q=0; q<NUM_QUBITS; q++) {
            if(circuitData[q][s]) operations.push({q, op: circuitData[q][s]});
        }
        
        // Sort operations (not strictly needed for disjoint gates, but...)
        // Apply them to state
        operations.forEach(({q, op}) => {
            if(op.type === 'CNOT_T') return; // Handled by Control
            
            if(op.type === 'CNOT_C') {
                state = applyCNOT(state, q, op.target);
            } else {
                state = applySingleGate(state, q, GATES[op.type]);
            }
        });
    }

    updateVisualization(state);
}

function applySingleGate(state, wire, gateMatrix) {
    // gateMatrix is 2x2. state is 2^N.
    // Efficient update: iterate all pairs (i0, i1) where bit 'wire' is 0 or 1
    // New amplitude is linear comb.
    let newState = [...state];
    let N = NUM_QUBITS;
    
    // Identify pairs
    // Bit mask for wire position
    // e.g. wire 0 (LSB or MSB? Lets say Q0 is Top/MSB for notation |Q0 Q1 Q2>)
    // Standard is usually |Qn ... Q0>. Let's align UI Q0 = MSB for readability |Q0 Q1 Q2>
    
    // If Q0 is MSB (index 0 in layout), then its bit value has weight 2^(N-1-wire)
    let bitVal = 1 << (N - 1 - wire);
    
    for (let i = 0; i < state.length; i++) {
        if ((i & bitVal) === 0) {
            // This is the |...0...> component (index i)
            // The partner |...1...> is (index i + bitVal)
            let i0 = i;
            let i1 = i + bitVal;
            
            let a = state[i0];
            let b = state[i1];
            
            // [ a' ] = [ M00 M01 ] [ a ]
            // [ b' ]   [ M10 M11 ] [ b ]
            
            let res0 = gateMatrix[0][0].mul(a).add(gateMatrix[0][1].mul(b));
            let res1 = gateMatrix[1][0].mul(a).add(gateMatrix[1][1].mul(b));
            
            newState[i0] = res0;
            newState[i1] = res1;
        }
    }
    return newState;
}

function applyCNOT(state, controlWire, targetWire) {
    let newState = [...state];
    let N = NUM_QUBITS;
    let controlBit = 1 << (N - 1 - controlWire);
    let targetBit = 1 << (N - 1 - targetWire);
    
    for (let i = 0; i < state.length; i++) {
        // If control bit is 1, SWAP pairs based on target bit
        if ((i & controlBit) !== 0) {
            // Control is activated
            // We only process if target is 0, to find its 1 partner and swap them
            if ((i & targetBit) === 0) {
                let i0 = i;
                let i1 = i + targetBit;
                
                // CNOT is X (NOT) on target if control is 1
                // X swaps coefficients: new0 = old1, new1 = old0
                let temp = newState[i0];
                newState[i0] = newState[i1];
                newState[i1] = temp;
            }
        }
    }
    return newState;
}

// Visualization Logic
function updateVisualization(state) {
    // 1. Probabilities
    probChart.innerHTML = '';
    probLabels.innerHTML = '';
    
    let maxProb = 0;
    
    // Detect Superposition or Entanglement
    let nonZeroStates = 0;
    let probDist = [];

    state.forEach((amp, idx) => {
        let p = amp.absSq();
        probDist.push(p);
        if (p > 0.01) nonZeroStates++;
        if (p > maxProb) maxProb = p;
    });

    // Render Bars
    probDist.forEach((p, idx) => {
        let binaryString = idx.toString(2).padStart(NUM_QUBITS, '0');
        
        // Bar
        let barContainer = document.createElement('div');
        barContainer.className = 'flex flex-col items-center justify-end w-8 group';
        barContainer.style.height = '100%';
        
        let barHeight = (p * 100).toFixed(1) + '%';
        let bar = document.createElement('div');
        bar.style.height = barHeight;
        bar.className = 'w-full bg-primary/80 hover:bg-primary transition-all rounded-t relative';
        
        if (p > 0.01) {
            let label = document.createElement('span');
            label.className = 'absolute -top-5 left-1/2 -translate-x-1/2 text-[10px] font-bold text-gray-700 dark:text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity';
            label.innerText = (p * 100).toFixed(0) + '%';
            bar.appendChild(label);
        }

        barContainer.appendChild(bar);
        probChart.appendChild(barContainer);

        // Label
        let l = document.createElement('div');
        l.className = 'w-8 text-center text-[10px] ' + (p > 0.01 ? 'font-bold text-gray-900 dark:text-white' : 'text-gray-300 dark:text-gray-600');
        l.innerText = '|' + binaryString + '⟩';
        probLabels.appendChild(l);
    });

    // 2. State Vector Text
    let vecStr = "";
    state.forEach((amp, idx) => {
        let mag = amp.absSq();
        if (mag > 0.0001) {
            let binary = idx.toString(2).padStart(NUM_QUBITS, '0');
            // Format complex number nicely
            let re = amp.re;
            let im = amp.im;
            
            let term = "";
            let sign = (re >= 0 && vecStr !== "") ? "+" : "";
            
            // Simplification for pure real/imag
            let valStr = "";
            if(Math.abs(im) < 0.001) valStr = re.toFixed(3);
            else if(Math.abs(re) < 0.001) valStr = im.toFixed(3) + "i";
            else valStr = `(${re.toFixed(2)}${im>=0?'+':''}${im.toFixed(2)}i)`;

            vecStr += `${sign} ${valStr}|${binary}⟩ `;
        }
    });
    stateVectorDisplay.textContent = "|ψ⟩ = " + (vecStr || "0");

    // 3. Physics Text
    if (nonZeroStates > 1) {
        // Check for entanglement (heuristic: can probability be factored?)
        // Simple check: Bell state |00> + |11> (non-zero @ 0 and 3, zero @ 1, 2)
        // If it was separable, p(00)*p(11) would equal p(01)*p(10) roughly
        let p00 = probDist[0];
        let p11 = probDist[7] || probDist[3]; // for 3 qubits |111> is index 7
        // ... simplistic check
        
        physicsText.innerHTML = "<strong>Superposition Active:</strong> The system exists in multiple states simultaneously. Measuring it will collapse the wavefunction randomly to one of the basis states shown above.";
    } else {
        physicsText.innerHTML = "<strong>Deterministic State:</strong> The system is in a classical-like state. Measuring it yields a 100% predictable result.";
    }
}

function showGateMath(type) {
    if (!type) {
        matrixDisplay.innerHTML = '<span class="text-gray-400">Hover over a gate...</span>';
        return;
    }
    
    if(type === 'CNOT_C') {
        matrixDisplay.innerHTML = `
            <div class="text-center">
                <div class="mb-2 font-bold text-gray-700 dark:text-gray-300">CNOT (Controlled-NOT)</div>
                <div class="math-matrix inline-grid grid-cols-4 gap-2 text-xs">
                    <span>1</span><span>0</span><span>0</span><span>0</span>
                    <span>0</span><span>1</span><span>0</span><span>0</span>
                    <span>0</span><span>0</span><span>0</span><span>1</span>
                    <span>0</span><span>0</span><span>1</span><span>0</span>
                </div>
                <div class="mt-2 text-xs text-gray-500">
                    If Control is |1⟩, FLIP Target.<br>Creates Entanglement.
                </div>
            </div>`;
        return;
    }

    const m = GATES[type];
    if (!m) return;
    
    let html = `
        <div class="text-center">
             <div class="mb-2 font-bold text-gray-700 dark:text-gray-300">${type} Gate</div>
             <div class="math-matrix">
                <span>${fmtC(m[0][0])}</span> <span>${fmtC(m[0][1])}</span>
                <span>${fmtC(m[1][0])}</span> <span>${fmtC(m[1][1])}</span>
             </div>
        </div>
    `;
    matrixDisplay.innerHTML = html;
}

function fmtC(c) {
    // Format for matrix display (simplify fractions visually if possible, or just raw)
    // 0.707 -> 1/√2
    let s = c.toString();
    if(Math.abs(c.re - 0.7071) < 0.01) return "1/√2";
    if(Math.abs(c.re + 0.7071) < 0.01) return "-1/√2";
    return s || "0";
}

// Init
resetBtn.addEventListener('click', () => {
    for(let q=0; q<NUM_QUBITS; q++) circuitData[q].fill(null);
    renderGrid();
    simulate();
});

renderGrid();
simulate();

</script>
