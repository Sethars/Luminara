<?php

$title = "Chip & Cash Exchange Shop";
$css = "shop"; 
$script = [
    "logout",
    "shop"
];
$checkAuth = true;

?>

    <?php
    include_once __DIR__ . '/../components/header.php';
    ?>

<div id="main-content">


    <?php
    include_once __DIR__ . '/../components/navbar.php';
    ?>




    <div class="shop-container">
      <div class="shop-header">
        <h1><i class="bi bi-shop"></i> Chip & Cash Exchange Shop</h1>
        <p>Tukar Chip menjadi Cash atau sebaliknya dengan mudah!</p>
      </div>

      <!-- Balance Display -->
      <div class="balance-display">
        <div class="balance-item">
          <div class="balance-value" id="chipBalance">0</div>
          <div class="balance-label">Chip</div>
        </div>
        <div class="balance-item">
          <div class="balance-value" id="cashBalance">0</div>
          <div class="balance-label">Cash</div>
        </div>
      </div>

      <!-- Top Cards -->
      <div class="row mb-4">
        <div class="col-md-6">
          <div class="card reward-card" id="dailyLoginCard">
            <div class="card-header">
              <i class="bi bi-calendar-check"></i> Login Harian
            </div>
            <div class="card-body text-center">
              <div class="reward-icon">
                <i class="bi bi-coin"></i>
              </div>
              <h4>100 Chip</h4>
              <p class="card-text">Klaim hadiah login harian Anda!</p>
              <button
                class="btn btn-primary-custom btn-exchange"
                id="dailyLoginBtn"
              >
                Klaim Sekarang
              </button>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card reward-card" id="welcomeBonusCard">
            <div class="card-header">
              <i class="bi bi-gift"></i> Hadiah Selamat Datang
            </div>
            <div class="card-body text-center">
              <div class="reward-icon">
                <i class="bi bi-piggy-bank"></i>
              </div>
              <h4>1000 Chip</h4>
              <p class="card-text">Hadiah khusus untuk member baru!</p>
              <button
                class="btn btn-primary-custom btn-exchange"
                id="welcomeBonusBtn"
              >
                Klaim Sekarang
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- VIP Card and Countdown -->
      <div class="row mb-4">
        <div class="col-md-6">
          <div class="vip-card">
            <h2 class="vip-title">
              <i class="bi bi-crown-fill"></i> AKSES VIP EKSKLUSIF
            </h2>
            <p class="vip-subtitle">Nikmati keistimewaan menjadi member VIP!</p>
            <div class="vip-price">50.000 Cash</div>
            <div class="vip-benefits">
              <ul>
                <li>
                  <i class="bi bi-check-circle-fill"></i> Rate penukaran lebih
                  tinggi
                </li>
              </ul>
            </div>
            <button class="btn btn-vip" id="buyVipBtn">
              Beli VIP Sekarang
            </button>
          </div>
        </div>
        <div class="col-md-6 d-flex align-items-center justify-content-center ">
          <div class="countdown-container d-none d-md-block" style="padding:35px;">
            <h2>
              <i class="bi bi-clock-history"></i> Klaim Hadiah Harian Dalam
            </h2>
            <div class="countdown" id="countdown" style="padding: 20px;">
              <div class="countdown-item" style="padding:40px;">
                <span class="countdown-value" id="hours" style="font-size:50px;">00</span>
                <span class="countdown-label">Jam</span>
              </div>
              <div class="countdown-item" style="padding:40px;">

                <span class="countdown-value" id="minutes" style="font-size:50px;">00</span>
                <span class="countdown-label">Menit</span>
              </div>
              <div class="countdown-item" style="padding:40px;">

                <span class="countdown-value" id="seconds" style="font-size:50px;">00</span>
                <span class="countdown-label">Detik</span>
              </div>
            </div>
          </div>



        <div class="countdown-container d-md-none d-sm-block" style="padding:35px;">
            <h4>
              <i class="bi bi-clock-history"></i> Klaim Hadiah Harian Dalam
            </h4>
            <div class="countdown" id="countdown" >
              <div class="countdown-item">
                <span class="countdown-value" id="hours">00</span>
                <span class="countdown-label">Jam</span>
              </div>
              <div class="countdown-item">

                <span class="countdown-value" id="minutes">00</span>
                <span class="countdown-label">Menit</span>
              </div>
              <div class="countdown-item" >

                <span class="countdown-value" id="seconds">00</span>
                <span class="countdown-label">Detik</span>
              </div>
            </div>
          </div>
        </div>
      </div>
        <br><br><br>


      <!-- Exchange Cards -->
    <div class="row">
        <!-- Cash to Chip -->
        <div class="">
          <h3 class="text-white text-center mb-3">
            <i class="bi bi-arrow-down-up"></i> Cash ke Chip
          </h3>
        <br><br><br>

          <!-- First row: 5, 10, 25, 50 chips -->
          <div class="row mb-3">
            <div class="col-md-3 mb-3">
              <div class="exchange-card">
                <div class="exchange-header">
                  <i class="bi bi-coin"></i> 5 Chip
                </div>
                <div class="exchange-body">
                  <div class="exchange-rate">5 Cash = 1 Chip</div>
                  <div class="exchange-amount">25 Cash</div>
                  <button
                    class="btn btn-primary-custom btn-exchange"
                    data-cash="25"
                    data-chip="5"
                    data-type="cash-to-chip"
                  >
                    Tukar Sekarang
                  </button>
                </div>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="exchange-card">
                <div class="exchange-header">
                  <i class="bi bi-coin"></i> 10 Chip
                </div>
                <div class="exchange-body">
                  <div class="exchange-rate">5 Cash = 1 Chip</div>
                  <div class="exchange-amount">50 Cash</div>
                  <button
                    class="btn btn-primary-custom btn-exchange"
                    data-cash="50"
                    data-chip="10"
                    data-type="cash-to-chip"
                  >
                    Tukar Sekarang
                  </button>
                </div>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="exchange-card">
                <div class="exchange-header">
                  <i class="bi bi-coin"></i> 25 Chip
                </div>
                <div class="exchange-body">
                  <div class="exchange-rate">5 Cash = 1 Chip</div>
                  <div class="exchange-amount">125 Cash</div>
                  <button
                    class="btn btn-primary-custom btn-exchange"
                    data-cash="125"
                    data-chip="25"
                    data-type="cash-to-chip"
                  >
                    Tukar Sekarang
                  </button>
                </div>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="exchange-card">
                <div class="exchange-header">
                  <i class="bi bi-coin"></i> 50 Chip
                </div>
                <div class="exchange-body">
                  <div class="exchange-rate">5 Cash = 1 Chip</div>
                  <div class="exchange-amount">250 Cash</div>
                  <button
                    class="btn btn-primary-custom btn-exchange"
                    data-cash="250"
                    data-chip="50"
                    data-type="cash-to-chip"
                  >
                    Tukar Sekarang
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Second row: 100 chips (2 columns) -->
          <div class="row">
            <div class="col-md-6 mb-3">
              <div class="exchange-card">
                <div class="exchange-header">
                  <i class="bi bi-coin"></i> 100 Chip
                </div>
                <div class="exchange-body">
                  <div class="exchange-rate">5 Cash = 1 Chip</div>
                  <div class="exchange-amount">500 Cash</div>
                  <button
                    class="btn btn-primary-custom btn-exchange"
                    data-cash="500"
                    data-chip="100"
                    data-type="cash-to-chip"
                  >
                    Tukar Sekarang
                  </button>
                </div>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <div class="exchange-card">
                <div class="exchange-header">
                  <i class="bi bi-coin"></i> 100 Chip (VIP)
                </div>
                <div class="exchange-body">
                  <div class="exchange-rate">4 Cash = 1 Chip</div>
                  <div class="exchange-amount">400 Cash</div>
                  <button
                    class="btn btn-primary-custom btn-exchange"
                    data-cash="400"
                    data-chip="100"
                    data-type="cash-to-chip-vip"
                  >
                    Tukar Sekarang
                  </button>
                </div>
              </div>
            </div>
          </div>
            <br><br><br>

        </div>

        <!-- Chip to Cash -->
        <div class="">
          <h3 class="text-white text-center mb-3">
            <i class="bi bi-arrow-down-up"></i> Chip ke Cash
          </h3>
        <br><br><br>

          <!-- First row: 5, 10, 25, 50 chips -->
          <div class="row mb-3">
            <div class="col-md-3 mb-3">
              <div class="exchange-card">
                <div class="exchange-header">
                  <i class="bi bi-cash"></i> 5 Chip
                </div>
                <div class="exchange-body">
                  <div class="exchange-rate">1 Chip = 4 Cash</div>
                  <div class="exchange-amount">20 Cash</div>
                  <button
                    class="btn btn-success-custom btn-exchange"
                    data-cash="20"
                    data-chip="5"
                    data-type="chip-to-cash"
                  >
                    Tukar Sekarang
                  </button>
                </div>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="exchange-card">
                <div class="exchange-header">
                  <i class="bi bi-cash"></i> 10 Chip
                </div>
                <div class="exchange-body">
                  <div class="exchange-rate">1 Chip = 4 Cash</div>
                  <div class="exchange-amount">40 Cash</div>
                  <button
                    class="btn btn-success-custom btn-exchange"
                    data-cash="40"
                    data-chip="10"
                    data-type="chip-to-cash"
                  >
                    Tukar Sekarang
                  </button>
                </div>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="exchange-card">
                <div class="exchange-header">
                  <i class="bi bi-cash"></i> 25 Chip
                </div>
                <div class="exchange-body">
                  <div class="exchange-rate">1 Chip = 4 Cash</div>
                  <div class="exchange-amount">100 Cash</div>
                  <button
                    class="btn btn-success-custom btn-exchange"
                    data-cash="100"
                    data-chip="25"
                    data-type="chip-to-cash"
                  >
                    Tukar Sekarang
                  </button>
                </div>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="exchange-card">
                <div class="exchange-header">
                  <i class="bi bi-cash"></i> 50 Chip
                </div>
                <div class="exchange-body">
                  <div class="exchange-rate">1 Chip = 4 Cash</div>
                  <div class="exchange-amount">200 Cash</div>
                  <button
                    class="btn btn-success-custom btn-exchange"
                    data-cash="200"
                    data-chip="50"
                    data-type="chip-to-cash"
                  >
                    Tukar Sekarang
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Second row: 100 chips (2 columns) -->
          <div class="row">
            <div class="col-md-6 mb-3">
              <div class="exchange-card">
                <div class="exchange-header">
                  <i class="bi bi-cash"></i> 100 Chip
                </div>
                <div class="exchange-body">
                  <div class="exchange-rate">1 Chip = 4 Cash</div>
                  <div class="exchange-amount">400 Cash</div>
                  <button
                    class="btn btn-success-custom btn-exchange"
                    data-cash="400"
                    data-chip="100"
                    data-type="chip-to-cash"
                  >
                    Tukar Sekarang
                  </button>
                </div>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <div class="exchange-card">
                <div class="exchange-header">
                  <i class="bi bi-cash"></i> 100 Chip (VIP)
                </div>
                <div class="exchange-body">
                  <div class="exchange-rate">1 Chip = 5 Cash</div>
                  <div class="exchange-amount">500 Cash</div>
                  <button
                    class="btn btn-success-custom btn-exchange"
                    data-cash="500"
                    data-chip="100"
                    data-type="chip-to-cash-vip"
                  >
                    Tukar Sekarang
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Notification -->
    <div class="notification" id="notification"></div>







    <?php
    include_once __DIR__ . '/../components/footer.php';
    ?>
</div>

<div id="loading" class="d-none">
    <?php
    include_once __DIR__ . '/../components/loadingScreen.php' 
    ?>
</div>