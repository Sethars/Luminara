import { addBadge } from "../module_js/add_badges.js";
import { formatMoney } from "../module_js/format_money.js";

document.addEventListener("DOMContentLoaded", function () {
  let allUsers = []; // simpan semua data leaderboard

  // Fungsi render badges hanya tampilkan yg "used"
  function renderBadges(badges) {
    if (!badges) return "";
    if (typeof badges === "string") {
      try {
        badges = JSON.parse(badges);
      } catch (e) {
        return "";
      }
    }
    if (!badges.used || badges.used.length === 0) return "";

    return badges.used
      .map(
        (badge) => `
        <span class="badge ${badgeStyles[badge] || "bg-dark text-white"} me-1">
          <i class="${badgeIcons[badge] || "fa-solid fa-star"}"></i> ${badge}
        </span>
      `
      )
      .join("");
  }

  function renderLeaderboard(data) {
    const tbody = document.getElementById("leaderboardBody");
    const noResults = document.getElementById("noResults");

    if (!data || data.length === 0) {
      tbody.innerHTML = "";
      noResults.style.display = "block";
      return;
    }

    noResults.style.display = "none";
    tbody.innerHTML = data
      .map(
        (user, index) => `
        <tr style="animation-delay: ${index * 0.1}s">
          <td class="rank rank-${user.rank <= 3 ? user.rank : ""}">#${
          user.rank
        }</td>
          <td>
            <div class="user-info">
              <img src="${user.avatar}" alt="${user.name}" class="user-avatar">
              <div class="user-details">
                <h3>${user.name}</h3>
                <div class="badges">${renderBadges(user.badges)}</div>
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

  // Fungsi pencarian + Easter Egg
  function searchUsers(query) {
    if (!query) {
      renderLeaderboard(allUsers);
      return;
    }
    // normal search
    const filtered = allUsers.filter((user) =>
      user.name.toLowerCase().includes(query.toLowerCase())
    );
    renderLeaderboard(filtered);
    // Easter Egg: deteksi kata "istereg"
    if (
      query.toLowerCase().includes("ireng") ||
      query.toLowerCase().includes("nigger") ||
      query.toLowerCase().includes("nigga")
    ) {
      const tbody = document.getElementById("leaderboardBody");
      const noResults = document.getElementById("noResults");
      noResults.style.display = "none";

      tbody.insertAdjacentHTML(
        "afterbegin",
        `
      <tr style="animation-delay: 0s">
        <td></td>
        <td colspan="3" class="text-center easter-egg">
          <div class="easter-egg-box">
            <p>🎉 Badges Rahasia Unlocked!</p>
          </div>
        </td>
      </tr>
      `
      );

      addBadge("Racist");
    }
  }

  // Fetch leaderboard
  fetch("api/leaderboard")
    .then((res) => {
      if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
      return res.json();
    })
    .then((res) => {
      if (res.success) {
        allUsers = res.data;
        renderLeaderboard(allUsers);
      } else {
        console.error(res.message, res.error);
      }
    })
    .catch((err) => console.error("Fetch error:", err));

  // Event listener input search
  const searchInput = document.getElementById("searchInput");
  searchInput.addEventListener("input", function () {
    searchUsers(this.value);
  });
});

setInterval(async () => {
  try {
    const res = await fetch("api/updateTopRank");
    const text = await res.text(); // ambil raw
    try {
      const data = JSON.parse(text);
      console.log("Update badge:", data.message);
    } catch (e) {
      console.error("Response bukan JSON:", text);
    }
  } catch (err) {
    console.error("Fetch error:", err);
  }
}, 1000);
