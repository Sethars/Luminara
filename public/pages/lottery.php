<?php
$title = "Luminara Money Jumpscare";
$css = "lottery"; 
$script = [
    "logout",
    "lottery"
];
$checkAuth = true;
?>

<?php include_once __DIR__ . '/../components/header.php'; ?>

    <?php include_once __DIR__ . '/../components/navbar.php'; ?>

    <div id="main-content" style="">


        <div class="lottery-container position-absolute top-50 start-50 translate-middle">
            <h1 class="lottery-title">Luminara Money Jumpscare</h1>

            <div class="jackpot-display floating" id="jackpot-display">
                <i class="fas fa-trophy"></i> HADIAH UTAMA:
                <span id="jackpot-amount">50,000</span>
            </div>

            <div class="winner-announcement" id="winner-announcement">
                <i class="fas fa-crown"></i> PEMENANG: <span id="winner-name"></span>
            </div>

            <div class="countdown-container">
                <h3 class="countdown-title">Waktu Tersisa</h3>
                <div class="countdown" id="countdown">
                    <div class="countdown-item">
                        <div class="countdown-value" id="days">00</div>
                        <div class="countdown-label">Hari</div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-value" id="hours">00</div>
                        <div class="countdown-label">Jam</div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-value" id="minutes">00</div>
                        <div class="countdown-label">Menit</div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-value" id="seconds">00</div>
                        <div class="countdown-label">Detik</div>
                    </div>
                </div>
            </div>

            <div class="ticket-section">
                <div class="ticket-price">
                    <span class="price-label" style="color:white;">Harga Tiket:</span>
                    <span class="price-value">5000Cash</span>
                </div>
                <button class="buy-button" id="buy-ticket">Beli Tiket Sekarang</button>
            </div>

            <div class="ticket-display" id="ticket-display" style="display:none">
                <h3 class="ticket-title">Tiket Anda</h3>
                <div class="lottery-numbers" id="ticket-numbers"></div>
                <div class="ticket-info">
                    <span>ID Tiket: <span id="ticket-id"></span></span>
                    <span>Tanggal: <span id="ticket-date"></span></span>
                </div>
            </div>

            <div class="history-section">
                <h3 class="history-title">Riwayat Pembelian</h3>
                <div class="history-list" id="history-list">
                    <p style="text-align: center; color: #ccc">Belum ada pembelian</p>
                </div>
            </div>
        </div>

        <!-- Tombol peraturan -->
        <div class="rules-button" id="rules-button">
            <i class="fas fa-info"></i>
        </div>

        <!-- Modal peraturan -->
        <div id="rules-modal" class="modal">
            <div class="modal-content">
                <span class="close-modal" id="close-modal">&times;</span>
                <h2 class="modal-title">Peraturan Lotre</h2>
                <div class="modal-text">
                    <p>Selamat datang di Luminara Money Jumpscare! Berikut adalah peraturan yang berlaku:</p>
                    <ul>
                        <li>Setiap tiket berharga 5000Cash</li>
                        <li>Setiap pembelian tiket akan menambah 50% dari harga tiket ke hadiah utama</li>
                        <li>Pemenang akan ditentukan setelah countdown berakhir</li>
                        <li><strong>Hanya ada 1 pemenang</strong> yang akan mendapatkan seluruh hadiah utama</li>
                        <li>Pemenang adalah pemegang tiket dengan nomor yang cocok dengan nomor yang diundi</li>
                        <li>Hasil undian akan diumumkan secara transparan setelah periode lotre berakhir</li>
                        <li>Tiket yang sudah dibeli tidak dapat dikembalikan</li>
                        <li>Hadiah akan langsung ditransfer kepada pemenang setelah pengumuman</li>
                    </ul>
                    <p>Terima kasih telah berpartisipasi dalam Luminara Money Jumpscare. Semoga beruntung!</p>
                </div>
            </div>
        </div>

        <div class="notification" id="notification" style="margin-top:75px;"></div>
    </div>

    <?php include_once __DIR__ . '/../components/footer.php'; ?>


<div id="loading" class="d-none">
    <?php include_once __DIR__ . '/../components/loadingScreen.php'; ?>
</div>
