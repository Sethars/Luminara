let chambers = [],
  currentIndex = 0,
  turn = "player",
  actionType = null;
let timerInterval,
  timeLeft = 10,
  reveal = false;
let isMuted = false;

// Game statistics
let playerStats = { wins: 0, losses: 0 };
let botStats = { wins: 0, losses: 0 };

const templates = [
  "Apakah kau benar-benar yakin dengan keputusan ini?",
  "Sesuatu terasa tidak beres, masih mau lanjut?",
  "Pikirkan sekali lagi, mungkin ada jalan lain...",
];

const pistolImg = document.getElementById("pistolImg");
const btnSelf = document.getElementById("btnSelf");
const btnBot = document.getElementById("btnBot");
const playerStatus = document.getElementById("playerStatus");
const botStatus = document.getElementById("botStatus");
const turnIndicator = document.getElementById("turnIndicator");
const bgm = document.getElementById("bgm");
const muteBtn = document.getElementById("muteBtn");

function initChambers() {
  chambers = Array(6).fill(false);
  chambers[Math.floor(Math.random() * 6)] = true;
  currentIndex = 0;
  renderChambers();
}

function renderChambers() {
  const container = document.getElementById("chambers");
  container.innerHTML = "";
  chambers.forEach((hasBullet, i) => {
    const div = document.createElement("div");
    if (i === currentIndex) {
      div.className = "chamber active";
    } else if (reveal && hasBullet) {
      div.className = "chamber bullet";
    } else {
      div.className = "chamber";
    }
    container.appendChild(div);
  });
}

// Flags untuk bot
let botSmartMode = true; // fitur 1
let botSuicideLast = true; // fitur 2

function toggleBotSmart() {
  botSmartMode = !botSmartMode;
  document.getElementById("btnBotSmart").textContent =
    "Bot Smart Mode: " + (botSmartMode ? "ON" : "OFF");
}

function toggleBotSuicide() {
  botSuicideLast = !botSuicideLast;
  document.getElementById("btnBotSuicide").textContent =
    "Bot Suicide Last: " + (botSuicideLast ? "ON" : "OFF");
}

function updateProgress() {
  let percent = (timeLeft / 10) * 100;
  const bar = document.getElementById("turnProgressBar");
  bar.style.width = percent + "%";
  bar.style.background = turn === "player" ? "limegreen" : "orange";
}

function updateButtons() {
  btnSelf.disabled = turn !== "player";
  btnBot.disabled = turn !== "player";
}

function updateTurnIndicator() {
  turnIndicator.textContent =
    turn === "player" ? "PLAYER'S TURN" : "BOT'S TURN";
  turnIndicator.style.backgroundColor =
    turn === "player" ? "#3498db" : "#e74c3c";
}

function updateStats() {
  document.getElementById("playerWins").textContent = playerStats.wins;
  document.getElementById("playerLosses").textContent = playerStats.losses;
  const playerTotal = playerStats.wins + playerStats.losses;
  const playerRatio =
    playerTotal > 0 ? Math.round((playerStats.wins / playerTotal) * 100) : 0;
  document.getElementById("playerRatio").textContent = playerRatio + "%";

  document.getElementById("botWins").textContent = botStats.wins;
  document.getElementById("botLosses").textContent = botStats.losses;
  const botTotal = botStats.wins + botStats.losses;
  const botRatio =
    botTotal > 0 ? Math.round((botStats.wins / botTotal) * 100) : 0;
  document.getElementById("botRatio").textContent = botRatio + "%";
}

function showPistol(target) {
  pistolImg.src =
    target === "self"
      ? "https://www.gunsandammo.com/files/2014/12/Magnum_Research_Stainless_Desert_Eagle_F.jpg"
      : "https://images.guns.com/prod/2022/02/02/61fac8e727d7ad3eeb2d22fd6fe364b2bf4ae5f7b0556.jpg?imwidth=600";

  pistolImg.className = "fadeIn";

  if (target === "self") {
    pistolImg.classList.add("shake_scared");
  } else {
    pistolImg.classList.remove("shake_scared");
  }
}

function fadeOutPistol() {
  pistolImg.className = "fadeOut";
}

function closeModal() {
  document.getElementById("confirmModal").style.display = "none";
}

function restartGame() {
  document.getElementById("gameOverModal").style.display = "none";
  currentIndex = 0;
  turn = "player";
  playerStatus.textContent = "";
  botStatus.textContent = "";

  document.getElementById("playerHealth").style.width = "100%";
  document.getElementById("botHealth").style.width = "100%";

  initChambers();
  startTurn();
}

function toggleReveal() {
  reveal = !reveal;
  renderChambers();
}

function toggleMute() {
  isMuted = !isMuted;
  if (isMuted) {
    bgm.pause();
    muteBtn.textContent = "🔇";
  } else {
    bgm.play();
    muteBtn.textContent = "🔊";
  }
}

function startGame() {
  document.getElementById("welcomeModal").style.display = "none";
  bgm.volume = 0.5;
  bgm.play().catch((e) => console.log("Autoplay prevented:", e));
  initChambers();
  startTurn();
}

function advanceChamber() {
  currentIndex++;
  renderChambers();
}

btnSelf.addEventListener("mouseenter", () => {
  if (turn === "player") showPistol("self");
});
btnBot.addEventListener("mouseenter", () => {
  if (turn === "player") showPistol("bot");
});
btnSelf.addEventListener("mouseleave", () => {
  if (document.getElementById("confirmModal").style.display !== "flex")
    fadeOutPistol();
});
btnBot.addEventListener("mouseleave", () => {
  if (document.getElementById("confirmModal").style.display !== "flex")
    fadeOutPistol();
});

