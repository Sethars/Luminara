<?php

$title = "Casual Black Jack Lobby";
$css = "BJLobby_cs"; 
$script = [
    "logout",
    "BJLobby_cs",
    "dashboard"
];
$checkAuth = true;
?>

    <?php include_once __DIR__ . '/../components/header.php'; ?>
    <?php include_once __DIR__ . '/../components/navbar.php'; ?>

<div class="lobby-container">
    <div class="lobby-header">
        <h2 class="lobby-title">Daftar Lobbys</h2>
        <button class="create-btn" onclick="createLobby()">+ Create Lobby</button>
        <input type="text" id="searchLobby" class="search-input" placeholder="Search lobby...">
    </div>

    <div class="lobby-list" id="lobbyList">
        <?php
        $lobbies = [
            [   
                "creator" => "Aventurine",
                "profile" => "aven.jpg",
                "desc" => "All or nothing."
            ],
            [
                "creator" => "Ubur-Ubur Ivy",
                "profile" => "March 7th Evernight₊˚⊹⋆.jpg",
                "desc" => "DIEEE."
            ],
            [
                "creator" => "Pemain Blackjack Nomor 1",
                "profile" => "download (6)",
                "desc" => "Lobby high stakes, hanya untuk yang berani."
            ]

        ];

        foreach ($lobbies as $lobby): ?>
            <div class="lobby-card">
                <img src="<?= !empty($lobby['profile']) ? "../assets/img/photo_profile/" . $lobby['profile'] : '../assets/img/photo_profile/ppkosong.jpg' ?>"  
                     alt="profile" 
                     class="lobby-profile">
                <div class="lobby-info">
                    <h3><?= htmlspecialchars($lobby['creator']) ?></h3>
                    <p><?= htmlspecialchars($lobby['desc']) ?></p>
                </div>
                <button class="join-btn" onclick="joinLobby('<?= $lobby['creator'] ?>')">Gabung</button>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<?php include_once __DIR__ . '/../components/footer.php'; ?>