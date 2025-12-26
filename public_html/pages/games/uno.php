<?php
/**
 * UNO Multiplayer Game
 * Real-time multiplayer using PeerJS (WebRTC P2P)
 */
?>
<main class="flex flex-1 justify-center pt-20 md:pt-24 pb-8">
    <div class="w-full h-full" id="uno-app">
        
        <!-- Lobby Screen -->
        <div id="lobby-screen" class="flex flex-col items-center justify-center min-h-[80vh] px-4">
            <div class="w-full max-w-md bg-surface border border-border rounded-2xl p-8 shadow-xl">
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-black text-white mb-2" style="font-style: italic; color: #ff5555; text-shadow: 2px 2px 0 #ffaa00;">UNO</h1>
                    <p class="text-muted text-sm">Multiplayer Edition</p>
                </div>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-muted mb-2">Your Name</label>
                        <input type="text" id="player-name" maxlength="20" placeholder="Enter your name..."
                            class="w-full px-4 py-3 bg-bg border border-border rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <button id="btn-create-room" onclick="lobby.createRoom()" 
                            class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition-all transform hover:scale-105 shadow-lg">
                            <span class="material-symbols-outlined align-middle mr-1">add_circle</span>
                            Create Room
                        </button>
                        <button id="btn-join-room" onclick="lobby.showJoinDialog()"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-all transform hover:scale-105 shadow-lg">
                            <span class="material-symbols-outlined align-middle mr-1">login</span>
                            Join Room
                        </button>
                    </div>
                    
                    <!-- Join Room Dialog (hidden by default) -->
                    <div id="join-dialog" class="hidden">
                        <label class="block text-sm font-medium text-muted mb-2">Room Code</label>
                        <div class="flex gap-2">
                            <input type="text" id="room-code-input" maxlength="6" placeholder="ABC123"
                                class="flex-1 px-4 py-3 bg-bg border border-border rounded-lg text-white uppercase text-center tracking-widest font-mono text-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            <button onclick="lobby.joinRoom()" class="px-6 py-3 bg-primary hover:opacity-90 text-white font-bold rounded-lg transition-all">
                                Join
                            </button>
                        </div>
                    </div>
                </div>
                
                <div id="lobby-status" class="mt-6 text-center text-sm text-muted hidden">
                    <div class="inline-flex items-center gap-2">
                        <div class="animate-spin w-4 h-4 border-2 border-primary border-t-transparent rounded-full"></div>
                        <span id="lobby-status-text">Connecting...</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Room Screen -->
        <div id="room-screen" class="hidden flex flex-col items-center justify-center min-h-[80vh] px-4">
            <div class="w-full max-w-lg bg-surface border border-border rounded-2xl p-8 shadow-xl">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Room</h2>
                        <div class="flex items-center gap-2 mt-2">
                            <span id="room-code-display" class="text-2xl font-mono font-bold text-primary tracking-widest">------</span>
                            <button onclick="lobby.copyRoomCode()" class="text-muted hover:text-white transition-colors" title="Copy room code">
                                <span class="material-symbols-outlined text-xl">content_copy</span>
                            </button>
                        </div>
                    </div>
                    <button onclick="lobby.leaveRoom()" class="text-muted hover:text-red-500 transition-colors">
                        <span class="material-symbols-outlined">logout</span>
                    </button>
                </div>
                
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-muted mb-3">Players (<span id="player-count">0</span>/4)</h3>
                    <div id="player-list" class="space-y-2">
                        <!-- Players will be rendered here -->
                    </div>
                </div>
                
                <div id="host-controls" class="hidden">
                    <button id="btn-start-game" onclick="lobby.startGame()" disabled
                        class="w-full px-6 py-4 bg-green-600 hover:bg-green-700 disabled:bg-gray-600 disabled:cursor-not-allowed text-white font-bold rounded-lg transition-all text-lg shadow-lg">
                        <span class="material-symbols-outlined align-middle mr-2">play_arrow</span>
                        Start Game
                    </button>
                    <p id="start-hint" class="text-center text-sm text-muted mt-2">Need at least 2 players to start</p>
                </div>
                
                <div id="guest-waiting" class="hidden text-center py-4">
                    <div class="inline-flex items-center gap-2 text-muted">
                        <div class="animate-pulse w-3 h-3 bg-yellow-500 rounded-full"></div>
                        <span>Waiting for host to start the game...</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Game Screen -->
        <div id="game-screen" class="hidden h-full">
            <!-- Game Header -->
            <div class="fixed top-16 left-0 right-0 z-40 bg-bg/90 backdrop-blur-sm border-b border-border px-4 py-2">
                <div class="max-w-7xl mx-auto flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <span class="text-xl font-black" style="color: #ff5555; font-style: italic;">UNO</span>
                        <span class="text-sm text-muted">Room: <span id="game-room-code" class="font-mono text-primary">------</span></span>
                    </div>
                    <div class="flex items-center gap-4">
                        <button onclick="game.showRules()" class="text-muted hover:text-white transition-colors text-sm">
                            <span class="material-symbols-outlined align-middle">help</span> Rules
                        </button>
                        <button onclick="lobby.leaveRoom()" class="text-red-400 hover:text-red-300 transition-colors text-sm">
                            Leave
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Game Board -->
            <div id="game-board" class="relative w-full h-[calc(100vh-180px)] mt-12">
                <!-- Direction Indicator -->
                <div id="direction-indicator" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 border-2 border-dashed border-white/10 rounded-full pointer-events-none z-0"></div>
                
                <!-- Opponent Areas (positioned dynamically) -->
                <div id="opponents-container"></div>
                
                <!-- Center Area -->
                <div id="center-area" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 flex items-center gap-8 z-10">
                    <!-- Color Indicator -->
                    <div id="color-indicator" class="absolute -top-16 left-1/2 -translate-x-1/2 w-12 h-12 rounded-full border-4 border-white shadow-lg transition-colors duration-300"></div>
                    
                    <!-- Deck -->
                    <div id="deck-pile" onclick="game.drawCard()" 
                        class="w-20 h-28 rounded-xl bg-gradient-to-br from-gray-800 to-gray-900 border-2 border-white cursor-pointer shadow-xl hover:scale-105 transition-transform flex items-center justify-center">
                        <span class="text-red-500 font-black text-lg italic transform -rotate-12" style="text-shadow: 1px 1px 0 #ffaa00;">UNO</span>
                    </div>
                    
                    <!-- Discard Pile -->
                    <div id="discard-pile" class="w-20 h-28 rounded-xl border-2 border-white/20">
                        <!-- Top card will be rendered here -->
                    </div>
                </div>
                
                <!-- Human Player Area -->
                <div id="human-area" class="absolute bottom-4 left-1/2 -translate-x-1/2 flex flex-col items-center w-full max-w-4xl px-4">
                    <div class="flex items-center gap-3 mb-3">
                        <span id="human-name" class="font-bold text-white"></span>
                        <span id="turn-indicator" class="hidden px-2 py-1 bg-yellow-500 text-black text-xs font-bold rounded animate-pulse">YOUR TURN</span>
                    </div>
                    <div id="human-hand" class="flex justify-center flex-wrap gap-1">
                        <!-- Cards will be rendered here -->
                    </div>
                </div>
                
                <!-- UNO Button -->
                <button id="btn-uno" onclick="game.sayUno()" 
                    class="hidden absolute bottom-32 left-1/2 -translate-x-1/2 px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-black text-xl rounded-full shadow-xl animate-pulse transition-transform hover:scale-110">
                    UNO!
                </button>
            </div>
        </div>
        
        <!-- Toast Container -->
        <div id="toast-container" class="fixed top-24 left-1/2 -translate-x-1/2 z-50 flex flex-col gap-2"></div>
        
        <!-- Color Picker Modal -->
        <div id="color-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm">
            <div class="bg-surface border border-border rounded-2xl p-6 shadow-2xl">
                <h3 class="text-xl font-bold text-white text-center mb-4">Choose Color</h3>
                <div class="grid grid-cols-2 gap-3">
                    <button onclick="game.pickColor('red')" class="w-20 h-20 rounded-xl bg-[#ff5555] hover:scale-110 transition-transform shadow-lg"></button>
                    <button onclick="game.pickColor('blue')" class="w-20 h-20 rounded-xl bg-[#0099ff] hover:scale-110 transition-transform shadow-lg"></button>
                    <button onclick="game.pickColor('green')" class="w-20 h-20 rounded-xl bg-[#55aa55] hover:scale-110 transition-transform shadow-lg"></button>
                    <button onclick="game.pickColor('yellow')" class="w-20 h-20 rounded-xl bg-[#ffaa00] hover:scale-110 transition-transform shadow-lg"></button>
                </div>
            </div>
        </div>
        
        <!-- Challenge Modal -->
        <div id="challenge-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm">
            <div class="bg-surface border border-border rounded-2xl p-6 shadow-2xl max-w-sm text-center">
                <h3 id="challenge-title" class="text-xl font-bold text-white mb-2">Wild +4!</h3>
                <p id="challenge-msg" class="text-muted mb-6">A player used Wild Draw 4. Do you want to challenge?</p>
                <div class="flex justify-center gap-4">
                    <button onclick="game.respondChallenge(false)" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-lg transition-all">
                        Accept (+4)
                    </button>
                    <button onclick="game.respondChallenge(true)" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg transition-all">
                        Challenge!
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Game Over Modal -->
        <div id="gameover-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm">
            <div class="bg-surface border border-border rounded-2xl p-8 shadow-2xl max-w-sm text-center">
                <h3 id="winner-title" class="text-3xl font-black text-white mb-2">🎉 Winner!</h3>
                <p id="winner-name" class="text-xl text-primary mb-6"></p>
                <div id="gameover-host-btns" class="hidden space-y-3">
                    <button onclick="game.playAgain()" class="w-full px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition-all">
                        Play Again
                    </button>
                    <button onclick="lobby.backToRoom()" class="w-full px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-lg transition-all">
                        Back to Room
                    </button>
                </div>
                <div id="gameover-guest-msg" class="hidden text-muted">
                    Waiting for host...
                </div>
            </div>
        </div>
        
        <!-- Rules Modal -->
        <div id="rules-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm" onclick="if(event.target === this) this.classList.add('hidden')">
            <div class="bg-surface border border-border rounded-2xl p-6 shadow-2xl max-w-lg max-h-[80vh] overflow-y-auto">
                <h3 class="text-2xl font-bold text-white mb-4">UNO Rules</h3>
                <ul class="space-y-2 text-sm text-gray-300">
                    <li><strong class="text-white">Goal:</strong> Be the first to play all your cards.</li>
                    <li><strong class="text-white">Matching:</strong> Play a card that matches the top card by color, number, or symbol.</li>
                    <li><strong class="text-white">Wild:</strong> Can be played anytime. Choose the next color.</li>
                    <li><strong class="text-white">Wild +4:</strong> Choose color + next player draws 4. Can be challenged!</li>
                    <li><strong class="text-white">Draw 2:</strong> Next player draws 2 and loses turn.</li>
                    <li><strong class="text-white">Skip:</strong> Next player loses their turn.</li>
                    <li><strong class="text-white">Reverse:</strong> Changes play direction.</li>
                    <li><strong class="text-white">UNO:</strong> Click "UNO" when you have 2 cards left!</li>
                </ul>
                <button onclick="document.getElementById('rules-modal').classList.add('hidden')" 
                    class="mt-6 w-full px-4 py-2 bg-primary hover:opacity-90 text-white font-bold rounded-lg transition-all">
                    Got it!
                </button>
            </div>
        </div>
        
    </div>
