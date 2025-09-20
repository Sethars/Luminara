document.addEventListener("DOMContentLoaded", function () {
  // Initialize balances
  let chipBalance = parseInt(localStorage.getItem("chipBalance")) || 0;
  let cashBalance = parseInt(localStorage.getItem("cashBalance")) || 0;
  let isVIP = localStorage.getItem("isVIP") === "true";

  // Update balance display
  updateBalanceDisplay();

  // Check daily login
  checkDailyLogin();

  // Check welcome bonus
  checkWelcomeBonus();

  // Start countdown
  startCountdown();

  // Event listeners
  document
    .getElementById("dailyLoginBtn")
    .addEventListener("click", claimDailyLogin);
  document
    .getElementById("welcomeBonusBtn")
    .addEventListener("click", claimWelcomeBonus);
  document.getElementById("buyVipBtn").addEventListener("click", buyVIP);

  // Exchange buttons
  const exchangeButtons = document.querySelectorAll(".btn-exchange[data-type]");
  exchangeButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const type = this.getAttribute("data-type");
      const cash = parseInt(this.getAttribute("data-cash"));
      const chip = parseInt(this.getAttribute("data-chip"));

      if (type === "cash-to-chip") {
        exchangeCashToChip(cash, chip);
      } else if (type === "chip-to-cash") {
        exchangeChipToCash(chip, cash);
      } else if (type === "cash-to-chip-vip") {
        if (isVIP) {
          exchangeCashToChip(cash, chip);
        } else {
          showNotification("Hanya untuk member VIP!", "error");
        }
      } else if (type === "chip-to-cash-vip") {
        if (isVIP) {
          exchangeChipToCash(chip, cash);
        } else {
          showNotification("Hanya untuk member VIP!", "error");
        }
      }
    });
  });

  function updateBalanceDisplay() {
    document.getElementById("chipBalance").textContent = chipBalance;
    document.getElementById("cashBalance").textContent = cashBalance;

    // Save to localStorage
    localStorage.setItem("chipBalance", chipBalance);
    localStorage.setItem("cashBalance", cashBalance);
  }

  function checkDailyLogin() {
    const lastLoginDate = localStorage.getItem("lastLoginDate");
    const today = new Date().toDateString();

    if (lastLoginDate === today) {
      // Already claimed today
      document.getElementById("dailyLoginCard").classList.add("claimed");
      document.getElementById("dailyLoginBtn").disabled = true;
      document.getElementById("dailyLoginBtn").textContent = "Sudah Diambil";
    }
  }

  function checkWelcomeBonus() {
    const welcomeBonusClaimed =
      localStorage.getItem("welcomeBonusClaimed") === "true";

    if (welcomeBonusClaimed) {
      // Already claimed
      document.getElementById("welcomeBonusCard").classList.add("claimed");
      document.getElementById("welcomeBonusBtn").disabled = true;
      document.getElementById("welcomeBonusBtn").textContent = "Sudah Diambil";
    }
  }

  function claimDailyLogin() {
    const lastLoginDate = localStorage.getItem("lastLoginDate");
    const today = new Date().toDateString();

    if (lastLoginDate !== today) {
      // Can claim
      chipBalance += 100;
      if (isVIP) {
        chipBalance += 400; // Extra bonus for VIP
      }
      updateBalanceDisplay();

      // Set last login date
      localStorage.setItem("lastLoginDate", today);

      // Update UI
      document.getElementById("dailyLoginCard").classList.add("claimed");
      document.getElementById("dailyLoginBtn").disabled = true;
      document.getElementById("dailyLoginBtn").textContent = "Sudah Diambil";

      // Show notification
      if (isVIP) {
        showNotification("Berhasil klaim 500 Chip (Bonus VIP)!", "success");
      } else {
        showNotification("Berhasil klaim 100 Chip!", "success");
      }
    }
  }

  function claimWelcomeBonus() {
    const welcomeBonusClaimed =
      localStorage.getItem("welcomeBonusClaimed") === "true";

    if (!welcomeBonusClaimed) {
      // Can claim
      chipBalance += 1000;
      updateBalanceDisplay();

      // Set as claimed
      localStorage.setItem("welcomeBonusClaimed", "true");

      // Update UI
      document.getElementById("welcomeBonusCard").classList.add("claimed");
      document.getElementById("welcomeBonusBtn").disabled = true;
      document.getElementById("welcomeBonusBtn").textContent = "Sudah Diambil";

      // Show notification
      showNotification("Berhasil klaim 1000 Chip!", "success");
    }
  }

  function buyVIP() {
    if (isVIP) {
      showNotification("Anda sudah menjadi member VIP!", "error");
      return;
    }

    if (cashBalance >= 100000) {
      cashBalance -= 100000;
      isVIP = true;
      localStorage.setItem("isVIP", "true");
      updateBalanceDisplay();
      showNotification("Selamat! Anda sekarang adalah member VIP!", "vip");

      // Update VIP button
      document.getElementById("buyVipBtn").textContent = "Anda adalah VIP";
      document.getElementById("buyVipBtn").disabled = true;
    } else {
      showNotification("Cash Anda tidak mencukupi untuk membeli VIP!", "error");
    }
  }

  function exchangeCashToChip(cashAmount, chipAmount) {
    if (cashBalance >= cashAmount) {
      cashBalance -= cashAmount;
      chipBalance += chipAmount;
      updateBalanceDisplay();
      showNotification(
        `Berhasil menukar ${cashAmount} Cash menjadi ${chipAmount} Chip!`,
        "success"
      );
    } else {
      showNotification("Cash Anda tidak mencukupi!", "error");
    }
  }

  function exchangeChipToCash(chipAmount, cashAmount) {
    if (chipBalance >= chipAmount) {
      chipBalance -= chipAmount;
      cashBalance += cashAmount;
      updateBalanceDisplay();
      showNotification(
        `Berhasil menukar ${chipAmount} Chip menjadi ${cashAmount} Cash!`,
        "success"
      );
    } else {
      showNotification("Chip Anda tidak mencukupi!", "error");
    }
  }

  function showNotification(message, type) {
    const notification = document.getElementById("notification");
    notification.textContent = message;
    notification.className = `notification ${type}`;
    notification.classList.add("show");

    setTimeout(() => {
      notification.classList.remove("show");
    }, 3000);
  }

  // Misalnya server kasih waktu sekarang (epoch detik) GMT+7
  let serverTime = Math.floor(Date.now() / 1000); // contoh dummy, harusnya ambil dari API

  function startCountdown(serverEpoch) {
    function updateCountdown() {
      let now = new Date(serverEpoch * 1000);

      // Target jam 00:00 (midnight) besok
      let midnight = new Date(now);
      midnight.setHours(24, 0, 0, 0);

      // Selisih dalam detik
      let diff = Math.floor((midnight.getTime() - now.getTime()) / 1000);

      if (diff <= 0) {
        // Kalau sudah lewat jam 12 malam
        document.getElementById("countdown").innerHTML = `
        <h3 class="text-success">Sudah Bisa Claim Hadiah!</h3>
        <button class="btn btn-primary mt-2" id="claimBtn">
          Klaim Hadiah
        </button>
      `;

        // Pasang event listener untuk claimBtn
        document.getElementById("claimBtn").addEventListener("click", () => {
          // Misalnya kamu mau jalankan fungsi claimDailyLogin
          claimDailyLogin();

          // Reset waktu (ambil live time lagi dari server)
          serverEpoch = Math.floor(Date.now() / 1000);
          updateCountdown();
        });

        return; // Hentikan hitungan, jangan render jam-menit-detik lagi
      }

      // Konversi ke jam, menit, detik
      const hours = Math.floor(diff / 3600);
      const minutes = Math.floor((diff % 3600) / 60);
      const seconds = diff % 60;

      document.getElementById("hours").textContent = hours
        .toString()
        .padStart(2, "0");
      document.getElementById("minutes").textContent = minutes
        .toString()
        .padStart(2, "0");
      document.getElementById("seconds").textContent = seconds
        .toString()
        .padStart(2, "0");

      // Tambahin 1 detik ke serverEpoch biar terus maju
      serverEpoch++;
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);
  }

  // Panggil
  startCountdown(serverTime);

  // Check if user is already VIP
  if (isVIP) {
    document.getElementById("buyVipBtn").textContent = "Anda adalah VIP";
    document.getElementById("buyVipBtn").disabled = true;
  }
});
