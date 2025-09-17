<?php

$title = "Profile";
$css = "profile"; 
$script = [
    "logout",
    "profile"
];
$checkAuth = true;


$badgeStyles = [
  "VIP"         => "bg-warning text-dark fw-bold border border-warning", // emas mewah
  "Developer"   => "bg-success text-white",
  "Moderator"   => "bg-info text-white",
  "Beta Tester" => "bg-secondary text-white"
];

$badgeIcons = [
  "VIP"         => "fa fa-diamond me-2",     // diamond
  "Developer"   => "fa fa-code me-2",    // code
  "Moderator"   => "fa fa-shield me-2",  // shield
  "Beta Tester" => "fa fa-flask me-2"    // flask
];

$badges = [
    "used" => ["VIP", "Developer"],
    "unused" => [ "Moderator", "Beta Tester"]
];

$username   = $user["username"] ?? "Kitasan's Father";
$bio        = $user["bio"] ?? "Bio pengguna akan tampil di sini...";
$gender     = $user["gender"] ?? "-";
$photo      = $user["photo"] ?? "";

$photoPath = !empty($photo) ? "/assets/img/photo_profile/" . htmlspecialchars($photo) : "/assets/img/photo_profile/ppkosong.jpg";

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
                    src="<?= $photoPath ?>"
                    class="rounded-circle me-3"
                    width="100"
                    height="100"
                    alt="Foto Profil">

                <!-- Info -->
                <div>
                    <h4 id="preview-username"><?= htmlspecialchars($username) ?></h4>
                    <p id="preview-bio" class="text-muted mb-1"><?= htmlspecialchars($bio) ?></p>
                    <p id="preview-gender" class="small text-secondary">Gender: <?= htmlspecialchars($gender) ?></p>
                    
                    <!-- Badge -->
                    <div id="preview-badges" class="d-flex flex-wrap gap-2 mt-2">
                        <?php
                        if (!empty($badges["used"])) {
                            foreach ($badges["used"] as $badge) {
                                $style = $badgeStyles[$badge] ?? "bg-dark text-white";
                                $icon  = $badgeIcons[$badge] ?? "fa-solid fa-star"; // default icon
                                echo '<span class="badge ' . $style . '">
                                        <i class="' . $icon . ' me-1"></i>' . htmlspecialchars($badge) . '
                                    </span>';
                            }
                        } else {
                            echo '<span class="text-muted small">Tidak ada badge yang digunakan</span>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>


        <h3 class="mb-3">Edit Data</h3>

        <!-- Card Nama -->
        <div class="card mb-3">
            <div class="card-header">Nama</div>
            <div class="card-body">
                <input type="text" id="username" class="form-control mb-3" placeholder="Masukkan nama anda">
                <button class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>

        <!-- Card Foto Profile -->
        <div class="card mb-3">
            <div class="card-header">Foto Profile</div>
            <div class="card-body d-flex align-items-center">
                <img src="<?= $photoPath ?>" alt="Profile" id="photo-preview-mini"
                     class="rounded-circle me-3" width="80" height="80">
                <input type="file" id="profilePhoto" class="form-control me-3">
                <button class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>

        <!-- Card Bio -->
        <div class="card mb-3">
            <div class="card-header">Bio</div>
            <div class="card-body">
                <textarea id="bio" class="form-control mb-3" rows="3" placeholder="Tuliskan sesuatu tentang dirimu"></textarea>
                <button class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>

        <!-- Card Gender -->
        <div class="card mb-3">
            <div class="card-header">Gender</div>
            <div class="card-body">
                <select id="gender" class="form-select mb-3">
                    <option value="">Pilih gender</option>
                    <option value="male">Laki-laki</option>
                    <option value="female">Perempuan</option>
                    <option value="other">Kapal Tempur</option>
                </select>
                <button class="btn btn-primary">Simpan Perubahan</button>
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
                        <ul id="used-badges" class="list-group min-vh-25 border p-2">
                            <?php foreach ($badges["used"] as $badge): ?>
                                <li class="list-group-item badge-item" data-badge="<?= $badge ?>">
                                    <i class="<?= $badgeIcons[$badge] ?> me-1"></i><?= $badge ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Unused Badges -->
                    <div class="col-md-6">
                        <h6>Tidak Digunakan</h6>
                        <ul id="unused-badges" class="list-group min-vh-25 border p-2">
                            <?php foreach ($badges["unused"] as $badge): ?>
                                <li class="list-group-item badge-item" data-badge="<?= $badge ?>">
                                    <i class="<?= $badgeIcons[$badge] ?> me-1"></i><?= $badge ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <button id="saveBadgesBtn" class="btn btn-primary mt-3">Simpan Perubahan</button>
            </div>
        </div>


        <!-- Card Ganti Password -->
        <div class="card mb-3">
            <div class="card-header">Ganti Password</div>
            <div class="card-body">
                <input type="password" id="oldPassword" class="form-control mb-2" placeholder="Password lama">
                <input type="password" id="newPassword" class="form-control mb-2" placeholder="Password baru">
                <input type="password" id="confirmPassword" class="form-control mb-3" placeholder="Konfirmasi password baru">
                <button class="btn btn-primary">Simpan Perubahan</button>
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
      <div class="modal-header">
        <h5 class="modal-title" id="deleteAccountLabel">Konfirmasi Hapus Akun</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <p>Masukkan password untuk mengkonfirmasi penghapusan akun:</p>
        <input type="password" id="deletePassword" class="form-control" placeholder="Password anda">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger">Hapus Permanen</button>
      </div>
    </div>
  </div>
</div>


<!-- TODO: bentuk json badge 

{
  "used": ["VIP"],
  "unused": ["Developer", "Moderator", "Beta Tester"]
}
  

-->
