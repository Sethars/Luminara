<style>
modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1050;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
}

.modal-content {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
  width: 600px;
  max-width: 90%;
  padding: 0;
  overflow: hidden;
}

.modal-content h2 {
  background: #f8f9fa;
  border-bottom: 1px solid #dee2e6;
  padding: 15px 20px;
  margin: 0;
  font-size: 1.25rem;
  font-weight: 500;
  color: #212529;
}

#editUserForm {
  padding: 20px;
}

.form-group {
  display: flex;
  align-items: flex-start;
  margin-bottom: 20px;
}

.form-group label {
  width: 120px;
  padding: 8px 10px 0 0;
  font-weight: 500;
  color: #495057;
  text-align: right;
  flex-shrink: 0;
}

.form-group select,
.form-group input {
  flex: 1;
  min-width: 200px;
  padding: 8px 12px;
  border: 1px solid #ced4da;
  border-radius: 4px;
  background-color: #fff;
  color: #495057;
  font-size: 1rem;
  line-height: 1.5;
}

.form-group select:focus,
.form-group input:focus {
  border-color: #80bdff;
  outline: 0;
}

/* Container untuk role dan badges side by side */
.role-badges-container {
  display: flex;
  gap: 20px;
  margin-bottom: 20px;
}

.role-badges-container > div {
  flex: 1;
}

.badges-list {
  display: flex;
  flex-wrap: wrap;
  max-height: 180px;
  overflow-y: auto;
  border: 1px solid #ced4da;
  border-radius: 4px;
  background-color: #fff;
  padding: 5px;
  gap: 6px;
}

/* Scrollbar styling */
.badges-list::-webkit-scrollbar {
  width: 8px;
}
.badges-list::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}
.badges-list::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}
.badges-list::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}

/* Label wrapper */
.badges-list label {
  display: inline-flex; /* pastikan horizontal */
  align-items: center;  /* vertikal sejajar tengah */
  gap: 8px;             /* jarak antara checkbox dan teks */
  background: #f8fafc;
  padding: 6px 10px;
  border-radius: 6px;
  border: 1px solid #ccc;
  font-size: 14px;
  cursor: pointer;
  transition: background 0.2s ease;
  line-height: 1; /* penting biar teks sejajar tengah */
}

.badges-list label:hover {
  background-color: #e9ecef;
}

/* Checkbox */
.badges-list input[type="checkbox"] {
  transform: scale(1.1);
  cursor: pointer;
  margin: 0;
  flex-shrink: 0;
  vertical-align: middle;
  transform: scale(1.1);

}

.badges-list span {
  vertical-align: middle;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 25px;
  padding-top: 15px;
  border-top: 1px solid #dee2e6;
}

.modal-actions button {
  padding: 8px 16px;
  border-radius: 4px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  border: 1px solid transparent;
}

#cancelEdit {
  background-color: #6c757d;
  color: #fff;
}

#cancelEdit:hover {
  background-color: #5a6268;
}

#saveEdit {
  background-color: #007bff;
  color: #fff;
}

#saveEdit:hover {
  background-color: #0069d9;
}
</style>

<body>

  <!-- Modal Edit User -->  
  <div id="editUserModal" class="modal" style="display:none;">
    <div class="modal-content">
      <h2>Edit User</h2>
      <form id="editUserForm">
        <input type="hidden" id="editUserId" />

        <div class="form-group">
          <label for="editUserRole">Role:</label>
          <select id="editUserRole">
            <option value="admin">Admin</option>
            <option value="member">Member</option>
            <option value="gacor">Gacor</option>
            <option value="owner">Owner</option>
          </select>
        </div>

        <div class="form-group">
          <label>Badges:</label>
          <div id="badgesList" class="badges-list"></div>
        </div>

        <div class="modal-actions">
          <button type="button" id="cancelEdit">Cancel</button>
          <button type="submit" id="saveEdit">Save</button>
        </div>
      </form>
    </div>
  </div>



  <div class="admin-container">
    <!-- Main Content -->
    <main class="main-content">

      <!-- Page Content -->
      <div class="content">
        <!-- Recent Users Table -->
        <div class="data-table-container">
          <div class="table-header" style="display:flex; justify-content:space-between; align-items:center;">
            <h2 class="table-title">Users</h2>
            <input
              type="text"
              id="searchInput"
              placeholder="Search users..."
              style="padding:6px 10px; border:1px solid #ccc; border-radius:5px;"
            />
          </div>

          <table class="data-table">
            <thead>
              <tr>
                <th>id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Joined</th>
                <Th>Badges</Th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="recentUsersTableBody">
              <!-- diisi lewat JS -->
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</body>
<script>
  const allBadges = [
    "VIP",
    "Developer",
    "Moderator",
    "Beta_Tester",
    "WS5",
    "Racist",
    "Lottery_Winner",
    "TOP_1",
    "TOP_2",
    "TOP_3",
  ];

  const modal = document.getElementById("editUserModal");
  const editUserId = document.getElementById("editUserId");
  const editUserRole = document.getElementById("editUserRole");
  const badgesList = document.getElementById("badgesList");
  const cancelEdit = document.getElementById("cancelEdit");
  const editForm = document.getElementById("editUserForm");

  // Buka modal saat klik tombol edit
  document.addEventListener("click", (e) => {
    if (e.target.closest(".action-btn.edit")) {
      const row = e.target.closest("tr");
      const userId = row.querySelector(".id").textContent.trim();
      const role = row.querySelector(".role").textContent.trim();
      const badgesRaw = row.querySelector(".badges")?.textContent || "{}";

      let badges;
      try {
        badges = JSON.parse(badgesRaw);
      } catch {
        badges = { used: [], unused: [] };
      }

      openEditModal({ id: userId, role, badges });
    }
  });

  function openEditModal(user) {
    editUserId.value = user.id;
    editUserRole.value = user.role;

    // Render badge list
    badgesList.innerHTML = "";
    allBadges.forEach((badge) => {
      const isUsed = user.badges.used.includes(badge);
      const label = document.createElement("label");
      label.innerHTML = `
        <input type="checkbox" value="${badge}" ${isUsed ? "checked" : ""}>
        <span>${badge.replace(/_/g, " ")}</span>
      `;
      badgesList.appendChild(label);
    });

    modal.style.display = "flex";
  }

  cancelEdit.addEventListener("click", () => {
    modal.style.display = "none";
  });

  window.addEventListener("click", (e) => {
    if (e.target === modal) modal.style.display = "none";
  });

  editForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const id = editUserId.value;
    const role = editUserRole.value;
    const usedBadges = Array.from(
      badgesList.querySelectorAll("input:checked")
    ).map((input) => input.value);

    const payload = {
      id,
      role,
      badges: JSON.stringify({ used: usedBadges, unused: [] }),
    };

    console.log("User updated:", payload);

    // Contoh update ke backend
    /*
    fetch("api/updateUserRoleAndBadges.php", {
      method: "POST",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify(payload),
    })
    .then(res => res.json())
    .then(data => {
      alert(data.message);
      modal.style.display = "none";
    });
    */

    modal.style.display = "none";
  });
</script>
