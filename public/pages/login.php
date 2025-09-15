<?php

$title = "Login";
$css = "login"; 
$script = "login";
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
        <h3><strong>Selamat datang kembali!</strong></h3>
        <p class="text-secondary mb-4">Login untuk melanjutkan</p>
        
        <form id="loginForm">

          <!-- Email -->
          <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" required>
          </div>
          <!-- Password -->
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
              <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
              <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                  <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>
          <br>
          <p id="warningText" class="text-danger"></p>
          <!-- Button -->
           <div class="d-grid gap-2 mb-3 d-flex justify-content-center">
              <button type="button" 
                      class="btn gradient-gold w-100" 
                      onclick="showModal('loginModal');">
                <strong>
                  Demo
                </strong>
              </button>
              <button type="submit" class="btn gradient-gold w-100"><strong>Login</strong></button>
           </div>



        </form>
        <hr>
          <div class="d-flex justify-content-between mt-3">
            <p class="mb-0">
              Belum punya akun? 
              <a style="color:#f8c471;" href="register">Daftar</a>
            </p>
            <p class="mb-0">
              Lupa password?
              <a style="color:#f8c471;" href="resetPassword">Iya,hehe</a>
            </p>
          </div>
      </div>
    </div>
  </div>
</div>

<?php
include_once __DIR__ . '/../components/footer.php';
?>

<div class="modal fade" id="loginSuccess" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <img class="bottom-0 end-0" src="../assets/img/modal/modal_img.png" alt="Loading..." style="width: 12.8rem; height: 12.8rem; position: absolute; z-index: 0; opacity: 0.5;">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Login Berhasil</h1>
      </div>
      <div class="modal-body">
        <p id="warningMessage"></p>
        Anda akan diarahkan ke halaman utama.
      </div>
      <div class="modal-footer">
        <button onclick="window.location.href='/'" type="button" class="btn btn-login" style="z-index: 1;">
          <strong>
            Oke
          </strong>
        </button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="loginFailed" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <img class="bottom-0 end-0" src="../assets/img/modal/modal_gagal_img.jpeg" alt="Loading..." style="width: auto; height: 12.8rem; position: absolute; z-index: 0; opacity: 1;">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Login Gagal</h1>
      </div>
      <div class="modal-body">
        <p id="warningMessage"></p>
        Email atau password salah.
      </div>
      <div class="modal-footer">
        <button onclick="closeModal('loginFailed')" type="button" class="btn btn-login" style="z-index: 1;">
          <strong>
            Oke
          </strong>
        </button>
      </div>
    </div>
  </div>
</div>



