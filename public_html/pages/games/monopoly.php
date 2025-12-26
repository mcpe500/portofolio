<?php
/**
 * Monopoly Multiplayer Game
 * Real-time multiplayer using PeerJS (WebRTC P2P)
 */
?>
<style>
    :root {
        --cell-size: 55px;
        --c-brown: #8b4513;
        --c-lblue: #87ceeb;
        --c-pink: #ffb6c1;
        --c-orange: #ffa500;
        --c-red: #ff0000;
        --c-yellow: #ffd700;
        --c-green: #008000;
        --c-blue: #0000ff;
    }
    
    #monopoly-app { min-height: 80vh; }
    
    /* Board */
    .mono-board {
        width: calc(var(--cell-size) * 11);
        height: calc(var(--cell-size) * 11);
        background: #cde6d0;
        border: 4px solid #000;
        display: grid;
        grid-template-columns: repeat(11, var(--cell-size));
        grid-template-rows: repeat(11, var(--cell-size));
        position: relative;
        margin: 0 auto;
    }
    
    .mono-cell {
        border: 1px solid #999;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-end;
        font-size: 7px;
        text-align: center;
        padding: 2px;
        background: white;
        font-weight: bold;
        overflow: hidden;
    }
    
    .mono-cell.center {
        grid-column: 2 / 11;
        grid-row: 2 / 11;
        background: #cde6d0;
        justify-content: center;
    }
    
    .mono-cell.corner { font-size: 10px; }
    
    .color-bar {
        width: 100%;
        height: 16px;
        position: absolute;
        top: 0;
        left: 0;
    }
    
    .prop-name {
        margin-top: 18px;
        font-size: 6px;
        line-height: 1.1;
        text-transform: uppercase;
        color: #333;
    }
    
    .prop-price { font-size: 7px; margin-bottom: 2px; color: #222; }
    
    .bg-brown { background: var(--c-brown); }
    .bg-lblue { background: var(--c-lblue); }
    .bg-pink { background: var(--c-pink); }
    .bg-orange { background: var(--c-orange); }
    .bg-red { background: var(--c-red); }
    .bg-yellow { background: var(--c-yellow); }
    .bg-green { background: var(--c-green); }
    .bg-blue { background: var(--c-blue); }
    
    .mono-token {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 1px 1px 3px rgba(0,0,0,0.5);
        position: absolute;
        z-index: 10;
        transition: top 0.4s, left 0.4s;
    }
    
    .mono-sidebar {
        background: rgba(30,30,30,0.9);
        border-radius: 8px;
        padding: 12px;
        min-width: 280px;
    }
    
    .mono-panel {
        background: rgba(255,255,255,0.1);
        border-radius: 6px;
        padding: 10px;
        margin-bottom: 10px;
    }
    
    .mono-btn {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        margin-top: 5px;
        transition: all 0.2s;
    }
    
    .mono-btn:disabled { opacity: 0.5; cursor: not-allowed; }
    .mono-btn-primary { background: #3498db; color: white; }
    .mono-btn-success { background: #27ae60; color: white; }
    .mono-btn-danger { background: #e74c3c; color: white; }
    .mono-btn-warning { background: #e67e22; color: white; }
    
    .mono-log {
        height: 120px;
        overflow-y: auto;
        font-size: 10px;
        background: #1a1a1a;
        color: #0f0;
        padding: 5px;
        font-family: monospace;
        border-radius: 4px;
    }
    
    .mono-log-entry { margin-bottom: 2px; border-bottom: 1px solid #333; }
    
    .player-card {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px;
        border-radius: 4px;
        margin-bottom: 4px;
        background: rgba(255,255,255,0.05);
    }
    
    .player-card.active {
        background: rgba(46, 204, 113, 0.2);
        border-left: 3px solid #2ecc71;
    }
    
    .player-token-icon {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid white;
    }
    
    .dice-container {
        display: flex;
        gap: 8px;
        justify-content: center;
        margin-bottom: 10px;
    }
    
    .dice {
        width: 40px;
        height: 40px;
        background: white;
        border: 2px solid #333;
        border-radius: 6px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 22px;
        font-weight: bold;
        color: #333;
    }
    
    .property-owner-marker {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        border: 1px solid white;
    }
    
    .house-display {
        position: absolute;
        top: 18px;
        left: 2px;
        display: flex;
        gap: 1px;
    }
    
    .house-icon {
        width: 6px;
        height: 6px;
        background: #27ae60;
    }
    
    .hotel-icon {
        width: 10px;
        height: 8px;
        background: #c0392b;
    }
</style>

<main class="flex flex-1 justify-center pt-20 md:pt-24 pb-8">
    <div class="w-full" id="monopoly-app">
        
        <!-- Lobby Screen -->
        <div id="mono-lobby" class="flex flex-col items-center justify-center min-h-[80vh] px-4">
            <div class="w-full max-w-md bg-surface border border-border rounded-2xl p-8 shadow-xl">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-black text-white mb-2" style="color: #c0392b;">MONOPOLY</h1>
                    <p class="text-muted text-sm">Multiplayer Edition</p>
                </div>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-muted mb-2">Your Name</label>
                        <input type="text" id="mono-player-name" maxlength="15" placeholder="Enter your name..."
                            class="w-full px-4 py-3 bg-bg border border-border rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <button id="mono-btn-create" onclick="monoLobby.createRoom()" 
                            class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition-all">
                            <span class="material-symbols-outlined align-middle mr-1">add_circle</span>
                            Create Room
                        </button>
                        <button onclick="monoLobby.showJoinDialog()"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-all">
                            <span class="material-symbols-outlined align-middle mr-1">login</span>
                            Join Room
                        </button>
                    </div>
                    
                    <div id="mono-join-dialog" class="hidden">
                        <label class="block text-sm font-medium text-muted mb-2">Room Code</label>
                        <div class="flex gap-2">
                            <input type="text" id="mono-room-code" maxlength="6" placeholder="ABC123"
                                class="flex-1 px-4 py-3 bg-bg border border-border rounded-lg text-white uppercase text-center tracking-widest font-mono text-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            <button onclick="monoLobby.joinRoom()" class="px-6 py-3 bg-primary hover:opacity-90 text-white font-bold rounded-lg">
                                Join
                            </button>
                        </div>
                    </div>
                </div>
                
                <div id="mono-lobby-status" class="mt-6 text-center text-sm text-muted hidden">
                    <div class="inline-flex items-center gap-2">
                        <div class="animate-spin w-4 h-4 border-2 border-primary border-t-transparent rounded-full"></div>
                        <span id="mono-lobby-status-text">Connecting...</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Room Screen -->
        <div id="mono-room" class="hidden flex flex-col items-center justify-center min-h-[80vh] px-4">
            <div class="w-full max-w-lg bg-surface border border-border rounded-2xl p-8 shadow-xl">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Room</h2>
                        <div class="flex items-center gap-2 mt-2">
                            <span id="mono-room-code-display" class="text-2xl font-mono font-bold text-primary tracking-widest">------</span>
                            <button onclick="monoLobby.copyRoomCode()" class="text-muted hover:text-white transition-colors">
                                <span class="material-symbols-outlined text-xl">content_copy</span>
                            </button>
                        </div>
                    </div>
                    <button onclick="monoLobby.leaveRoom()" class="text-muted hover:text-red-500 transition-colors">
                        <span class="material-symbols-outlined">logout</span>
                    </button>
                </div>
                
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-muted mb-3">Players (<span id="mono-player-count">0</span>/4)</h3>
                    <div id="mono-player-list" class="space-y-2"></div>
                </div>
                
                <div id="mono-host-controls" class="hidden">
                    <button id="mono-btn-start" onclick="monoLobby.startGame()" disabled
                        class="w-full px-6 py-4 bg-green-600 hover:bg-green-700 disabled:bg-gray-600 disabled:cursor-not-allowed text-white font-bold rounded-lg text-lg">
                        <span class="material-symbols-outlined align-middle mr-2">play_arrow</span>
                        Start Game
                    </button>
                    <p id="mono-start-hint" class="text-center text-sm text-muted mt-2">Need 2-4 players</p>
                </div>
                
                <div id="mono-guest-waiting" class="hidden text-center py-4">
                    <div class="inline-flex items-center gap-2 text-muted">
                        <div class="animate-pulse w-3 h-3 bg-yellow-500 rounded-full"></div>
                        <span>Waiting for host to start...</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Game Screen -->
        <div id="mono-game" class="hidden px-4">
            <div class="flex flex-col lg:flex-row gap-4 justify-center items-start">
                <!-- Board -->
                <div class="mono-board" id="mono-board"></div>
                
                <!-- Sidebar -->
                <div class="mono-sidebar">
                    <div class="mono-panel">
                        <div class="text-sm font-bold text-white mb-2">Controls</div>
                        <div class="dice-container">
                            <div class="dice" id="mono-dice1">🎲</div>
                            <div class="dice" id="mono-dice2">🎲</div>
                        </div>
                        <button id="mono-btn-roll" onclick="monoGame.rollDice()" class="mono-btn mono-btn-primary">Roll Dice</button>
                        <button id="mono-btn-jail-pay" onclick="monoGame.payJailFine()" class="mono-btn mono-btn-warning hidden">Pay $50 Jail Fine</button>
                        <button id="mono-btn-end-turn" onclick="monoGame.endTurn()" class="mono-btn mono-btn-success" disabled>End Turn</button>
                    </div>
                    
                    <div class="mono-panel">
                        <div class="text-sm font-bold text-white mb-2">Players</div>
                        <div id="mono-game-players"></div>
                    </div>
                    
                    <div class="mono-panel">
                        <div class="text-sm font-bold text-white mb-2">Game Log</div>
                        <div class="mono-log" id="mono-game-log"></div>
                    </div>
                    
                    <button onclick="monoLobby.leaveRoom()" class="mono-btn mono-btn-danger">Leave Game</button>
                </div>
            </div>
        </div>
        
        <!-- Buy Property Modal -->
        <div id="mono-buy-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm">
            <div class="bg-surface border border-border rounded-2xl p-6 shadow-2xl max-w-sm text-center">
                <h3 id="mono-buy-title" class="text-xl font-bold text-white mb-4">Buy Property?</h3>
                <div id="mono-buy-body" class="text-muted mb-4"></div>
                <div class="flex gap-3 justify-center">
                    <button onclick="monoGame.buyProperty()" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg">Buy</button>
                    <button onclick="monoGame.declineProperty()" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-lg">Decline</button>
                </div>
            </div>
        </div>
        
        <!-- Game Over Modal -->
        <div id="mono-gameover-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm">
            <div class="bg-surface border border-border rounded-2xl p-8 shadow-2xl max-w-sm text-center">
                <h3 class="text-3xl font-black text-white mb-2">🎉 Winner!</h3>
                <p id="mono-winner-name" class="text-xl text-primary mb-6"></p>
                <button onclick="monoLobby.backToRoom()" class="px-6 py-3 bg-primary hover:opacity-90 text-white font-bold rounded-lg">Back to Room</button>
            </div>
        </div>
        
    </div>
</main>

<script src="https://unpkg.com/peerjs@1.5.4/dist/peerjs.min.js"></script>
<script>
/**
 * Monopoly Multiplayer Game
 */

const PLAYER_COLORS = ['#e74c3c', '#3498db', '#2ecc71', '#f1c40f'];

const BOARD_DATA = [
    { name: "GO", type: "corner", action: "go" },
    { name: "Mediterranean", type: "prop", price: 60, group: "brown", rents: [2,10,30,90,160,250], houseCost: 50 },
    { name: "Chest", type: "chest" },
    { name: "Baltic", type: "prop", price: 60, group: "brown", rents: [4,20,60,180,320,450], houseCost: 50 },
    { name: "Income Tax", type: "tax", price: 200 },
    { name: "Reading RR", type: "rr", price: 200 },
    { name: "Oriental", type: "prop", price: 100, group: "lblue", rents: [6,30,90,270,400,550], houseCost: 50 },
    { name: "Chance", type: "chance" },
    { name: "Vermont", type: "prop", price: 100, group: "lblue", rents: [6,30,90,270,400,550], houseCost: 50 },
    { name: "Connecticut", type: "prop", price: 120, group: "lblue", rents: [8,40,100,300,450,600], houseCost: 50 },
    { name: "Jail", type: "corner", action: "jail" },
    { name: "St. Charles", type: "prop", price: 140, group: "pink", rents: [10,50,150,450,625,750], houseCost: 100 },
    { name: "Electric Co", type: "util", price: 150 },
    { name: "States", type: "prop", price: 140, group: "pink", rents: [10,50,150,450,625,750], houseCost: 100 },
    { name: "Virginia", type: "prop", price: 160, group: "pink", rents: [12,60,180,500,700,900], houseCost: 100 },
    { name: "Penn RR", type: "rr", price: 200 },
    { name: "St. James", type: "prop", price: 180, group: "orange", rents: [14,70,200,550,750,950], houseCost: 100 },
    { name: "Chest", type: "chest" },
    { name: "Tennessee", type: "prop", price: 180, group: "orange", rents: [14,70,200,550,750,950], houseCost: 100 },
    { name: "New York", type: "prop", price: 200, group: "orange", rents: [16,80,220,600,800,1000], houseCost: 100 },
    { name: "Free Parking", type: "corner" },
    { name: "Kentucky", type: "prop", price: 220, group: "red", rents: [18,90,250,700,875,1050], houseCost: 150 },
    { name: "Chance", type: "chance" },
    { name: "Indiana", type: "prop", price: 220, group: "red", rents: [18,90,250,700,875,1050], houseCost: 150 },
    { name: "Illinois", type: "prop", price: 240, group: "red", rents: [20,100,300,750,925,1100], houseCost: 150 },
    { name: "B&O RR", type: "rr", price: 200 },
    { name: "Atlantic", type: "prop", price: 260, group: "yellow", rents: [22,110,330,800,975,1150], houseCost: 150 },
    { name: "Ventnor", type: "prop", price: 260, group: "yellow", rents: [22,110,330,800,975,1150], houseCost: 150 },
    { name: "Water Works", type: "util", price: 150 },
    { name: "Marvin", type: "prop", price: 280, group: "yellow", rents: [24,120,360,850,1025,1200], houseCost: 150 },
    { name: "Go To Jail", type: "corner", action: "gotojail" },
    { name: "Pacific", type: "prop", price: 300, group: "green", rents: [26,130,390,900,1100,1275], houseCost: 200 },
    { name: "No. Carolina", type: "prop", price: 300, group: "green", rents: [26,130,390,900,1100,1275], houseCost: 200 },
    { name: "Chest", type: "chest" },
    { name: "Penn Ave", type: "prop", price: 320, group: "green", rents: [28,150,450,1000,1200,1400], houseCost: 200 },
    { name: "Short Line", type: "rr", price: 200 },
    { name: "Chance", type: "chance" },
    { name: "Park Place", type: "prop", price: 350, group: "blue", rents: [35,175,500,1100,1300,1500], houseCost: 200 },
    { name: "Luxury Tax", type: "tax", price: 100 },
    { name: "Boardwalk", type: "prop", price: 400, group: "blue", rents: [50,200,600,1400,1700,2000], houseCost: 200 }
];

const CARDS_CHANCE = [
    { text: "Advance to Go (Collect $200)", action: "move", to: 0 },
    { text: "Go to Jail", action: "gotojail" },
    { text: "Pay poor tax of $15", action: "pay", amount: 15 },
    { text: "Collect $150", action: "collect", amount: 150 },
    { text: "Advance to Illinois Ave", action: "move", to: 24 },
    { text: "Bank pays dividend of $50", action: "collect", amount: 50 }
];

const CARDS_CHEST = [
    { text: "Advance to Go", action: "move", to: 0 },
    { text: "Bank error. Collect $200", action: "collect", amount: 200 },
    { text: "Doctor's fee. Pay $50", action: "pay", amount: 50 },
    { text: "Go to Jail", action: "gotojail" },
    { text: "Tax refund. Collect $20", action: "collect", amount: 20 },
    { text: "Collect $100", action: "collect", amount: 100 }
];

// ============== UTILITY ==============
function showMonoToast(msg, type = 'info') {
    let container = document.getElementById('mono-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'mono-toast-container';
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
const monoLobby = {
    peer: null,
    connections: [],
    hostConnection: null,
    isHost: false,
    roomCode: '',
    playerName: '',
    players: [],
    savedRoomCode: '',
    reconnecting: false,
    
    showJoinDialog() {
        document.getElementById('mono-join-dialog').classList.remove('hidden');
        document.getElementById('mono-room-code').focus();
    },
    
    showStatus(msg) {
        document.getElementById('mono-lobby-status').classList.remove('hidden');
        document.getElementById('mono-lobby-status-text').textContent = msg;
    },
    
    hideStatus() {
        document.getElementById('mono-lobby-status').classList.add('hidden');
    },
    
    getPlayerName() {
        const name = document.getElementById('mono-player-name').value.trim();
        if (!name) {
            showMonoToast('Please enter your name', 'warning');
            return null;
        }
        return name;
    },
    
    createRoom() {
        this.playerName = this.getPlayerName();
        if (!this.playerName) return;
        
        this.showStatus('Creating room...');
        this.isHost = true;
        
        const randomCode = Math.random().toString(36).substring(2, 8).toUpperCase();
        const peerId = 'monopoly-room-' + randomCode.toLowerCase();
        
        this.peer = new Peer(peerId);
        
        this.peer.on('open', (id) => {
            this.roomCode = randomCode;
            this.players = [{ id: id, name: this.playerName, isHost: true }];
            this.hideStatus();
            this.showRoomScreen();
            showMonoToast('Room created!', 'success');
        });
        
        this.peer.on('connection', (conn) => this.handleNewConnection(conn));
        
        this.peer.on('error', (err) => {
            console.error('Peer error:', err);
            if (err.type === 'unavailable-id') {
                showMonoToast('Retrying...', 'info');
                setTimeout(() => this.createRoom(), 500);
            } else {
                showMonoToast('Connection error', 'error');
                this.hideStatus();
            }
        });
    },
    
    joinRoom() {
        this.playerName = this.getPlayerName();
        if (!this.playerName) return;
        
        const code = document.getElementById('mono-room-code').value.trim().toUpperCase();
        if (code.length < 4) {
            showMonoToast('Enter a valid room code', 'warning');
            return;
        }
        
        this.savedRoomCode = code;
        this.showStatus('Joining room...');
        this.isHost = false;
        
        this.peer = new Peer();
        
        this.peer.on('open', () => this.tryConnect(code));
        
        this.peer.on('error', (err) => {
            console.error('Peer error:', err);
            showMonoToast('Room not found', 'error');
            this.hideStatus();
        });
    },
    
    tryConnect(code, retryCount = 0) {
        const hostPeerId = 'monopoly-room-' + code.toLowerCase();
        const conn = this.peer.connect(hostPeerId, { metadata: { name: this.playerName }, reliable: true });
        
        const timeout = setTimeout(() => {
            if (!this.hostConnection) {
                if (retryCount < 2) {
                    showMonoToast(`Retrying (${retryCount + 2}/3)...`, 'info');
                    this.tryConnect(code, retryCount + 1);
                } else {
                    showMonoToast('Room not found', 'error');
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
            showMonoToast('Joined room!', 'success');
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
            showMonoToast(`${player.name} joined!`, 'info');
        });
        
        conn.on('data', (data) => this.handleHostMessage(conn, data));
        conn.on('close', () => this.handlePlayerDisconnect(conn.peer));
    },
    
    setupGuestConnection(conn) {
        conn.on('data', (data) => this.handleGuestMessage(data, conn));
        conn.on('close', () => {
            showMonoToast('Disconnected', 'error');
            this.returnToLobby();
        });
    },
    
    handleHostMessage(conn, data) {
        if (data.type === 'ping') { conn.send({ type: 'pong' }); return; }
        if (data.type === 'player_action') {
            console.log('[Host] Action from', conn.peer, data.action);
            monoGame.handleRemoteAction(conn.peer, data.action);
        }
    },
    
    handleGuestMessage(data, conn) {
        if (data.type === 'ping') { conn.send({ type: 'pong' }); return; }
        console.log('[Guest] Message:', data.type);
        
        switch (data.type) {
            case 'player_list':
                this.players = data.players;
                this.updateRoomUI();
                this.showRoomScreen();
                break;
            case 'game_start':
                monoGame.startAsGuest(data.state);
                break;
            case 'game_state':
                monoGame.updateState(data.state);
                break;
            case 'game_over':
                monoGame.showGameOver(data.winner);
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
        showMonoToast('A player left', 'warning');
    },
    
    broadcast(data) {
        console.log('[Host] Broadcasting:', data.type);
        this.connections.forEach(conn => {
            if (conn && conn.open) conn.send(data);
        });
    },
    
    sendToHost(data) {
        console.log('[Guest] Sending:', data.type);
        if (this.hostConnection && this.hostConnection.open) {
            this.hostConnection.send(data);
        } else {
            showMonoToast('Connection lost', 'error');
        }
    },
    
    broadcastPlayerList() {
        this.broadcast({ type: 'player_list', players: this.players.map(p => ({ id: p.id, name: p.name, isHost: p.isHost })) });
    },
    
    showRoomScreen() {
        document.getElementById('mono-lobby').classList.add('hidden');
        document.getElementById('mono-room').classList.remove('hidden');
        document.getElementById('mono-game').classList.add('hidden');
        document.getElementById('mono-room-code-display').textContent = this.roomCode;
        
        if (this.isHost) {
            document.getElementById('mono-host-controls').classList.remove('hidden');
            document.getElementById('mono-guest-waiting').classList.add('hidden');
        } else {
            document.getElementById('mono-host-controls').classList.add('hidden');
            document.getElementById('mono-guest-waiting').classList.remove('hidden');
        }
        this.updateRoomUI();
    },
    
    updateRoomUI() {
        const list = document.getElementById('mono-player-list');
        list.innerHTML = this.players.map((p, i) => `
            <div class="flex items-center gap-3 px-4 py-3 bg-bg rounded-lg border border-border">
                <div class="w-8 h-8 rounded-full" style="background:${PLAYER_COLORS[i]}"></div>
                <span class="font-medium text-white flex-1">${p.name}</span>
                ${p.isHost ? '<span class="text-xs bg-yellow-500/20 text-yellow-500 px-2 py-1 rounded">HOST</span>' : ''}
            </div>
        `).join('');
        
        document.getElementById('mono-player-count').textContent = this.players.length;
        
        const startBtn = document.getElementById('mono-btn-start');
        const hint = document.getElementById('mono-start-hint');
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
        showMonoToast('Room code copied!', 'success');
    },
    
    startGame() {
        if (!this.isHost) return;
        monoGame.initAsHost(this.players.map(p => ({ id: p.id, name: p.name })));
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
        document.getElementById('mono-lobby').classList.remove('hidden');
        document.getElementById('mono-room').classList.add('hidden');
        document.getElementById('mono-game').classList.add('hidden');
        document.getElementById('mono-buy-modal').style.display = 'none';
        document.getElementById('mono-gameover-modal').style.display = 'none';
    },
    
    backToRoom() {
        document.getElementById('mono-gameover-modal').style.display = 'none';
        if (this.isHost) {
            this.broadcast({ type: 'back_to_room' });
        }
        this.showRoomScreen();
    }
};

// ============== GAME ==============
const monoGame = {
    state: null,
    myPlayerId: null,
    pendingBuyProp: null,
    boardBuilt: false,
    
    initAsHost(players) {
        const props = JSON.parse(JSON.stringify(BOARD_DATA));
        props.forEach((p, i) => { p.id = i; p.owner = null; p.houses = 0; p.hotel = false; p.mortgaged = false; });
        
        const gamePlayers = players.map((p, i) => ({
            id: p.id,
            name: p.name,
            color: PLAYER_COLORS[i],
            money: 1500,
            pos: 0,
            props: [],
            jailTurns: 0,
            jailed: false,
            bankrupt: false
        }));
        
        this.state = {
            players: gamePlayers,
            properties: props,
            currentPlayer: 0,
            diceVal: [0, 0],
            doublesCount: 0,
            phase: 'roll', // roll, action, end
            gameOver: false,
            logMessages: [] // Synced log messages
        };
        
        this.myPlayerId = monoLobby.peer.id;
        this.buildBoard();
        this.showGameScreen();
        this.log('Game started!');
        monoLobby.broadcast({ type: 'game_start', state: this.state });
        this.render();
    },
    
    startAsGuest(state) {
        this.state = state;
        this.myPlayerId = monoLobby.peer.id;
        this.buildBoard();
        this.showGameScreen();
        this.render();
    },
    
    updateState(state) {
        this.state = state;
        this.render();
    },
    
    showGameScreen() {
        document.getElementById('mono-lobby').classList.add('hidden');
        document.getElementById('mono-room').classList.add('hidden');
        document.getElementById('mono-game').classList.remove('hidden');
    },
    
    buildBoard() {
        if (this.boardBuilt) return;
        this.boardBuilt = true;
        
        const boardEl = document.getElementById('mono-board');
        boardEl.innerHTML = '';
        
        // Center
        const center = document.createElement('div');
        center.className = 'mono-cell center';
        center.innerHTML = '<div style="font-size:24px; color:#c0392b; font-weight:bold; transform:rotate(-45deg);">MONOPOLY</div>';
        boardEl.appendChild(center);
        
        const getCoords = (i) => {
            // Bottom row (GO to Jail): i=0-10, row=10, col=10 down to 0
            if (i <= 10) return { col: 10 - i, row: 10 };
            // Left side (St. Charles to Free Parking): i=11-20, col=0, row=9 down to 0
            if (i <= 20) return { col: 0, row: 20 - i };
            // Top row (Kentucky to Go To Jail): i=21-30, row=0, col=1 to 10
            if (i <= 30) return { col: i - 20, row: 0 };
            // Right side (Pacific to Boardwalk): i=31-39, col=10, row=1 to 9
            return { col: 10, row: i - 30 };
        };
        
        BOARD_DATA.forEach((p, i) => {
            const coords = getCoords(i);
            const cell = document.createElement('div');
            cell.className = 'mono-cell';
            cell.style.gridColumn = coords.col + 1;
            cell.style.gridRow = coords.row + 1;
            
            if (p.type === 'corner') cell.classList.add('corner');
            
            if (['prop', 'rr', 'util'].includes(p.type)) {
                const colorClass = ['brown','lblue','pink','orange','red','yellow','green','blue'].includes(p.group) ? `bg-${p.group}` : '';
                cell.innerHTML = `
                    <div class="color-bar ${colorClass}"></div>
                    <div class="prop-name">${p.name}</div>
                    ${p.price ? `<div class="prop-price">$${p.price}</div>` : ''}
                    <div class="house-display" id="houses-${i}"></div>
                    <div class="property-owner-marker hidden" id="owner-${i}"></div>
                `;
            } else {
                cell.innerHTML = `<div style="font-weight:bold;">${p.name}</div>`;
            }
            cell.id = `mono-cell-${i}`;
            boardEl.appendChild(cell);
        });
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
        if (!this.isMyTurn() || this.state.phase !== 'roll') return;
        
        if (!monoLobby.isHost) {
            monoLobby.sendToHost({ type: 'player_action', action: { type: 'roll' } });
            return;
        }
        
        this.processRoll();
    },
    
    processRoll(peerId = null) {
        const p = this.getCurrentPlayer();
        if (p.bankrupt) return;
        
        const d1 = Math.floor(Math.random() * 6) + 1;
        const d2 = Math.floor(Math.random() * 6) + 1;
        this.state.diceVal = [d1, d2];
        
        this.log(`${p.name} rolled ${d1} + ${d2} = ${d1+d2}`);
        
        if (p.jailed) {
            if (d1 === d2) {
                p.jailed = false;
                p.jailTurns = 0;
                this.log(`${p.name} rolled doubles to escape jail!`);
                this.movePlayer(d1 + d2);
            } else {
                p.jailTurns++;
                if (p.jailTurns >= 3) {
                    this.log(`${p.name} must pay $50 fine.`);
                    p.money -= 50;
                    p.jailed = false;
                    this.movePlayer(d1 + d2);
                } else {
                    this.log(`${p.name} stays in jail (${p.jailTurns}/3)`);
                    this.state.phase = 'end';
                }
            }
        } else {
            if (d1 === d2) {
                this.state.doublesCount++;
                if (this.state.doublesCount >= 3) {
                    this.log(`${p.name} rolled 3 doubles! Go to jail!`);
                    this.sendToJail(this.state.currentPlayer);
                    this.state.phase = 'end';
                    this.syncState();
                    return;
                }
            } else {
                this.state.doublesCount = 0;
            }
            this.movePlayer(d1 + d2);
        }
        
        this.syncState();
    },
    
    movePlayer(steps) {
        const p = this.getCurrentPlayer();
        let newPos = p.pos + steps;
        
        if (newPos >= 40) {
            newPos -= 40;
            p.money += 200;
            this.log(`${p.name} passed GO. Collected $200.`);
        }
        
        p.pos = newPos;
        this.resolveLanding(newPos);
    },
    
    resolveLanding(pos) {
        const p = this.getCurrentPlayer();
        const space = this.state.properties[pos];
        
        this.log(`${p.name} landed on ${space.name}`);
        
        if (['prop', 'rr', 'util'].includes(space.type)) {
            if (space.owner === null) {
                // Unowned - offer to buy
                this.state.phase = 'action';
                this.state.pendingBuy = pos;
            } else if (space.owner !== this.state.currentPlayer && !space.mortgaged) {
                // Pay rent
                const rent = this.calculateRent(space);
                const owner = this.state.players[space.owner];
                this.log(`${p.name} pays $${rent} rent to ${owner.name}`);
                p.money -= rent;
                owner.money += rent;
                this.checkBankruptcy(p);
                this.state.phase = 'end';
            } else {
                this.state.phase = 'end';
            }
        } else if (space.type === 'tax') {
            this.log(`${p.name} pays $${space.price} tax`);
            p.money -= space.price;
            this.checkBankruptcy(p);
            this.state.phase = 'end';
        } else if (space.type === 'chance' || space.type === 'chest') {
            this.drawCard(space.type);
            this.state.phase = 'end';
        } else if (space.action === 'gotojail') {
            this.sendToJail(this.state.currentPlayer);
            this.state.phase = 'end';
        } else {
            this.state.phase = 'end';
        }
        
        this.syncState();
    },
    
    calculateRent(space) {
        const owner = this.state.players[space.owner];
        
        if (space.type === 'rr') {
            const count = owner.props.filter(pid => this.state.properties[pid].type === 'rr').length;
            return [25, 50, 100, 200][count - 1] || 25;
        }
        if (space.type === 'util') {
            const count = owner.props.filter(pid => this.state.properties[pid].type === 'util').length;
            const roll = this.state.diceVal[0] + this.state.diceVal[1];
            return count === 1 ? roll * 4 : roll * 10;
        }
        
        let rentIdx = space.hotel ? 5 : space.houses;
        if (rentIdx === 0 && this.checkMonopoly(space.owner, space.group)) {
            return space.rents[0] * 2;
        }
        return space.rents[rentIdx];
    },
    
    checkMonopoly(ownerId, group) {
        if (!group) return false;
        const groupProps = this.state.properties.filter(p => p.group === group);
        return groupProps.every(p => p.owner === ownerId);
    },
    
    drawCard(type) {
        const deck = type === 'chance' ? CARDS_CHANCE : CARDS_CHEST;
        const card = deck[Math.floor(Math.random() * deck.length)];
        const p = this.getCurrentPlayer();
        
        this.log(`${p.name} drew: ${card.text}`);
        
        if (card.action === 'move') {
            if (card.to < p.pos) p.money += 200; // Passed GO
            p.pos = card.to;
        } else if (card.action === 'gotojail') {
            this.sendToJail(this.state.currentPlayer);
        } else if (card.action === 'pay') {
            p.money -= card.amount;
            this.checkBankruptcy(p);
        } else if (card.action === 'collect') {
            p.money += card.amount;
        }
    },
    
    sendToJail(playerIdx) {
        const p = this.state.players[playerIdx];
        p.pos = 10;
        p.jailed = true;
        p.jailTurns = 0;
        this.log(`${p.name} sent to jail!`);
    },
    
    payJailFine() {
        if (!this.isMyTurn()) return;
        const p = this.getCurrentPlayer();
        if (!p.jailed) return;
        
        if (!monoLobby.isHost) {
            monoLobby.sendToHost({ type: 'player_action', action: { type: 'pay_jail' } });
            return;
        }
        
        if (p.money >= 50) {
            p.money -= 50;
            p.jailed = false;
            p.jailTurns = 0;
            this.log(`${p.name} paid $50 to leave jail`);
            this.syncState();
        } else {
            showMonoToast('Not enough money!', 'error');
        }
    },
    
    buyProperty() {
        if (!this.isMyTurn()) return;
        
        const propId = this.state.pendingBuy;
        if (propId === undefined) return;
        
        if (!monoLobby.isHost) {
            monoLobby.sendToHost({ type: 'player_action', action: { type: 'buy', propId } });
            document.getElementById('mono-buy-modal').style.display = 'none';
            return;
        }
        
        this.processBuy(this.state.currentPlayer, propId);
        document.getElementById('mono-buy-modal').style.display = 'none';
    },
    
    processBuy(playerIdx, propId) {
        const p = this.state.players[playerIdx];
        const prop = this.state.properties[propId];
        
        if (p.money >= prop.price) {
            p.money -= prop.price;
            prop.owner = playerIdx;
            p.props.push(propId);
            this.log(`${p.name} bought ${prop.name} for $${prop.price}`);
        }
        
        this.state.pendingBuy = undefined;
        this.state.phase = 'end';
        this.syncState();
    },
    
    declineProperty() {
        if (!monoLobby.isHost) {
            monoLobby.sendToHost({ type: 'player_action', action: { type: 'decline' } });
            document.getElementById('mono-buy-modal').style.display = 'none';
            return;
        }
        
        this.log(`${this.getCurrentPlayer().name} declined to buy`);
        this.state.pendingBuy = undefined;
        this.state.phase = 'end';
        document.getElementById('mono-buy-modal').style.display = 'none';
        this.syncState();
    },
    
    endTurn() {
        if (!this.isMyTurn() || this.state.phase !== 'end') return;
        
        if (!monoLobby.isHost) {
            monoLobby.sendToHost({ type: 'player_action', action: { type: 'end_turn' } });
            return;
        }
        
        this.processEndTurn();
    },
    
    processEndTurn() {
        // Check if doubles - roll again
        if (this.state.diceVal[0] === this.state.diceVal[1] && this.state.doublesCount > 0 && this.state.doublesCount < 3) {
            this.state.phase = 'roll';
            this.log(`${this.getCurrentPlayer().name} rolls again (doubles)`);
        } else {
            // Next player
            let next = this.state.currentPlayer;
            do {
                next = (next + 1) % this.state.players.length;
            } while (this.state.players[next].bankrupt && next !== this.state.currentPlayer);
            
            this.state.currentPlayer = next;
            this.state.doublesCount = 0;
            this.state.phase = 'roll';
            this.log(`${this.state.players[next].name}'s turn`);
        }
        
        this.checkWinner();
        this.syncState();
    },
    
    checkBankruptcy(player) {
        if (player.money < 0) {
            player.bankrupt = true;
            this.log(`${player.name} is BANKRUPT!`);
            // Return properties
            player.props.forEach(pid => {
                this.state.properties[pid].owner = null;
                this.state.properties[pid].houses = 0;
            });
            player.props = [];
        }
    },
    
    checkWinner() {
        const active = this.state.players.filter(p => !p.bankrupt);
        if (active.length === 1) {
            this.state.gameOver = true;
            monoLobby.broadcast({ type: 'game_over', winner: active[0] });
            this.showGameOver(active[0]);
        }
    },
    
    showGameOver(winner) {
        document.getElementById('mono-winner-name').textContent = winner.name;
        document.getElementById('mono-gameover-modal').style.display = 'flex';
    },
    
    handleRemoteAction(peerId, action) {
        if (!monoLobby.isHost) return;
        
        const playerIdx = this.state.players.findIndex(p => p.id === peerId);
        if (playerIdx !== this.state.currentPlayer) {
            console.log('Not this player\'s turn');
            return;
        }
        
        switch (action.type) {
            case 'roll':
                if (this.state.phase === 'roll') this.processRoll();
                break;
            case 'buy':
                this.processBuy(playerIdx, action.propId);
                break;
            case 'decline':
                this.state.pendingBuy = undefined;
                this.state.phase = 'end';
                this.syncState();
                break;
            case 'end_turn':
                if (this.state.phase === 'end') this.processEndTurn();
                break;
            case 'pay_jail':
                const p = this.state.players[playerIdx];
                if (p.jailed && p.money >= 50) {
                    p.money -= 50;
                    p.jailed = false;
                    p.jailTurns = 0;
                    this.log(`${p.name} paid $50 to leave jail`);
                    this.syncState();
                }
                break;
        }
    },
    
    syncState() {
        if (monoLobby.isHost) {
            monoLobby.broadcast({ type: 'game_state', state: this.state });
        }
        this.render();
    },
    
    log(msg) {
        // Add to state for syncing
        if (this.state && this.state.logMessages) {
            this.state.logMessages.unshift(msg);
            // Keep only last 50 messages
            if (this.state.logMessages.length > 50) {
                this.state.logMessages.pop();
            }
        }
        this.renderLog();
    },
    
    renderLog() {
        const logEl = document.getElementById('mono-game-log');
        if (!logEl || !this.state || !this.state.logMessages) return;
        logEl.innerHTML = this.state.logMessages.map(msg => 
            `<div class="mono-log-entry">${msg}</div>`
        ).join('');
    },
    
    // ============== RENDERING ==============
    render() {
        if (!this.state) return;
        
        // Dice
        document.getElementById('mono-dice1').textContent = this.state.diceVal[0] || '🎲';
        document.getElementById('mono-dice2').textContent = this.state.diceVal[1] || '🎲';
        
        // Buttons
        const isMyTurn = this.isMyTurn();
        const p = this.getCurrentPlayer();
        
        document.getElementById('mono-btn-roll').disabled = !(isMyTurn && this.state.phase === 'roll');
        document.getElementById('mono-btn-end-turn').disabled = !(isMyTurn && this.state.phase === 'end');
        
        // Jail button
        const jailBtn = document.getElementById('mono-btn-jail-pay');
        if (isMyTurn && p && p.jailed && this.state.phase === 'roll') {
            jailBtn.classList.remove('hidden');
        } else {
            jailBtn.classList.add('hidden');
        }
        
        // Buy modal
        if (isMyTurn && this.state.pendingBuy !== undefined && this.state.phase === 'action') {
            const prop = this.state.properties[this.state.pendingBuy];
            const me = this.state.players[this.getMyPlayerIndex()];
            document.getElementById('mono-buy-title').textContent = `Buy ${prop.name}?`;
            document.getElementById('mono-buy-body').innerHTML = `
                <div>Price: <strong>$${prop.price}</strong></div>
                <div>Your Money: <strong>$${me.money}</strong></div>
            `;
            document.getElementById('mono-buy-modal').style.display = 'flex';
        }
        
        // Players
        const playersEl = document.getElementById('mono-game-players');
        playersEl.innerHTML = this.state.players.filter(p => !p.bankrupt).map((p, i) => `
            <div class="player-card ${this.state.currentPlayer === this.state.players.indexOf(p) ? 'active' : ''}">
                <div class="player-token-icon" style="background:${p.color}"></div>
                <div class="flex-1">
                    <div class="text-white text-sm font-bold">${p.name}</div>
                    <div class="text-green-400 text-xs">$${p.money}</div>
                </div>
                ${p.jailed ? '<span class="text-xs text-orange-400">JAIL</span>' : ''}
            </div>
        `).join('');
        
        // Tokens
        this.renderTokens();
        
        // Property owners
        this.state.properties.forEach((prop, i) => {
            const ownerEl = document.getElementById(`owner-${i}`);
            if (ownerEl && prop.owner !== null) {
                ownerEl.classList.remove('hidden');
                ownerEl.style.background = this.state.players[prop.owner]?.color || '#888';
            } else if (ownerEl) {
                ownerEl.classList.add('hidden');
            }
        });
        
        // Render game log from state
        this.renderLog();
    },
    
    renderTokens() {
        // Remove old tokens
        document.querySelectorAll('.mono-token').forEach(t => t.remove());
        
        const boardEl = document.getElementById('mono-board');
        
        this.state.players.forEach((p, i) => {
            if (p.bankrupt) return;
            
            const cell = document.getElementById(`mono-cell-${p.pos}`);
            if (!cell) return;
            
            const token = document.createElement('div');
            token.className = 'mono-token';
            token.style.backgroundColor = p.color;
            
            const offset = [0, 4, 8, 12][i] || 0;
            token.style.top = (cell.offsetTop + 25 + offset) + 'px';
            token.style.left = (cell.offsetLeft + 25 + offset) + 'px';
            
            boardEl.appendChild(token);
        });
    }
};

// Init
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('mono-player-name').addEventListener('keypress', e => {
        if (e.key === 'Enter') monoLobby.createRoom();
    });
    document.getElementById('mono-room-code').addEventListener('keypress', e => {
        if (e.key === 'Enter') monoLobby.joinRoom();
    });
});
</script>
