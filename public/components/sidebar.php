

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="index.php" class="brand-link">
    <img src="../assets/img/favico.png" alt="Logo"
         class="brand-image img-circle" style="opacity: .8">
    <span class="brand-text font-weight-light">Luminara</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user -->
     <div class="user-panel mt-3 pb-3 mb-3">
        <div class=" mt-3 pb-3 mb-3 d-flex">
          <div class="image">
              <img src="../assets/img/photo_profile/ppkosong.jpg"
                  class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
              <a id="username" href="/profile" class="d-block"></a>
          </div>
        </div>
        
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column"
          data-widget="treeview" role="menu" data-accordion="false">

        <!-- Dashboard -->
        <li class="nav-item">
          <a href="/" class="nav-link active">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Logout Button -->
        <li class="nav-item">
          <form id="logoutForm" method="POST" action="logout.php" style="margin:0;">
            <button type="submit" class="nav-link active btn-danger text-left">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p class="ml-2">Logout</p>
            </button>
          </form>
        </li>

        <!-- Master Data -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="bi bi-dice-3-fill"></i>
            <p>
              Gambling
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/BJLobby" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Black Jack</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="devices.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Roulette</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Settings -->
        <li class="nav-item">
          <a href="settings.php" class="nav-link">
            <i class="nav-icon fa fa-user-o "></i>
            <p>Settings</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>