<?php

$title = "Luminara";
$css = "dashboard"; 
$script = [
    "logout",
    "dashboard"
];
$checkAuth = true;

?>


<div id="main-content">
    <?php
    include_once __DIR__ . '/../components/header.php';
    ?>

    <?php
    include_once __DIR__ . '/../components/navbar.php';
    ?>

    <?php
    include_once __DIR__ . '/../components/footer.php';
    ?>
</div>

<div id="loading" class="d-none">
    <?php
    include_once __DIR__ . '/../components/loadingScreen.php' 
    ?>
</div>