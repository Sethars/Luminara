import { fetchWithAuth } from "../module_js/fetch_with_auth.js";

// Function to reload/randomize players
document.getElementById("reloadPlayers").addEventListener("click", function () {
  const playersContainer = document.getElementById("playersContainer");
  const playerNames = [
    "PlayerOne",
    "LuckyGambler",
    "CasinoKing",
    "HighRoller",
    "ChipMaster",
    "RoyalFlush",
    "Jackpot",
    "LuckySeven",
    "BigWinner",
    "AceHigh",
  ];
  const statuses = ["status-online", "status-offline"];
  const statusTexts = [
    "Today",
    "Yesterday",
    "2 days ago",
    "3 days ago",
    "1 week ago",
    "2 weeks ago",
    "1 month ago",
  ];

  // Clear current players
  playersContainer.innerHTML = "";

  // Generate 5 random players
  for (let i = 0; i < 5; i++) {
    const randomName =
      playerNames[Math.floor(Math.random() * playerNames.length)];
    const randomChips = Math.floor(Math.random() * 50000) + 1000;
    const randomStatus = Math.random() > 0.3 ? statuses[0] : statuses[1];
    const randomStatusText =
      statusTexts[Math.floor(Math.random() * statusTexts.length)];

    const playerCard = document.createElement("div");
    playerCard.className = "player-card";
    playerCard.innerHTML = `
                    <div class="player-body">
                        <div class="player-avatar">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                        <div class="player-info">
                            <div class="player-name">
                                ${randomName}
                                <span class="player-status ${randomStatus}"></span>
                            </div>
                            <div class="player-chips">
                                <i class="fas fa-coins"></i>
                                ${randomChips.toLocaleString()}
                            </div>
                        </div>
                    </div>
                    <div class="player-footer">
                        Last login: ${randomStatusText}
                    </div>
                `;

    playersContainer.appendChild(playerCard);
  }

  // Add animation effect
  const cards = playersContainer.querySelectorAll(".player-card");
  cards.forEach((card, index) => {
    card.style.opacity = "0";
    card.style.transform = "translateY(20px)";

    setTimeout(() => {
      card.style.transition = "opacity 0.3s, transform 0.3s";
      card.style.opacity = "1";
      card.style.transform = "translateY(0)";
    }, index * 100);
  });
});

const delayBetween = 1000;

async function loadAnnouncements() {
  const container = document.getElementById("announcementContent");

  try {
    // pastikan fetchWithAuth ada, atau ganti dengan fetch biasa
    const res = await fetchWithAuth("api/getAnnouncements");
    const data = await res.json();

    // kalau gagal, tampilkan pesan error
    if (!data.success) {
      container.innerHTML = data.message || "Tidak ada pengumuman.";
      return;
    }

    // cek apakah ada array announcements
    if (!Array.isArray(data.data) || data.data.length === 0) {
      container.innerHTML = "Tidak ada pengumuman.";
      return;
    }
    // buat ticker: gabungkan semua pesan jadi satu
    const messages = data.data.map((a) => a.pesan); // aman karena sudah dicek array
    startTicker(messages);
  } catch (err) {
    console.error("Gagal load announcement:", err);
    container.innerHTML = "Terjadi kesalahan saat memuat pengumuman.";
  }
}

function startTicker(messages) {
  const container = document.getElementById("announcementContent");
  const ticker = document.getElementById("announcementTicker");
  let currentIndex = 0;

  function showMessage() {
    container.innerHTML = messages[currentIndex]; // tetap pakai innerHTML

    const newHeight = container.scrollHeight;
    ticker.style.height = newHeight + "px";

    container.style.transition = "none";
    container.style.transform = `translateX(${window.innerWidth}px)`;
    container.offsetHeight; // reflow

    const duration = Math.max(10, container.textContent.length / 2);
    container.style.transition = `transform ${duration}s linear`;
    container.style.transform = `translateX(-${container.scrollWidth}px)`;

    container.addEventListener(
      "transitionend",
      (e) => {
        if (e.propertyName === "transform") {
          setTimeout(() => {
            currentIndex = (currentIndex + 1) % messages.length;
            showMessage();
          }, delayBetween);
        }
      },
      { once: true }
    );
  }

  showMessage();
}

// mulai load dari backend
loadAnnouncements();
