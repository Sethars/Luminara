<?php

 $title = "Game List";
 $css = "game_list"; 
 $script = [
    "logout",
    "game_list"
];
 $checkAuth = true;

?>

<?php
include_once __DIR__ . '/../components/header.php';
?>

<div id="main-content" style="  background-color: #0f172a;">



<body>
  <div class="crimson-pattern"></div>

    <?php
    include_once __DIR__ . '/../components/navbar.php';
    ?>

  <div class="container">
    <div class="header">
      <h1>DAFTAR GAME POPULER</h1>
      <p class="lead text-white">Temukan game favoritmu dan mulai petualangan seru!</p>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="game-card">
          <img
            src="../assets/img/list_game/bc_cov.png"
            class="game-image"
            alt="Game 1"
          />
          <div class="game-content">
            <h3 class="game-title">Black Jack</h3>
            <p class="game-description">
              Jelajahi dunia fantasi yang penuh dengan makhluk ajaib dan
              tantangan epik. Kumpulkan item langka dan kuasai kekuatan sihir!
            </p>
            <div class="game-stats">
              <!-- <div class="game-stat">
                <i class="fas fa-users"></i>
                <span>12.5K Pemain</span>
              </div> -->
              <div class="game-stat">
                <i class="fa fa-battery-half text-warning"></i>
                <span>Medium</span>
              </div>
            </div>
            <button class="play-button" onclick="window.location.href='/casual_black_jack'">
              <i class="fas fa-play"></i> Mainkan Sekarang
            </button>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="game-card">
          <img
            src="../assets/img/list_game/Roulatte.jpg"
            class="game-image"
            alt="Game 2"
          />
          <div class="game-content">
            <h3 class="game-title">Russian Roulate</h3>
            <p class="game-description">
              Uji adrenalinmu di sirkuit balap paling berbahaya. Kendarai mobil
              super cepat dan kalahkan semua lawanmu!
            </p>
            <div class="game-stats">
              <!-- <div class="game-stat">
                <i class="fas fa-users"></i>
                <span>8.2K Pemain</span>
              </div> -->
              <div class="game-stat">
                <i class="fa fa-battery-three-quarters text-warning"></i>
                <span>Medium Hard</span>
              </div>
            </div>
            <button class="play-button" onclick="window.location.href='/RRLobby_list'">
              <i class="fas fa-play"></i> Mainkan Sekarang
            </button>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="game-card">
          <img
            src="../assets/img/list_game/tikext.jpg"
            class="game-image"
            alt="Game 3"
          />
          <div class="game-content">
            <h3 class="game-title">Lottery</h3>
            <p class="game-description">
              Bergabunglah dalam pertempuran antar galaksi! Pilih senjatamu,
              kumpulkan pasukan, dan taklukkan alam semesta!
            </p>
            <div class="game-stats">
              <!-- <div class="game-stat">
                <i class="fas fa-users"></i>
                <span>15.7K Pemain</span>
              </div> -->
              <div class="game-stat">
                <i class="fa fa-battery-full"></i>
                <span>Hard</span>
              </div>
            </div>
            <button class="play-button" onclick="window.location.href='/lottery'">
              <i class="fas fa-play"></i> Mainkan Sekarang
            </button>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="game-card">
          <img
            src="../assets/img/list_game/capc.jpg"
            class="game-image"
            alt="Game 4"
          />
          <div class="game-content">
            <h3 class="game-title">Coin Flip</h3>
            <p class="game-description">
              Selesaikan teka-teki paling rumit dan ungkap misteri tersembunyi.
              Uji kecerdasanmu di setiap level!
            </p>
            <div class="game-stats">
              <!-- <div class="game-stat">
                <i class="fas fa-users"></i>
                <span>9.3K Pemain</span>
              </div> -->
              <div class="game-stat">
                <i class="fa fa-battery-quarter text-success"></i>
                <span>Easy</span>
              </div>
            </div>
            <button class="play-button" onclick="window.location.href='/coin_flip'">
              <i class="fas fa-play"></i> Mainkan Sekarang
            </button>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="game-card">
          <img
            src="../assets/img/list_game/slot.jpg"
            class="game-image"
            alt="Game 4"
          />
          <div class="game-content">
            <h3 class="game-title">Slot Machine</h3>
            <p class="game-description">
              Selesaikan teka-teki paling rumit dan ungkap misteri tersembunyi.
              Uji kecerdasanmu di setiap level!
            </p>
            <div class="game-stats">
              <!-- <div class="game-stat">
                <i class="fas fa-users"></i>
                <span>9.3K Pemain</span>
              </div> -->
              <div class="game-stat">
                <i class="fa fa-battery-full "></i>
                <i class="fa fa-battery-full text-white"></i>
                <span>Indonesia</span>
              </div>
            </div>
            <button class="play-button" onclick="window.location.href='/slot'">
              <i class="fas fa-play"></i> Mainkan Sekarang
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- <footer class="footer">
    <div class="container">
      <p>&copy; 2023 GameHub. Semua hak dilindungi.</p>
    </div>
  </footer> -->
</body>






    <?php
    include_once __DIR__ . '/../components/footer.php';
    ?>
</div>

<div id="loading" class="d-none">
    <?php
    include_once __DIR__ . '/../components/loadingScreen.php' 
    ?>
</div>