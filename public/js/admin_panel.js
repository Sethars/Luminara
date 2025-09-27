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

// Sidebar menu active state
const menuItems = document.querySelectorAll(".menu-item");
menuItems.forEach((item) => {
  item.addEventListener("click", function () {
    menuItems.forEach((i) => i.classList.remove("active"));
    this.classList.add("active");
  });
});

// admin_panel.js
document.addEventListener("DOMContentLoaded", () => {
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

  menuItems.forEach((item) => {
    item.addEventListener("click", (e) => {
      e.preventDefault();
      const sec = item.getAttribute("data-section");
      if (sec) showSection(sec);
    });
  });

  // default
  showSection("dashboard");
});

// contoh data dummy 5 user
const recentUsers = [
  {
    name: "Robert Wilson",
    email: "robert@example.com",
    role: "Customer",
    joined: "May 22, 2023",
  },
  {
    name: "Emily Johnson",
    email: "emily@example.com",
    role: "Moderator",
    joined: "Jun 5, 2023",
  },
  {
    name: "Michael Smith",
    email: "michael@example.com",
    role: "Customer",
    joined: "Jul 12, 2023",
  },
  {
    name: "Sophia Brown",
    email: "sophia@example.com",
    role: "Admin",
    joined: "Aug 19, 2023",
  },
  {
    name: "Daniel Lee",
    email: "daniel@example.com",
    role: "Customer",
    joined: "Sep 3, 2023",
  },
];

const tableBody = document.getElementById("recentUsersTableBody");

function renderTable(users) {
  tableBody.innerHTML = ""; // reset isi
  users.forEach((user) => {
    const row = document.createElement("tr");
    row.innerHTML = `
          <td>${user.name}</td>
          <td>${user.email}</td>
          <td>${user.role}</td>
          <td>${user.joined}</td>
          <td>
            <div class="table-actions">
              <button class="action-btn edit" title="Edit">
                <i class="fas fa-edit"></i>
              </button>
            </div>
          </td>
        `;
    tableBody.appendChild(row);
  });
}

// render pertama
renderTable(recentUsers);

// debounce helper
function debounce(fn, delay = 300) {
  let timeout;
  return (...args) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => fn(...args), delay);
  };
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