</main>

<script src="https://unpkg.com/peerjs@1.5.4/dist/peerjs.min.js"></script>
<script>
/**
 * UNO Multiplayer Game
 * Using PeerJS for WebRTC P2P connections
 */

// ============== CONSTANTS ==============
const COLORS = ['red', 'blue', 'green', 'yellow'];
const CARD_COLORS = {
    red: '#ff5555',
    blue: '#0099ff', 
    green: '#55aa55',
    yellow: '#ffaa00',
    black: '#333'
};
const SYMBOLS = {
    skip: '⊘',
    reverse: '⇄',
    draw2: '+2',
    wild: '🌈',
    wild4: '+4'
};

// ============== UTILITY FUNCTIONS ==============
function shuffle(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
}

function generateRoomCode(peerId) {
    // Extract a readable 6-char code from peer ID
    return peerId.replace(/[^a-zA-Z0-9]/g, '').substring(0, 6).toUpperCase();
}

function showToast(msg, type = 'info') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    const colors = {
        info: 'border-blue-500',
        success: 'border-green-500',
        warning: 'border-yellow-500',
        error: 'border-red-500'
    };
    toast.className = `px-4 py-2 bg-black/90 text-white rounded-lg text-sm border-l-4 ${colors[type]} animate-fade-in`;
    toast.textContent = msg;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// ============== CONNECTION MANAGER ==============
