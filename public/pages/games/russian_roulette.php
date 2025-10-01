<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Russian Roulette</title>
    <link rel="stylesheet" href="../../css/russian_roulette.css">
  </head>
  <body>
    <h1>RUSSIAN ROULETTE</h1>

    <div class="arena">
      <div class="card player-card">
        <div class="avatar player-avatar"></div>
        <div class="player-name">PLAYER</div>
        <div class="health-bar">
          <div class="health-fill" id="playerHealth"></div>
        </div>
        <div class="bet-amount">Taruhan: $100</div>
        <div class="stats">
          <div class="stat-item">
            <span>Menang</span>
            <span class="stat-value" id="playerWins">0</span>
          </div>
          <div class="stat-item">
            <span>Kalah</span>
            <span class="stat-value" id="playerLosses">0</span>
          </div>
          <div class="stat-item">
            <span>Rasio</span>
            <span class="stat-value" id="playerRatio">0%</span>
          </div>
        </div>
        <div class="status" id="playerStatus"></div>
      </div>

      <img id="pistolImg" src="" alt="Pistol" />
      <div class="turn-indicator" id="turnIndicator">PLAYER'S TURN</div>

      <div class="card bot-card">
        <div class="avatar bot-avatar"></div>
        <div class="bot-name">BOT</div>
        <div class="health-bar">
          <div class="health-fill" id="botHealth"></div>
        </div>
        <div class="bet-amount">Taruhan: $100</div>
        <div class="stats">
          <div class="stat-item">
            <span>Menang</span>
            <span class="stat-value" id="botWins">0</span>
          </div>
          <div class="stat-item">
            <span>Kalah</span>
            <span class="stat-value" id="botLosses">0</span>
          </div>
          <div class="stat-item">
            <span>Rasio</span>
            <span class="stat-value" id="botRatio">0%</span>
          </div>
        </div>
        <div class="status" id="botStatus"></div>
      </div>
    </div>

    <div class="chambers" id="chambers"></div>

    <div class="actions" id="playerActions">
      <button id="btnSelf">Tembak Diri Sendiri</button>
      <button id="btnBot">Tembak Lawan</button>
    </div>

    <div id="turnProgress"><div id="turnProgressBar"></div></div>

    <div class="game-info">
      <p>Nasib ada di tangan Anda. Putar peluru dan hadapi konsekuensinya.</p>
    </div>

    <!-- Welcome Modal -->
    <div class="modal" id="welcomeModal">
      <div class="modal-content">
        <h2>Selamat Datang</h2>
        <p>Selamat datang di Russian Roulette! Game yang menguji nyali Anda.</p>
        <p>
          Aturan mainnya sederhana: ada 6 chamber, 1 di antaranya berisi peluru.
          Pemain dan bot bergantian menembak diri sendiri atau lawan.
        </p>
        <p>Siapa yang tertembak peluru, dia kalah!</p>
        <p>Apakah Anda siap menghadapi tantangan ini?</p>
        <button class="start-btn" onclick="startGame()">Mulai Game</button>
      </div>
    </div>

    <div class="modal" id="confirmModal">
      <div class="modal-content">
        <p id="confirmText"></p>
        <button id="confirmYes">Yakin</button>
        <button onclick="closeModal()">Batal</button>
      </div>
    </div>

    <div class="modal" id="gameOverModal">
      <div class="modal-content">
        <p id="gameOverText"></p>
        <button onclick="restartGame()">Main Lagi</button>
        <button onclick="window.location.reload()">Keluar</button>
      </div>
    </div>

    <div style="position: fixed; right: 10px; bottom: 10px; text-align: right">
      <button id="revealBtn" onclick="toggleReveal()">Reveal</button>
      <button id="muteBtn" onclick="toggleMute()">🔊</button>
      <button id="btnBotSmart" onclick="toggleBotSmart()">
        Bot Smart Mode: ON
      </button>
      <button id="btnBotSuicide" onclick="toggleBotSuicide()">
        Bot Suicide Last: ON
      </button>
    </div>

    <audio id="sfxEmpty" src="../../assets/audio/roulette/kosong.aac"></audio>
    <audio id="sfxShot" src="../../assets/audio/roulette/dor.aac"></audio>
    <audio id="bgm" src="../../assets/audio/roulette/bpkMulyono.mp3" loop></audio>


  </body>
  <script src="../../js/russian_roulette.js"></script>
</html>
