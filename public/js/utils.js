// ambil data user dan profile dari localStorage
const user = JSON.parse(localStorage.getItem("user"));
const profile = JSON.parse(localStorage.getItem('profile'));

const token = localStorage.getItem("token");
window.token = token;

window.isDemo = function () {
  const isDemo = JSON.parse(localStorage.getItem("demo"));

  if (isDemo === true) {
    return true;
  }
};

// Tampilkan username
window.showUsernameAndPp = function () {
  const el = document.getElementById("username");
  const epp = document.getElementById("navbar-profile-photo");
  const isEpp = JSON.parse(localStorage.getItem("profile")) || {};
  if (el && epp) {
    el.textContent = user ? user.username : "Demo";
    epp.src = isEpp.photo
      ? isEpp.photo
      : "/assets/img/photo_profile/ppkosong.jpg";
  } else {
    console.error("Element #username not found.");
  }
};

// Auto jalan setelah DOM siap
document.addEventListener("DOMContentLoaded", function () {
  window.showUsernameAndPp();
});

function isDifferent(a, b) {
  return JSON.stringify(a) !== JSON.stringify(b);
}

// TODO: STYLE BADGE
const badgeIcons = {
  VIP: "fa fa-diamond me-2", // diamond
  Developer: "fa fa-code me-2", // code
  Moderator: "fa fa-shield me-2", // shield
  Beta_Tester: "fa fa-flask me-2", // flask
  WS5: "fa fa-fire me-2", // fire
  Racist: "fas fa-skull-crossbones me-2", // skull
};

const badgeStyles = {
  VIP: "bg-warning text-dark fw-bold border border-warning", // emas mewah
  Developer: "bg-success text-white",
  Moderator: "bg-info text-white",
  Beta_Tester: "bg-secondary text-white",
  WS5: "bg-warning text-dark", // win streak 5
  Racist: "bg-dark text-white", // hitam
};

//fungsi hapus _
function underscoreDelete(str) {
  if (typeof str !== "string") return str; // jaga-jaga kalau bukan string
  return str.includes("_") ? str.replace(/_/g, " ") : str;
}

// fungsi render
window.renderBadges = function (badges) {
  if (typeof badges === "string") {
    badges = JSON.parse(badges);
  }

  const usedContainer = document.getElementById("used-badges") || "";
  if (usedContainer) {
    usedContainer.innerHTML = "";
    badges.used.forEach((badge) => {
      const li = document.createElement("li");
      li.className = "list-group-item badge-item";
      li.dataset.badge = badge;
      li.innerHTML = `<i class="${
        badgeIcons[badge] || "fa-solid fa-star"
      } me-1"></i>${underscoreDelete(badge)}`;
      usedContainer.appendChild(li);
    });
  }

  const unusedContainer = document.getElementById("unused-badges") || "";
  if (unusedContainer) {
    unusedContainer.innerHTML = "";
    badges.unused.forEach((badge) => {
      const li = document.createElement("li");
      li.className = "list-group-item badge-item";
      li.dataset.badge = badge;
      li.innerHTML = `<i class="${
        badgeIcons[badge] || "fa-solid fa-star"
      } me-1"></i>${underscoreDelete(badge)}`;
      unusedContainer.appendChild(li);
    });
  }

  const previewContainer = document.getElementById("preview-badges") || "";
  if (previewContainer) {
    previewContainer.innerHTML = "";
    if (badges.used.length > 0) {
      badges.used.forEach((badge) => {
        const span = document.createElement("span");
        span.className = `badge ${badgeStyles[badge] || "bg-dark text-white"}`;
        span.innerHTML = `<i class="${
          badgeIcons[badge] || "fa-solid fa-star"
        } me-1"></i>${underscoreDelete(badge)}`;
        previewContainer.appendChild(span);
      });
    } else {
      previewContainer.innerHTML = `<span class="text-muted small">Tidak ada badge yang digunakan</span>`;
    }
  }
};