const connectionManager = {
    heartbeatInterval: null,
    heartbeatTimeout: null,
    reconnectAttempts: 0,
    maxReconnectAttempts: 5,
    reconnectDelay: 1000,
    lastPong: Date.now(),
    isConnected: false,
    pendingMessages: [],
    
    // Start heartbeat for a connection
    startHeartbeat(conn, isHost = false) {
        this.stopHeartbeat();
        this.isConnected = true;
        this.reconnectAttempts = 0;
        this.updateConnectionStatus('connected');
        
        this.heartbeatInterval = setInterval(() => {
            if (conn && conn.open) {
                conn.send({ type: 'ping', timestamp: Date.now() });
                
                // Check if we got a pong recently
                this.heartbeatTimeout = setTimeout(() => {
                    if (Date.now() - this.lastPong > 10000) {
                        console.warn('Connection seems dead, no pong received');
                        this.updateConnectionStatus('unstable');
                    }
                }, 5000);
            }
        }, 3000);
    },
    
    // Handle pong response
    handlePong() {
        this.lastPong = Date.now();
        if (this.heartbeatTimeout) {
            clearTimeout(this.heartbeatTimeout);
        }
        this.updateConnectionStatus('connected');
    },
    
    // Stop heartbeat
    stopHeartbeat() {
        if (this.heartbeatInterval) {
            clearInterval(this.heartbeatInterval);
            this.heartbeatInterval = null;
        }
        if (this.heartbeatTimeout) {
            clearTimeout(this.heartbeatTimeout);
            this.heartbeatTimeout = null;
        }
    },
    
    // Update connection status UI
    updateConnectionStatus(status) {
        let indicator = document.getElementById('connection-status');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.id = 'connection-status';
            indicator.className = 'fixed bottom-4 right-4 px-3 py-1 rounded-full text-xs font-medium flex items-center gap-2 z-50';
            document.body.appendChild(indicator);
        }
        
        const statuses = {
            connected: { color: 'bg-green-500/20 text-green-400', icon: '●', text: 'Connected' },
            connecting: { color: 'bg-yellow-500/20 text-yellow-400', icon: '◐', text: 'Connecting...' },
            unstable: { color: 'bg-orange-500/20 text-orange-400', icon: '◐', text: 'Unstable' },
            disconnected: { color: 'bg-red-500/20 text-red-400', icon: '○', text: 'Disconnected' },
            reconnecting: { color: 'bg-blue-500/20 text-blue-400', icon: '↻', text: 'Reconnecting...' }
        };
        
        const s = statuses[status] || statuses.disconnected;
        indicator.className = `fixed bottom-4 right-4 px-3 py-1 rounded-full text-xs font-medium flex items-center gap-2 z-50 ${s.color}`;
        indicator.innerHTML = `<span class="${status === 'reconnecting' ? 'animate-spin' : ''}">${s.icon}</span> ${s.text}`;
        
        this.isConnected = (status === 'connected');
    },
    
    // Queue message if disconnected
    queueMessage(data) {
        this.pendingMessages.push(data);
    },
    
    // Send queued messages
    flushQueue(conn) {
        while (this.pendingMessages.length > 0 && conn && conn.open) {
            const msg = this.pendingMessages.shift();
            conn.send(msg);
        }
    },
    
    // Get reconnect delay with exponential backoff
    getReconnectDelay() {
        return Math.min(this.reconnectDelay * Math.pow(2, this.reconnectAttempts), 30000);
    },
    
    // Reset on successful connect
    resetReconnect() {
        this.reconnectAttempts = 0;
        this.reconnectDelay = 1000;
    }
};

