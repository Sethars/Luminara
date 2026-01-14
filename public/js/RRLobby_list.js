import { showModal } from "../module_js/show_modal.js";
import { closeModal } from "../module_js/close_modal.js";
import { Encoder } from "../module_js/encrypt.js";
import { fetchWithAuth } from "../module_js/fetch_with_auth.js";
import { getQueryParam } from "../module_js/get_query.js";

const encoder = new Encoder();

//elemnt DOM
const searchInput = document.getElementById("searchInput");
const refreshButton = document.getElementById("btn-refresh");
const refreshIcon = document.getElementById("refresh-icon");
const gameMode = document.getElementById("gameMode");
const createLobbyForm = document.getElementById("createLobbyForm");

//data dari query
const action = getQueryParam("action");
const newMode = getQueryParam("mode");
const newBet = getQueryParam("bet");

// data kosong untuk lobby
let lobbies = [];
let filteredLobbies = [];
let currentPage = 1;
const itemsPerPage = 5;

// Initialize page
document.addEventListener("DOMContentLoaded", function () {
  //ambil data dan render
  getLobbiesList();

  // Event listeners
  if (searchInput) searchInput.addEventListener("keyup", searchLobbies);
  if(refreshButton) refreshButton.addEventListener("click", getLobbiesList);
  if (gameMode) gameMode.addEventListener("change", updateModeDisplay);
  if (createLobbyForm){
    createLobbyForm.addEventListener("submit", submitCreateLobby);
  }

  //action dari query param
  if(action === "addLobby"){
    showModal("createLobbyModal");
    gameMode.value = newMode;
    document.getElementById("lobbyBet").value = newBet;
  }
});

// get data lobby
function getLobbiesList() {
  refreshIcon.classList.add("spin");
  refreshButton.disabled = true;

  fetch("api/getLobbiesList")
    .then(res => res.json())
    .then(data => {
      if (!data.success) {
        showToast(data.message);
      }
      lobbies = data.lobbies;
      filteredLobbies = [...lobbies];
      renderLobbies();
      renderPagination();
      updateModeDisplay();
    })
    .catch(() => {
      showToast("Gagal mengambil data lobbies");
    })
    .finally(() => {
      refreshIcon.classList.remove("spin");
      refreshButton.disabled = false;
    });
}

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
          ? `<button data-slug="${encoder.encode(`name=${lobby.name}&id=${lobby.id}&mode=${lobby.mode}`)}" data-name="${lobby.name}" class="btn btn-red">
                <i class="fas fa-sign-in-alt me-2"></i>Bergabung
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
                <span class="badge ${statusClass} badge-status">${lobby.status}</span>
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
                    Bet: ${lobby.bet}
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
  document.querySelectorAll("[data-slug]").forEach((btn) => {
    btn.addEventListener("click", () =>
      joinLobby(btn.dataset.slug, btn.dataset.name)
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
  const lobbyNameDisplay = document.getElementById("lobby-name");
  const lobbyName = document.getElementById("lobbyName");
  const mode = document.getElementById("gameMode").value;
  const modeDisplay = document.getElementById("modeDisplay");
  const passwordDisplay = document.getElementById("passwordDisplay");
  const button = document.getElementById("createLobbyButton");
  if (!modeDisplay) return;

  if(mode !== "pvp") {
    lobbyName.removeAttribute("required");
    lobbyNameDisplay.classList.add("d-none");
    passwordDisplay.classList.add("d-none");
  } else {
    lobbyName.setAttribute("required", "required");
    lobbyNameDisplay.classList.remove("d-none");
    passwordDisplay.classList.remove("d-none");
  }

  if(mode === "") {
    modeDisplay.classList.add("d-none");
    button.disabled = true;
  } else {
    modeDisplay.classList.remove("d-none");
    button.disabled = false;
  }
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
  const password = document.getElementById("lobbyPassword").value;

  if (lobbyBet < 10) {
    showToast("Taruhan minimal 10 chips!");
    return;
  }

  const name = gameMode === "bot" ? "LOBBY VS BOT" : lobbyName

  fetchWithAuth('api/addLobby', {
    method: "POST",
    header: {"Content-Type" : "application/json"},
    body: JSON.stringify({name, gameMode, lobbyBet, password})
  })
  .then(res => res.json())
  .then(data => {
    if(data.success){
      if(data.lobby && gameMode === "pvp"){
        lobbies.unshift(data.lobby);
        filteredLobbies = [...lobbies];
        currentPage = 1;

        renderLobbies();
        renderPagination();
      }

      closeModal("createLobbyModal");

      showToast(
        `Lobby "${lobbyName}" berhasil dibuat! ${
          gameMode === "bot" ? "Mulai bermain" : "Menunggu pemain lain bergabung"
        }.`
      );

      const lobbyId = data.lobby.id;

      if(gameMode === "bot"){
        setTimeout(() => window.location.href=`games/russian-roulette?lobby=${encoder.encode(`name=${lobbyName}&id=${data.lobby}&mode=${gameMode}`)}`, 500)
      } else {
        setTimeout(() => window.location.href=`russian-roulette-lobby?lobby=${encoder.encode(`name=${lobbyName}&id=${lobbyId}&mode=${gameMode}`)}`, 500)
      }
    }
  })
  .catch(() => {});
}

// Join lobby
function joinLobby(slug, name) {
  const decodedSlug = encoder.decode(slug);
  const id = new URLSearchParams(decodedSlug).get('id');

  fetch("api/validateJoinLobby", {
    method: "POST",
    header: {"Content-Type" : "application/json"},
    body: JSON.stringify({id})
  })
  .then(res => res.json())
  .then(data => {
    if(!data.success){
      showToast(data.message)
      return;
    }
    
    if(!data.password){
      redirectLobby(id, slug, name);
      return;
    }

    showModal("password-modal");
    document.getElementById("join-private-lobby").addEventListener("click", function (){
      const password = document.getElementById("password-lobby").value;
      const msg = document.getElementById("msg");
      console.log(data)

      msg.textContent = "";
      msg.className = "";

      if(password !== data.password){
        msg.textContent = "Password salah";
        msg.className = "text-danger";
        return;
      }
      
      closeModal("password-modal")
      redirectLobby(id, slug, name);
    })
  })
}

function redirectLobby(id, slug, name) {
  fetchWithAuth("api/joinLobby", {
    method: "POST",
    header: {"Content-Type" : "application/json"},
    body: JSON.stringify({id})
  })
  .then(res => res.json())
  .then(data => {
    if(!data.success){
      showToast(data.message);
      return;
    }

  showToast(`Bergabung ke lobby: ${name}`);
  setTimeout(() => window.location.href = `russian-roulette-lobby?lobby=${slug}`, 500)
  })
  .catch(() => {});
}

// Show toast notification
function showToast(message) {
  const toast = document.getElementById("toast");
  toast.textContent = message;
  toast.classList.add("show");

  setTimeout(() => toast.classList.remove("show"), 3000);
}
