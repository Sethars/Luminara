<?php
$title = "Russian Roulette";
$css = "RRLobby_list"; 
$script = [
    "logout",
    "RRLobby_list"
];
$checkAuth = true;

include_once __DIR__ . '/../components/header.php';
?>

<div id="main-content">
    <!-- Navbar -->
    <?php include_once __DIR__ . '/../components/navbar.php'; ?>

    <!-- Main Content -->
    <main class="container py-4">
      <!-- Section Header -->
      <div class="mb-4">
        <h2 class="h3 fw-bold mb-1">Daftar Lobby</h2>
        <p class="text-secondary">
          Bergabung atau buat lobby baru untuk memulai permainan
        </p>
      </div>

      <!-- Action Buttons -->
      <div class="row g-3 mb-4">
        <div class="col-12 col-md-auto">
          <button class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#createLobbyModal">
            <i class="fas fa-plus-circle me-2"></i>
            Buat Lobby Baru
          </button>
        </div>

        <!-- Search Bar -->
        <div class="col">
          <div class="position-relative">
            <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
            <input
              type="text"
              id="searchInput"
              placeholder="Cari lobby..."
              class="form-control search-input ps-5"
            />
          </div>
        </div>

        <!-- Refresh Button -->
        <div class="col-12 col-md-auto">
          <button class="btn btn-gray w-100" id="btn-refresh">
            <i class="fas fa-refresh me-2" id="refresh-icon"></i>
            <span class="btn-refresh">Refresh</span>
          </button>
        </div>

        <!-- Rules Button -->
        <div class="col-12 col-md-auto">
          <button class="btn btn-gray w-100" data-bs-toggle="modal" data-bs-target="#rulesModal">
            <i class="fas fa-book me-2"></i>
            <span class="btn-rules-text">Rules</span>
          </button>
        </div>
      </div>

      <!-- Lobby List -->
      <div id="lobbyList"></div>

      <!-- Pagination -->
      <div id="pagination" class="d-flex justify-content-center align-items-center gap-2 mb-4"></div>

      <!-- Empty State -->
      <div id="emptyState" class="text-center py-5 d-none">
        <i class="fas fa-search display-4 text-secondary mb-3"></i>
        <h3 class="h5 fw-semibold mb-2">Tidak ada lobby ditemukan</h3>
        <p class="text-secondary">Coba kata kunci lain atau buat lobby baru</p>
      </div>
    </main>

    <!-- Create Lobby Modal -->
    <div class="modal fade" id="createLobbyModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title text-danger">Buat Lobby Baru</h3>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form id="createLobbyForm">
              <div class="mb-3" id="lobby-name">
                <label class="form-label text-secondary">Nama Lobby</label>
                <input type="text" id="lobbyName" placeholder="Masukkan nama lobby" class="form-control" />
              </div>

              <div class="mb-3">
                <label class="form-label text-secondary">Mode Permainan</label>
                <select id="gameMode" required class="form-select">
                  <option value="">Pilih mode permainan</option>
                  <option value="pvp">Player vs Player</option>
                  <option value="bot">Player vs Bot</option>
                </select>
              </div>

              <div id="modeDisplay"></div>

              <div class="mb-3">
                <label class="form-label text-secondary">Taruhan (Chips)</label>
                <div class="position-relative">
                  <input type="number" id="lobbyBet" required min="10" value="10" placeholder="Minimal 10 chips" class="form-control" />
                  <div class="position-absolute top-50 end-0 translate-middle-y me-3 text-secondary">
                    <i class="fas fa-coins"></i>
                  </div>
                </div>
                <small class="text-secondary">Minimal taruhan: 10 chips</small>
              </div>

              <div class="mb-3" id="passwordDisplay">
                <label class="form-label text-secondary">Password (Opsional)</label>
                <div class="position-relative">
                  <input type="password" id="lobbyPassword" placeholder="Password" class="form-control" />
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-gray" data-bs-dismiss="modal">Batal</button>
            <button type="submit" form="createLobbyForm" id="createLobbyButton" class="btn btn-red">Buat Lobby</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Rules Modal -->
    <div class="modal fade" id="rulesModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title text-danger">
              Aturan Permainan Russian Roulette
            </h3>
            <button
              type="button"
              class="btn-close btn-close-white"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body rules-content">
            <div class="rules-section">
              <h4 class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                Peringatan
              </h4>
              <p>
                Permainan ini bersifat simulasi dan menggunakan chips virtual.
                Tidak ada uang asli yang terlibat dalam permainan ini.
              </p>
            </div>

            <div class="rules-section">
              <h4 class="d-flex align-items-center">
                <i class="fas fa-gamepad text-primary me-2"></i>
                Cara Bermain
              </h4>
              <ol class="ps-3">
                <li>Setiap pemain menaruh taruhan yang disepakati</li>
                <li>
                  Revolver berisi 6 peluru, 1 di antaranya adalah peluru asli
                </li>
                <li>
                  Pemain bergantian menembakkan revolver ke arah kepala sendiri
                </li>
                <li>
                  Pemain yang terkena peluru asli kalah dan kehilangan taruhan
                </li>
                <li>Pemain yang selamat memenangkan taruhan</li>
              </ol>
            </div>

            <div class="rules-section">
              <h4 class="d-flex align-items-center">
                <i class="fas fa-users text-success me-2"></i>
                Mode Permainan
              </h4>
              <div class="row g-3">
                <div class="col-12">
                  <div class="mode-display">
                    <h5 class="fw-semibold mb-1">Player vs Player</h5>
                    <p class="small text-secondary">
                      Bermain melawan pemain lain. Tunggu pemain lain bergabung
                      sebelum memulai permainan.
                    </p>
                  </div>
                </div>
                <div class="col-12">
                  <div class="mode-display">
                    <h5 class="fw-semibold mb-1">Player vs Bot</h5>
                    <p class="small text-secondary">
                      Bermain melawan AI (komputer). Bot akan menjadi lawan Anda
                      dalam permainan.
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <div class="rules-section">
              <h4 class="d-flex align-items-center">
                <i class="fas fa-coins text-warning me-2"></i>
                Sistem Taruhan
              </h4>
              <ul class="ps-3">
                <li>Taruhan minimal: 10 chips</li>
                <li>Pemenang mendapatkan 100% taruhan (dikurangi fee 5%)</li>
                <li>Taruhan harus disetujui oleh semua pemain</li>
              </ul>
            </div>

            <div class="rules-section">
              <h4 class="d-flex align-items-center">
                <i class="fas fa-shield-alt text-purple me-2"></i>
                Fair Play
              </h4>
              <p>
                Sistem menggunakan RNG (Random Number Generator) untuk memastikan
                hasil permainan adil dan acak. Setiap pemain memiliki peluang yang
                sama untuk menang atau kalah.
              </p>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-red"
              data-bs-dismiss="modal"
            >
              Mengerti
            </button>
          </div>
        </div>
      </div>
    </div>


    <!-- Toast Container -->
    <div class="toast-container">
      <div id="toast" class="custom-toast"></div>
    </div>

    <!-- Password modal -->
     <div class="modal fade" id="password-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Masukkan Password</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <label for="password-lobby">Password</label>
            <input type="password" name="password-lobby" id="password-lobby" class="form-control">
            <p id="msg"></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keluar</button>
            <button type="button" class="btn btn-primary" id="join-private-lobby">Masuk</button>
          </div>
        </div>
      </div>
    </div>

</div>

<div id="loading" class="d-none">
    <?php include_once __DIR__ . '/../components/loadingScreen.php' ?>
</div>

<?php include_once __DIR__ . '/../components/footer.php'; ?>
