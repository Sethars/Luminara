document.addEventListener("DOMContentLoaded", function () {
  // Handle submenu toggle on mobile
  const submenuToggles = document.querySelectorAll(
    ".dropdown-submenu > .dropdown-toggle"
  );

  submenuToggles.forEach(function (toggle) {
    toggle.addEventListener("click", function (e) {
      if (window.innerWidth <= 768) {
        e.preventDefault();
        e.stopPropagation();

        const parent = this.parentElement;
        const submenu = parent.querySelector(".dropdown-menu");

        // Add back button if not exists
        if (!submenu.querySelector(".dropdown-back")) {
          const backButton = document.createElement("li");
          backButton.className = "dropdown-back";
          backButton.innerHTML =
            '<a class="dropdown-item" href="#"><i class="bi bi-arrow-left me-2"></i> Back</a>';
          submenu.prepend(backButton);

          backButton.addEventListener("click", function (e) {
            e.preventDefault();
            parent.classList.remove("show");
          });
        }

        // Toggle submenu
        parent.classList.toggle("show");
      }
    });
  });

  // Data dummy untuk leaderboard
  const leaderboardData = [
    {
      rank: 1,
      name: "Ahmad Rizki",
      money: 9850,
      avatar: "https://picsum.photos/seed/user1/100/100",
    },
    {
      rank: 2,
      name: "Siti Nurhaliza",
      money: 8750,
      avatar: "https://picsum.photos/seed/user2/100/100",
    },
    {
      rank: 3,
      name: "Budi Santoso",
      money: 8200,
      avatar: "https://picsum.photos/seed/user3/100/100",
    },
    {
      rank: 4,
      name: "Maya Putri",
      money: 7650,
      avatar: "https://picsum.photos/seed/user4/100/100",
    },
    {
      rank: 5,
      name: "Rizky Pratama",
      money: 7200,
      avatar: "https://picsum.photos/seed/user5/100/100",
    },
    {
      rank: 6,
      name: "Dewi Lestari",
      money: 6800,
      avatar: "https://picsum.photos/seed/user6/100/100",
    },
    {
      rank: 7,
      name: "Fajar Nugroho",
      money: 6450,
      avatar: "https://picsum.photos/seed/user7/100/100",
    },
    {
      rank: 8,
      name: "Intan Permata",
      money: 6100,
      avatar: "https://picsum.photos/seed/user8/100/100",
    },
    {
      rank: 9,
      name: "Hendra Wijaya",
      money: 5800,
      avatar: "https://picsum.photos/seed/user9/100/100",
    },
    {
      rank: 10,
      name: "Rina Susanti",
      money: 5500,
      avatar: "https://picsum.photos/seed/user10/100/100",
    },
  ];

  // Fungsi untuk format mata uang
  function formatMoney(amount) {
    // Format menjadi bilangan bulat dengan pemisah ribuan titik
    return amount.toLocaleString("id-ID");
  }

  // Fungsi untuk render leaderboard
  function renderLeaderboard(data) {
    const tbody = document.getElementById("leaderboardBody");
    const noResults = document.getElementById("noResults");

    if (data.length === 0) {
      tbody.innerHTML = "";
      noResults.style.display = "block";
      return;
    }

    noResults.style.display = "none";
    tbody.innerHTML = data
      .map(
        (user, index) => `
                    <tr style="animation-delay: ${index * 0.1}s">
                        <td class="rank rank-${
                          user.rank <= 3 ? user.rank : ""
                        }">#${user.rank}</td>
                        <td>
                            <div class="user-info">
                                <img src="${user.avatar}" alt="${
          user.name
        }" class="user-avatar">
                                <div class="user-details">
                                    <h3>${user.name}</h3>
                                </div>
                            </div>
                        </td>
                        <td class="money">
                            <i class="fas fa-dollar-sign money-icon"></i>
                            ${formatMoney(user.money)}
                        </td>
                    </tr>
                `
      )
      .join("");
  }

  // Fungsi pencarian
  function searchUsers(query) {
    const filtered = leaderboardData.filter((user) =>
      user.name.toLowerCase().includes(query.toLowerCase())
    );
    renderLeaderboard(filtered);
  }

  // Event listeners
  document.getElementById("searchInput").addEventListener("input", (e) => {
    searchUsers(e.target.value);
  });

  // Initial render
  renderLeaderboard(leaderboardData);
});