// ============== LOBBY MANAGER ==============
const lobby = {
    peer: null,
    connections: [], // Host: array of connections to guests
    hostConnection: null, // Guest: connection to host
    isHost: false,
    roomCode: '',
    playerName: '',
    players: [], // [{id, name, isHost}]
    reconnecting: false,
    savedRoomCode: '',
    
    init() {
        // Initialize on name input
        document.getElementById('player-name').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.createRoom();
        });
        document.getElementById('room-code-input').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.joinRoom();
        });
    },
    
    getPlayerName() {
        const name = document.getElementById('player-name').value.trim();
        if (!name) {
            showToast('Please enter your name', 'warning');
            return null;
        }
        return name;
    },
    
    showStatus(msg) {
        document.getElementById('lobby-status').classList.remove('hidden');
        document.getElementById('lobby-status-text').textContent = msg;
    },
    
    hideStatus() {
        document.getElementById('lobby-status').classList.add('hidden');
    },
    
    showJoinDialog() {
        document.getElementById('join-dialog').classList.remove('hidden');
        document.getElementById('room-code-input').focus();
    },
    
    async createRoom() {
        this.playerName = this.getPlayerName();
        if (!this.playerName) return;
        
        this.showStatus('Creating room...');
        this.isHost = true;
        connectionManager.updateConnectionStatus('connecting');
        
        try {
            this.peer = new Peer();
            
            this.peer.on('open', (id) => {
                this.roomCode = generateRoomCode(id);
                this.players = [{
                    id: id,
                    name: this.playerName,
                    isHost: true
                }];
                
                this.hideStatus();
                this.showRoomScreen();
                showToast('Room created!', 'success');
            });
            
            this.peer.on('connection', (conn) => {
                this.handleNewConnection(conn);
            });
            
            this.peer.on('error', (err) => {
                console.error('Peer error:', err);
                showToast('Connection error. Please try again.', 'error');
                this.hideStatus();
            });
            
        } catch (err) {
            console.error(err);
            showToast('Failed to create room', 'error');
            this.hideStatus();
        }
    },
    
    async joinRoom() {
        this.playerName = this.getPlayerName();
        if (!this.playerName) return;
        
        const code = document.getElementById('room-code-input').value.trim().toUpperCase();
        if (code.length < 4) {
            showToast('Please enter a valid room code', 'warning');
            return;
        }
        
        this.savedRoomCode = code;
        this.showStatus('Joining room...');
        this.isHost = false;
        connectionManager.updateConnectionStatus('connecting');
        
        try {
            this.peer = new Peer();
            
            this.peer.on('open', (myId) => {
                this.tryConnect(code, myId);
            });
            
            this.peer.on('error', (err) => {
                console.error('Peer error:', err);
                if (err.type === 'peer-unavailable') {
                    this.handleConnectionFailure('Room not found. Check the code.');
                } else {
                    this.handleConnectionFailure('Connection error.');
                }
            });
            
            this.peer.on('disconnected', () => {
                if (!this.reconnecting) {
                    this.attemptReconnect();
                }
            });
            
        } catch (err) {
            console.error(err);
            this.handleConnectionFailure('Failed to join room');
        }
    },
    
    handleConnectionFailure(msg) {
        if (connectionManager.reconnectAttempts < connectionManager.maxReconnectAttempts) {
            this.attemptReconnect();
        } else {
            showToast(msg, 'error');
            connectionManager.updateConnectionStatus('disconnected');
            this.hideStatus();
            connectionManager.reconnectAttempts = 0;
        }
    },
    
    attemptReconnect() {
        if (this.reconnecting) return;
        
        this.reconnecting = true;
        connectionManager.reconnectAttempts++;
        connectionManager.updateConnectionStatus('reconnecting');
        
        const delay = connectionManager.getReconnectDelay();
        showToast(`Reconnecting... (${connectionManager.reconnectAttempts}/${connectionManager.maxReconnectAttempts})`, 'warning');
        this.showStatus(`Reconnecting in ${Math.round(delay/1000)}s...`);
        
        setTimeout(() => {
            this.reconnecting = false;
            if (this.isHost) {
                // Host can't reconnect, just show error
                showToast('Connection lost. Please create a new room.', 'error');
                this.returnToLobby();
            } else if (this.savedRoomCode) {
                // Try to rejoin
                if (this.peer) this.peer.destroy();
                this.joinRoom();
            }
        }, delay);
    },
    
    tryConnect(code, myId, retryCount = 0) {
        const hostPeerId = 'uno-room-' + code.toLowerCase();
        
        const conn = this.peer.connect(hostPeerId, {
            metadata: { name: this.playerName },
            reliable: true
        });
        
        const connectTimeout = setTimeout(() => {
            if (!this.hostConnection) {
                if (retryCount < 2) {
                    showToast(`Retrying connection... (${retryCount + 2}/3)`, 'info');
                    this.tryConnect(code, myId, retryCount + 1);
                } else {
                    this.handleConnectionFailure('Room not found or host offline');
                }
            }
        }, 5000);
        
        conn.on('open', () => {
            clearTimeout(connectTimeout);
            this.hostConnection = conn;
            this.roomCode = code;
            this.setupGuestConnection(conn);
            this.hideStatus();
            connectionManager.resetReconnect();
            connectionManager.startHeartbeat(conn);
            showToast('Joined room!', 'success');
        });
        
        conn.on('error', (err) => {
            clearTimeout(connectTimeout);
            console.error('Connection error:', err);
            if (retryCount < 2) {
                setTimeout(() => this.tryConnect(code, myId, retryCount + 1), 1000);
            } else {
                this.handleConnectionFailure('Failed to connect to room');
            }
        });
    },
    
    handleNewConnection(conn) {
        if (this.players.length >= 4) {
            conn.close();
            return;
        }
        
        conn.on('open', () => {
            const player = {
                id: conn.peer,
                name: conn.metadata?.name || 'Player',
                isHost: false,
                connection: conn
            };
            
            this.connections.push(conn);
            this.players.push(player);
            
            // Send current player list to all
            this.broadcastPlayerList();
            this.updateRoomUI();
            
            showToast(`${player.name} joined!`, 'info');
        });
        
        conn.on('data', (data) => this.handleHostMessage(conn, data));
        conn.on('close', () => this.handlePlayerDisconnect(conn.peer));
    },
    
    setupGuestConnection(conn) {
        conn.on('data', (data) => this.handleGuestMessage(data, conn));
        conn.on('close', () => {
            connectionManager.updateConnectionStatus('disconnected');
            connectionManager.stopHeartbeat();
            if (!this.reconnecting && this.savedRoomCode) {
                showToast('Connection lost. Attempting to reconnect...', 'warning');
                this.attemptReconnect();
            } else {
                showToast('Disconnected from room', 'error');
                this.returnToLobby();
            }
        });
    },
    
    handleHostMessage(conn, data) {
        // Handle ping/pong for connection health
        if (data.type === 'ping') {
            conn.send({ type: 'pong', timestamp: data.timestamp });
            return;
        }
        if (data.type === 'pong') {
            connectionManager.handlePong();
            return;
        }
        
        switch(data.type) {
            case 'player_action':
                console.log('[Host] Received action from', conn.peer, ':', data.action.type);
                game.handleRemoteAction(conn.peer, data.action);
                break;
        }
    },
    
    handleGuestMessage(data, conn) {
        // Handle ping/pong for connection health
        if (data.type === 'ping') {
            if (conn && conn.open) {
                conn.send({ type: 'pong', timestamp: data.timestamp });
            }
            return;
        }
        if (data.type === 'pong') {
            connectionManager.handlePong();
            return;
        }
        
        console.log('[Guest] Received message:', data.type);
        
        switch(data.type) {
            case 'player_list':
                this.players = data.players;
                this.updateRoomUI();
                this.showRoomScreen();
                break;
            case 'game_start':
                console.log('[Guest] Game starting with state');
                game.startAsGuest(data.gameState);
                break;
            case 'game_state':
                console.log('[Guest] Received game state, current player:', data.gameState.currentPlayer);
                game.updateState(data.gameState);
                break;
            case 'game_over':
                game.showGameOver(data.winner);
                break;
            case 'back_to_room':
                this.backToRoomAsGuest();
                break;
        }
    },
    
    handlePlayerDisconnect(peerId) {
        this.players = this.players.filter(p => p.id !== peerId);
        this.connections = this.connections.filter(c => c.peer !== peerId);
        this.broadcastPlayerList();
        this.updateRoomUI();
        showToast('A player left the room', 'warning');
    },
    
    broadcastPlayerList() {
        const playerData = this.players.map(p => ({
            id: p.id,
            name: p.name,
            isHost: p.isHost
        }));
        
        this.broadcast({ type: 'player_list', players: playerData });
    },
    
    broadcast(data) {
        console.log('[Host] Broadcasting:', data.type);
        let sentCount = 0;
        this.connections.forEach(conn => {
            if (conn && conn.open) {
                try {
                    conn.send(data);
                    sentCount++;
                } catch (e) {
                    console.error('[Host] Failed to send to', conn.peer, e);
                }
            }
        });
        console.log('[Host] Sent to', sentCount, 'connections');
    },
    
    sendToHost(data) {
        console.log('[Guest] Sending to host:', data.type, data.action?.type);
        if (this.hostConnection && this.hostConnection.open) {
            try {
                this.hostConnection.send(data);
            } catch (e) {
                console.error('[Guest] Failed to send to host:', e);
                showToast('Connection error. Please wait...', 'warning');
                // Try to reconnect
                this.attemptReconnect();
            }
        } else {
            console.error('[Guest] Host connection not open!');
            showToast('Lost connection to host. Reconnecting...', 'error');
            this.attemptReconnect();
        }
    },
    
    showRoomScreen() {
        document.getElementById('lobby-screen').classList.add('hidden');
        document.getElementById('room-screen').classList.remove('hidden');
        document.getElementById('game-screen').classList.add('hidden');
        
        document.getElementById('room-code-display').textContent = this.roomCode;
        
        if (this.isHost) {
            document.getElementById('host-controls').classList.remove('hidden');
            document.getElementById('guest-waiting').classList.add('hidden');
        } else {
            document.getElementById('host-controls').classList.add('hidden');
            document.getElementById('guest-waiting').classList.remove('hidden');
        }
        
        this.updateRoomUI();
    },
    
    updateRoomUI() {
        const list = document.getElementById('player-list');
        list.innerHTML = '';
        
        this.players.forEach(p => {
            const div = document.createElement('div');
            div.className = 'flex items-center gap-3 px-4 py-3 bg-bg rounded-lg border border-border';
            div.innerHTML = `
                <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center text-lg">
                    ${p.name.charAt(0).toUpperCase()}
                </div>
                <span class="font-medium text-white flex-1">${p.name}</span>
                ${p.isHost ? '<span class="text-xs bg-yellow-500/20 text-yellow-500 px-2 py-1 rounded">HOST</span>' : ''}
            `;
            list.appendChild(div);
        });
        
        document.getElementById('player-count').textContent = this.players.length;
        
        // Enable/disable start button
        const startBtn = document.getElementById('btn-start-game');
        const startHint = document.getElementById('start-hint');
        if (this.players.length >= 2) {
            startBtn.disabled = false;
            startHint.classList.add('hidden');
        } else {
            startBtn.disabled = true;
            startHint.classList.remove('hidden');
        }
    },
    
    copyRoomCode() {
        navigator.clipboard.writeText(this.roomCode);
        showToast('Room code copied!', 'success');
    },
    
    startGame() {
        if (!this.isHost) return;
        
        game.initAsHost(this.players.map(p => ({
            id: p.id,
            name: p.name
        })));
    },
    
    leaveRoom() {
        if (this.peer) {
            this.peer.destroy();
        }
        this.returnToLobby();
    },
    
    returnToLobby() {
        this.peer = null;
        this.connections = [];
        this.hostConnection = null;
        this.players = [];
        this.roomCode = '';
        
        document.getElementById('lobby-screen').classList.remove('hidden');
        document.getElementById('room-screen').classList.add('hidden');
        document.getElementById('game-screen').classList.add('hidden');
        document.getElementById('join-dialog').classList.add('hidden');
    },
    
    backToRoom() {
        if (this.isHost) {
            this.broadcast({ type: 'back_to_room' });
        }
        this.showRoomScreen();
    },
    
    backToRoomAsGuest() {
        document.getElementById('gameover-modal').classList.add('hidden');
        this.showRoomScreen();
    }
};

