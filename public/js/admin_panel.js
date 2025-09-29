import { fetchWithAuth } from "../module_js/fetch_with_auth.js";
import { debounce } from "../module_js/debounce.js";

// element DOM
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
  try{
    const res = await fetchWithAuth('api/getAdminData');
    const data = await res.json();
    if(!data.success){
      console.error(data.message || "Gagal mengambil data")
    }

    // render total user
    document.getElementById("total-user").textContent = data.totalUser;

    // render table users
    renderTable(data.users, tableUser);

    // render table lottery
    renderTable(data.lottery, tableLottery, false);

    // hitung harga tiket
    const inputPrizes = document.getElementById("prizes").addEventListener("input", 
      debounce((e) => {
        calcTicketPrice(e);
      }, 300)
    );

    // tambah event lottery
    document.getElementById("lottery-form").addEventListener('submit', addLotteryEvent)

    menuItems.forEach((item) => {
      item.addEventListener("click", (e) => {
        e.preventDefault();
        const sec = item.getAttribute("data-section");
        if (sec) showSection(sec);
      });
    });

    // default
    showSection("dashboard");
  } catch(err){
    console.error(err)
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
        const safeValue = value.toLowerCase().replace(/\s+/g, "-").replace(/[^a-z0-9\-_]/g, "");
        if (safeValue) {
          span.classList.add(`${key}-${safeValue}`);
        }
      }

      span.textContent = value;
      
      cell.appendChild(span);
      row.appendChild(cell);
    });

    if(action){
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
function calcTicketPrice(e){
  const ticketPrice = parseInt(e.target.value) / 10;

  const ticketPriceInput = document.getElementById("ticketPrice")
  ticketPriceInput.placehorder = ticketPrice
  ticketPriceInput.value = ticketPrice
}

// tambah event lottery
function addLotteryEvent(e){
  e.preventDefault();
  
  const form = e.target;
  const fd = new FormData(form);

  const prizes = parseInt(fd.get("prizes"))
  const ticketPrice = prizes / 10;

  fd.append("ticketPrice", ticketPrice)

  const obj = {};
  fd.forEach((value, key) => {
    obj[key] = value;
  });

  console.log(obj)

  fetchWithAuth("api/addLotteryEvent", {
    method: "POST",
    headers: {"Content-Type" : "application/json"},
    body: JSON.stringify(obj)
  })
  .then(res => res.json())
  .then(data => {
    console.log(data)
    if(data.success){
      renderTable(data.lottery, tableLottery, false)
    }
  })
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
