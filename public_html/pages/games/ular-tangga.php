<?php
/**
 * Ular Tangga (Snakes & Ladders) Multiplayer Game
 * Real-time multiplayer using PeerJS (WebRTC P2P)
 */
?>
<style>
    :root {
        --cell-size: min(6vw, 45px);
        --c1: #e0f7fa;
        --c2: #ffffff;
    }
    
    #ulartangga-app { min-height: 80vh; }
    
    /* Board */
    .ut-board-container {
        position: relative;
        width: calc(var(--cell-size) * 10);
        height: calc(var(--cell-size) * 10);
        border: 4px solid #8b4513;
        background: #fdf5e6;
        margin: 0 auto;
    }
    
    .ut-grid {
        display: grid;
        grid-template-columns: repeat(10, 1fr);
        grid-template-rows: repeat(10, 1fr);
        width: 100%;
        height: 100%;
        position: relative;
        z-index: 1;
    }
    
    .ut-cell {
        border: 1px solid rgba(0,0,0,0.1);
        display: flex;
        justify-content: flex-start;
        align-items: flex-start;
        font-size: 8px;
        padding: 2px;
        font-weight: bold;
        color: #333;
    }
    
    .ut-cell:nth-child(even) { background-color: var(--c2); }
    .ut-cell:nth-child(odd) { background-color: var(--c1); }
    
    #ut-svg-overlay {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        z-index: 5;
        pointer-events: none;
    }
    
    .ut-token {
        width: calc(var(--cell-size) * 0.5);
        height: calc(var(--cell-size) * 0.5);
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.5);
        position: absolute;
        z-index: 10;
        transition: top 0.5s ease-in-out, left 0.5s ease-in-out;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 9px;
        color: white;
        font-weight: bold;
    }
    
    .ut-sidebar {
        background: rgba(30,30,30,0.9);
        border-radius: 8px;
        padding: 12px;
        min-width: 260px;
    }
    
    .ut-panel {
        background: rgba(255,255,255,0.1);
        border-radius: 6px;
        padding: 10px;
        margin-bottom: 10px;
    }
    
    .ut-dice {
        width: 60px;
        height: 60px;
        background: white;
        border: 3px solid #333;
        border-radius: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 28px;
        font-weight: bold;
        color: #333;
        cursor: pointer;
        box-shadow: 3px 3px 0 #333;
        transition: transform 0.1s;
        margin: 0 auto;
    }
    
    .ut-dice:hover { transform: scale(1.05); }
    .ut-dice:active { transform: translate(2px, 2px); box-shadow: 1px 1px 0 #333; }
    .ut-dice.rolling { animation: ut-shake 0.5s infinite; }
    .ut-dice:disabled { opacity: 0.5; cursor: not-allowed; }
    
    @keyframes ut-shake {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(10deg); }
        75% { transform: rotate(-10deg); }
    }
    
    .ut-log {
        height: 100px;
        overflow-y: auto;
        font-size: 10px;
        background: #1a1a1a;
        color: #0f0;
        padding: 5px;
        font-family: monospace;
        border-radius: 4px;
    }
    
    .ut-log-entry { margin-bottom: 2px; border-bottom: 1px solid #333; }
    
    .ut-player-card {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px;
        border-radius: 4px;
        margin-bottom: 4px;
        background: rgba(255,255,255,0.05);
    }
    
    .ut-player-card.active {
        background: rgba(46, 204, 113, 0.2);
        border-left: 3px solid #2ecc71;
    }
    
    .ut-player-token {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 2px solid white;
    }
</style>

<main class="flex flex-1 justify-center pt-20 md:pt-24 pb-8">
    <div class="w-full" id="ulartangga-app">
        
        <!-- Lobby Screen -->
        <div id="ut-lobby" class="flex flex-col items-center justify-center min-h-[80vh] px-4">
            <div class="w-full max-w-md bg-surface border border-border rounded-2xl p-8 shadow-xl">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-black text-white mb-2">🐍 Ular Tangga 🪜</h1>
                    <p class="text-muted text-sm">Multiplayer Snakes & Ladders</p>
                </div>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-muted mb-2">Your Name</label>
                        <input type="text" id="ut-player-name" maxlength="15" placeholder="Enter your name..."
                            class="w-full px-4 py-3 bg-bg border border-border rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <button onclick="utLobby.createRoom()" 
                            class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition-all">
                            <span class="material-symbols-outlined align-middle mr-1">add_circle</span>
                            Create Room
                        </button>
                        <button onclick="utLobby.showJoinDialog()"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-all">
                            <span class="material-symbols-outlined align-middle mr-1">login</span>
                            Join Room
                        </button>
                    </div>
                    
                    <div id="ut-join-dialog" class="hidden">
                        <label class="block text-sm font-medium text-muted mb-2">Room Code</label>
                        <div class="flex gap-2">
                            <input type="text" id="ut-room-code" maxlength="6" placeholder="ABC123"
                                class="flex-1 px-4 py-3 bg-bg border border-border rounded-lg text-white uppercase text-center tracking-widest font-mono text-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            <button onclick="utLobby.joinRoom()" class="px-6 py-3 bg-primary hover:opacity-90 text-white font-bold rounded-lg">
                                Join
                            </button>
                        </div>
                    </div>
                </div>
                
                <div id="ut-lobby-status" class="mt-6 text-center text-sm text-muted hidden">
                    <div class="inline-flex items-center gap-2">
                        <div class="animate-spin w-4 h-4 border-2 border-primary border-t-transparent rounded-full"></div>
                        <span id="ut-lobby-status-text">Connecting...</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Room Screen -->
        <div id="ut-room" class="hidden flex flex-col items-center justify-center min-h-[80vh] px-4">
            <div class="w-full max-w-lg bg-surface border border-border rounded-2xl p-8 shadow-xl">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Room</h2>
                        <div class="flex items-center gap-2 mt-2">
                            <span id="ut-room-code-display" class="text-2xl font-mono font-bold text-primary tracking-widest">------</span>
                            <button onclick="utLobby.copyRoomCode()" class="text-muted hover:text-white transition-colors">
                                <span class="material-symbols-outlined text-xl">content_copy</span>
                            </button>
                        </div>
                    </div>
                    <button onclick="utLobby.leaveRoom()" class="text-muted hover:text-red-500 transition-colors">
                        <span class="material-symbols-outlined">logout</span>
                    </button>
                </div>
                
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-muted mb-3">Players (<span id="ut-player-count">0</span>/4)</h3>
                    <div id="ut-player-list" class="space-y-2"></div>
                </div>
                
                <div id="ut-host-controls" class="hidden">
                    <button id="ut-btn-start" onclick="utLobby.startGame()" disabled
                        class="w-full px-6 py-4 bg-green-600 hover:bg-green-700 disabled:bg-gray-600 disabled:cursor-not-allowed text-white font-bold rounded-lg text-lg">
                        <span class="material-symbols-outlined align-middle mr-2">play_arrow</span>
                        Start Game
                    </button>
                    <p id="ut-start-hint" class="text-center text-sm text-muted mt-2">Need 2-4 players</p>
                </div>
                
                <div id="ut-guest-waiting" class="hidden text-center py-4">
                    <div class="inline-flex items-center gap-2 text-muted">
                        <div class="animate-pulse w-3 h-3 bg-yellow-500 rounded-full"></div>
                        <span>Waiting for host to start...</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Game Screen -->
        <div id="ut-game" class="hidden px-4">
            <div class="flex flex-col lg:flex-row gap-4 justify-center items-start">
                <!-- Board -->
                <div class="ut-board-container" id="ut-board-container">
                    <div class="ut-grid" id="ut-grid"></div>
                    <svg id="ut-svg-overlay"></svg>
                    <div id="ut-tokens-container"></div>
                </div>
                
                <!-- Sidebar -->
                <div class="ut-sidebar">
                    <div class="ut-panel">
                        <div class="text-sm font-bold text-white mb-2 text-center">Roll Dice</div>
                        <div class="ut-dice" id="ut-dice" onclick="utGame.rollDice()">🎲</div>
                        <p id="ut-status-msg" class="text-center text-sm mt-2 text-muted">Your turn</p>
                    </div>
                    
                    <div class="ut-panel">
                        <div class="text-sm font-bold text-white mb-2">Players</div>
                        <div id="ut-game-players"></div>
                    </div>
                    
                    <div class="ut-panel">
                        <div class="text-sm font-bold text-white mb-2">Game Log</div>
                        <div class="ut-log" id="ut-game-log"></div>
                    </div>
                    
                    <button onclick="utLobby.leaveRoom()" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg mt-2">Leave Game</button>
                </div>
            </div>
        </div>
        
        <!-- Winner Modal -->
        <div id="ut-winner-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm">
            <div class="bg-surface border border-border rounded-2xl p-8 shadow-2xl max-w-sm text-center">
                <h3 class="text-3xl font-black text-white mb-2">🎉 Winner!</h3>
                <p id="ut-winner-name" class="text-xl text-primary mb-6"></p>
                <button onclick="utLobby.backToRoom()" class="px-6 py-3 bg-primary hover:opacity-90 text-white font-bold rounded-lg">Back to Room</button>
            </div>
        </div>
        
    </div>
</main>

<script src="https://unpkg.com/peerjs@1.5.4/dist/peerjs.min.js"></script>
<script>
/**
 * Ular Tangga Multiplayer
 */

const PLAYER_COLORS = ['#e74c3c', '#3498db', '#2ecc71', '#f1c40f'];

// Snakes: Head -> Tail
const SNAKES = { 16: 6, 47: 26, 49: 11, 56: 53, 62: 19, 64: 60, 87: 24, 93: 73, 95: 75, 98: 78 };

// Ladders: Bottom -> Top
const LADDERS = { 1: 38, 4: 14, 9: 31, 21: 42, 28: 84, 36: 44, 51: 67, 71: 91, 80: 100 };

// Toast
function showUTToast(msg, type = 'info') {
    let container = document.getElementById('ut-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'ut-toast-container';
        container.className = 'fixed top-24 left-1/2 -translate-x-1/2 z-50 flex flex-col gap-2';
        document.body.appendChild(container);
    }
    const colors = { info: 'border-blue-500', success: 'border-green-500', warning: 'border-yellow-500', error: 'border-red-500' };
    const toast = document.createElement('div');
    toast.className = `px-4 py-2 bg-black/90 text-white rounded-lg text-sm border-l-4 ${colors[type]}`;
    toast.textContent = msg;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// ============== LOBBY ==============
const utLobby = {
    peer: null,
    connections: [],
    hostConnection: null,
    isHost: false,
    roomCode: '',
    playerName: '',
    players: [],
    
    showJoinDialog() {
        document.getElementById('ut-join-dialog').classList.remove('hidden');
        document.getElementById('ut-room-code').focus();
    },
    
    showStatus(msg) {
        document.getElementById('ut-lobby-status').classList.remove('hidden');
        document.getElementById('ut-lobby-status-text').textContent = msg;
    },
    
    hideStatus() {
        document.getElementById('ut-lobby-status').classList.add('hidden');
    },
    
    getPlayerName() {
        const name = document.getElementById('ut-player-name').value.trim();
        if (!name) { showUTToast('Please enter your name', 'warning'); return null; }
        return name;
    },
    
    createRoom() {
        this.playerName = this.getPlayerName();
        if (!this.playerName) return;
        
        this.showStatus('Creating room...');
        this.isHost = true;
        
        const randomCode = Math.random().toString(36).substring(2, 8).toUpperCase();
        const peerId = 'ulartangga-room-' + randomCode.toLowerCase();
        
        this.peer = new Peer(peerId);
        
        this.peer.on('open', (id) => {
            this.roomCode = randomCode;
            this.players = [{ id: id, name: this.playerName, isHost: true }];
            this.hideStatus();
            this.showRoomScreen();
            showUTToast('Room created!', 'success');
        });
        
        this.peer.on('connection', (conn) => this.handleNewConnection(conn));
        
        this.peer.on('error', (err) => {
            console.error('Peer error:', err);
            if (err.type === 'unavailable-id') {
                showUTToast('Retrying...', 'info');
                setTimeout(() => this.createRoom(), 500);
            } else {
                showUTToast('Connection error', 'error');
                this.hideStatus();
            }
        });
    },
    
    joinRoom() {
        this.playerName = this.getPlayerName();
        if (!this.playerName) return;
        
        const code = document.getElementById('ut-room-code').value.trim().toUpperCase();
        if (code.length < 4) { showUTToast('Enter a valid room code', 'warning'); return; }
        
        this.showStatus('Joining room...');
        this.isHost = false;
        
        this.peer = new Peer();
        this.peer.on('open', () => this.tryConnect(code));
        this.peer.on('error', (err) => {
            console.error('Peer error:', err);
            showUTToast('Room not found', 'error');
            this.hideStatus();
        });
    },
    
    tryConnect(code, retryCount = 0) {
        const hostPeerId = 'ulartangga-room-' + code.toLowerCase();
        const conn = this.peer.connect(hostPeerId, { metadata: { name: this.playerName }, reliable: true });
        
        const timeout = setTimeout(() => {
            if (!this.hostConnection) {
                if (retryCount < 2) {
                    showUTToast(`Retrying (${retryCount + 2}/3)...`, 'info');
                    this.tryConnect(code, retryCount + 1);
                } else {
                    showUTToast('Room not found', 'error');
                    this.hideStatus();
                }
            }
        }, 5000);
        
        conn.on('open', () => {
            clearTimeout(timeout);
            this.hostConnection = conn;
            this.roomCode = code;
            this.setupGuestConnection(conn);
            this.hideStatus();
            showUTToast('Joined room!', 'success');
        });
        
        conn.on('error', () => {
            clearTimeout(timeout);
            if (retryCount < 2) setTimeout(() => this.tryConnect(code, retryCount + 1), 1000);
        });
    },
    
    handleNewConnection(conn) {
        if (this.players.length >= 4) { conn.close(); return; }
        
        conn.on('open', () => {
            const player = { id: conn.peer, name: conn.metadata?.name || 'Player', isHost: false };
            this.connections.push(conn);
            this.players.push(player);
            this.broadcastPlayerList();
            this.updateRoomUI();
            showUTToast(`${player.name} joined!`, 'info');
        });
        
        conn.on('data', (data) => this.handleHostMessage(conn, data));
        conn.on('close', () => this.handlePlayerDisconnect(conn.peer));
    },
    
    setupGuestConnection(conn) {
        conn.on('data', (data) => this.handleGuestMessage(data));
        conn.on('close', () => {
            showUTToast('Disconnected', 'error');
            this.returnToLobby();
        });
    },
    
    handleHostMessage(conn, data) {
        if (data.type === 'player_action') {
            console.log('[Host] Action from', conn.peer, data.action);
            utGame.handleRemoteAction(conn.peer, data.action);
        }
    },
    
    handleGuestMessage(data) {
        console.log('[Guest] Message:', data.type);
        switch (data.type) {
            case 'player_list':
                this.players = data.players;
                this.updateRoomUI();
                this.showRoomScreen();
                break;
            case 'game_start':
                utGame.startAsGuest(data.state);
                break;
            case 'game_state':
                utGame.updateState(data.state);
                break;
            case 'game_over':
                utGame.showWinner(data.winner);
                break;
            case 'back_to_room':
                this.showRoomScreen();
                break;
        }
    },
    
    handlePlayerDisconnect(peerId) {
        this.players = this.players.filter(p => p.id !== peerId);
        this.connections = this.connections.filter(c => c.peer !== peerId);
        this.broadcastPlayerList();
        this.updateRoomUI();
        showUTToast('A player left', 'warning');
    },
    
    broadcast(data) {
        this.connections.forEach(conn => { if (conn && conn.open) conn.send(data); });
    },
    
    sendToHost(data) {
        if (this.hostConnection && this.hostConnection.open) {
            this.hostConnection.send(data);
        } else {
            showUTToast('Connection lost', 'error');
        }
    },
    
    broadcastPlayerList() {
        this.broadcast({ type: 'player_list', players: this.players.map(p => ({ id: p.id, name: p.name, isHost: p.isHost })) });
    },
    
    showRoomScreen() {
        document.getElementById('ut-lobby').classList.add('hidden');
        document.getElementById('ut-room').classList.remove('hidden');
        document.getElementById('ut-game').classList.add('hidden');
        document.getElementById('ut-room-code-display').textContent = this.roomCode;
        
        if (this.isHost) {
            document.getElementById('ut-host-controls').classList.remove('hidden');
            document.getElementById('ut-guest-waiting').classList.add('hidden');
        } else {
            document.getElementById('ut-host-controls').classList.add('hidden');
            document.getElementById('ut-guest-waiting').classList.remove('hidden');
        }
        this.updateRoomUI();
    },
    
    updateRoomUI() {
        const list = document.getElementById('ut-player-list');
        list.innerHTML = this.players.map((p, i) => `
            <div class="flex items-center gap-3 px-4 py-3 bg-bg rounded-lg border border-border">
                <div class="w-8 h-8 rounded-full" style="background:${PLAYER_COLORS[i]}"></div>
                <span class="font-medium text-white flex-1">${p.name}</span>
                ${p.isHost ? '<span class="text-xs bg-yellow-500/20 text-yellow-500 px-2 py-1 rounded">HOST</span>' : ''}
            </div>
        `).join('');
        
        document.getElementById('ut-player-count').textContent = this.players.length;
        
        const startBtn = document.getElementById('ut-btn-start');
        const hint = document.getElementById('ut-start-hint');
        if (this.players.length >= 2) {
            startBtn.disabled = false;
            hint.classList.add('hidden');
        } else {
            startBtn.disabled = true;
            hint.classList.remove('hidden');
        }
    },
    
    copyRoomCode() {
        navigator.clipboard.writeText(this.roomCode);
        showUTToast('Room code copied!', 'success');
    },
    
    startGame() {
        if (!this.isHost) return;
        utGame.initAsHost(this.players.map(p => ({ id: p.id, name: p.name })));
    },
    
    leaveRoom() {
        if (this.peer) this.peer.destroy();
        this.returnToLobby();
    },
    
    returnToLobby() {
        this.peer = null;
        this.connections = [];
        this.hostConnection = null;
        this.players = [];
        document.getElementById('ut-lobby').classList.remove('hidden');
        document.getElementById('ut-room').classList.add('hidden');
        document.getElementById('ut-game').classList.add('hidden');
        document.getElementById('ut-winner-modal').style.display = 'none';
    },
    
    backToRoom() {
        document.getElementById('ut-winner-modal').style.display = 'none';
        if (this.isHost) this.broadcast({ type: 'back_to_room' });
        this.showRoomScreen();
    }
};

// ============== GAME ==============
const utGame = {
    state: null,
    myPlayerId: null,
    boardBuilt: false,
    isRolling: false,
    
    initAsHost(players) {
        const gamePlayers = players.map((p, i) => ({
            id: p.id,
            name: p.name,
            color: PLAYER_COLORS[i],
            pos: 0
        }));
        
        this.state = {
            players: gamePlayers,
            currentPlayer: 0,
            diceVal: 0,
            gameOver: false,
            logMessages: []
        };
        
        this.myPlayerId = utLobby.peer.id;
        this.buildBoard();
        this.showGameScreen();
        this.log('Game started! First player: ' + gamePlayers[0].name);
        utLobby.broadcast({ type: 'game_start', state: this.state });
        this.render();
    },
    
    startAsGuest(state) {
        this.state = state;
        this.myPlayerId = utLobby.peer.id;
        this.buildBoard();
        this.showGameScreen();
        this.render();
    },
    
    updateState(state) {
        this.state = state;
        this.isRolling = false;
        this.render();
    },
    
    showGameScreen() {
        document.getElementById('ut-lobby').classList.add('hidden');
        document.getElementById('ut-room').classList.add('hidden');
        document.getElementById('ut-game').classList.remove('hidden');
    },
    
    buildBoard() {
        if (this.boardBuilt) return;
        this.boardBuilt = true;
        
        const grid = document.getElementById('ut-grid');
        grid.innerHTML = '';
        
        // Generate 100 cells in zig-zag pattern
        for (let row = 10; row >= 1; row--) {
            let startNum = (row - 1) * 10 + 1;
            let endNum = row * 10;
            let nums = [];
            for (let i = startNum; i <= endNum; i++) nums.push(i);
            if (row % 2 !== 0) nums.reverse();
            
            nums.forEach(num => {
                const cell = document.createElement('div');
                cell.className = 'ut-cell';
                cell.id = `ut-cell-${num}`;
                cell.textContent = num;
                grid.appendChild(cell);
            });
        }
        
        this.drawConnectors();
        window.addEventListener('resize', () => { this.drawConnectors(); this.renderTokens(); });
    },
    
    drawConnectors() {
        const svg = document.getElementById('ut-svg-overlay');
        svg.innerHTML = '';
        
        const getCenter = (num) => {
            const cell = document.getElementById(`ut-cell-${num}`);
            if (!cell) return { x: 0, y: 0 };
            return {
                x: cell.offsetLeft + cell.offsetWidth / 2,
                y: cell.offsetTop + cell.offsetHeight / 2
            };
        };
        
        // Draw ladders
        for (let [start, end] of Object.entries(LADDERS)) {
            const p1 = getCenter(start);
            const p2 = getCenter(end);
            this.drawLadder(svg, p1.x, p1.y, p2.x, p2.y);
        }
        
        // Draw snakes
        for (let [start, end] of Object.entries(SNAKES)) {
            const p1 = getCenter(start);
            const p2 = getCenter(end);
            this.drawSnake(svg, p1.x, p1.y, p2.x, p2.y);
        }
    },
    
    drawLadder(svg, x1, y1, x2, y2) {
        const lineW = 6;
        this.createLine(svg, x1 - lineW, y1, x2 - lineW, y2, "#8B4513", 3);
        this.createLine(svg, x1 + lineW, y1, x2 + lineW, y2, "#8B4513", 3);
        
        const dist = Math.sqrt((x2-x1)**2 + (y2-y1)**2);
        const steps = Math.floor(dist / 12);
        for (let i = 1; i < steps; i++) {
            const ratio = i / steps;
            const rx = x1 + (x2 - x1) * ratio;
            const ry = y1 + (y2 - y1) * ratio;
            this.createLine(svg, rx - lineW, ry, rx + lineW, ry, "#A0522D", 2);
        }
    },
    
    drawSnake(svg, x1, y1, x2, y2) {
        const midX = (x1 + x2) / 2 + (Math.random() * 30 - 15);
        const midY = (y1 + y2) / 2 + (Math.random() * 30 - 15);
        
        const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
        path.setAttribute("d", `M ${x1} ${y1} Q ${midX} ${midY} ${x2} ${y2}`);
        path.setAttribute("stroke", "#27ae60");
        path.setAttribute("stroke-width", "6");
        path.setAttribute("fill", "none");
        path.setAttribute("stroke-linecap", "round");
        
        const head = document.createElementNS("http://www.w3.org/2000/svg", "circle");
        head.setAttribute("cx", x1);
        head.setAttribute("cy", y1);
        head.setAttribute("r", "6");
        head.setAttribute("fill", "#2ecc71");
        
        svg.appendChild(path);
        svg.appendChild(head);
    },
    
    createLine(svg, x1, y1, x2, y2, color, width) {
        const line = document.createElementNS("http://www.w3.org/2000/svg", "line");
        line.setAttribute("x1", x1);
        line.setAttribute("y1", y1);
        line.setAttribute("x2", x2);
        line.setAttribute("y2", y2);
        line.setAttribute("stroke", color);
        line.setAttribute("stroke-width", width);
        svg.appendChild(line);
    },
    
    getMyPlayerIndex() {
        return this.state.players.findIndex(p => p.id === this.myPlayerId);
    },
    
    isMyTurn() {
        return this.state.currentPlayer === this.getMyPlayerIndex();
    },
    
    getCurrentPlayer() {
        return this.state.players[this.state.currentPlayer];
    },
    
    // ============== ACTIONS ==============
    rollDice() {
        if (!this.isMyTurn() || this.isRolling || this.state.gameOver) return;
        
        if (!utLobby.isHost) {
            utLobby.sendToHost({ type: 'player_action', action: { type: 'roll' } });
            this.isRolling = true;
            return;
        }
        
        this.processRoll();
    },
    
    async processRoll() {
        const p = this.getCurrentPlayer();
        this.isRolling = true;
        
        // Animate dice
        document.getElementById('ut-dice').classList.add('rolling');
        await new Promise(r => setTimeout(r, 600));
        
        const roll = Math.floor(Math.random() * 6) + 1;
        this.state.diceVal = roll;
        
        document.getElementById('ut-dice').classList.remove('rolling');
        document.getElementById('ut-dice').textContent = roll;
        
        this.log(`${p.name} rolled ${roll}`);
        
        // Move player
        await this.movePlayer(p, roll);
        
        // Check win
        if (p.pos >= 100) {
            this.state.gameOver = true;
            utLobby.broadcast({ type: 'game_over', winner: p });
            this.showWinner(p);
            return;
        }
        
        // Bonus turn for 6
        if (roll === 6) {
            this.log(`${p.name} gets bonus turn!`);
            this.isRolling = false;
            this.syncState();
            return;
        }
        
        // Next turn
        this.state.currentPlayer = (this.state.currentPlayer + 1) % this.state.players.length;
        this.log(`${this.getCurrentPlayer().name}'s turn`);
        this.isRolling = false;
        this.syncState();
    },
    
    async movePlayer(player, steps) {
        let target = player.pos + steps;
        
        // Bounce back if over 100
        if (target > 100) {
            this.log("Over 100! Bouncing back.");
            target = 100 - (target - 100);
        }
        
        player.pos = target;
        this.renderTokens();
        await new Promise(r => setTimeout(r, 400));
        
        // Check snake
        if (SNAKES[player.pos]) {
            this.log(`Oh no! Snake! Down to ${SNAKES[player.pos]}`);
            player.pos = SNAKES[player.pos];
            this.renderTokens();
            await new Promise(r => setTimeout(r, 400));
        }
        
        // Check ladder
        if (LADDERS[player.pos]) {
            this.log(`Yay! Ladder! Up to ${LADDERS[player.pos]}`);
            player.pos = LADDERS[player.pos];
            this.renderTokens();
            await new Promise(r => setTimeout(r, 400));
        }
    },
    
    showWinner(winner) {
        document.getElementById('ut-winner-name').textContent = winner.name + ' wins!';
        document.getElementById('ut-winner-modal').style.display = 'flex';
    },
    
    handleRemoteAction(peerId, action) {
        if (!utLobby.isHost) return;
        
        const playerIdx = this.state.players.findIndex(p => p.id === peerId);
        if (playerIdx !== this.state.currentPlayer) {
            console.log('Not this player\'s turn');
            return;
        }
        
        if (action.type === 'roll') {
            this.processRoll();
        }
    },
    
    syncState() {
        if (utLobby.isHost) {
            utLobby.broadcast({ type: 'game_state', state: this.state });
        }
        this.render();
    },
    
    log(msg) {
        if (this.state && this.state.logMessages) {
            this.state.logMessages.unshift(msg);
            if (this.state.logMessages.length > 30) this.state.logMessages.pop();
        }
        this.renderLog();
    },
    
    renderLog() {
        const logEl = document.getElementById('ut-game-log');
        if (!logEl || !this.state || !this.state.logMessages) return;
        logEl.innerHTML = this.state.logMessages.map(msg => 
            `<div class="ut-log-entry">${msg}</div>`
        ).join('');
    },
    
    render() {
        if (!this.state) return;
        
        // Dice
        document.getElementById('ut-dice').textContent = this.state.diceVal || '🎲';
        
        // Status
        const p = this.getCurrentPlayer();
        const statusEl = document.getElementById('ut-status-msg');
        if (this.isMyTurn()) {
            statusEl.textContent = 'Your turn - Roll!';
            statusEl.style.color = '#2ecc71';
        } else {
            statusEl.textContent = `${p?.name}'s turn`;
            statusEl.style.color = p?.color || '#888';
        }
        
        // Players
        const playersEl = document.getElementById('ut-game-players');
        playersEl.innerHTML = this.state.players.map((p, i) => `
            <div class="ut-player-card ${this.state.currentPlayer === i ? 'active' : ''}">
                <div class="ut-player-token" style="background:${p.color}"></div>
                <div class="flex-1">
                    <div class="text-white text-sm font-bold">${p.name}</div>
                    <div class="text-xs text-muted">Pos: ${p.pos === 0 ? 'Start' : p.pos}</div>
                </div>
            </div>
        `).join('');
        
        this.renderTokens();
        this.renderLog();
    },
    
    renderTokens() {
        const container = document.getElementById('ut-tokens-container');
        if (!container || !this.state) return;
        
        // Remove old tokens
        container.innerHTML = '';
        
        this.state.players.forEach((p, idx) => {
            const token = document.createElement('div');
            token.className = 'ut-token';
            token.style.backgroundColor = p.color;
            token.textContent = idx + 1;
            
            let targetNum = p.pos === 0 ? 1 : p.pos;
            const cell = document.getElementById(`ut-cell-${targetNum}`);
            
            if (cell) {
                const offset = idx * 4;
                if (p.pos === 0) {
                    token.style.left = (cell.offsetLeft + cell.offsetWidth/2 - 10 + offset) + 'px';
                    token.style.top = (cell.offsetTop + cell.offsetHeight + 2) + 'px';
                    token.style.opacity = '0.5';
                } else {
                    token.style.left = (cell.offsetLeft + cell.offsetWidth/2 - 10 + offset) + 'px';
                    token.style.top = (cell.offsetTop + cell.offsetHeight/2 - 10 + offset) + 'px';
                    token.style.opacity = '1';
                }
            }
            
            container.appendChild(token);
        });
    }
};

// Init
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('ut-player-name').addEventListener('keypress', e => {
        if (e.key === 'Enter') utLobby.createRoom();
    });
    document.getElementById('ut-room-code').addEventListener('keypress', e => {
        if (e.key === 'Enter') utLobby.joinRoom();
    });
});
</script>
