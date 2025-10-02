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
        <div class="avatar player-avatar">
          <img src="../../assets/img/photo_profile/ppkosong.jpg" alt="" style="width:100px; height:100px;">
        </div>
        <div class="player-name">Hiiragi</div>
        <div class="health-bar">
          <div class="health-fill" id="playerHealth"></div>
        </div>
        <div class="bet-amount">Taruhan: 100 Chip</div>
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
    <div class="modal" id="welcomeModal" style="display: flex; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(15, 23, 42, 0.75); justify-content: center; align-items: center;">
      <div class="modal-content-conn" style="background-color: #1e293b; border-radius: 8px; padding: 24px; max-width: 420px; width: 90%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2); border: 1px solid #334155;">
        <div style="display: flex; align-items: center; margin-bottom: 16px;">
          <div style="width: 40px; height: 40px; background-color: rgba(220, 38, 38, 0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px; color: #dc2626;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
            </svg>
          </div>
          <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: #f1f5f9;">Selamat Datang</h3>
        </div>
        
        <p style="margin: 0 0 16px 0; font-size: 0.95rem; color: #cbd5e1; line-height: 1.5;">Selamat datang di Russian Roulette! Game yang menguji nyali Anda.</p>
        
        <div style="background-color: rgba(220, 38, 38, 0.08); border: 1px solid rgba(220, 38, 38, 0.2); border-radius: 6px; padding: 12px; margin-bottom: 16px;">
          <p style="margin: 0 0 12px 0; font-size: 0.95rem; color: #f87171; line-height: 1.5; font-weight: 500;">
            Aturan mainnya:
          </p>
          <ul style="margin: 0; padding-left: 20px; color: #fecaca;">
            <li style="margin-bottom: 8px; font-size: 0.95rem; line-height: 1.5;">Ada 6 chamber, 1 di antaranya berisi peluru</li>
            <li style="margin-bottom: 8px; font-size: 0.95rem; line-height: 1.5;">Pemain dan bot bergantian menembak diri sendiri atau lawan</li>
            <li style="font-size: 0.95rem; line-height: 1.5;">Siapa yang tertembak peluru, dia kalah!</li>
          </ul>
        </div>
        
        <p style="margin: 0 0 20px 0; font-size: 0.95rem; color: #fca5a5; line-height: 1.5; font-weight: 500;">Apakah Anda siap menghadapi tantangan ini?</p>
        
        <div style="display: flex; justify-content: flex-end; gap: 10px;">
          <!-- <button onclick="closeModal()" style="padding: 8px 14px; background-color: transparent; color: #94a3b8; border: 1px solid #334155; border-radius: 6px; font-size: 0.875rem; font-weight: 500; cursor: pointer;">Keluar</button> -->
          <button onclick="startGame()" style="padding: 8px 14px; background-color: #dc2626; color: white; border: none; border-radius: 6px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#b91c1c'" onmouseout="this.style.backgroundColor='#dc2626'">Mulai Game</button>
        </div>
      </div>
    </div>

    <div class="modal" id="confirmModal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(15, 23, 42, 0.75); justify-content: center; align-items: center;">
      <div class="modal-content-conn" style="background-color: #1e293b; border-radius: 8px; padding: 24px; max-width: 420px; width: 90%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2); border: 1px solid #334155;">
        <div style="display: flex; align-items: center; margin-bottom: 16px;">
          <div style="width: 40px; height: 40px; background-color: rgba(239, 68, 68, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px; color: #ef4444;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: #f1f5f9;">Konfirmasi Tindakan</h3>
        </div>
        
        <p id="confirmText" style="margin: 0 0 20px 0; font-size: 0.95rem; color: #cbd5e1; line-height: 1.5;"></p>
        
        <div style="display: flex; justify-content: flex-end; gap: 10px;">
          <button onclick="closeModal()" style="padding: 8px 14px; background-color: transparent; color: #94a3b8; border: 1px solid #334155; border-radius: 6px; font-size: 0.875rem; font-weight: 500; cursor: pointer;">Batal</button>
          <button id="confirmYes" style="padding: 8px 14px; background-color: #ef4444; color: white; border: none; border-radius: 6px; font-size: 0.875rem; font-weight: 500; cursor: pointer;">Lanjutkan</button>
        </div>
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
    <audio id="bgm" src="../../assets/audio/roulette/bgm.mp3" loop></audio>


  </body>
  <script src="../../js/russian_roulette.js"></script>
</html>
