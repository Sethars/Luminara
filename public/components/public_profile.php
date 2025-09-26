<style>
    :root {
  --primary-blue: #3498db;
  --secondary-gray: #7f8c8d;
  --light-gray: #ecf0f1;
  --medium-gray: #bdc3c7;
  --dark-gray: #34495e;
  --white: #ffffff;
  --accent-green: #2ecc71;
  --accent-orange: #e67e22;
  --text-color: #2c3e50;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

body {
  background-color: #f9f9f9;
  color: var(--text-color);
  line-height: 1.6;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.profile-header {
  display: flex;
  align-items: center;
  background: var(--white);
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  padding: 25px;
  margin-bottom: 25px;
}

.profile-avatar {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  background-color: var(--primary-blue);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--white);
  font-size: 40px;
  margin-right: 25px;
}

.profile-info h1 {
  font-size: 28px;
  margin-bottom: 8px;
  color: var(--dark-gray);
}

.profile-info p {
  color: var(--secondary-gray);
  font-size: 15px;
  margin-bottom: 12px;
}

.profile-bio {
  color: var(--text-color);
  font-size: 15px;
  line-height: 1.5;
  max-width: 600px;
  padding: 12px;
  background-color: var(--light-gray);
  border-radius: 0 18px 18px 18px;
}

.stats-container {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 25px;
  margin-bottom: 25px;
}

.stat-card {
  background: var(--white);
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.stat-card h3 {
  font-size: 16px;
  margin-bottom: 10px;
  color: var(--secondary-gray);
  font-weight: 500;
}

.stat-value {
  font-size: 32px;
  font-weight: bold;
  color: var(--primary-blue);
}

.chart-container {
  background: var(--white);
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  padding: 20px;
  margin-bottom: 25px;
  height: 350px;
}

.achievements-section {
  background: var(--white);
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  padding: 20px;
  margin-bottom: 25px;
}

.achievements-section h2 {
  font-size: 20px;
  margin-bottom: 15px;
  color: var(--dark-gray);
  padding-bottom: 8px;
  border-bottom: 1px solid var(--light-gray);
}

.public-badges-container {
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
  margin-top: 15px;
}

.public-badges-container span {
  display: inline-block;   /* biar bisa dikasih margin/padding */
  margin: 2px 2px;         /* jarak antar badge */
  padding: 4px 8px;        /* ruang di dalam badge */
  border-radius: 6px;      /* sudut melengkung */
  background-color: #f0f0f0; /* warna latar */
  color: #333;             /* warna teks */
  font-size: 1rem;      /* ukuran font */
}

.comments-section {
  background: var(--white);
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  padding: 20px;
}

.comments-section h2 {
  font-size: 20px;
  margin-bottom: 15px;
  color: var(--dark-gray);
  padding-bottom: 8px;
  border-bottom: 1px solid var(--light-gray);
}

.comment-form {
  margin-bottom: 20px;
}

.comment-form textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid var(--medium-gray);
  border-radius: 6px;
  resize: vertical;
  min-height: 80px;
  font-family: inherit;
  font-size: 14px;
}

.comment-form button {
  background-color: var(--primary-blue);
  color: var(--white);
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  margin-top: 8px;
  transition: background-color 0.2s;
}

.comment-form button:hover {
  background-color: #2980b9;
}

.comments-list {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.comment {
  border-bottom: 1px solid var(--light-gray);
  padding-bottom: 12px;
}

.comment:last-child {
  border-bottom: none;
}

.comment-header {
  display: flex;
  align-items: center;
  margin-bottom: 8px;
}

.comment-avatar {
  width: 35px;
  height: 35px;
  border-radius: 50%;
  background-color: var(--accent-green);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--white);
  margin-right: 12px;
  font-size: 14px;
}

.comment-user {
  font-weight: 600;
  color: var(--dark-gray);
  font-size: 14px;
}

.comment-date {
  color: var(--secondary-gray);
  font-size: 12px;
  margin-left: auto;
}

.comment-text {
  color: var(--text-color);
  line-height: 1.5;
  font-size: 14px;
  padding-left: 47px;
}

@media (max-width: 768px) {
  .stats-container {
    grid-template-columns: 1fr;
  }

  .profile-header {
    flex-direction: column;
    text-align: center;
  }

  .profile-avatar {
    margin-right: 0;
    margin-bottom: 15px;
  }

  .profile-bio {
    max-width: 100%;
  }
}

.comment {
  position: relative;
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 8px;
  margin-bottom: 12px;
  background: #fff;
}

.comment-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.comment-avatar{
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: white;
}

.comment-avatar img{
  width: 32px;
  height: 32px;
  border-radius: 50%;
}

.comment-user {
  font-weight: bold;
  margin-right: auto;
  margin-left: 8px;
}

.comment-date {
  font-size: 12px;
  color: #666;
  margin-right: 12px;
}

/* Menu trigger */
.comment-menu {
  position: relative;
  display: flex;
  align-items: center;
  gap: 4px;
  cursor: pointer;
}

.comment-menu i {
  font-size: 16px;
  color: #888;
  transition: color 0.2s;
}

.comment-menu:hover i {
  color: #333;
}

/* Indicator segitiga pointing left */
.comment-indicator {
  width: 0;
  height: 0;
  border-top: 6px solid transparent;
  border-bottom: 6px solid transparent;
  border-right: 6px solid #888;
  transition: border-right-color 0.2s;
}

.comment-menu:hover .comment-indicator {
  border-right-color: #333;
}

/* Actions menu (pojok kanan bawah) */
.comment-actions {
  position: absolute;
  bottom: 8px;
  right: 8px;
  display: flex;
  gap: 6px;
  opacity: 0;
  transform: translateY(10px);
  pointer-events: none;
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.comment-actions button {
  padding: 5px 10px;
  font-size: 13px;
  border: none;
  border-radius: 6px;
  background: #f5f5f5;
  cursor: pointer;
  transition: background 0.2s;
}

.comment-actions button:hover {
  background: #e1e1e1;
}

/* Hover (desktop) */
.comment:hover .comment-actions {
  opacity: 1;
  transform: translateY(0);
  pointer-events: auto;
}

.comment-actions button.btn-danger {
  background: #f44336;   /* merah */
  color: #fff;
}

.comment-actions button.btn-danger:hover {
  background: #d32f2f;   /* merah lebih gelap */
}


</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



    <div class="container">
      <!-- Profile Header -->
      <div class="profile-header position-relative p-3 border rounded bg-light">
        <img id="public-preview-photo" class="rounded-circle me-3" width="100" height="100" alt="Foto Profil">
        <div class="profile-info">
          <h1>
            <span id="public-profile-username"></span>
            <i id="public-profile-gender" class="bi"></i>
          </h1>
          <p>Anggota sejak <span id="created_at"></span></p>
          <div class="profile-bio" id="public-preview-bio"></div>
        </div>

        <!-- Tombol Back -->
        <button id="backBtn" class="btn btn-secondary position-absolute top-0 end-0 m-2">
          Back
        </button>

        <!-- Footer Cash -->
        <div class="profile-footer position-absolute bottom-0 end-0 w-100 text-end px-3 py-2">
          <small class="text-muted">Cash:</small>
          <span id="public-preview-cash" class="fw-bold text-success"></span>
        </div>
      </div>



      <!-- Stats Section -->
      <div class="stats-container">
        <div class="stat-card">
          <h3>Total Matches</h3>
          <div class="stat-value" id="total-matches">0</div>
        </div>
        <div class="stat-card">
          <h3>Win Rate</h3>
          <div class="stat-value" id="win-rate">0%</div>
        </div>
      </div>

      <!-- Win Rate Chart -->
      <div class="chart-container">
        <canvas id="winRateChart"></canvas>
      </div>

      <!-- Achievements & Badges Section -->
      <div class="achievements-section">
        <h2>Achievements & Badges</h2>
        <div class="public-badges-container"></div>
      </div>

      <!-- Comments Section -->
      <div class="comments-section">
        <h2>Komentar</h2>
          <div class="comments-list"></div>
      </div>
    </div>

  <script>

document.querySelectorAll('.comment-menu').forEach(menu => {
  menu.addEventListener('click', () => {
    const actions = menu.closest('.comment').querySelector('.comment-actions');
    const isVisible = actions.style.opacity === '1';
    if (isVisible) {
      actions.style.opacity = '0';
      actions.style.transform = 'translateY(10px)';
      actions.style.pointerEvents = 'none';
    } else {
      actions.style.opacity = '1';
      actions.style.transform = 'translateY(0)';
      actions.style.pointerEvents = 'auto';
    }
  });
});



  </script>