import { fetchWithAuth } from "../module_js/fetch_with_auth.js";
import { debounce } from "../module_js/debounce.js";

// element DOM
const photoProfile = document.getElementById("photo-profile-admin");
const username = document.getElementById("username-admin");
const tableUser = document.getElementById("recentUsersTableBody");
const tableLottery = document.getElementById("recentLotteryTableBody");

// Toggle open
document
  .getElementById("mobileMenuToggle")
  .addEventListener("click", function () {
    document.getElementById("sidebar").classList.toggle("active");
  });

// Close sidebar
document.getElementById("closeSidebar").addEventListener("click", function () {
  document.getElementById("sidebar").classList.remove("active");
});

// admin_panel.js
document.addEventListener("DOMContentLoaded", async function () {
  try {
    const res = await fetchWithAuth("api/getAdminData");
    const data = await res.json();
    if (!data.success) {
      console.error(data.message || "Gagal mengambil data");
      if (data.error === 401) {
        window.location.href = "/401";
      }
    }
    // render data admin
    photoProfile.src = JSON.parse(localStorage.getItem("profile")).photo
    username.textContent = JSON.parse(localStorage.getItem("user")).username

    // render total user
    document.getElementById("total-user").textContent = data.totalUser;

    // render table users
    renderTable(data.users, tableUser);

    // render table lottery
    renderTable(data.lottery, tableLottery, false);

    // hitung harga tiket
    const inputPrizes = document.getElementById("prizes").addEventListener(
      "input",
      debounce((e) => {
        calcTicketPrice(e);
      }, 300)
    );

    // tambah event lottery
    document
      .getElementById("lottery-form")
      .addEventListener("submit", addLotteryEvent);

    menuItems.forEach((item) => {
      item.addEventListener("click", (e) => {
        e.preventDefault();
        const sec = item.getAttribute("data-section");
        if (sec) showSection(sec);
      });
    });

    // default
    showSection("dashboard");
  } catch (err) {
    console.error(err);
  }
});

// hanya pilih menu-item yang punya data-section (exclude logout/link eksternal)
const menuItems = document.querySelectorAll(
  ".sidebar-menu .menu-item[data-section]"
);
const sections = {
  dashboard: document.getElementById("admin_dashboard"),
  users: document.getElementById("admin_users_dashboard"),
  lottery: document.getElementById("admin_lottery_dashboard"),
  announcement: document.getElementById("admin_announcement_dashboard"),
  event: document.getElementById("admin_event_dashboard"),
};

function showSection(section) {
  Object.values(sections).forEach((el) => el && (el.style.display = "none"));
  if (sections[section]) sections[section].style.display = "block";

  // update active class hanya untuk yang punya data-section
  document
    .querySelectorAll(".sidebar-menu .menu-item")
    .forEach((item) => item.classList.remove("active"));
  const activeItem = document.querySelector(
    `.sidebar-menu .menu-item[data-section="${section}"]`
  );
  if (activeItem) activeItem.classList.add("active");
}

function renderTable(items, tableBody, action = true) {
  tableBody.innerHTML = "";

  items.forEach((item) => {
    const row = document.createElement("tr");

    // Loop semua key dalam object user
    Object.entries(item).forEach(([key, value]) => {
      const cell = document.createElement("td");
      const span = document.createElement("span");

      span.classList.add(key);

      if (typeof value === "string") {
        const safeValue = value
          .toLowerCase()
          .replace(/\s+/g, "-")
          .replace(/[^a-z0-9\-_]/g, "");
        if (safeValue) {
          span.classList.add(`${key}-${safeValue}`);
        }
      }

      span.textContent = value;

      cell.appendChild(span);
      row.appendChild(cell);
    });

    if (action) {
      // Tambahin kolom actions di ujung
      const actionCell = document.createElement("td");
      actionCell.innerHTML = `
        <div class="table-actions">
          <button class="action-btn edit" title="Edit">
            <i class="fas fa-edit"></i>
          </button>
        </div>
      `;
      row.appendChild(actionCell);
    }

    tableBody.appendChild(row);
  });
}

// hitung harga tiket
function calcTicketPrice(e) {
  const ticketPrice = parseInt(e.target.value) / 10;

  const ticketPriceInput = document.getElementById("ticketPrice");
  ticketPriceInput.placehorder = ticketPrice;
  ticketPriceInput.value = ticketPrice;
}

// tambah event lottery
function addLotteryEvent(e) {
  e.preventDefault();

  const form = e.target;
  const fd = new FormData(form);

  const prizes = parseInt(fd.get("prizes"));
  const ticketPrice = prizes / 10;

  fd.append("ticketPrice", ticketPrice);

  const obj = {};
  fd.forEach((value, key) => {
    obj[key] = value;
  });

  console.log(obj);

  fetchWithAuth("api/addLotteryEvent", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(obj),
  })
    .then((res) => res.json())
    .then((data) => {
      console.log(data);
      if (data.success) {
        renderTable(data.lottery, tableLottery, false);
      } else {
        console.error(data.message || "Anda tidak memiliki akses");
        if (data.error === 401) {
          window.location.href = "/401";
        }
      }
    });
}

