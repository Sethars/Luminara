<?php
$title = "Lobby Russian Roulette";
$css = "RRLobby"; 
$script = [
  "RRLobby"
];
$checkAuth = true;

include_once __DIR__ . '/../components/header.php';
?>

<header class="header">
<button class="quit-btn" id="quitBtn">
    <i class="fa fa-sign-out"></i>
    Keluar
</button>
  <h1 class="title" id="lobby-title">Russian Roulette</h1>
  <p class="subtitle">1 vs 1 Duel Arena</p>
</header>
<body>
  <main class="main-container">
    <!-- Mobile Rules Section -->
    <div class="mobile-rules-wrapper">
        <div class="mobile-rules-header" id="rulesMobile">
            <h3 class="mobile-rules-title">Peraturan</h3>
            <span id="arrow-icon">▼</span>
        </div>

        <div class="mobile-rules">
            <ul class="mobile-rules-list">
                <li>Setiap pemain akan bergantian menembakkan pistol ke kepala mereka sendiri.</li>
                <li>Pistol memiliki 6 chamber dengan 1 bullet di dalamnya.</li>
                <li>Pemain yang kalah adalah pemain yang mendapatkan bullet.</li>
                <li>Pemain dapat menaikkan taruhan sebelum permainan dimulai.</li>
                <li>Permainan akan dimulai ketika semua pemain sudah siap.</li>
                <li>Pemain dapat keluar dari permainan kapan saja sebelum permainan dimulai.</li>
            </ul>
        </div>
    </div>

    <div class="game-layout">
        <!-- Player 1 Card -->
        <div class="player-card" id="player1Card">
            <div class="player-number">Pemain 1</div>
            <div class="player-avatar" id="player1Avatar"><i class="bi bi-incognito text-white"></i></div>
            <div class="player-name" id="player1Name">-</div>
            <div class="player-status" id="player1Status">Menunggu...</div>
        </div>

        <!-- VS Indicator -->
        <div class="vs-indicator">VS</div>

        <!-- Player 2 Card -->
        <div class="player-card" id="player2Card">
            <div class="player-number">Pemain 2</div>
            <div class="player-avatar" id="player2Avatar"><i class="bi bi-incognito text-white"></i></div>
            <div class="player-name" id="player2Name">-</div>
            <div class="player-status" id="player2Status">Menunggu...</div>
        </div>

        <!-- Game Center Card -->
        <div class="game-center">
            <div class="game-info">
                <div class="info-item">
                    <div class="info-label">Bullet</div>
                    <div class="info-value" id="bulletCount">1</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Chamber</div>
                    <div class="info-value" id="chamberCount">6</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Taruhan</div>
                    <div class="info-value" id="betAmount">0</div>
                </div>
            </div>
            
            <div class="action-buttons">
                <button class="btn btn-primary" id="readyBtn">Siap</button>
                <button class="btn btn-secondary d-none" id="startBtn" disabled>Mulai Permainan</button>
            </div>
            
            <div class="status-container">
                <span class="status-text" id="statusText">0/2 Pemain Siap</span>
                <span class="status-indicator" id="statusIndicator"></span>
            </div>
        </div>
    </div>

    <!-- Chat Container -->
    <div class="chat-container">
        <div class="chat-header">Lobby Chat</div>
        <div class="chat-messages" id="chatMessages">
            <div class="chat-message system">Selamat datang di lobby <b id="lobby-name-welcome"></b></div>
        </div>
        <div class="chat-input-container">
            <input type="text" class="chat-input" id="chatInput" placeholder="Ketik pesan...">
            <button class="chat-send-btn" id="chatSendBtn">Kirim</button>
        </div>
    </div>
  </main>
</body>


    <!-- Rules Button -->
    <button class="rules-btn" id="rulesBtn">?</button>

    <!-- Rules Modal - Navy and Dark Red Theme -->
    <div class="modal" id="rulesModal">
        <div class="modal-container">
            <div class="modal-content">
                <button class="modal-close" id="modalClose">&times;</button>
                <div class="modal-header">
                    <h2 class="modal-title">Peraturan Permainan</h2>
                </div>
                <div class="modal-body">
                    <ul class="modal-rules-list">
                        <li>Setiap pemain akan bergantian menembakkan pistol ke kepala mereka sendiri.</li>
                        <li>Pistol memiliki 6 chamber dengan 1 bullet di dalamnya.</li>
                        <li>Pemain yang kalah adalah pemain yang mendapatkan bullet.</li>
                        <li>Pemain dapat menaikkan taruhan sebelum permainan dimulai.</li>
                        <li>Permainan akan dimulai ketika semua pemain sudah siap.</li>
                        <li>Pemain dapat keluar dari permainan kapan saja sebelum permainan dimulai.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

<div id="loading" class="d-none">
    <?php include_once __DIR__ . '/../components/loadingScreen.php' ?>
</div>

<?php include_once __DIR__ . '/../components/footer.php'; ?>