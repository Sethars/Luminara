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
              <h4 id="dailyAmount">100 Chip</h4>
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
                  <i class="bi bi-check-circle-fill"></i> Daily reward lebih
                  tinggi
                </li>
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
          <div class="countdown-container" style="padding:35px;">
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
            <div id="claimReady" style="display:none;">
              <h3 class="text-success">Sudah Bisa Claim Hadiah!</h3>
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
                  <i class="bi bi-cash"></i> 25 Cash
                </div>
                <div class="exchange-body">
                  <div class="exchange-rate">5 Cash = 1 Chip</div>
                  <div class="exchange-amount">5 Chip</div>
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
                  <i class="bi bi-cash"></i> 50 Cash
                </div>
                <div class="exchange-body">
                  <div class="exchange-rate">5 Cash = 1 Chip</div>
                  <div class="exchange-amount">10 Chip</div>
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
                  <i class="bi bi-cash"></i> 125 Cash
                </div>
                <div class="exchange-body">
                  <div class="exchange-rate">5 Cash = 1 Chip</div>
                  <div class="exchange-amount">25 Chip</div>
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
                  <i class="bi bi-cash"></i> 250 Cash
                </div>
                <div class="exchange-body">
                  <div class="exchange-rate">5 Cash = 1 Chip</div>
                  <div class="exchange-amount">50 Chip</div>
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
                  <i class="bi bi-cash"></i> 500 Cash
                </div>
                <div class="exchange-body">
                  <div class="exchange-rate">5 Cash = 1 Chip</div>
                  <div class="exchange-amount">100 Chip</div>
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
                  <i class="bi bi-cash"></i> 450 Cash (VIP)
                </div>
                <div class="exchange-body">
                  <div class="exchange-rate">4.5 Cash = 1 Chip</div>
                  <div class="exchange-amount">100 Chip</div>
                  <button
                    class="btn btn-primary-custom btn-exchange"
                    id="exchangeVIPtoChip"
                    data-cash="450"
                    data-chip="100"
                    data-type="cash-to-chip-vip"
                    disabled
                  >
                    Tukar Sekarang
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 mb-3">
              <div class="exchange-card">
                <div class="exchange-header">
                  <i class="bi bi-cash"></i> Cash to Chip
                </div>
                <div class="exchange-body">
                  <div class="row">
                    <div class="exchange-rate col-6">5 Cash = 1 Chip</div>
                    <div class="exchange-rate col-6">4.5 Cash = 1 Chip (VIP)</div>
                  </div>
                  <div class="d-flex flex-column justify-content-center align-items-center w-100">
                    <input id="custom-cash-to-chip" type="number" class="w-100 m-4 rounded p-2" placeholder="Masukkan jumlah chip yang ingin Anda tukar">
                    <button
                      type="submit"
                      class="btn btn-primary-custom btn-exchange-custom"
                      data-type="custom-cash-to-chip"
                    >
                      Tukar Sekarang
                    </button>
                  </div>
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
                  <i class="bi bi-coin"></i> 5 Chip
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
                  <i class="bi bi-coin"></i> 10 Chip
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
                  <i class="bi bi-coin"></i> 25 Chip
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
                  <i class="bi bi-coin"></i> 50 Chip
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
                  <i class="bi bi-coin"></i> 100 Chip
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
                  <i class="bi bi-coin"></i> 100 Chip (VIP)
                </div>
                <div class="exchange-body">
                  <div class="exchange-rate">1 Chip = 4.5 Cash</div>
                  <div class="exchange-amount">450 Cash</div>
                  <button
                    class="btn btn-success-custom btn-exchange"
                    id="exchangeVIPtoCash"
                    data-cash="450"
                    data-chip="100"
                    data-type="chip-to-cash-vip"
                    disabled
                  >
                    Tukar Sekarang
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 mb-3">
              <div class="exchange-card">
                <div class="exchange-header">
                  <i class="bi bi-cash"></i> Cash to Chip
                </div>
                <div class="exchange-body">
                  <div class="row">
                    <div class="exchange-rate col-6">1 Chip = 4 Cash</div>
                    <div class="exchange-rate col-6">1 Chip = 4.5 Cash (VIP)</div>
                  </div>
                  <input id="custom-chip-to-cash" type="number" class="w-100 m-4 rounded p-2">
                  <button
                    type="submit"
                    class="btn btn-primary-custom btn-exchange-custom"
                    data-type="custom-chip-to-cash"
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