// fungsi search
function handleSearch(query) {
  query = query.toLowerCase();
  const filtered = recentUsers.filter((user) =>
    Object.values(user).some((val) => String(val).toLowerCase().includes(query))
  );
  renderTable(filtered);
}

// event listener dengan debounce
const searchInput = document.getElementById("searchInput");
searchInput.addEventListener(
  "keyup",
  debounce((e) => {
    handleSearch(e.target.value);
  }, 300)
);

const announcementList = document.getElementById("announcementList");
const emptyState = document.getElementById("emptyState");
const modal = document.getElementById("announcementModal");
const deleteModal = document.getElementById("deleteModal");

const modalTitle = document.getElementById("modalTitle");
const announcementIdInput = document.getElementById("announcementId");
const pesanInput = document.getElementById("pesan");
const saveBtn = document.getElementById("saveBtn");

let deleteId = null;

// load announcements
async function loadAnnouncements() {
  const res = await fetchWithAuth("api/getAnnouncements");
  const data = await res.json();

  if (data.success && data.data.length > 0) {
    emptyState.style.display = "none";
    announcementList.innerHTML = "";

    data.data.forEach((item) => {
      const div = document.createElement("div");
      div.className = "announcement-item";
      div.innerHTML = `
        <div class="announcement-content">
          <p>${item.pesan}</p>
          <small>Dibuat: ${item.created_at}</small>
        </div>
        <div class="announcement-actions">
          <button class="btn btn-sm btn-edit" data-id="${item.id}" data-pesan="${item.pesan}">
            <i class="fas fa-edit"></i>
          </button>
          <button class="btn btn-sm btn-delete" data-id="${item.id}">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      `;
      announcementList.appendChild(div);
    });

    // attach events
    document.querySelectorAll(".btn-edit").forEach((btn) => {
      btn.addEventListener("click", () => {
        openModal("edit", btn.dataset.id, btn.dataset.pesan);
      });
    });

    document.querySelectorAll(".btn-delete").forEach((btn) => {
      btn.addEventListener("click", () => {
        deleteId = btn.dataset.id;
        deleteModal.style.display = "block";
      });
    });
  } else {
    announcementList.innerHTML = "";
    emptyState.style.display = "block";
  }
}

// open modal (add / edit)
function openModal(mode, id = null, pesan = "") {
  if (mode === "add") {
    modalTitle.textContent = "Tambah Announcement";
    announcementIdInput.value = "";
    pesanInput.value = "";
  } else {
    modalTitle.textContent = "Edit Announcement";
    announcementIdInput.value = id;
    pesanInput.value = pesan;
  }
  modal.style.display = "block";
}

// close modal
function closeModal() {
  modal.style.display = "none";
}
function closeDeleteModal() {
  deleteModal.style.display = "none";
}

// save announcement
async function saveAnnouncement() {
  const id = announcementIdInput.value;
  const pesan = pesanInput.value;

  if (!pesan) return alert("Pesan tidak boleh kosong!");

  let url = "api/addAnnouncement";
  let method = "POST";
  let body = { pesan };

  if (id) {
    url = "api/editAnnouncement";
    method = "PUT";
    body = { id, pesan };
  }

  const res = await fetchWithAuth(url, {
    method,
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(body),
  });
  const data = await res.json();

  if (data.success) {
    closeModal();
    loadAnnouncements();
  } else {
    alert(data.message || "Gagal simpan");
  }
}

// confirm delete
async function confirmDelete() {
  if (!deleteId) return;
  const res = await fetchWithAuth("api/deleteAnnouncement", {
    method: "DELETE",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id: deleteId }),
  });
  const data = await res.json();

  if (data.success) {
    closeDeleteModal();
    loadAnnouncements();
  } else {
    alert(data.message || "Gagal hapus");
  }
}

// event binding
document
  .getElementById("addAnnouncementBtn")
  .addEventListener("click", () => openModal("add"));
document.getElementById("closeModal").addEventListener("click", closeModal);
document.getElementById("cancelBtn").addEventListener("click", closeModal);
saveBtn.addEventListener("click", saveAnnouncement);

document
  .getElementById("closeDeleteModal")
  .addEventListener("click", closeDeleteModal);
document
  .getElementById("cancelDeleteBtn")
  .addEventListener("click", closeDeleteModal);
document
  .getElementById("confirmDeleteBtn")
  .addEventListener("click", confirmDelete);

// init
loadAnnouncements();
