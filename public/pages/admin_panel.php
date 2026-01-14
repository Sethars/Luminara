<?php

$title = "AdminLuminara";
$css = "admin_panel"; 
$script = [
    "admin_panel",
];
$checkAuth = true;

?>

    <?php
    include_once __DIR__ . '/../components/header.php';
    ?>

    <div id="main-content">
            <!-- Sidebar -->
            <aside class="sidebar" id="sidebar">
                <div class="sidebar-header">
                    <div class="logo"><i class="fas fa-cube"></i> Luminara</div>
                    <button id="closeSidebar" class="close-btn d-flex d-lg-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <nav class="sidebar-menu">
                    <a href="#" class="menu-item active" data-section="dashboard">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="#" class="menu-item" data-section="announcement">
                        <i class="fa fa-bullhorn"></i> Announcement
                    </a>
                    <a href="#" class="menu-item" data-section="users">
                        <i class="fas fa-users"></i> Users
                    </a>
                    <a href="#" class="menu-item" data-section="lottery">
                        <i class="fas fa-dice"></i> Lottery Event
                    </a>
 
                    <a href="#" class="menu-item" data-section="event">
                        <i class="fa fa-calendar"></i> Admin Event
                    </a>                    
                    <a href="/" class="menu-item logout">
                        <i class="fas fa-sign-out-alt"></i> Keluar Admin Panel
                    </a>
                </nav>
            </aside>

        <header class="header">
            <div style="display: flex; align-items: center; gap: 20px">
                <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <i class="fas fa-bars"></i>
                </button>
            </div>

            <div class="header-actions">
                <div class="user-profile">
                <img
                    id="photo-profile-admin"
                    alt="User"
                    class="user-avatar"
                />
                <div>
                    <div style="font-weight: 600" id="username-admin">Admin</div>
                </div>
                </div>
            </div>
        </header>
            
        <div id="admin_dashboard" class="" style="display:block;">
            <?php include __DIR__ . '/../components/admin_dashboard.php'; ?>
        </div>

        <div id="admin_users_dashboard" class="" style="display:none;">
            <?php include __DIR__ . '/../components/admin_users_dashboard.php'; ?>
        </div>

        <div id="admin_lottery_dashboard" class="" style="display:none;">
            <?php include __DIR__ . '/../components/admin_lottery_dashboard.php'; ?>
        </div>

        <div id="admin_event_dashboard" class="" style="display:none;">
            <?php include __DIR__ . '/../components/admin_event_dashboard.php'; ?>
        </div>

        <div id="admin_announcement_dashboard" class="" style="display:none;">
            <?php include __DIR__ . '/../components/admin_announcement_dashboard.php'; ?>
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