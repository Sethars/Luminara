<?php

$title = "Black Jack";
$css = "casual_black_jack"; 
$script = [
    // "logout",
    "casual_black_jack"
];
$checkAuth = true;

?>


<div id="main-content">
    <?php
    include_once __DIR__ . '/../components/header.php';
    ?>

    <body>
        
        <header>
        <button class=" position-absolute top-0 start-0 fas fa-sign-out-alt me-2" style="margin:15px; rotate:180deg; border-radius:10px; color:crimson; background-color:transparent; font-size:2rem;"></button>
        <h1>♠️ Blackjack ♥️</h1>
        <p>Play against the dealer bot</p>
        </header>

        <main class="game-container">
        <div class="game-info">
            <div class="score">Wins: <span id="wins">0</span></div>
            <div class="score">Losses: <span id="losses">0</span></div>
            <div class="score">Ties: <span id="ties">0</span></div>
        </div>

        <div class="message" id="message">Place your bet to start playing</div>

        <div class="betting-section active" id="betting-section">
            <div class="bet-container">
            <div class="bet-display">
                <span class="bet-label">Bet:</span>
                <input
                type="number"
                id="bet-amount"
                class="bet-input"
                value="10"
                min="5"
                max="1000"
                disabled
                />
                <button class="reset-button" id="reset-bet">↺</button>
            </div>

            <div class="bet-chip-container">
                <button class="chip-button" data-value="5">5</button>
                <button class="chip-button" data-value="10">10</button>
                <button class="chip-button" data-value="20">20</button>
                <button class="chip-button" data-value="100">100</button>
                <button class="chip-button" data-value="1000">1000</button>
            </div>

            <div class="bet-info">Click on chip values to set your bet</div>
            </div>
        </div>

        <div class="insurance-section" id="insurance-section">
            <h3>Insurance?</h3>
            <p>Dealer shows an Ace. Would you like to take insurance?</p>
            <div class="controls">
            <button id="take-insurance">Yes</button>
            <button id="decline-insurance">No</button>
            </div>
        </div>

        <section class="dealer-section">
            <div class="section-title" style="color:white;">
            <h2 style="color:white;">Dealer</h2>
            <span id="dealer-score" style="color:white;">Score: 0</span>
            </div>
            <div class="hand" id="dealer-hand"></div>
        </section>

        <section class="player-section">
            <div class="section-title">
            <h2 style="color:white;">Player</h2>
            <span id="player-score" style="color:white;">Score: 0</span>
            </div>
            <div class="hand" id="player-hand"></div>
            <div class="split-hands" id="split-hands" style="display: none">
            <div class="split-hand">
                <div class="split-hand-title" style="color:white;">Hand 1</div>
                <div class="hand" id="split-hand-1"></div>
                <div class="controls" id="split-hand-1-controls"></div>
            </div>
            <div class="split-hand">
                <div class="split-hand-title" style="color:white;">Hand 2</div>
                <div class="hand" id="split-hand-2"></div>
                <div class="controls" id="split-hand-2-controls"></div>
            </div>
            </div>
        </section>

        <div class="controls">
            <button id="hit-btn" disabled>Hit</button>
            <button id="stand-btn" disabled>Stand</button>
            <button id="double-btn" disabled>Double Down</button>
            <button id="split-btn" disabled>Split</button>
            <button id="new-game-btn">Place Bet & Play</button>
        </div>

        <div class="stats">
            <div class="stat-item">
            <div class="stat-value" id="games-played" style="color:white;">0</div>
            <div style="color:white;">Games Played</div>
        </div>
            <div class="stat-item">
            <div class="stat-value" id="blackjacks" style="color:white;">0</div>
            <div style="color:white;">Blackjacks</div>
        </div>
            <div class="stat-item">
            <div class="stat-value" id="win-rate" style="color:white;">0%</div>
            <div style="color:white;">Win Rate</div>
        </div>
        </div>
        </main>

        <!-- Chip and Bet Info Panel -->
        <div class="chip-bet-panel">
        <div class="chip-info-panel">
            <div class="chip-icon-panel">$</div>
            <span class="chip-label-panel">Chips:</span>
            <span class="chip-value-panel" id="chip-count-panel">1000</span>
        </div>
        <div class="bet-info-panel">
            <div class="chip-icon-panel">B</div>
            <span class="bet-label-panel">Bet:</span>
            <span class="bet-value-panel" id="bet-amount-panel">0</span>
        </div>
        </div>

        <button class="rules-btn" id="rules-btn">?</button>

        <div class="rules-modal" id="rules-modal">
        <div class="rules-content">
            <div class="rules-header">
            <h2 class="rules-title">Blackjack Rules</h2>
            <button class="close-rules" id="close-rules" style="  border-radius: 0; width: 0px; height: autopx;">X</button>
            </div>

            <div class="rules-section">
            <h3>🎯 Tujuan Permainan</h3>
            <ul>
                <li>
                Mengalahkan dealer dengan memiliki jumlah kartu sedekat mungkin
                dengan 21, tapi tidak boleh lebih dari 21.
                </li>
            </ul>
            </div>

            <div class="rules-section">
            <h3>🃏 Nilai Kartu</h3>
            <ul>
                <li>2–10 → sesuai angka</li>
                <li>J, Q, K → bernilai 10</li>
                <li>
                As (Ace) → bisa dihitung sebagai 1 atau 11, tergantung mana yang
                lebih menguntungkan
                </li>
            </ul>
            </div>

            <div class="rules-section">
            <h3>💰 Taruhan</h3>
            <ul>
                <li>
                Sebelum kartu dibagikan, pemain memasang taruhan dengan chip
                </li>
                <li>Minimum bet: 5 chips</li>
            </ul>
            </div>

            <div class="rules-section">
            <h3>🔄 Pembagian Kartu</h3>
            <ul>
                <li>Dealer bagikan 2 kartu ke pemain → terbuka</li>
                <li>
                Dealer ambil 2 kartu: 1 terbuka (upcard) dan 1 tertutup (hole
                card)
                </li>
            </ul>
            </div>

            <div class="rules-section">
            <h3>🃏 Blackjack (Natural)</h3>
            <ul>
                <li>
                Kalau 2 kartu pertama = As + 10/J/Q/K → disebut Blackjack (total
                21)
                </li>
                <li>Pembayaran: 1.5 kali taruhan</li>
                <li>Kalau dealer juga dapat blackjack → hasilnya seri (push)</li>
            </ul>
            </div>

            <div class="rules-section">
            <h3>🎮 Giliran Pemain</h3>
            <ul>
                <li><strong>Hit</strong> → minta kartu tambahan</li>
                <li><strong>Stand</strong> → berhenti, tidak minta kartu lagi</li>
                <li>
                <strong>Double Down</strong> → gandakan taruhan, tapi hanya boleh
                ambil 1 kartu tambahan
                </li>
                <li>
                <strong>Split</strong> → kalau 2 kartu pertama sama (misalnya 8 &
                8), bisa dipisah jadi 2 tangan dengan taruhan baru
                </li>
                <li>
                <strong>Bust</strong> → kalau total lebih dari 21 → otomatis kalah
                </li>
            </ul>
            </div>

            <div class="rules-section">
            <h3>🎮 Giliran Dealer</h3>
            <ul>
                <li>Dealer buka kartu tertutup</li>
                <li>Jika total ≤ 16 → wajib ambil kartu</li>
                <li>Jika total ≥ 17 → wajib berhenti (stand)</li>
                <li>Dealer tidak bisa pilih sendiri, harus ikuti aturan ini</li>
            </ul>
            </div>

            <div class="rules-section">
            <h3>🛡 Asuransi (Insurance)</h3>
            <ul>
                <li>
                Kalau kartu terbuka dealer = As, pemain boleh pasang taruhan
                sampingan (setengah dari taruhan awal)
                </li>
                <li>
                Taruhan ini menang kalau kartu tertutup dealer = 10 (berarti
                dealer dapat blackjack)
                </li>
                <li>Pembayaran: 2 banding 1</li>
            </ul>
            </div>

            <div class="rules-section">
            <h3>⚖ Penyelesaian (Settlement)</h3>
            <ul>
                <li>Kalau dealer bust → semua pemain yang masih hidup menang</li>
                <li>Kalau dealer tidak bust:</li>
                <li>Pemain dengan total lebih tinggi dari dealer → menang</li>
                <li>Pemain dengan total lebih rendah dari dealer → kalah</li>
                <li>Sama dengan dealer → seri (push), taruhan dikembalikan</li>
            </ul>
            </div>
        </div>
        </div>
    </body>

    <?php
    include_once __DIR__ . '/../components/footer.php';
    ?>
</div>

<div id="loading" class="d-none">
    <?php
    include_once __DIR__ . '/../components/loadingScreen.php' 
    ?>
</div>