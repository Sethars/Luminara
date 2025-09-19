// ambil data user dari localStorage
const user = JSON.parse(localStorage.getItem("user"));
if (user) {
  window.userId = user.id; // jadi global
}

// Ambil query param dari URL
window.getQueryParam = function (param) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(param);
};

// Show modal (Bootstrap)
window.showModal = function (modalId) {
  const modalElement = document.getElementById(modalId);
  if (modalElement) {
    const modal =
      bootstrap.Modal.getInstance(modalElement) ||
      new bootstrap.Modal(modalElement);
    modal.show();
  } else {
    console.error(`Modal with ID ${modalId} not found.`);
  }
};

// Close modal (Bootstrap)
window.closeModal = function (modalId) {
  const modalElement = document.getElementById(modalId);
  if (modalElement) {
    const modal =
      bootstrap.Modal.getInstance(modalElement) ||
      new bootstrap.Modal(modalElement);
    modal.hide();
  } else {
    console.error(`Modal with ID ${modalId} not found.`);
  }
};

// Loading button handler
window.setLoading = function (isLoading, btnId) {
  const btn = document.getElementById(btnId);

  if (!btn) {
    console.error(`Button with ID ${btnId} not found.`);
    return;
  }

  if (isLoading) {
    btn.disabled = true;
    btn.dataset.originalText = btn.innerHTML;
    btn.innerHTML = `
      <span class="spinner-border spinner-border-sm me-2" role="status"></span>`;
  } else {
    btn.disabled = false;
    btn.innerHTML = btn.dataset.originalText || "Submit";
  }
};

// Generate random string
window.generateRandomString = function (length) {
  const chars =
    "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  let result = "";

  for (let i = 0; i < length; i++) {
    const randomIndex = Math.floor(Math.random() * chars.length);
    result += chars[randomIndex];
  }

  return result;
};

// Update localStorage data
window.updateLocalData = function (storageKey, field, value) {
  const data = JSON.parse(localStorage.getItem(storageKey));

  if (!data) {
    console.error(storageKey + " tidak ditemukan di localStorage");
    return;
  }

  try {
    data[field] = value;
    localStorage.setItem(storageKey, JSON.stringify(data));
    console.log(`${storageKey}.${field} berhasil diupdate jadi:`, value);
  } catch (err) {
    console.error("Gagal update data:", err);
  }
};

window.isDemo = function () {
  const isDemo = JSON.parse(localStorage.getItem("demo"));

  if (isDemo === true) {
    return true;
  }
};

// Tampilkan username
window.showUsernameAndPp = function () {
  const el = document.getElementById("username");
  const epp = document.getElementById('navbar-profile-photo');
  const isEpp = JSON.parse(localStorage.getItem('profile')) || {};
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
  try{
    getDataProfile().then(success => {
      window.showUsernameAndPp();
    })
  } catch(err){}
});

//Ambil data profile
async function getDataProfile() {
  try {
    const res = await fetch("api/getDataProfile", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ userId }),
    });

    const result = await res.json();

    if (result.success) {
      localStorage.setItem("profile", JSON.stringify(result.profile));
      return true;
    }
  } catch (err) {}
}

//Ambil badges tiap 5 menit
let oldBadges = localStorage.getItem("profile")
  ? JSON.parse(localStorage.getItem("profile")).badges
  : null;

function isDifferent(a, b) {
  return JSON.stringify(a) !== JSON.stringify(b);
}

// Loop tiap 5 menit
setInterval(() => {
  getDataProfile().then((success) => {
    if (success) {
      const profile = JSON.parse(localStorage.getItem("profile"));
      const newBadges = profile.badges;

      if (
        isDifferent(oldBadges, newBadges) &&
        window.location.pathname === "/profile"
      ) {
        console.log("Badges berubah, render ulang!");
        oldBadges = newBadges; // update oldBadges
        renderBadges(newBadges);
      }
    }
  });
}, 5 * 60 * 1000);

// TODO: STYLE BADGE
const badgeIcons = {
  VIP: "fa fa-diamond me-2", // diamond
  Developer: "fa fa-code me-2", // code
  Moderator: "fa fa-shield me-2", // shield
  Beta_Tester: "fa fa-flask me-2", // flask
  WS5: "fa fa-fire me-2", // fire
  Racist: "fas fa-skull-crossbones", // skull
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
function underscoreDelete(str){
  if (typeof str !== "string") return str;   // jaga-jaga kalau bukan string
  return str.includes("_") ? str.replace(/_/g, " ") : str;
}

// fungsi render
window.renderBadges = function(badges) {
  if (typeof badges === "string") {
    badges = JSON.parse(badges);
  }

  const usedContainer = document.getElementById("used-badges");
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

  const unusedContainer = document.getElementById("unused-badges");
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

  const previewContainer = document.getElementById("preview-badges");
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

//Add Badge
window.addBadge = async function (newBadge) {
  const profile = JSON.parse(localStorage.getItem('profile'));
  if (!profile) {
    console.error("Profile not found in localStorage");
    return;
  }

  let badges = profile.badges;
  if (typeof badges === "string") {
    try {
      badges = JSON.parse(badges);
    } catch (e) {
      console.error("Gagal parse badges:", e);
      badges = { used: [], unused: [] };
    }
  }
  if (!badges.used) badges.used = [];
  if (!badges.unused) badges.unused = [];

  if (!badges.unused.includes(newBadge) && !badges.used.includes(newBadge)) {
    badges.unused.push(newBadge);
  } else {
    console.log("Badge sudah ada");
    return;
  }
  profile.badges = badges;

  try {
    const res = await fetch("api/updateBadges", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        userId: user.id,
        badgeConfig: badges
      }),
    });

    const data = await res.json();
    localStorage.setItem("profile", JSON.stringify(profile));
  } catch (err) {
    console.error("Error update badge:", err);
  }
}
