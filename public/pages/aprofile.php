<?php

$title = "Profile";
$css = "aprofile"; 
$script = [
    "logout",
    "aprofile"
];
$checkAuth = true;

?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



    <?php
    include_once __DIR__ . '/../components/header.php';
    ?>

<div id="main-content">


    <?php
    include_once __DIR__ . '/../components/navbar.php';
    ?>



  <body>
    <div class="container">
      <!-- Profile Header -->
      <div class="profile-header">
        <div class="profile-avatar">
          <i class="fas fa-user"></i>
        </div>
        <div class="profile-info">
          <h1 id="profile">Ahmad Fauzi</h1>
          <p>Anggota sejak Januari 2022</p>
          <div class="profile-bio" id="user-bio">
            Pemain berpengalaman dengan fokus pada strategi dan kerja tim.
          </div>
        </div>
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


  </body>



    <?php
    include_once __DIR__ . '/../components/footer.php'
    ?>
</div>

<div id="loading" class="d-none">
    <?php
    include_once __DIR__ . '/../components/loadingScreen.php' 
    ?>
</div>