btnSelf.addEventListener("click", () => confirmAction("self"));
btnBot.addEventListener("click", () => confirmAction("bot"));

const confirmYes = document.getElementById("confirmYes");
function confirmAction(target) {
  if (turn !== "player") return;
  actionType = target;
  document.getElementById("confirmText").innerText =
    templates[Math.floor(Math.random() * templates.length)];
  document.getElementById("confirmModal").style.display = "flex";
}
confirmYes.addEventListener("click", () => {
  closeModal();
  btnSelf.disabled = true;
  btnBot.disabled = true;
  performAction("player", actionType);
});

function performAction(actor, target) {
  clearInterval(timerInterval);
  const isSelf = target === "self";
  const chamberHasBullet = chambers[currentIndex];

  pistolImg.src =
    actor === "player"
      ? isSelf
        ? "https://www.gunsandammo.com/files/2014/12/Magnum_Research_Stainless_Desert_Eagle_F.jpg"
        : "https://images.guns.com/prod/2022/02/02/61fac8e727d7ad3eeb2d22fd6fe364b2bf4ae5f7b0556.jpg?imwidth=600"
      : isSelf
      ? "https://images.guns.com/prod/2022/02/02/61fac8e727d7ad3eeb2d22fd6fe364b2bf4ae5f7b0556.jpg?imwidth=600"
      : "https://www.gunsandammo.com/files/2014/12/Magnum_Research_Stainless_Desert_Eagle_F.jpg";

  if (actor === "player")
    playerStatus.textContent = isSelf
      ? "Player menembak diri sendiri"
      : "Player menembak bot";
  else
    botStatus.textContent = isSelf
      ? "Bot menembak diri sendiri"
      : "Bot menembak player";

  pistolImg.className = "fadeIn";

  setTimeout(() => {
    if (chamberHasBullet) {
      document.getElementById("sfxShot").play();

      // Hapus class recoil lama
      pistolImg.classList.remove("recoil-kiri", "recoil-kanan");

      // Tentukan arah recoil sesuai actor & target
      if (actor === "player" && target === "self")
        pistolImg.classList.add("recoil-kiri");
      else if (actor === "player" && target === "bot")
        pistolImg.classList.add("recoil-kanan");
      else if (actor === "bot" && target === "self")
        pistolImg.classList.add("recoil-kanan");
      else if (actor === "bot" && target === "player")
        pistolImg.classList.add("recoil-kiri");

      if (isSelf) {
        if (actor === "player")
          document.getElementById("playerHealth").style.width = "0%";
        else document.getElementById("botHealth").style.width = "0%";
      } else {
        if (actor === "player")
          document.getElementById("botHealth").style.width = "0%";
        else document.getElementById("playerHealth").style.width = "0%";
      }

      setTimeout(() => pistolImg.classList.add("fadeOut"), 400);
      setTimeout(
        () =>
          gameOver(
            actor,
            isSelf
              ? actor === "player"
                ? "player"
                : "bot"
              : actor === "player"
              ? "bot"
              : "player"
          ),
        1000
      );
    } else {
      document.getElementById("sfxEmpty").play();
      advanceChamber();
      setTimeout(() => fadeOutPistol(), 500);

      if (isSelf) {
        // tetap giliran yang sama
        setTimeout(() => startTurn(), 1000);
      } else {
        // pindah giliran
        setTimeout(nextTurn, 1000);
      }
    }
  }, 600);
}

function botThink() {
  btnSelf.disabled = true;
  btnBot.disabled = true;
  botStatus.textContent = "(Bot sedang berfikir...)";
  let delay = Math.floor(Math.random() * 2000) + 1000;

  setTimeout(() => {
    let choice;

    // --- Kondisi baru: jika 2 chamber berikutnya ada peluru → bot tembak player
    if (
      chambers[(currentIndex + 1) % chambers.length] &&
      chambers[(currentIndex + 2) % chambers.length]
    ) {
      choice = "player";
    }
    // --- Fitur 2: kalau chamber berikutnya ada peluru → bot bunuh diri
    else if (botSuicideLast && chambers[(currentIndex + 1) % chambers.length]) {
      choice = "self";
    }
    // --- Fitur 1: kalau chamber saat ini ada peluru → bot tembak player
    else if (botSmartMode && chambers[currentIndex]) {
      choice = "player";
    }
    // --- Default random ---
    else {
      choice = Math.random() < 0.5 ? "self" : "player";
    }

    botStatus.textContent = `(Bot memilih menembak ${
      choice === "self" ? "dirinya sendiri" : "player"
    })`;

    setTimeout(() => performAction("bot", choice), 1000);
  }, delay);
}

function nextTurn() {
  turn = turn === "player" ? "bot" : "player";
  startTurn();
}

function gameOver(actor, victim) {
  setTimeout(() => {
    if (victim === "player") {
      playerStats.losses++;
      botStats.wins++;
    } else {
      playerStats.wins++;
      botStats.losses++;
    }
    updateStats();

    document.getElementById("gameOverText").textContent =
      victim === "player" ? "Bot Menang!" : "Player Menang!";
    document.getElementById("gameOverModal").style.display = "flex";
  }, 200);
}

function startTurn() {
  updateTurnIndicator();
  timeLeft = 10;
  updateProgress();

  // Tombol hanya aktif saat giliran player
  updateButtons();

  if (turn === "bot") botThink();

  timerInterval = setInterval(() => {
    timeLeft--;
    updateProgress();
    if (timeLeft <= 0) {
      clearInterval(timerInterval);
      if (turn === "player") {
        // player tidak memilih → otomatis tembak diri sendiri
        performAction("player", "self");
      } else {
        botThink();
      }
    }
  }, 1000);
}

updateStats();
