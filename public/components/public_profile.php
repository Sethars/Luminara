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
  border-radius: 6px;
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

.badges-container {
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
  margin-top: 15px;
}

.badge_aprofile {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 90px;
}

.badge_icon_aprofile {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background-color: var(--light-gray);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  margin-bottom: 6px;
}

.badge-name {
  font-size: 12px;
  text-align: center;
  color: var(--secondary-gray);
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

</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



    <div class="container">
      <!-- Profile Header -->
      <div class="profile-header position-relative p-3 border rounded bg-light">
          <img id="public-preview-photo" class="rounded-circle me-3" width="100" height="100" alt="Foto Profil">
        <div class="profile-info">
          <h1 id="public-preview-username">Demo</h1>
          <p id="">Anggota sejak Januari 2022</p>
          <div class="profile-bio" id="public-preview-bio">
          </div>
        </div>

        <button id="backBtn" class="btn btn-secondary position-absolute top-0 end-0 m-2">
          Back
        </button>
      </div>


      <!-- Stats Section -->
      <div class="stats-container">
        <div class="stat-card">
          <h3>Total Kemenangan</h3>
          <div class="stat-value" id="total-wins">42</div>
        </div>
        <div class="stat-card">
          <h3>Win Rate</h3>
          <div class="stat-value" id="win-rate">68.25%</div>
        </div>
      </div>

      <!-- Win Rate Chart -->
      <div class="chart-container">
        <canvas id="winRateChart"></canvas>
      </div>

      <!-- Achievements & Badges Section -->
      <div class="achievements-section">
        <h2>Achievements & Badges</h2>
        <div class="badges-container">
          <div class="badge_aprofile">
            <div class="badge_icon_aprofile">
              <i class="fas fa-trophy" style="color: var(--accent-orange)"></i>
            </div>
            <div class="badge-name">Juara 2023</div>
          </div>
        </div>
      </div>

      <!-- Comments Section -->
      <div class="comments-section">
        <h2>Komentar</h2>
        <div class="comment-form">
          <textarea placeholder="Tulis komentar Anda di sini..."></textarea>
          <button id="submit-comment">Kirim Komentar</button>
        </div>
        <div class="comments-list">
          <div class="comment">
            <div class="comment-header">
              <div class="comment-avatar">
                <i class="fas fa-user"></i>
              </div>
              <div class="comment-user">Budi Santoso</div>
              <div class="comment-date">2 hari yang lalu</div>
            </div>
            <div class="comment-text">
              Pemain yang sangat berbakat! Senang bisa bermain satu tim dengan
              Anda.
            </div>
          </div>
          <div class="comment">
            <div class="comment-header">
              <div class="comment-avatar">
                <i class="fas fa-user"></i>
              </div>
              <div class="comment-user">Siti Nurhaliza</div>
              <div class="comment-date">1 minggu yang lalu</div>
            </div>
            <div class="comment-text">
              Strategi yang Anda gunakan di turnamen kemarin sangat mengesankan.
              Saya belajar banyak dari Anda!
            </div>
          </div>
        </div>
      </div>
    </div>

  <script>
const ctx = document.getElementById("winRateChart").getContext("2d");
const winRateChart = new Chart(ctx, {
  type: "doughnut",
  data: {
    labels: ["Kemenangan", "Kekalahan"],
    datasets: [
      {
        data: [68.25, 31.75],
        backgroundColor: [
          "rgba(52, 152, 219, 0.8)",
          "rgba(189, 195, 199, 0.8)",
        ],
        borderColor: ["rgba(52, 152, 219, 1)", "rgba(189, 195, 199, 1)"],
        borderWidth: 1,
      },
    ],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: "bottom",
        labels: {
          font: {
            size: 14,
          },
          padding: 15,
        },
      },
      title: {
        display: true,
        text: "Win Rate Diagram",
        font: {
          size: 16,
        },
        padding: {
          top: 10,
          bottom: 15,
        },
      },
      tooltip: {
        callbacks: {
          label: function (context) {
            return context.label + ": " + context.raw.toFixed(2) + "%";
          },
        },
      },
    },
  },
});
  </script>