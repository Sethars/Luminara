<?php

$title = "Luminara";
$css = "dashboard"; 
$script = [
    "logout",
    "dashboard"
];
$checkAuth = true;

?>

<div class="loading">
    <?php
    include_once __DIR__ . '/../components/' 
    ?>
</div>

<div class="main-content">
    <?php
    include_once __DIR__ . '/../components/header.php';
    ?>

    <?php
    include_once __DIR__ . '/../components/navbar.php';
    ?>

    <?php
    include_once __DIR__ . '/../components/sidebar.php';
    ?>

    <?php
    include_once __DIR__ . '/../components/footer.php';
    ?>
</div>