<?php

$title = "Luminara";
$css = "dashboard"; 
$script = [
    "logout",
    "dashboard"
];
$checkAuth = true;

?>

    <?php
    include_once __DIR__ . '/../components/header.php';
    ?>

<div id="main-content">


    <?php
    include_once __DIR__ . '/../components/navbar.php';
    ?>

    <div style="width:100%; height:90vh; display:flex; justify-content:center; align-items:center;">
        <img src="https://scontent.fjog3-1.fna.fbcdn.net/v/t39.30808-6/535051286_1889578228623699_4432076639860256711_n.jpg?stp=dst-jpg_p552x414_tt6&_nc_cat=106&ccb=1-7&_nc_sid=aa7b47&_nc_eui2=AeHDL-0Jcj-nqZxqqfhrjhDuRFLiKSTqQZhEUuIpJOpBmBZsHvTtDXHR70wMWcrUOC69HP4DjlBA5fBvp_pLM55Q&_nc_ohc=YCcQqLrLgq0Q7kNvwG7aqch&_nc_oc=Adn-YpqrccMqHj9g5eKXZ30bLrvEYeacFUn0444xOOJV5Ybzpos5F7PNrlD6RnvOc-c&_nc_zt=23&_nc_ht=scontent.fjog3-1.fna&_nc_gid=XgzO989u_Wwy_j_-4tZy8w&oh=00_AfYE9X_24eomraQVzvja4XgphpdEEn7ZaD_PbAirFTMtJA&oe=68D7E134" alt="" style="width:500px; height:500px;">
    </div>


    <?php
    include_once __DIR__ . '/../components/footer.php';
    ?>
</div>

<div id="loading" class="d-none">
    <?php
    include_once __DIR__ . '/../components/loadingScreen.php' 
    ?>
</div>