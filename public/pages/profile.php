<?php

$title = "Profile";
$css = "profile"; 
$script = [
    "logout",
    "profile"
];
$checkAuth = true;

$id = 1;    
$user_id = 1; 

$username   = $user["username"] ?? "Demo";
$bio        = $user["bio"] ?? "Bio pengguna akan tampil di sini...";
$gender     = $user["gender"] ?? "-";
$photo      = $user["photo"] ?? "";
$cash       = $user["cash"] ?? 1000;

$photoPath = !empty($photo) ? "/assets/img/photo_profile/" . htmlspecialchars($photo) : 
"/assets/img/photo_profile/ppkosong.jpg";
?>

<div id="main-content">
    <?php include_once __DIR__ . '/../components/header.php'; ?>
    <?php include_once __DIR__ . '/../components/navbar.php'; ?>

    <div class="container my-4">
        <h2 class="mb-4">Profile</h2>

        <!-- Preview Profile -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light">
                <strong>Preview Profil Publik</strong>
            </div>
            <div class="card-body d-flex align-items-center">
                <?php
                ?>

                <!-- Foto -->
                <img id="preview-photo"
                    class="rounded-circle me-3"
                    width="100"
                    height="100"
                    alt="Foto Profil">

                <!-- Info -->
                <div>
                    <h4 id="preview-username"></h4>
                    <p id="preview-bio" class="text-muted mb-1"></p>
                    <p class="small text-secondary">Gender: <span id="preview-gender"></span></p>
                    
                    <!-- Badge -->
                    <div id="preview-badges" class="d-flex flex-wrap gap-2 mt-2"></div>
                </div>
            </div>
            <div>
                <div class="card-footer text-end">
                    <span class="text-muted small">Cash: $<?= number_format($cash, 0, ',', '.') ?></span>
            </div>
        </div>


        <h3 class="mb-3">Edit Data</h3>

        <!-- Card Nama -->
        <div class="card mb-3">
            <div class="card-header">Nama</div>
            <div class="card-body">
                <form id="changeNameForm">
                    <input type="text" id="newUsername" class="form-control mb-3" placeholder="Masukkan nama anda">
                    <p id="changeUsernameMsg"></p>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>

        <!-- Card Foto Profile -->
        <div class="card mb-3">
            <div class="card-header">Foto Profile</div>
            <div class="card-body d-flex align-items-center">
                <img src="/assets/img/photo_profile/ppkosong.jpg" alt="Profile" id="photo-preview-mini"
                     class="rounded-circle me-3" width="80" height="80">
                <input type="file" id="profilePhoto" class="form-control me-3">
                <p id="changePPMsg"></p>
                <button id="changePPBtn" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>

        <!-- Card Bio -->
        <div class="card mb-3">
            <div class="card-header">Bio</div>
            <div class="card-body">
                <form id="changeBioForm">
                    <textarea id="newBio" class="form-control mb-3" rows="3" placeholder="Tuliskan sesuatu tentang dirimu (0-50)"></textarea>
                    <p id="changeBioMsg"></p>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>

        <!-- Card Gender -->
        <div class="card mb-3">
            <div class="card-header">Gender</div>
            <div class="card-body">
                <select id="newGender" class="form-select mb-3">
                    <option value="">Pilih gender</option>
                    <option value="Male">Laki-laki</option>
                    <option value="Female">Perempuan</option>
                    <option value="Dragunov">Dragunov</option>
                </select>
                <p id="changeGenderMsg"></p>
                <button id="changeGenderBtn" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>

        <!-- Card Badge -->
        <div class="card mb-3">
            <div class="card-header">Badge</div>
            <div class="card-body">
                <p class="text-muted">Drag & Drop badge untuk mengatur mana yang ditampilkan ke publik.</p>
                <div class="row">
                    <!-- Used Badges -->
                    <div class="col-md-6">
                        <h6>Digunakan</h6>
                        <ul id="used-badges" class="list-group min-vh-25 border p-2"></ul>
                    </div>

                    <!-- Unused Badges -->
                    <div class="col-md-6">
                        <h6>Tidak Digunakan</h6>
                        <ul id="unused-badges" class="list-group min-vh-25 border p-2"></ul>
                    </div>
                </div>
                <p id="changeBadgeMsg"></p>
                <button id="saveBadgesBtn" class="btn btn-primary mt-3">Simpan Perubahan</button>
            </div>
        </div>


        <!-- Card Ganti Password -->
        <div class="card mb-3">
            <div class="card-header">Ganti Password</div>
            <div class="card-body">
                <form id="changePasswordForm">
                    <input type="password" id="oldPassword" class="form-control mb-2" placeholder="Password lama">
                    <input type="password" id="newPassword" class="form-control mb-2" placeholder="Password baru">
                    <input type="password" id="confirmPassword" class="form-control mb-3" placeholder="Konfirmasi password baru">
                    <p id="messageChangePassword"></p>
                    <button id="changePasswordBtn" type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>

        <!-- Card Hapus Akun -->
        <div class="card mb-5">
            <div class="card-header text-danger">Hapus Akun</div>
            <div class="card-body">
                <p class="text-muted">Akun anda akan dihapus secara permanen. Masukkan password untuk konfirmasi.</p>
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                    Hapus Akun
                </button>
            </div>
        </div>
    </div>

    <?php include_once __DIR__ . '/../components/footer.php'; ?>
</div>

<div id="loading" class="d-none">
    <?php include_once __DIR__ . '/../components/loadingScreen.php' ?>
</div>

<!-- Modal Konfirmasi Hapus Akun -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="deleteAccountForm">
        <div class="modal-header">
            <h5 class="modal-title" id="deleteAccountLabel">Konfirmasi Hapus Akun</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
            <p>Masukkan password untuk mengkonfirmasi penghapusan akun:</p>
            <input type="password" id="deletePassword" class="form-control" placeholder="Password anda">
            <p id="deleteAccountMsg"></p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button id="deleteAccountBtn" type="submit" class="btn btn-danger">Hapus Permanen</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal berhasil hapus akun -->
<div class="modal fade" id="deleteSuccess" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <img class="bottom-0 end-0" src="../assets/img/modal/modal_img.png" alt="Loading..." style="width: auto; height: 11.9rem; position: absolute; z-index: 0; opacity: 0.5;">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Berhasil Hapus Akun</h1>
      </div>
      <div class="modal-body">
        Anda akan diarahkan ke halaman login.
      </div>
      <div class="modal-footer">
        <button onclick="window.location.href='/login'" type="button" class="btn btn-deleteAcc" style="z-index: 1;">
          <strong>
            Oke
          </strong>
        </button>
      </div>
    </div>
  </div>
</div>