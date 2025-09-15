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
            <a id="username" href="profile.php" class="d-block"></a>
        </div>
        </div>
        <div>
            <form id="logoutForm" method="POST">
                <button type="submit" class="btn btn-danger btn-sm w-100">
                    <i class="fas fa-sign-out-alt"></i><strong>Logout</strong> 
                </button>
            </form>
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

        <!-- Master Data -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-database"></i>
            <p>
              Master Data
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="users.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Users</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="devices.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Devices</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Settings -->
        <li class="nav-item">
          <a href="settings.php" class="nav-link">
            <i class="nav-icon fas fa-cogs"></i>
            <p>Settings</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>