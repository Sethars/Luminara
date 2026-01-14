<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Coin Flip Casino</title>
    <link rel="stylesheet" href="../../css/coin_flip.css">
    <link
      href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400;500&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
  </head>
  <body>
    <a href="/game_list" class="exit-button">
      <i class="fas fa-times"></i>
    </a>

    <div class="casino-container">
      <div class="casino-table">
        <h1 class="game-title">Coin Flip Casino</h1>

        <div class="balance-container">
          <div class="balance-label">Chip Anda</div>
<!-- sinih -->
          <div class="balance-amount" id="balance"></div>
        </div>

        <div class="coin-container">
          <div class="coin" id="coin">
            <div class="coin-side heads">H</div>
            <div class="coin-side tails">T</div>
          </div>
        </div>

        <div class="betting-area">
          <div class="bet-label">Pilih Sisi Anda</div>
          <div class="bet-options">
            <div class="bet-option" id="heads-option">HEADS</div>
            <div class="bet-option" id="tails-option">TAILS</div>
          </div>
        </div>

        <div class="chips-container">
          <div class="chip chip-10" data-value="10">10</div>
          <div class="chip chip-50" data-value="50">50</div>
          <div class="chip chip-100" data-value="100">100</div>
          <div class="chip chip-500" data-value="500">500</div>
        </div>

        <div class="bet-amount">
          Taruhan: <span id="bet-amount">0 Chips</span>
        </div>

        <button class="flip-button" id="flip-button">FLIP COIN</button>

        <div class="message" id="message"></div>

        <div class="history">
          <div class="history-title">Riwayat</div>
          <div class="history-list" id="history-list"></div>
        </div>
      </div>
    </div>

    <a href="#" class="rules-button" id="rules-button">
      <i class="fas fa-question"></i>
    </a>

    <div class="gacor-container">
      <div class="gacor-label">Gacor Mode</div>
      <label class="switch">
        <input type="checkbox" id="gacor-switch" />
        <span class="slider"></span>
      </label>
      <div class="gacor-status gacor-off" id="gacor-status">OFF (25% Win)</div>
    </div>

    <!-- Rules Modal -->
    <div id="rules-modal" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <h2 class="modal-title">Aturan Permainan</h2>
        <div class="rules-content">
          <h3>Cara Bermain</h3>
          <ul>
            <li>Pilih sisi koin: HEADS atau TAILS</li>
            <li>Pilih jumlah taruhan dengan memilih chip</li>
            <li>Tekan tombol FLIP COIN untuk memulai</li>
            <li>Jika tebakan benar, Anda menang 2x taruhan</li>
            <li>Jika tebakan salah, Anda kehilangan taruhan</li>
          </ul>

          <h3>Gacor Mode</h3>
          <ul>
            <li>ON: Peluang menang 75%</li>
            <li>OFF: Peluang menang 25%</li>
          </ul>

          <h3>Chip Values</h3>
          <ul>
            <li>Chip Biru: 10 Chips</li>
            <li>Chip Merah: 50 Chips</li>
            <li>Chip Hijau: 100 Chips</li>
            <li>Chip Ungu: 500 Chips</li>
          </ul>
        </div>
      </div>
    </div>
    <script type="module" src="../../js/coin_flip.js"></script>
  </body>
</html>