// Modify createRoom to use predictable peer ID
const originalCreateRoom = lobby.createRoom.bind(lobby);
lobby.createRoom = async function() {
    this.playerName = this.getPlayerName();
    if (!this.playerName) return;
    
    this.showStatus('Creating room...');
    this.isHost = true;
    
    // Generate a random room code first
    const randomCode = Math.random().toString(36).substring(2, 8).toUpperCase();
    const predictablePeerId = 'uno-room-' + randomCode.toLowerCase();
    
    try {
        this.peer = new Peer(predictablePeerId);
        
        this.peer.on('open', (id) => {
            this.roomCode = randomCode;
            this.players = [{
                id: id,
                name: this.playerName,
                isHost: true
            }];
            
            this.hideStatus();
            this.showRoomScreen();
            showToast('Room created!', 'success');
        });
        
        this.peer.on('connection', (conn) => {
            this.handleNewConnection(conn);
        });
        
        this.peer.on('error', (err) => {
            console.error('Peer error:', err);
            if (err.type === 'unavailable-id') {
                // ID taken, try again with different code
                showToast('Retrying...', 'info');
                setTimeout(() => this.createRoom(), 500);
            } else {
                showToast('Connection error. Please try again.', 'error');
                this.hideStatus();
            }
        });
        
    } catch (err) {
        console.error(err);
        showToast('Failed to create room', 'error');
        this.hideStatus();
    }
};

