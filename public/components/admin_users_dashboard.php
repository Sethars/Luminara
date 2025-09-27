<body>
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
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Joined</th>
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
