import { showModal } from "../module_js/show_modal.js";
import { closeModal } from "../module_js/close_modal.js";

// Dummy data untuk lobby
let lobbies = [
  {
    id: 1,
    name: "Lobby Pemula",
    host: "Player1",
    players: 1,
    maxPlayers: 2,
    status: "Menunggu",
    bet: 1000,
    mode: "pvp",
    created: "2024-01-15 14:30",
  },
  {
    id: 2,
    name: "High Stakes",
    host: "ProGamer",
    players: 2,
    maxPlayers: 2,
    status: "Penuh",
    bet: 10000,
    mode: "pvp",
    created: "2024-01-15 15:45",
  },
  {
    id: 3,
    name: "Quick Game",
    host: "SpeedRunner",
    players: 1,
    maxPlayers: 1,
    status: "Bermain",
    bet: 500,
    mode: "bot",
    created: "2024-01-15 16:20",
  },
];

let filteredLobbies = [...lobbies];
let currentPage = 1;
const itemsPerPage = 5;

// Initialize page
document.addEventListener("DOMContentLoaded", function () {
  renderLobbies();
  renderPagination();
  updateModeDisplay();

  // Event listeners
  const searchInput = document.getElementById("searchInput");
  if (searchInput) searchInput.addEventListener("keyup", searchLobbies);

  const gameMode = document.getElementById("gameMode");
  if (gameMode) gameMode.addEventListener("change", updateModeDisplay);

  const createLobbyForm = document.getElementById("createLobbyForm");
  if (createLobbyForm)
    createLobbyForm.addEventListener("submit", submitCreateLobby);
});

