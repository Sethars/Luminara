<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>slot</title>
    <link rel="stylesheet" href="../../css/slot.css">
</head>
  <body>
    <!-- Chip Container -->
    <div class="chip-container">
      <div class="chip-icon">$</div>
      <div class="chip-amount" id="chipAmount"></div>
    </div>

    <div class="slot-container">
      <!-- Tombol Close -->
      <div class="close-btn">✕</div>

      <!-- Tombol Rules -->
      <button class="rules-btn" >Rules</button>

      <!-- Toggle On/Off -->
      <!-- <div class="toggle-container">
        <span class="toggle-label">Win Rate:</span>
        <div class="toggle-btn" id="winRateToggle">
          <div class="toggle-slider"></div>
        </div>
        <span class="win-rate" id="winRateText">25%</span>
      </div> -->

      <!-- Jackpot Container - Hidden by default -->
      <div class="jackpot-container" id="jackpotContainer">
        <div class="jackpot-piece left"></div>
        <div class="jackpot-piece middle"></div>
        <div class="jackpot-piece right"></div>
      </div>

      <!-- Slot Machine -->
      <div class="slot-machine">
        <div class="reel-container">
          <div class="reel" id="reel1">
            <div class="reel-item">🍒</div>
            <div class="reel-item">🍋</div>
            <div class="reel-item">🍊</div>
            <div class="reel-item">🍇</div>
            <div class="reel-item">🔔</div>
            <div class="reel-item">💎</div>
            <div
              class="reel-item seven"
              style="
                background-image: url('https://picsum.photos/seed/seven/100/100.jpg');
              "
            ></div>
          </div>
        </div>
        <div class="reel-container">
          <div class="reel" id="reel2">
            <div class="reel-item">🍒</div>
            <div class="reel-item">🍋</div>
            <div class="reel-item">🍊</div>
            <div class="reel-item">🍇</div>
            <div class="reel-item">🔔</div>
            <div class="reel-item">💎</div>
            <div
              class="reel-item seven"
              style="
                background-image: url('https://picsum.photos/seed/seven/100/100.jpg');
              "
            ></div>
          </div>
        </div>
        <div class="reel-container">
          <div class="reel" id="reel3">
            <div class="reel-item">🍒</div>
            <div class="reel-item">🍋</div>
            <div class="reel-item">🍊</div>
            <div class="reel-item">🍇</div>
            <div class="reel-item">🔔</div>
            <div class="reel-item">💎</div>
            <div
              class="reel-item seven"
              style="
                background-image: url('https://picsum.photos/seed/seven/100/100.jpg');
              "
            ></div>
          </div>
        </div>
      </div>

      <!-- Spin Button -->
      <button class="spin-btn" id="spinBtn">SPIN</button>
    </div>

    <!-- Rules Modal -->
    <div class="rules-modal" id="rulesModal">
      <div class="rules-content">
        <div class="close-rules">✕</div>
        <h2>Aturan Permainan</h2>
        <p>1. Setiap spin menghabiskan 10 chip.</p>
        <p>2. Jika Anda mendapatkan 3 simbol yang sama, Anda menang!</p>
        <p>3. Jackpot khusus akan terpicu jika Anda mendapatkan 3 simbol 7.</p>
        <p>4. Gunakan tombol Win Rate untuk mengubah peluang menang:</p>
        <p>- OFF: Peluang menang 25%</p>
        <p>- ON: Peluang menang 75%</p>
        <p>5. Semoga beruntung!</p>
      </div>
    </div>

    <!-- Notification -->
    <div class="notification" id="notification">
      <div class="notification-content">
        <div class="notification-message" id="notificationMessage"></div>
        <div class="notification-chip" id="notificationChip"></div>
      </div>
    </div>

    <!-- Audio Elements -->
    <audio id="spinSound" preload="auto">
      <source
        src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmFgU7k9n1unEiBC13yO/eizEIHWq+8+OWT"
        type="audio/wav"
      />
    </audio>

    <audio id="winSound" preload="auto">
      <source
        src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmFgU7k9n1unEiBC13yO/eizEIHWq+8+OWT"
        type="audio/wav"
      />
    </audio>

    <audio id="jackpotSound" preload="auto">
      <source
        src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmFgU7k9n1unEiBC13yO/eizEIHWq+8+OWT"
        type="audio/wav"
      />
    </audio>
    <script type="module" src="../../js/slot.js"></script>
  </body>
</html>