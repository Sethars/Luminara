<?php

$title = "Profile";
$css = "aprofile"; 
$script = [
    "logout",
    "aprofile",
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
      <div class="profile-header border rounded bg-light p-3">
        <div class="profile-avatar me-3">
          <img id="profile-photo" src="" alt="Photo Profile" class="rounded-circle" width="100" height="100">
        </div>
        <div class="profile-info">
          <h1>
            <span id="profile-username"></span>
            <i id="profile-gender" class="bi"></i>
          </h1>
          <p>Anggota sejak <span id="createdAt"></span></p>
          <div class="profile-bio" id="profile-bio"></div>
        </div>
      </div>

      <!-- Cash Footer -->
      <div class="profile-footer d-flex justify-content-end align-items-center px-3 py-2">
        <small class="text-muted me-2">Cash:</small>
        <span id="profile-cash" class="fw-bold text-success"></span>
      </div>


      <!-- Stats Section -->
      <div class="stats-container">
        <div class="stat-card">
          <h3>Total Matches</h3>
          <div class="stat-value" id="total-matches"></div>
        </div>
        <div class="stat-card">
          <h3>Win Rate</h3>
          <div class="stat-value" id="win-rate"></div>
        </div>
      </div>

      <!-- Win Rate Chart -->
      <div class="chart-container">
        <canvas id="winRateChart"></canvas>
      </div>

      <!-- Achievements & Badges Section -->
      <div class="achievements-section">
        <h2>Badges</h2>
        <div class="badges-container d-flex flex-wrap gap-2 mt-2"></div>
      </div>

      <!-- Comments Section -->
      <div class="comments-section">
        <h2>Komentar</h2>
        <div class="comment-form">
          <textarea id="comment" placeholder="Tulis komentar Anda di sini..."></textarea>
          <p id="comment-message"></p>
          <button id="submit-comment">Kirim Komentar</button>
        </div>
        <div class="comments-list"></div>
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