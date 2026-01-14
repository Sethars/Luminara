import { fetchWithAuth } from "../module_js/fetch_with_auth.js";
import { formatMoney } from "../module_js/format_money.js";

async function loadPlayers() {
  const playersContainer = document.getElementById("playersContainer");
  playersContainer.innerHTML = "";

  try {
    const res = await fetchWithAuth("api/getRandomUser");
    const data = await res.json();

    if (!data.success || !Array.isArray(data.data)) {
      playersContainer.innerHTML = `<div class="text-center text-muted">Tidak ada pemain ditemukan.</div>`;
      return;
    }

    data.data.forEach((player, index) => {
      // Status dummy biar ada indikator
      const statusClass =
        player.gender === "male" ? "status-online" : "status-offline";
      const statusText =
        player.gender === "male" ? "Baru saja online" : "Kemarin";

      const avatarSrc = player.photo
        ? player.photo
        : "/assets/img/photo_profile/ppkosong.jpg";

      const playerCard = document.createElement("div");
      playerCard.className = "player-card";
      playerCard.innerHTML = `
        <div class="player-body">
          <div class="player-avatar">
            <img src="${avatarSrc}" alt="${player.username}" class="avatar-img">
          </div>
          <div class="player-info">
            <div class="player-name">
              ${player.username}
              <span class="player-status ${statusClass}"></span>
            </div>
            <div class="player-chips">
              <i class="fa fa-money"></i>
              ${formatMoney(player.cash)}
            </div>
          </div>
        </div>
        <div class="player-footer">
          <span class="last-login">Last login: ${statusText}</span>
          <a href="/aprofile?id=${
            player.user_id
          }" class="btn-visit">Kunjungi</a>
        </div>
      `;

      playersContainer.appendChild(playerCard);

      // animasi masuk
      playerCard.style.opacity = "0";
      playerCard.style.transform = "translateY(20px)";
      setTimeout(() => {
        playerCard.style.transition = "opacity 0.3s, transform 0.3s";
        playerCard.style.opacity = "1";
        playerCard.style.transform = "translateY(0)";
      }, index * 100);
    });
  } catch (err) {
    console.error("Gagal load player:", err);
    playersContainer.innerHTML = `<div class="text-center text-danger">Error memuat pemain.</div>`;
  }
}

// Load otomatis saat page dimuat
document.addEventListener("DOMContentLoaded", loadPlayers);

document.getElementById("reloadPlayers").addEventListener("click", () => {
  loadPlayers();
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

const userEventsContainer = document.getElementById("userEventsContainer");

// Format tanggal GMT+7
function formatDateUser(dateString) {
  const options = {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
    timeZone: "Asia/Jakarta",
  };
  return new Date(dateString).toLocaleString("id-ID", options);
}

// Hitung status event (untuk badge atau expired)
function getEventStatusUser(endDate) {
  const now = new Date();
  const end = new Date(endDate);
  if (end < now) return "expired";
  return "active";
}

// Load event untuk user
async function loadUserEvents() {
  if (!userEventsContainer) return;

  try {
    const res = await fetchWithAuth("api/getEventsUsr");
    const data = await res.json();

    if (!data.success || !Array.isArray(data.data)) {
      userEventsContainer.innerHTML = `<p class="text-center text-white">Tidak ada event.</p>`;
      return;
    }

    renderUserEvents(data.data);
  } catch (err) {
    console.error("Error loadUserEvents:", err);
    userEventsContainer.innerHTML = `<p class="text-center text-danger">Gagal memuat event.</p>`;
  }
}

// Render event cards
function renderUserEvents(events) {
  userEventsContainer.innerHTML = "";

  if (events.length === 0) {
    userEventsContainer.innerHTML = `<p class="text-center text-white">Tidak ada event.</p>`;
    return;
  }

  events.forEach((ev) => {
    const status = getEventStatusUser(ev.end_at);
    const isVip = ev.vip == 1; // 1 = VIP
    const claimed = ev.claimed || false; // pastikan dari backend ada flag claimed

    const card = document.createElement("div");
    card.className = `event-card ${status} ${claimed ? "claimed" : ""}`;

    card.innerHTML = `
          <div class="event-header">
              ${ev.nama} ${isVip ? '<span class="vip-label">VIP</span>' : ""}
          </div>
          <div class="event-body">
              <div class="event-prize">
                  <i class="fas fa-coins"></i> ${ev.reward_chips.toLocaleString()} Chips
              </div>
              <div class="event-description">${ev.pesan || "-"}</div>
          </div>
          <div class="event-footer">
              <button class="claim-btn" ${
                status === "expired" || claimed ? "disabled" : ""
              } data-id="${ev.id}">
                  ${
                    status === "expired"
                      ? "Expired"
                      : claimed
                      ? "Claimed"
                      : "Claim"
                  }
              </button>
              <div class="expiry-date">Expires: ${formatDateUser(
                ev.end_at
              )}</div>
          </div>
          ${claimed ? '<div class="claimed-overlay">Sudah diklaim</div>' : ""}
      `;

    userEventsContainer.appendChild(card);
  });

  // Attach claim button click
  document.querySelectorAll(".claim-btn").forEach((btn) => {
    btn.addEventListener("click", () => claimEvent(btn.dataset.id, btn));
  });
}

// Claim event (fetch ke backend jika perlu)
async function claimEvent(id, btn) {
  if (!confirm("Yakin ingin klaim event ini?")) return;

  try {
    const res = await fetchWithAuth("api/claimEvent", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id }),
    });
    const data = await res.json();

    if (data.success) {
      btn.textContent = "Claimed";
      btn.disabled = true;
      alert(data.message);
      window.location.reload();
    } else {
      alert(data.message || "Gagal klaim event");
      window.location.reload();
    }
  } catch (err) {
    console.error(err);
    alert("Terjadi kesalahan saat klaim event");
  }
}

document.addEventListener("DOMContentLoaded", loadUserEvents);
