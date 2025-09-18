<?php
$title = "Contact";
$css = "kontak"; 
$script = ["logout", "contact"];
$checkAuth = true;

// === Data dummy users ===
$users = [
    [
        "id"      => 1,
        "username"=> "RajaNagaHitam",
        "profile" => "",
        "unread"  => 0
    ],
    [
        "id"      => 2,
        "username"=> "Castorice",
        "profile" => "cass.jpg", 
        "unread"  => 0
    ],
    [
        "id"      => 3,
        "username"=> "Asep Bensin",
        "profile" => "aven.jpg", 
        "unread"  => 1
    ],
];

// === Data dummy chat (per userId) ===
$messages = [
    1 => [
        [ "sender" => "RajaNagaHitam", "type" => "received", "text" => "Halo, apa kabar?" ],
        [ "sender" => "Me", "type" => "sent", "text" => "Baik, kamu?" ],
    ],
    2 => [
        [ "sender" => "Castorice", "type" => "received", "text" => "Udah makan?" ],
        [ "sender" => "Me", "type" => "sent", "text" => "Belum nih, suapin!" ],
    ],
    3 => [
        [ "sender" => "Asep Bensin", "type" => "received", "text" => "Bro, pinjem duit dong!" ],
    ],
];
?>

<div id="main-content">
  <?php include_once __DIR__ . '/../components/header.php'; ?>
  <?php include_once __DIR__ . '/../components/navbar.php'; ?>

  <div class="chat-container">
    <!-- Sidebar kiri -->
    <div class="chat-sidebar">
      <div class="search-box">
        <input type="text" id="searchUser" class="form-control" placeholder="Cari teman...">
      </div>
      <ul class="chat-list list-unstyled mt-3" id="chatUserList">
        <?php foreach ($users as $u): ?>
          <li class="chat-user d-flex align-items-center p-2" data-user="<?= $u['id'] ?>">
            <img src="<?= !empty($u['profile']) ? '/assets/img/photo_profile/' . htmlspecialchars($u['profile']) : '/assets/img/photo_profile/ppkosong.jpg' ?>"
                 alt="<?= htmlspecialchars($u['username']) ?>"
                 class="rounded-circle me-2" width="35" height="35">

            <span><?= htmlspecialchars($u['username']) ?></span>

            <?php if (!empty($u['unread']) && $u['unread'] > 0): ?>
              <span class="badge bg-danger ms-auto"><?= $u['unread'] ?></span>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <!-- Area Chat kanan -->
    <div class="chat-main">
      <div class="chat-header">
        <strong id="chatWith">Pilih teman</strong>
      </div>
      <div class="chat-messages" id="chatMessages">
        <p class="text-muted">Belum ada percakapan</p>
      </div>
      <div class="chat-input">
        <input type="text" id="chatText" class="form-control" placeholder="Tulis pesan...">
        <button id="sendBtn" class="btn btn-primary ms-2">
          <i class="bi bi-send-fill"></i>
        </button>
      </div>
    </div>
  </div>

  <?php include_once __DIR__ . '/../components/footer.php'; ?>
</div>

<div id="loading" class="d-none">
  <?php include_once __DIR__ . '/../components/loadingScreen.php' ?>
</div>

<script>
  // Pass data PHP ke JS
  window.chatData = {
    messages: <?= json_encode($messages); ?>
  };
</script>