// ============== GAME MANAGER ==============
const game = {
    state: null,
    myPlayerId: null,
    pendingColorPick: false,
    pendingChallenge: null,
    calledUno: false,
    
    createDeck() {
        let deck = [];
        
        // Number cards and action cards
        COLORS.forEach(color => {
            // One 0
            deck.push({ id: this.uid(), color, value: '0' });
            // Two of each 1-9, skip, reverse, draw2
            for (let i = 0; i < 2; i++) {
                for (let v = 1; v <= 9; v++) {
                    deck.push({ id: this.uid(), color, value: v.toString() });
                }
                deck.push({ id: this.uid(), color, value: 'skip' });
                deck.push({ id: this.uid(), color, value: 'reverse' });
                deck.push({ id: this.uid(), color, value: 'draw2' });
            }
        });
        
        // Wilds (4 of each)
        for (let i = 0; i < 4; i++) {
            deck.push({ id: this.uid(), color: 'black', value: 'wild' });
            deck.push({ id: this.uid(), color: 'black', value: 'wild4' });
        }
        
        return shuffle(deck);
    },
    
    uid() {
        return Math.random().toString(36).substring(2, 10);
    },
    
    initAsHost(players) {
        const deck = this.createDeck();
        const hands = {};
        
        // Deal 7 cards to each player
        players.forEach(p => {
            hands[p.id] = deck.splice(0, 7);
        });
        
        // Initial discard (avoid wild4 as first card)
        let topCard = deck.pop();
        while (topCard.value === 'wild4') {
            deck.unshift(topCard);
            shuffle(deck);
            topCard = deck.pop();
        }
        
        // Set initial color
        let currentColor = topCard.color === 'black' ? 'red' : topCard.color;
        
        this.state = {
            players: players,
            deck: deck,
            discard: [topCard],
            hands: hands,
            currentPlayer: 0,
            direction: 1,
            currentColor: currentColor,
            drawStack: 0,
            phase: 'playing' // playing, colorPick, challenge, gameOver
        };
        
        this.myPlayerId = lobby.peer.id;
        
        // Handle first card effects
        if (['skip', 'reverse', 'draw2'].includes(topCard.value)) {
            this.applyCardEffect(topCard, true);
        }
        
        // Send game state to all players
        lobby.broadcast({ type: 'game_start', gameState: this.state });
        
        this.showGameScreen();
        this.render();
    },
    
    startAsGuest(gameState) {
        this.state = gameState;
        this.myPlayerId = lobby.peer.id;
        this.showGameScreen();
        this.render();
    },
    
    updateState(gameState) {
        this.state = gameState;
        this.render();
        
        // Check if it's our turn for UNO button
        this.checkUnoButton();
    },
    
    showGameScreen() {
        document.getElementById('lobby-screen').classList.add('hidden');
        document.getElementById('room-screen').classList.add('hidden');
        document.getElementById('game-screen').classList.remove('hidden');
        
        document.getElementById('game-room-code').textContent = lobby.roomCode;
    },
    
    getMyPlayerIndex() {
        return this.state.players.findIndex(p => p.id === this.myPlayerId);
    },
    
    isMyTurn() {
        return this.state.currentPlayer === this.getMyPlayerIndex();
    },
    
    getTopCard() {
        return this.state.discard[this.state.discard.length - 1];
    },
    
    canPlayCard(card) {
        if (!this.isMyTurn()) return false;
        if (this.pendingColorPick || this.pendingChallenge) return false;
        
        const top = this.getTopCard();
        
        // Wilds can always be played
        if (card.color === 'black') return true;
        
        // Match color or value
        if (card.color === this.state.currentColor) return true;
        if (card.value === top.value) return true;
        
        return false;
    },
    
    playCard(cardIndex) {
        if (!this.isMyTurn()) return;
        
        const myHand = this.state.hands[this.myPlayerId];
        const card = myHand[cardIndex];
        
        if (!this.canPlayCard(card)) {
            showToast("Can't play that card!", 'warning');
            return;
        }
        
        // Check Wild+4 legality
        if (card.value === 'wild4') {
            const hasMatchingColor = myHand.some((c, i) => i !== cardIndex && c.color === this.state.currentColor);
            if (hasMatchingColor) {
                showToast("You can only play +4 if you have no matching color!", 'warning');
                return;
            }
        }
        
        // Handle wilds - need color picker first
        if (card.color === 'black') {
            this.pendingColorPick = true;
            this.pendingWildCard = card;
            this.pendingCardIndex = cardIndex;
            document.getElementById('color-modal').classList.remove('hidden');
            document.getElementById('color-modal').style.display = 'flex';
            return;
        }
        
        // For guests: send action to host
        if (!lobby.isHost) {
            lobby.sendToHost({ 
                type: 'player_action', 
                action: { 
                    type: 'play_card', 
                    cardIndex: cardIndex,
                    cardId: card.id
                } 
            });
            return;
        }
        
        // Host processes locally
        this.processPlayCard(this.myPlayerId, cardIndex, card);
    },
    
    // Host-only: process card play
    processPlayCard(playerId, cardIndex, card, chosenColor = null) {
        const hand = this.state.hands[playerId];
        const playerIndex = this.state.players.findIndex(p => p.id === playerId);
        
        // UNO penalty check
        if (hand.length === 2 && playerId === this.myPlayerId && !this.calledUno) {
            showToast("Forgot to say UNO! Draw 2 cards.", 'warning');
            this.drawCardsForPlayer(playerId, 2);
        }
        
        // Remove from hand and add to discard
        hand.splice(cardIndex, 1);
        this.state.discard.push(card);
        
        if (chosenColor) {
            this.state.currentColor = chosenColor;
            if (card.value === 'wild4') {
                const nextIndex = this.getNextPlayerIndex();
                const nextPlayer = this.state.players[nextIndex];
                this.drawCardsForPlayer(nextPlayer.id, 4);
                this.state.currentPlayer = nextIndex;
            }
        } else {
            this.state.currentColor = card.color;
            this.applyCardEffect(card);
        }
        
        // Check win
        if (hand.length === 0) {
            this.state.phase = 'gameOver';
            const winner = this.state.players[playerIndex];
            lobby.broadcast({ type: 'game_over', winner: winner });
            this.showGameOver(winner);
            return;
        }
        
        this.advanceTurn();
        this.syncState();
    },
    
    pickColor(color) {
        document.getElementById('color-modal').classList.add('hidden');
        document.getElementById('color-modal').style.display = 'none';
        
        this.pendingColorPick = false;
        const card = this.pendingWildCard;
        const cardIndex = this.pendingCardIndex;
        
        // For guests: send action to host
        if (!lobby.isHost) {
            lobby.sendToHost({ 
                type: 'player_action', 
                action: { 
                    type: 'play_card', 
                    cardIndex: cardIndex,
                    cardId: card.id,
                    chosenColor: color
                } 
            });
            return;
        }
        
        // Host processes locally
        this.processPlayCard(this.myPlayerId, cardIndex, card, color);
    },
    
    applyCardEffect(card, isFirstCard = false) {
        const numPlayers = this.state.players.length;
        
        switch (card.value) {
            case 'skip':
                if (!isFirstCard) {
                    const skipped = this.state.players[this.getNextPlayerIndex()];
                    showToast(`${skipped.name} is skipped!`, 'info');
                }
                this.state.currentPlayer = this.getNextPlayerIndex();
                break;
                
            case 'reverse':
                this.state.direction *= -1;
                showToast('Direction reversed!', 'info');
                if (numPlayers === 2 && !isFirstCard) {
                    // In 2-player, reverse acts like skip
                    this.state.currentPlayer = this.getNextPlayerIndex();
                }
                break;
                
            case 'draw2':
                const victimIndex = this.getNextPlayerIndex();
                const victim = this.state.players[victimIndex];
                this.drawCardsForPlayer(victim.id, 2);
                showToast(`${victim.name} draws 2 and is skipped!`, 'info');
                this.state.currentPlayer = victimIndex;
                break;
        }
    },
    
    getNextPlayerIndex() {
        let next = this.state.currentPlayer + this.state.direction;
        const len = this.state.players.length;
        if (next >= len) next = 0;
        if (next < 0) next = len - 1;
        return next;
    },
    
    advanceTurn() {
        this.state.currentPlayer = this.getNextPlayerIndex();
    },
    
    drawCard() {
        if (!this.isMyTurn()) return;
        if (this.pendingColorPick || this.pendingChallenge) return;
        
        // For guests: send action to host
        if (!lobby.isHost) {
            lobby.sendToHost({ 
                type: 'player_action', 
                action: { type: 'draw' } 
            });
            showToast('You drew a card', 'info');
            return;
        }
        
        // Host processes locally
        this.drawCardsForPlayer(this.myPlayerId, 1);
        showToast('You drew a card', 'info');
        
        this.advanceTurn();
        this.syncState();
    },
    
    drawCardsForPlayer(playerId, count) {
        for (let i = 0; i < count; i++) {
            if (this.state.deck.length === 0) {
                // Reshuffle discard into deck
                const top = this.state.discard.pop();
                this.state.deck = shuffle(this.state.discard);
                this.state.discard = [top];
            }
            
            if (this.state.deck.length > 0) {
                this.state.hands[playerId].push(this.state.deck.pop());
            }
        }
    },
    
    sayUno() {
        if (this.state.hands[this.myPlayerId].length === 2) {
            this.calledUno = true;
            showToast('UNO!', 'success');
            document.getElementById('btn-uno').classList.add('hidden');
            
            // Notify others
            if (lobby.isHost) {
                lobby.broadcast({ type: 'game_state', gameState: this.state, unoCall: this.myPlayerId });
            } else {
                lobby.sendToHost({ type: 'player_action', action: { type: 'uno' } });
            }
        }
    },
    
    checkUnoButton() {
        const myHand = this.state.hands[this.myPlayerId];
        const btn = document.getElementById('btn-uno');
        
        if (myHand && myHand.length === 2 && this.isMyTurn() && !this.calledUno) {
            btn.classList.remove('hidden');
        } else {
            btn.classList.add('hidden');
        }
    },
    
    checkWin() {
        const myIndex = this.getMyPlayerIndex();
        const myHand = this.state.hands[this.myPlayerId];
        
        if (myHand.length === 0) {
            this.state.phase = 'gameOver';
            const winner = this.state.players[myIndex];
            
            if (lobby.isHost) {
                lobby.broadcast({ type: 'game_over', winner: winner });
            }
            
            this.showGameOver(winner);
            return true;
        }
        return false;
    },
    
    showGameOver(winner) {
        document.getElementById('winner-name').textContent = winner.name;
        document.getElementById('gameover-modal').classList.remove('hidden');
        document.getElementById('gameover-modal').style.display = 'flex';
        
        if (lobby.isHost) {
            document.getElementById('gameover-host-btns').classList.remove('hidden');
            document.getElementById('gameover-guest-msg').classList.add('hidden');
        } else {
            document.getElementById('gameover-host-btns').classList.add('hidden');
            document.getElementById('gameover-guest-msg').classList.remove('hidden');
        }
    },
    
    playAgain() {
        document.getElementById('gameover-modal').classList.add('hidden');
        document.getElementById('gameover-modal').style.display = 'none';
        
        if (lobby.isHost) {
            this.initAsHost(lobby.players.map(p => ({ id: p.id, name: p.name })));
        }
    },
    
    handleRemoteAction(peerId, action) {
        // Host processes actions from guests
        if (!lobby.isHost) return;
        
        const playerIndex = this.state.players.findIndex(p => p.id === peerId);
        
        // Validate it's this player's turn
        if (playerIndex !== this.state.currentPlayer) {
            console.log('Not this player\'s turn:', peerId);
            return;
        }
        
        switch (action.type) {
            case 'play_card':
                const hand = this.state.hands[peerId];
                const card = hand[action.cardIndex];
                
                if (!card) {
                    console.log('Invalid card index:', action.cardIndex);
                    return;
                }
                
                // Use processPlayCard for consistency
                this.processPlayCard(peerId, action.cardIndex, card, action.chosenColor);
                break;
                
            case 'draw':
                this.drawCardsForPlayer(peerId, 1);
                this.advanceTurn();
                this.syncState();
                break;
                
            case 'uno':
                showToast(`${this.state.players[playerIndex]?.name}: UNO!`, 'info');
                break;
        }
    },
    
    syncState() {
        if (lobby.isHost) {
            lobby.broadcast({ type: 'game_state', gameState: this.state });
        }
        this.render();
    },
    
    // Submit action to host (for guests)
    submitAction(action) {
        if (lobby.isHost) {
            // Process locally
            this.handleRemoteAction(this.myPlayerId, action);
        } else {
            lobby.sendToHost({ type: 'player_action', action: action });
        }
    },
    
    showRules() {
        document.getElementById('rules-modal').classList.remove('hidden');
        document.getElementById('rules-modal').style.display = 'flex';
    },
    
    respondChallenge(challenge) {
        // Simplified - just close modal
        document.getElementById('challenge-modal').classList.add('hidden');
        this.pendingChallenge = null;
    },
    
    // ============== RENDERING ==============
    render() {
        if (!this.state) return;
        
        this.renderColorIndicator();
        this.renderDirectionIndicator();
        this.renderDiscardPile();
        this.renderHumanHand();
        this.renderOpponents();
        this.renderTurnIndicator();
        this.checkUnoButton();
    },
    
    renderColorIndicator() {
        const el = document.getElementById('color-indicator');
        el.style.backgroundColor = CARD_COLORS[this.state.currentColor] || '#ccc';
    },
    
    renderDirectionIndicator() {
        const el = document.getElementById('direction-indicator');
        el.style.animation = this.state.direction === 1 
            ? 'spin 8s linear infinite' 
            : 'spin 8s linear infinite reverse';
    },
    
    renderDiscardPile() {
        const pile = document.getElementById('discard-pile');
        const card = this.getTopCard();
        pile.innerHTML = this.renderCard(card);
    },
    
    renderCard(card, clickable = false, index = -1) {
        const symbol = SYMBOLS[card.value] || card.value;
        const bgColor = CARD_COLORS[card.color];
        const textColor = card.color === 'yellow' ? '#333' : '#fff';
        
        const innerBg = card.color === 'black' 
            ? 'background: conic-gradient(#ff5555, #0099ff, #55aa55, #ffaa00);'
            : 'background: rgba(255,255,255,0.9);';
        
        const innerColor = card.color === 'black' ? 'color: white; text-shadow: 0 0 5px black;' : `color: ${bgColor};`;
        
        return `
            <div class="w-20 h-28 rounded-xl shadow-lg flex items-center justify-center relative ${clickable ? 'cursor-pointer hover:scale-110 hover:-translate-y-2 transition-all' : ''}"
                style="background-color: ${bgColor};"
                ${clickable ? `onclick="game.playCard(${index})"` : ''}>
                <div class="w-[80%] h-[85%] rounded-[50%_10%] flex items-center justify-center text-2xl font-black transform -rotate-12"
                    style="${innerBg} ${innerColor}">
                    ${symbol}
                </div>
                <span class="absolute top-1 left-2 text-xs font-bold" style="color: ${textColor};">${symbol}</span>
                <span class="absolute bottom-1 right-2 text-xs font-bold rotate-180" style="color: ${textColor};">${symbol}</span>
            </div>
        `;
    },
    
    renderHumanHand() {
        const container = document.getElementById('human-hand');
        const myHand = this.state.hands[this.myPlayerId] || [];
        
        const myPlayer = this.state.players.find(p => p.id === this.myPlayerId);
        document.getElementById('human-name').textContent = myPlayer?.name || 'You';
        
        container.innerHTML = myHand.map((card, i) => {
            const canPlay = this.canPlayCard(card);
            return `
                <div class="transform transition-all ${canPlay ? '' : 'opacity-50'}" style="margin-left: ${i > 0 ? '-20px' : '0'};">
                    ${this.renderCard(card, canPlay && this.isMyTurn(), i)}
                </div>
            `;
        }).join('');
    },
    
    renderOpponents() {
        const container = document.getElementById('opponents-container');
        const myIndex = this.getMyPlayerIndex();
        
        // Get other players (excluding self)
        const others = this.state.players.filter((_, i) => i !== myIndex);
        
        // Position opponents around the board
        const positions = [
            { top: '15%', left: '50%', transform: 'translateX(-50%)' },  // Top
            { top: '50%', left: '10%', transform: 'translateY(-50%)' },   // Left
            { top: '50%', right: '10%', transform: 'translateY(-50%)' },  // Right
        ];
        
        container.innerHTML = others.map((player, i) => {
            const pos = positions[i] || positions[0];
            const handSize = this.state.hands[player.id]?.length || 0;
            const isActive = this.state.players.indexOf(player) === this.state.currentPlayer;
            
            const style = Object.entries(pos).map(([k, v]) => `${k}: ${v}`).join('; ');
            
            return `
                <div class="absolute flex flex-col items-center ${isActive ? '' : 'opacity-60'}" style="${style}">
                    <div class="w-12 h-12 rounded-full ${isActive ? 'ring-4 ring-yellow-500 animate-pulse' : ''} bg-gray-700 flex items-center justify-center text-xl mb-2">
                        ${player.name.charAt(0).toUpperCase()}
                    </div>
                    <span class="text-sm font-medium text-white">${player.name}</span>
                    <div class="flex mt-2">
                        ${Array(Math.min(handSize, 10)).fill().map(() => 
                            `<div class="w-3 h-5 bg-gray-800 border border-white/50 rounded -ml-2 first:ml-0"></div>`
                        ).join('')}
                        ${handSize > 10 ? `<span class="text-xs text-muted ml-1">+${handSize - 10}</span>` : ''}
                    </div>
                </div>
            `;
        }).join('');
    },
    
    renderTurnIndicator() {
        const indicator = document.getElementById('turn-indicator');
        if (this.isMyTurn()) {
            indicator.classList.remove('hidden');
        } else {
            indicator.classList.add('hidden');
        }
    }
};

// ============== INIT ==============
document.addEventListener('DOMContentLoaded', () => {
    lobby.init();
});

// Add spin animation
const style = document.createElement('style');
style.textContent = `
    @keyframes spin { from { transform: translate(-50%, -50%) rotate(0deg); } to { transform: translate(-50%, -50%) rotate(360deg); } }
    @keyframes fade-in { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in { animation: fade-in 0.3s ease-out; }
`;
document.head.appendChild(style);
</script>