// Render lobby list
function renderLobbies() {
  const lobbyList = document.getElementById("lobbyList");
  const emptyState = document.getElementById("emptyState");

  if (filteredLobbies.length === 0) {
    lobbyList.innerHTML = "";
    emptyState.classList.remove("d-none");
    document.getElementById("pagination").innerHTML = "";
    return;
  }

  emptyState.classList.add("d-none");

  const startIndex = (currentPage - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;
  const paginatedLobbies = filteredLobbies.slice(startIndex, endIndex);

  lobbyList.innerHTML = paginatedLobbies
    .map((lobby) => {
      const statusClass =
        lobby.status === "Menunggu"
          ? "badge-green"
          : lobby.status === "Penuh"
          ? "badge-red"
          : "badge-yellow";

      const modeBadge =
        lobby.mode === "bot"
          ? '<span class="badge badge-purple badge-status bot-indicator ms-2">vs Bot</span>'
          : '<span class="badge badge-blue badge-status ms-2">vs Player</span>';

      const joinButton =
        lobby.status === "Menunggu" && lobby.players < lobby.maxPlayers
          ? `<button data-join-id="${lobby.id}" class="btn btn-red">
                <i class="fas fa-sign-in-alt me-2"></i>Bergabung
            </button>`
          : lobby.status === "Bermain" && lobby.mode === "bot"
          ? `<button data-join-id="${lobby.id}" class="btn btn-red">
                <i class="fas fa-play me-2"></i>Main
            </button>`
          : `<button class="btn btn-gray" disabled>
                ${lobby.status === "Penuh" ? "Penuh" : "Bermain"}
            </button>`;

      const playerInfo =
        lobby.mode === "bot"
          ? '<div class="d-flex align-items-center"><i class="fas fa-robot me-2 text-purple"></i>vs AI Bot</div>'
          : `<div class="d-flex align-items-center"><i class="fas fa-users me-2"></i>${lobby.players}/${lobby.maxPlayers} Pemain</div>`;

      return `
        <div class="lobby-card">
          <div class="row g-3 align-items-start">
            <div class="col-12 col-md">
              <div class="d-flex align-items-center mb-2">
                <h3 class="h5 fw-bold me-3 mb-0">${lobby.name}</h3>
                <span class="badge ${statusClass} badge-status">${
        lobby.status
      }</span>
                ${modeBadge}
              </div>
              <div class="row g-2 text-secondary small">
                <div class="col-12 col-sm-6">
                  <div class="d-flex align-items-center">
                    <i class="fas fa-user me-2"></i>
                    Host: ${lobby.host}
                  </div>
                </div>
                <div class="col-12 col-sm-6">${playerInfo}</div>
                <div class="col-12 col-sm-6">
                  <div class="d-flex align-items-center">
                    <i class="fas fa-coins me-2 text-warning"></i>
                    Bet: ${lobby.bet.toLocaleString("id-ID")}
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                  <div class="d-flex align-items-center">
                    <i class="fas fa-clock me-2"></i>
                    ${lobby.created}
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-auto">
              ${joinButton}
            </div>
          </div>
        </div>
      `;
    })
    .join("");

  // Re-attach join button listeners
  document.querySelectorAll("[data-join-id]").forEach((btn) => {
    btn.addEventListener("click", () =>
      joinLobby(parseInt(btn.dataset.joinId))
    );
  });
}

// Render pagination
function renderPagination() {
  const pagination = document.getElementById("pagination");
  const totalPages = Math.ceil(filteredLobbies.length / itemsPerPage);

  if (totalPages <= 1) {
    pagination.innerHTML = "";
    return;
  }

  let paginationHTML = `
    <button class="btn btn-gray ${currentPage === 1 ? "disabled" : ""}" 
            ${currentPage === 1 ? "disabled" : ""} data-page="${
    currentPage - 1
  }">
      <i class="fas fa-chevron-left"></i>
    </button>
  `;

  for (let i = 1; i <= totalPages; i++) {
    paginationHTML += `
      <button class="btn ${
        i === currentPage ? "btn-red" : "btn-gray"
      }" data-page="${i}">
        ${i}
      </button>
    `;
  }

  paginationHTML += `
    <button class="btn btn-gray ${
      currentPage === totalPages ? "disabled" : ""
    }" 
            ${currentPage === totalPages ? "disabled" : ""} data-page="${
    currentPage + 1
  }">
      <i class="fas fa-chevron-right"></i>
    </button>
  `;

  pagination.innerHTML = paginationHTML;

  pagination.querySelectorAll("[data-page]").forEach((btn) => {
    btn.addEventListener("click", () => changePage(parseInt(btn.dataset.page)));
  });
}

// Change page
function changePage(page) {
  const totalPages = Math.ceil(filteredLobbies.length / itemsPerPage);
  if (page >= 1 && page <= totalPages) {
    currentPage = page;
    renderLobbies();
    renderPagination();
    window.scrollTo({ top: 0, behavior: "smooth" });
  }
}

// Search lobbies
function searchLobbies() {
  const searchTerm = document.getElementById("searchInput").value.toLowerCase();
  filteredLobbies = lobbies.filter(
    (lobby) =>
      lobby.name.toLowerCase().includes(searchTerm) ||
      lobby.host.toLowerCase().includes(searchTerm)
  );
  currentPage = 1;
  renderLobbies();
  renderPagination();
}

// Update mode display
function updateModeDisplay() {
  const mode = document.getElementById("gameMode").value;
  const modeDisplay = document.getElementById("modeDisplay");
  if (!modeDisplay) return;

  modeDisplay.innerHTML =
    mode === "pvp"
      ? `<div class="mode-display d-flex justify-content-between align-items-center">
          <div>
            <div class="fw-semibold d-flex align-items-center">
              Player vs Player
              <span class="badge badge-blue badge-status ms-2">PVP</span>
            </div>
            <div class="small text-secondary">Duel antara dua pemain</div>
          </div>
          <div class="bg-blue-600 rounded-circle p-2">
            <i class="fas fa-user-friends"></i>
          </div>
        </div>`
      : `<div class="mode-display d-flex justify-content-between align-items-center">
          <div>
            <div class="fw-semibold d-flex align-items-center">
              Player vs Bot
              <span class="badge badge-purple badge-status bot-indicator ms-2">AI</span>
            </div>
            <div class="small text-secondary">Main melawan komputer</div>
          </div>
          <div class="bg-purple-600 rounded-circle p-2">
            <i class="fas fa-robot"></i>
          </div>
        </div>`;
}

// Submit create lobby form
function submitCreateLobby(e) {
  e.preventDefault();

  const lobbyName = document.getElementById("lobbyName").value;
  const lobbyBet = parseInt(document.getElementById("lobbyBet").value);
  const gameMode = document.getElementById("gameMode").value;

  if (lobbyBet < 10) {
    showToast("Taruhan minimal 10 chips!");
    return;
  }

  const newLobby = {
    id: lobbies.length + 1,
    name: lobbyName,
    host: "You",
    players: 1,
    maxPlayers: gameMode === "bot" ? 1 : 2,
    status: gameMode === "bot" ? "Bermain" : "Menunggu",
    bet: lobbyBet,
    mode: gameMode,
    created: new Date().toLocaleString("id-ID", {
      day: "2-digit",
      month: "2-digit",
      year: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    }),
  };

  lobbies.unshift(newLobby);
  filteredLobbies = [...lobbies];
  currentPage = 1;

  renderLobbies();
  renderPagination();

  closeModal("createLobbyModal");

  showToast(
    `Lobby "${lobbyName}" berhasil dibuat! ${
      gameMode === "bot" ? "Mulai bermain" : "Menunggu pemain lain bergabung"
    }.`
  );
}

// Join lobby
function joinLobby(lobbyId) {
  const lobby = lobbies.find((l) => l.id === lobbyId);
  if (lobby) {
    if (lobby.mode === "bot") {
      showToast(`Memulai permainan melawan bot di lobby: ${lobby.name}`);
    } else {
      showToast(`Bergabung ke lobby: ${lobby.name}`);
    }
  }
}

// Show toast notification
function showToast(message) {
  const toast = document.getElementById("toast");
  toast.textContent = message;
  toast.classList.add("show");

  setTimeout(() => toast.classList.remove("show"), 3000);
}
