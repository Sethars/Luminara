import { fetchWithAuth } from "../../module_js/fetch_with_auth.js";

//import bagian logic wajib
import { renderChambers, updateProgress, updateButtons, updateTurnIndicator, closeModal } from "./modules/updateDisplay.js";
import { showPistol, fadeOutPistol } from "./modules/animationGun.js";
import { restartGame } from "./modules/menuGame.js";

export const pvp = (() => {
  //default value
  let bet = 0;
  let chambers = [];
  let currentIndex = 0;
  let turn = "player-1";
  let actionType = null;
  let timerInterval;
  let timeLeft = 20;
  let reveal = false;
  let canLeave = false;

  const templates = [
    "klo gw sih ga yakin",
    "jangan si klo kata gw",
    "mending satunya dah",
    "salah ini mah",
    "salah langkah mokad loh",
    "yakin ga nih",
  ];

  //element dom
  const pistolImg = document.getElementById("pistolImg");
  const btnSelf = document.getElementById("btnSelf");
  const btnEnemy = document.getElementById("btnEnemy");
  const playerStatus = document.getElementById("playerStatus");
  const enemyStatus = document.getElementById("enemyStatus");
  const bgm = document.getElementById("bgm");
  const confirmYes = document.getElementById("confirmYes");

  function checkLeave(canLeave){
    if(!canLeave){
      e.preventDefault();
      e.returnValue = "Kamu yakin mau keluar dari halaman ini?";
      return e.returnValue;
    }
  }

  function initDisplayPvp(id){
    window.addEventListener("beforeunload", checkLeave)

    //initiate data and display
    document.getElementById("welcomeModal").style.display = "none";
    verifyDataGame(id);
    document.getElementById("player-photo").src = JSON.parse(localStorage.getItem("profile")).photo;
    document.getElementById("player-name").textContent = JSON.parse(localStorage.getItem("user")).username;

    //event listener
    document.getElementById("startGameBtn").addEventListener("click", function(){
      startGame(id);
    });
    document.getElementById("play-again").addEventListener("click", playAgain);
    document.getElementById("new-game").addEventListener("click", playAgain);
    document.getElementById("revealBtn").addEventListener("click", toggleReveal);

    //btn tembak
    btnSelf.addEventListener("mouseenter", () => {
      if (turn === "player" && btnSelf.disabled === false) showPistol(pistolImg, "self");
    });
    btnEnemy.addEventListener("mouseenter", () => {
      if (turn === "player" && btnEnemy.disabled === false) showPistol(pistolImg, "enemy");
    });
    btnSelf.addEventListener("mouseleave", () => {
      if (document.getElementById("confirmModal").style.display !== "flex")
        fadeOutPistol(pistolImg);
    });
    btnEnemy.addEventListener("mouseleave", () => {
      if (document.getElementById("confirmModal").style.display !== "flex")
        fadeOutPistol(pistolImg);
    });

    btnSelf.addEventListener("click", () => confirmAction("self"));
    btnEnemy.addEventListener("click", () => confirmAction("bot"));

    confirmYes.addEventListener("click", () => {
      closeModal();
      if (turn !== "player") return; //kalau giliran udah lewat skip

      btnSelf.disabled = true;
      btnEnemy.disabled = true;
      performAction("player", actionType);
    });
  }

  function detailPlayers(){
    
  }

  function playAgain(){
    restartGame(bet, "pvp");
  }

  function verifyDataGame(id){
    fetchWithAuth("../api/beforeStartGame", {
      method: "POST",
      headers: {"Content-Type" : "application/json"},
      body: JSON.stringify({id})
    })
    .then(res => res.json())
    .then(data => {
      if(data.reddirect_modal && !data.success){
        document.getElementById("reddirectModal").style.display = "flex";
        document.getElementById("reddirectText").textContent = data.message;
        return;
      }

      if (!data.success || !data.game || !data.game.bullets) {
        if (!canLeave) {
          alert("Terjadi kesalahan saat memuat data game. Kamu akan dikembalikan ke lobby.");
          window.location.href = "/RRLobby_list";
        } else {
          window.location.reload();
        }
        return;
      }

      bet = data.game.bet;
      chambers = JSON.parse(data.game.bullets);
      currentIndex = data.chamber_index;
      document.querySelectorAll(".bet-amount-text").forEach(betAmount => {
        betAmount.textContent = bet;
      })
    })
    .catch(() => {});
  }

  function toggleReveal() {
    reveal = !reveal;
    renderChambers(chambers, currentIndex, reveal);
  }

  function startGame(id) {
    fetchWithAuth("../api/startGameVsBot", {
      method: "POST",
      headers: {"Content-Type" : "application/json"},
      body: JSON.stringify({id})
    })
    .then(res => res.json())
    .then(data => {
      if(!data.success){
        document.getElementById("reddirectModal").style.display = "flex";
        document.getElementById("reddirectText").textContent = data.message;
        return;
      }
      canLeave = false;
      document.getElementById("welcomeModal").style.display = "none";
      bgm.volume = 0.5;
      bgm.play().catch((e) => console.log("Autoplay prevented:", e));
      renderChambers(chambers, currentIndex, reveal);
      startTurn();
    })
    .catch(() => {});
  }

  function advanceChamber() {
    currentIndex = (currentIndex + 1) % chambers.length;
    renderChambers(chambers, currentIndex, reveal);
  }

  function confirmAction(target) {
    if (turn !== "player") return; // hanya player yg bisa buka modal
    actionType = target;
    document.getElementById("confirmText").innerText =
      templates[Math.floor(Math.random() * templates.length)];
    document.getElementById("confirmModal").style.display = "flex"; // buka modal
  }

  function performAction(actor, target) {
    clearInterval(timerInterval);
    const isSelf = target === "self";
    const chamberHasBullet = chambers[currentIndex];

    // Default pistol (sebelum cek peluru)
    pistolImg.src =
      actor === "player"
        ? isSelf
          ? "../../assets/img/roulette/kiri.png"
          : "../../assets/img/roulette/kanan.png"
        : isSelf
        ? "../../assets/img/roulette/kanan.png"
        : "../../assets/img/roulette/kiri.png";

    if (actor === "player")
      playerStatus.textContent = isSelf
        ? "Anda memilih menembak diri Anda sendiri"
        : "";
    // Anda menembak bot
    else
      enemyStatus.textContent = isSelf
        ? "Bot menembak diri sendiri"
        : "Bot menembak player";

    pistolImg.className = "fadeIn";

    setTimeout(() => {
      if (chamberHasBullet) {
        document.getElementById("sfxShot").play();

        // 🔥 Ganti gambar ke versi "dor"
        if (actor === "player" && target === "self")
          pistolImg.src = "../../assets/img/roulette/dor-kiri.png";
        else if (actor === "player" && target === "bot")
          pistolImg.src = "../../assets/img/roulette/dor-kanan.png";
        else if (actor === "bot" && target === "self")
          pistolImg.src = "../../assets/img/roulette/dor-kanan.png";
        else if (actor === "bot" && target === "player")
          pistolImg.src = "../../assets/img/roulette/dor-kiri.png";

        // Hapus class recoil lama
        pistolImg.classList.remove("recoil-kiri", "recoil-kanan");

        // Tambah recoil sesuai arah
        if (actor === "player" && target === "self")
          pistolImg.classList.add("recoil-kiri");
        else if (actor === "player" && target === "bot")
          pistolImg.classList.add("recoil-kanan");
        else if (actor === "bot" && target === "self")
          pistolImg.classList.add("recoil-kanan");
        else if (actor === "bot" && target === "player")
          pistolImg.classList.add("recoil-kiri");

        // Kurangi HP
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
        // Kalau kosong
        document.getElementById("sfxEmpty").play();
        advanceChamber();
        setTimeout(() => fadeOutPistol(pistolImg), 500);

        if (isSelf) {
          setTimeout(() => startTurn(), 1000); // tetap giliran yang sama
        } else {
          setTimeout(nextTurn, 1000); // pindah giliran
        }
      }
    }, 600);
  }

  function nextTurn() {
    turn = turn === "player" ? "bot" : "player";
    startTurn();
  }

  function gameOver(actor, victim) {
    if (timerInterval) {
      clearInterval(timerInterval);
      timerInterval = null;
    }

    setTimeout(() => {
      fetchWithAuth("../api/gameOverVsBot", {
        method: "POST",
        headers: {"Content-Type" : "application/json"},
        body: JSON.stringify({id, victim})
      })
      .then(res => res.json())
      .then(data => {
        if(data.success){
          document.getElementById("gameOverText").textContent =
            data.win ? "Anda Menang!" : "Bot Menang!";
          document.getElementById("gameOverInfo").textContent =
            data.win ? `Anda memenangkan ${data.getOrLose} chip` : `Anda kehilangan ${data.getOrLose} chip`;
          document.getElementById("gameOverModal").style.display = "flex";
          canLeave = true;
        }
      })
      .catch(() => {});
    }, 200);
  }

  function startTurn() {
    if (timerInterval) clearInterval(timerInterval);

    updateTurnIndicator(turn);
    timeLeft = 20;
    updateProgress(timeLeft, turn);

    // Tombol hanya aktif saat giliran player
    updateButtons(turn);

    if (turn === "bot");

    timerInterval = setInterval(() => {
      timeLeft--;
      updateProgress(timeLeft, turn);
      if (timeLeft <= 0) {
        clearInterval(timerInterval);

        if (turn === "player") {
          // kalau modal masih kebuka → tutup
          if (document.getElementById("confirmModal").style.display === "flex") {
            closeModal();
          }

          fadeOutPistol(pistolImg);
          playerStatus.textContent = "Waktu habis — giliran pindah ke Bot";
          btnSelf.disabled = true;
          btnEnemy.disabled = true;

          setTimeout(nextTurn, 300);
        } else {
          // botThink();
        }
      }
    }, 1000);
  }

  return { initDisplayPvp }
})();