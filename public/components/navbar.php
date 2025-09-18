<style>
/* Mobile-specific adjustments */
@media (max-width: 768px) {
  /* Make dropdown full width on mobile */
  .dropdown-menu {
    position: static !important;
    float: none !important;
    width: 100% !important;
    border: none !important;
    box-shadow: none !important;
    margin-top: 0 !important;
  }
  
  /* Style submenu items */
  .dropdown-submenu .dropdown-menu {
    position: static !important;
    transform: none !important;
    padding-left: 1.5rem !important;
  }
  
  /* Add indicator for submenu items */
  .dropdown-submenu > .dropdown-item::after {
    content: "›";
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.5rem;
  }
  
  /* Style active submenu */
  .dropdown-submenu.show > .dropdown-menu {
    display: block !important;
  }
  
  /* Add back button for submenu */
  .dropdown-back {
    display: block;
    padding: 0.5rem 1rem;
    color: #6c757d;
    border-bottom: 1px solid rgba(0,0,0,.15);
  }
  
  .dropdown-back:hover {
    background-color: #f8f9fa;
  }
}
</style>
<style>
/* Submenu style */
.dropdown-submenu {
  position: relative;
}

.dropdown-submenu > .dropdown-menu {
  top: 0;
  left: 100%;
  margin-top: -6px;
  margin-left: 0;
  display: none;
}

/* Desktop hover tetap jalan */
.dropdown-submenu:hover > .dropdown-menu {
  display: block;
}

/* Mobile: submenu muncul ke bawah, bukan ke samping */
@media (max-width: 991px) {
  .dropdown-submenu > .dropdown-menu {
    left: 0;
    top: 100%;
    margin-top: 0;
  }
}
</style>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container-fluid">
    <!-- Brand -->
    <a href="/" class="navbar-brand d-flex align-items-center">
      <img src="../assets/img/favico.png" alt="Logo" class="rounded-circle" style="height:32px; opacity:.85;">
      <span class="ms-2 fw-bold text-">Luminara</span>
    </a>

    <!-- Toggler -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
      aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar Menu -->
    <div class="collapse navbar-collapse" id="navbarMain">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <!-- Dashboard -->
        <li class="nav-item">
          <a href="/" class="nav-link">
            <i class="fas me-1"></i> Dashboard
          </a>
        </li>
        <!-- Gambling Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarGambling" role="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="bi bi-dice-3-fill me-1"></i> Gambling
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarGambling">
            <li class="dropdown-submenu">
              <a class="dropdown-item dropdown-toggle" href="#" id="bjSubmenu"
                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="far fa-circle me-2"></i> Black Jack
              </a>
              <ul class="dropdown-menu" aria-labelledby="bjSubmenu">
                <li>
                  <a class="dropdown-item" href="/BJLobbyCS">
                    <i class="bi bi-controller me-2"></i> Casual
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="/BJLobby">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128" style="enable-background:new 0 0 128 128; width: 1em;" xml:space="preserve"><path style="fill:#231f20" d="M102.263 128H25.737a5.821 5.821 0 0 1-5.815-5.815V5.815A5.82 5.82 0 0 1 25.737 0h76.526a5.821 5.821 0 0 1 5.815 5.815v116.369a5.821 5.821 0 0 1-5.815 5.816zM25.737 3.877a1.94 1.94 0 0 0-1.938 1.938v116.369a1.94 1.94 0 0 0 1.938 1.938h76.526a1.94 1.94 0 0 0 1.938-1.938V5.815a1.94 1.94 0 0 0-1.938-1.938H25.737zm52.535 51.921c-4.565-5.06-10.064-12.028-13.796-19.689C57.012 51.43 42.674 64 42.674 64s3.638 3.143 8.202 8.202S60.94 84.23 64.672 91.891C72.136 76.571 86.475 64 86.475 64s-3.63-3.143-8.203-8.202zM41.977 15.043c-1.662-1.842-3.663-4.378-5.022-7.166-2.717 5.576-7.936 10.152-7.936 10.152s1.324 1.144 2.986 2.986c1.661 1.842 3.663 4.378 5.022 7.166 2.717-5.576 7.936-10.152 7.936-10.152s-1.321-1.144-2.986-2.986zm53.634 93.475c-1.661-1.842-3.663-4.378-5.022-7.166-2.717 5.576-7.936 10.152-7.936 10.152s1.324 1.144 2.986 2.986c1.661 1.842 3.663 4.378 5.022 7.166 2.717-5.576 7.936-10.152 7.936-10.152s-1.322-1.144-2.986-2.986z"/></svg>
                    <i class="bi me-2"></i> Twenty One
                  </a>
                </li>
              </ul>
            </li>
            <li>
              <a class="dropdown-item" href="devices.php">
                <i class="far fa-circle me-2"></i> Roulette
              </a>
            </li>
          </ul>
        </li>
        <!-- Settings -->
        <!-- <li class="nav-item">
          <a href="settings.php" class="nav-link">
            <i class="fa fa-user-o me-1"></i> Settings
          </a>
        </li> -->
        <!-- Contact -->

      </ul>

      <!-- Right Side: User Profile & Logout -->
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarUser" role="button"
            data-bs-toggle="dropdown" aria-expanded="false">
            <img src="../assets/img/photo_profile/ppkosong.jpg" class="rounded-circle border" alt="User Image"
              style="height:32px; width:32px; margin-right:8px;">
            <span id="username" class="fw-semibold">Username</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUser">
            <li>
              <a class="dropdown-item" href="/profile">
                <i class="fas fa-user me-2"></i> Profile
              </a>
            <li class="nav-item">
            <li><hr class="dropdown-divider"></li>
              <a href="/contact" class="dropdown-item">
                <i class="fas fa-envelope me-2"></i> Contact
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form id="logoutForm" method="POST" action="logout.php" style="margin:0;">
                <button type="submit" class="dropdown-item text-danger">
                  <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
              </form>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Handle submenu toggle on mobile
  const submenuToggles = document.querySelectorAll('.dropdown-submenu > .dropdown-toggle');
  
  submenuToggles.forEach(function(toggle) {
    toggle.addEventListener('click', function(e) {
      if (window.innerWidth <= 768) {
        e.preventDefault();
        e.stopPropagation();
        
        const parent = this.parentElement;
        const submenu = parent.querySelector('.dropdown-menu');
        
        // Add back button if not exists
        if (!submenu.querySelector('.dropdown-back')) {
          const backButton = document.createElement('li');
          backButton.className = 'dropdown-back';
          backButton.innerHTML = '<a class="dropdown-item" href="#"><i class="bi bi-arrow-left me-2"></i> Back</a>';
          submenu.prepend(backButton);
          
          backButton.addEventListener('click', function(e) {
            e.preventDefault();
            parent.classList.remove('show');
          });
        }
        
        // Toggle submenu
        parent.classList.toggle('show');
      }
    });
  });
});
</script>