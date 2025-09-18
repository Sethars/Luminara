<?php

$title = "Reset Password";
$css = "change_password"; 
$script = "resetPassword";
$checkAuth = false; 

?>
<?php
include_once __DIR__ . '/../components/header.php';
?>

<div class="container d-flex align-items-center justify-content-center vh-100">
  <div class="col-md-5">
    <div class="card shadow-lg border-0 rounded-4">
      <div class="card-body p-4">
        <h3 class="text-center mb-4">Reset Password</h3>
        
        <br>
        <form method="POST" action="" id="resetPasswordForm">
          <!-- token hidden -->
          <input type="hidden" name="token" value="">

          <div class="mb-3">
            <label for="password" class="form-label">Email</label>
            <input type="email" placeholder="Masukan alamat email" class="form-control" id="email" name="password"  required>
          </div>
          <br>
            <p>
              <span id="messageSuccess" style="color: lime;"></span>
              <span id="messageFailed" style="color: red;"></span>
            </p>
          <br>
        
          <button type="submit" id="sendEmail" class="btn gradient-gold w-100">
            <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
            <span id="btnText">Kirim Email</span>
          </button>
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
    <img src="../assets/img/reset_password/rst_pass.jpg" alt="Reset Password Illustration" class="img-fluid position-absolute top-50 start-50 translate-middle img2 d-none d-lg-block">
    <img src="../assets/img/reset_password/rst_pass2.jpg" alt="Reset Password Illustration" class="img-fluid position-absolute top-50 start-50 translate-middle img1 d-none d-lg-block">
    <img src="../assets/img/reset_password/rst_pass.jpg" alt="Reset Password Illustration" class="img-fluid position-absolute top-50 start-50 translate-middle img4 d-lg-none">
    <img src="../assets/img/reset_password/rst_pass2.jpg" alt="Reset Password Illustration" class="img-fluid position-absolute top-50 start-50 translate-middle img5 d-lg-none ">
</div>

<?php
include_once __DIR__ . '/../components/footer.php';
?>