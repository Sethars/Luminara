<?php

$title = "Verifikasi ";
$css = "login"; 
$script = "verification";
$checkAuth = false; // Tidak perlu autentikasi untuk halaman verifikasi

?>
<?php
include_once __DIR__ . '/../components/header.php';
?>

<div class="container-fluid">
  <div class="row">
    <!-- 3/4 bagian kiri -->
    <div class="flex-column col-lg-7 left-side d-none d-lg-flex align-items-center justify-content-center">
      <!-- Bisa isi gambar/banner kalau mau -->
      <img src="../assets/img/logo.png" alt="Login Banner" class="img-fluid w-50">
       <strong class="penjelasan">Lorem, ipsum dolor sit amet consectetur</strong>
       <br>

      <div id="carouselExampleFade" class="carousel slide carousel-fade w-75" data-bs-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="../assets/img/1.png" class="d-block w-100" alt="...">
          </div>
          <div class="carousel-item">
            <img src="../assets/img/2.png" class="d-block w-100" alt="...">
          </div>
          <div class="carousel-item">
            <img src="../assets/img/3.png" class="d-block w-100" alt="...">
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </div>  




    <!-- LOGIN -->
    <div class="col-lg-5 p-4 right-side bg-white flex-column align-items-start justify-content-center">
      <div class="w-100">
        <div class="d-flex d-lg-none justify-content-center mb-4">
          <!-- Logo or Image for Mobile View -->
         <img src="../assets/img/logo.png" alt="Logo" style="width: 250px;" >
        </div>
        <h3><strong>Verifikasi Email</strong></h3>
        <div class="d-flex  mb-4">
          <p class="text-secondary mb-4">Masukan kode OTP yang dikirim melewati email sebelum <p style="opacity: 0;">_</p> <strong style="color:#c73232;" id="timer">05:00</strong> </p>
        </div>
        
        <form id="verificationForm">

          <!-- Email -->
          <div class="col-auto">
            <label for="otp">Kode OTP</label>
          </div>
          <div class="row">
            <div class="col">
              <input type="text" class="form-control" id="otp" placeholder="Masukan kode OTP" aria-label="otp">
            </div>
            <div class="col">
            <button id="resendOTP" class="btn gradient-gold w-100 w-md-50" type="button">
                <strong>Kirim ulang</strong>
            </button>
            </div>
          </div>
          <p id="warningText" class="text-danger"></p>

          <br>
          <!-- Button -->
          <button type="submit" class="btn gradient-gold w-100">
                <strong>
                  Lanjut
                </strong>
          </button>
        </form>
        <hr>
          <div class="d-flex justify-content-between mt-3">
            <p class="mb-0">
              Belum punya akun? 
              <a style="color:#f8c471;" href="register">Daftar</a>
            </p>
          </div>
      </div>
    </div>
  </div>
</div>


<?php
include_once __DIR__ . '/../components/footer.php';
?>