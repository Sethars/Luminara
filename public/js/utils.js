// ambil data user dan profile dari localStorage
const user = JSON.parse(localStorage.getItem("user"));
const profile = JSON.parse(localStorage.getItem('profile'));

window.isDemo = function () {
  const isDemo = JSON.parse(localStorage.getItem("demo"));

  if (isDemo === true) {
    return true;
  }
};

//SEMENTARA
// const formData = { started_at: document.getElementById("lottery_date").value };
window.makeEvent = function(day) {
  const formData = { started_at: day };
  
  fetch("/api/createEventLottery", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Authorization": "Bearer " + token // <-- pastikan token valid ya
    },
    body: JSON.stringify(formData)
  })
  .then(res => res.json())
  .then(data => console.log(data));
}

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
  Lottery_Winner: "fa fa-ticket",
  TOP_1: "fa fa-trophy",
  TOP_2: "fa fa-trophy",
  TOP_3: "fa fa-trophy",
};

const badgeStyles = {
  VIP: "bg-gradient bg-warning text-dark fw-bold border border-dark", // emas mewah
  Developer: "bg-success text-white",
  Moderator: "bg-primary text-white border border-primary",
  Beta_Tester: "bg-secondary text-white",
  WS5: "bg-orange text-dark fw-bold border border-orange shadow-sm",
  Racist: "bg-dark text-white border border-danger", // hitam
  Lottery_Winner: "bg-warning text-white border border-danger fw-bold shadow",
  TOP_1: "bg-warning text-dark fw-bold border border-warning shadow-sm",
  TOP_2: "bg-secondary text-white fw-bold border border-secondary shadow-sm",
  TOP_3: "bg-orange text-dark fw-bold border border-orange shadow-sm",
};

//fungsi hapus _
function underscoreDelete(str) {
  if (typeof str !== "string") return str; // jaga-jaga kalau bukan string
  return str.includes("_") ? str.replace(/_/g, " ") : str;
}

// fungsi render
function renderBadges (container, badges) {
  if (typeof badges === "string") {
    badges = JSON.parse(badges);
  }

  const badgeContainer = document.querySelector(container) || "";
  if (badgeContainer) {
    badgeContainer.innerHTML = "";
    if(badges.length > 0){
      badges.forEach((badge) => {
        const li = document.createElement('li');
        li.className = "list-group-item badge-item";
        li.dataset.badge = badge;
        li.innerHTML = `<i class="${
          badgeIcons[badge] || "fa-solid fa-star"
        } me-1"></i>${underscoreDelete(badge)}`;
        badgeContainer.appendChild(li);
      });
    }
  }
};

function renderPreviewBadges(container, badges, defaultText){
  const badgeContainer = document.querySelector(container) || "";
  if (badgeContainer) {
    badgeContainer.innerHTML = "";
    if (badges.length > 0) {
      badges.forEach((badge) => {
        const span = document.createElement("span");
        span.className = `badge ${badgeStyles[badge] || "bg-dark text-white"} me-1`;
        span.innerHTML = `<i class="${
          badgeIcons[badge] || "fa-solid fa-star"
        } me-1"></i>${underscoreDelete(badge)}`;
        badgeContainer.appendChild(span);
      });
    } else {
      badgeContainer.innerHTML = `<span class="text-muted small">${defaultText}</span>`;
    }
  }
}


