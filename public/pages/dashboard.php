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

<div id="main-content" style="  background-color: #0f172a;">

    <?php
    include_once __DIR__ . '/../components/navbar.php';
    ?>

    <br><br>

    <!-- Announcement Section -->
    <div class="announcement">
        <h2><i class="fas fa-bullhorn"></i> Announcement</h2>
        <div class="announcement-ticker" id="announcementTicker">
            <div class="announcement-content" id="announcementContent" style="color:white; padding-bottom:20px;"></div>
        </div>
    </div>

    <br><br>
    <!-- Dashboard Content -->
    <div class="dashboard-container">

        
        <!-- Admin Events Section -->
        <div class="admin-events">
            <h2><i class="fas fa-gift"></i> Admin Events</h2>
            <div class="events-container">
                <div class="event-card">
                    <div class="event-header">Weekend Bonus</div>
                    <div class="event-body">
                        <div class="event-prize">
                            <i class="fas fa-coins"></i>
                            5,000 Chips
                        </div>
                        <div class="event-description">Special weekend bonus for all active players. Claim now before it expires!</div>
                    </div>
                    <div class="event-footer">
                        <button class="claim-btn">Claim</button>
                        <div class="expiry-date">Expires: 2023-12-31</div>
                    </div>
                </div>
                
                <div class="event-card">
                    <div class="event-header">Lucky Draw</div>
                    <div class="event-body">
                        <div class="event-prize">
                            <i class="fas fa-coins"></i>
                            10,000 Chips
                        </div>
                        <div class="event-description">Participate in our lucky draw event for a chance to win big prizes!</div>
                    </div>
                    <div class="event-footer">
                        <button class="claim-btn">Claim</button>
                        <div class="expiry-date">Expires: 2024-01-15</div>
                    </div>
                </div>
                
                <div class="event-card">
                    <div class="event-header">New Year Special</div>
                    <div class="event-body">
                        <div class="event-prize">
                            <i class="fas fa-coins"></i>
                            15,000 Chips
                        </div>
                        <div class="event-description">Celebrate the New Year with our special bonus event. Limited time only!</div>
                    </div>
                    <div class="event-footer">
                        <button class="claim-btn">Claim</button>
                        <div class="expiry-date">Expires: 2024-01-31</div>
                    </div>
                </div>
                
                <div class="event-card">
                    <div class="event-header">High Roller Bonus</div>
                    <div class="event-body">
                        <div class="event-prize">
                            <i class="fas fa-coins"></i>
                            25,000 Chips
                        </div>
                        <div class="event-description">Exclusive bonus for our VIP players. Claim your special reward now!</div>
                    </div>
                    <div class="event-footer">
                        <button class="claim-btn">Claim</button>
                        <div class="expiry-date">Expires: 2024-02-14</div>
                    </div>
                </div>
                
                <div class="event-card">
                    <div class="event-header">Anniversary Event</div>
                    <div class="event-body">
                        <div class="event-prize">
                            <i class="fas fa-coins"></i>
                            50,000 Chips
                        </div>
                        <div class="event-description">Celebrate our casino anniversary with this massive bonus event!</div>
                    </div>
                    <div class="event-footer">
                        <button class="claim-btn">Claim</button>
                        <div class="expiry-date">Expires: 2024-03-01</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Players Section -->
        <div class="players-section">
            <div class="players-header">
                <h2><i class="fas fa-users"></i> Players List</h2>
                <button class="reload-btn" id="reloadPlayers">
                    <i class="fas fa-sync-alt"></i> Reload
                </button>
            </div>
            <div class="players-container" id="playersContainer">

            </div>
        </div>
        
        <!-- Games Section -->
        <div class="games-section">
            <h2><i class="fas fa-gamepad"></i> Games List</h2>
            <div class="games-container">
                <div class="game-card">
                    <div class="game-image">
                        <i class="fas fa-dice fa-3x"></i>
                    </div>
                    <div class="game-info">
                        <div class="game-title">Dice Game</div>
                        <div class="game-description">Roll the dice and test your luck</div>
                    </div>
                </div>
                
                <div class="game-card">
                    <div class="game-image">
                        <i class="fas fa-circle-notch fa-3x"></i>
                    </div>
                    <div class="game-info">
                        <div class="game-title">Roulette</div>
                        <div class="game-description">Spin the wheel and win big</div>
                    </div>
                </div>
                
                <div class="game-card">
                    <div class="game-image">
                        <i class="fas fa-coins fa-3x"></i>
                    </div>
                    <div class="game-info">
                        <div class="game-title">Slot Machine</div>
                        <div class="game-description">Match symbols to win prizes</div>
                    </div>
                </div>
                
                <div class="game-card">
                    <div class="game-image">
                        <i class="fas fa-dice-d20 fa-3x"></i>
                    </div>
                    <div class="game-info">
                        <div class="game-title">Craps</div>
                        <div class="game-description">Classic dice game with multiple bets</div>
                    </div>
                </div>
                
                <div class="game-card">
                    <div class="game-image">
                        <i class="fas fa-crown fa-3x"></i>
                    </div>
                    <div class="game-info">
                        <div class="game-title">Blackjack</div>
                        <div class="game-description">Beat the dealer without going over 21</div>
                    </div>
                </div>
                
                <div class="game-card">
                    <div class="game-image">
                        <i class="fas fa-poker-chip fa-3x"></i>
                    </div>
                    <div class="game-info">
                        <div class="game-title">Poker</div>
                        <div class="game-description">Test your skills in this card game</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Dashboard Content -->
     <br><br>

    <?php
    include_once __DIR__ . '/../components/footer.php';
    ?>
</div>

<div id="loading" class="d-none">
    <?php
    include_once __DIR__ . '/../components/loadingScreen.php' 
    ?>
</div>