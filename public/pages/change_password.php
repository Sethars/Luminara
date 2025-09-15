<?php

$title = "Change Password";
$css = "change_password"; 
$script = "changeResetPassword";
$type = "text/javascript";
$checkAuth = false; 

?>
<?php
include_once __DIR__ . '/../components/header.php';
?>

<div class="container d-flex align-items-center justify-content-center vh-100">
  <div class="col-md-5">
    <div class="card shadow-lg border-0 rounded-4">
      <div class="card-body p-4">
        <h3 class="text-center mb-4">Change Your Password</h3>

        <form id="changePasswordForm" method="POST">
          
          <div class="mb-3">
            <label for="password" class="form-label">Password Baru</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>

          <div class="mb-3">
            <label for="confirmPassword" class="form-label">Konfirmasi Password</label>
            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
          </div>

          <button type="submit" class="btn gradient-gold w-100">Simpan Password</button>
        </form>

        <div class="text-center mt-3">
          <a href="/login" class="text-decoration-none lnkLgn">Kembali ke Login</a>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
    <!-- <img src="../assets/img/rst_pass.jpg" alt="Reset Password Illustration" class="img-fluid position-absolute bottom-0 end-0" style="width: 300px; opacity: 0.1;"> -->
    <!-- <img src="../assets/img/rst_pass.jpg" alt="Reset Password Illustration" class="img-fluid position-absolute top-0 start-0" style="width: 300px; opacity: 0.1;"> -->
    <img src="../assets/img/reset_password/rst_pass.jpg" alt="Reset Password Illustration" class="img-fluid position-absolute top-50 start-50 translate-middle img2 d-none d-lg-block" style="">
    <img src="../assets/img/reset_password/rst_pass2.jpg" alt="Reset Password Illustration" class="img-fluid position-absolute top-50 start-50 translate-middle img1 d-none d-lg-block" style="">
    <img src="../assets/img/reset_password/rst_pass.jpg" alt="Reset Password Illustration" class="img-fluid position-absolute top-50 start-50 translate-middle img4 d-lg-none" style="">
    <img src="../assets/img/reset_password/rst_pass2.jpg" alt="Reset Password Illustration" class="img-fluid position-absolute top-50 start-50 translate-middle img5 d-lg-none " style="">
</div>

<!-- Modal -->
<div class="modal fade" id="warningModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
      </div>
      <div class="modal-body">
        <p id="warningMessage"></p>
        Silahkan klik tombol "Understood" untuk mengirim ulang email reset password.
      </div>
      <div class="modal-footer">
        <button onclick="window.location.href='/resetPassword'" type="button" class="btn btn-primary">Understood</button>
      </div>
    </div>
  </div>
</div>

<?php
include_once __DIR__ . '/../components/footer.php';
?>