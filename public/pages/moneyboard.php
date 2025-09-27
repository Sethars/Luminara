<?php

$title = "Leaderboard";
$css = "leaderboard"; 
$script = [
    "logout",
    "leaderboard"
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

    <div class="main-content">
        <div class="container">
            <div class="page-header">
                <h1><i class="fas fa-trophy"></i> Leaderboard</h1>
                <p class="subtitle">Peringkat Gambler Terbaik</p>
            </div>

            <div class="search-container">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Cari nama pengguna...">
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <div class="leaderboard-container">
                <div class="leaderboard-header">
                    <h2><i class="fas fa-crown"></i> Top Gambler Terkaya</h2>
                </div>

                <div class="table-responsive">
                    <table class="leaderboard-table">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Pengguna</th>
                                <th>Money</th>
                            </tr>
                        </thead>
                        <tbody id="leaderboardBody">
                            <!-- Data akan diisi oleh JavaScript -->
                        </tbody>
                    </table>
                </div>

                <div class="no-results" id="noResults" style="display: none;">
                    <i class="fas fa-search"></i>
                    <h3>Tidak ada hasil ditemukan</h3>
                    <h3>atau kurang jago</h3>
                    <p>Coba kata kunci lain</p>
                </div>
            </div>
        </div>
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