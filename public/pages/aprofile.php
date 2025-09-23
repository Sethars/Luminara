<?php

$title = "Profile";
$css = "aprofile"; 
$script = [
    "logout",
    "aprofile"
];
$checkAuth = true;



$id = $_GET['id'] ?? 0;

if (!$id) {
    echo "User ID tidak ditemukan.";
    exit;
}


$stmt = $conn->prepare("

SELECT 
    users.*, 
    profiles.*, 
    economy.*
FROM users
LEFT JOIN profiles ON profiles.user_id = users.id
LEFT JOIN economy ON economy.user_id = users.id
WHERE users.id = ? LIMIT 1;



");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    echo "User tidak ditemukan.";
    exit;
}





?>




    <?php
    include_once __DIR__ . '/../components/header.php';
    ?>

<div id="main-content">


    <?php
    include_once __DIR__ . '/../components/navbar.php';
    ?>




    









    <?php
    include_once __DIR__ . '/../components/footer.php'
    ?>
</div>

<div id="loading" class="d-none">
    <?php
    include_once __DIR__ . '/../components/loadingScreen.php' 
    ?>
</div>