import { formatMoney } from "../module_js/format_money.js";

document.addEventListener("DOMContentLoaded", async function () {
  try{
    const res = await fetch('api/getShopData', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })

    const data = await res.json();
    if(data.success){
      let cashBalance = data.cash; 
      let chipBalance = data.chip; 
      let isVIP = data.isVip; 
      let welcomeBonusClaimed = data.isClaimed; 
      let canClaimDaily = data.canClaimDaily;

      // Update balance display
      updateBalanceDisplay();

      // Check daily login and amount
      checkDailyLogin();
      document.getElementById('dailyAmount').textContent = (isVIP ? '500' : '100') + ' ' + 'Chip';

      // Check welcome bonus
      checkWelcomeBonus();

      // Start countdown
      startCountdown(canClaimDaily);

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
            exchangeCashToChip(cash, chip, false);
          } else if (type === "chip-to-cash") {
            exchangeChipToCash(chip, cash, false);
          } else if (type === "cash-to-chip-vip") {
            if (isVIP) {
              exchangeCashToChip(cash, chip, true);
            } else {
              showNotification("Hanya untuk member VIP!", "error");
            }
          } else if (type === "chip-to-cash-vip") {
            if (isVIP) {
              exchangeChipToCash(chip, cash, true);
            } else {
              showNotification("Hanya untuk member VIP!", "error");
            }
          }
        });
      });

      function updateBalanceDisplay() {
        document.getElementById("chipBalance").textContent = formatMoney(chipBalance);
        document.getElementById("cashBalance").textContent = formatMoney(cashBalance);
      }

      function checkDailyLogin() {
        if (!canClaimDaily) {
          // Already claimed today
          document.getElementById("dailyLoginCard").classList.add("claimed");
          document.getElementById("dailyLoginBtn").disabled = true;
          document.getElementById("dailyLoginBtn").textContent = "Sudah Diambil";
        }
      }

      function checkWelcomeBonus() {
        if (welcomeBonusClaimed) {
          // Already claimed
          document.getElementById("welcomeBonusCard").classList.add("claimed");
          document.getElementById("welcomeBonusBtn").disabled = true;
          document.getElementById("welcomeBonusBtn").textContent = "Sudah Diambil";
        }
      }

      function claimDailyLogin() {
        if (canClaimDaily) {
          fetch('api/claimDaily',{
            headers: {
              Authorization : `Bearer ${token}`
            }
          })
          .then(res => res.json())
          .then(data => {
            if(data.success){
              chipBalance += data.chip;
              canClaimDaily = false;

              // Update UI
              updateBalanceDisplay();
              startCountdown();

              document.getElementById("dailyLoginCard").classList.add("claimed");
              document.getElementById("dailyLoginBtn").disabled = true;
              document.getElementById("dailyLoginBtn").textContent = "Sudah Diambil";
              // Show notification
              if (data.vip) {
                showNotification("Berhasil klaim 500 Chip (Bonus VIP)!", "success");
              } else {
                showNotification("Berhasil klaim 100 Chip!", "success");
              }
            } else {
              console.error(data.message);
              if(data.error) console.error(data.error);
            }
          })
        }
      }

      function claimWelcomeBonus() {
        if (!welcomeBonusClaimed) {
          // Can claim
          fetch('api/claimWelcomeBonus',{
            headers: {
              Authorization: `Bearer ${token}`
            }
          })
          .then(res => res.json())
          .then(data => {
            if(data.success){
              //Update chip
              chipBalance += 1000;

              // Set as claimed
              welcomeBonusClaimed = true;
    
              // Update UI
              updateBalanceDisplay();
              document.getElementById("welcomeBonusCard").classList.add("claimed");
              document.getElementById("welcomeBonusBtn").disabled = true;
              document.getElementById("welcomeBonusBtn").textContent = "Sudah Diambil";
    
              // Show notification
              showNotification("Berhasil klaim 1000 Chip!", "success");
            } else {
              console.error(data.message);
              if(data.error) console.error(data.error);
            }
          })

        }
      }

      function buyVIP() {
        if (isVIP) {
          showNotification("Anda sudah menjadi member VIP!", "error");
          return;
        }

        if(cashBalance < 50000){
          showNotification("Cash Anda tidak mencukupi untuk membeli VIP!", "error");
          return;
        }

        fetch('api/buyVip',{
          headers: {
            Authorization: `Bearer ${token}`
          }
        })
        .then(res => res.json())
        .then(data => {
          if(data.success){
            cashBalance -= 50000;
            isVIP = true;

            //Update UI + Show Notification
            updateBalanceDisplay();
            showNotification("Selamat! Anda sekarang adalah member VIP!", "vip");

            // Update VIP button
            document.getElementById("buyVipBtn").textContent = "Anda adalah VIP";
            document.getElementById("buyVipBtn").disabled = true;

            if(data.badges){
              const profile = JSON.parse(localStorage.getItem('profile'));
              profile.badges = data.badges;
              localStorage.setItem('profile', JSON.stringify(profile))
            }
          } else {
            showNotification(data.message, "error");
            if(data.error) console.error(data.error);
          }
        })
      }

      function exchangeCashToChip(cashAmount, chipAmount, needVip) {
        if (cashBalance < cashAmount) {
          showNotification("Cash Anda tidak mencukupi!", "error");
          return;
        } 

        if(needVip){
          if(!isVIP){
            showNotification("Hanya untuk member VIP!", "error");
            return;
          }
        }

        fetch('api/exchangeCashToChip', {
          method: "POST",
          headers: {
            'Content-Type' : 'application/json',
            Authorization : `Bearer ${token}`
          },
          body: JSON.stringify({cashAmount, chipAmount, needVip})
        })
        .then(res => res.json())
        .then(data => {
          if(data.success){
            cashBalance -= cashAmount;
            chipBalance += chipAmount;
            updateBalanceDisplay();
            showNotification(
              `Berhasil menukar ${cashAmount} Cash menjadi ${chipAmount} Chip!`,
              "success"
            );
          } else {
            showNotification(data.message, "error");
            if(data.error) console.error(data.error);
          }
        })
      }

      function exchangeChipToCash(chipAmount, cashAmount, needVip) {
        if (chipBalance < chipAmount) {
          showNotification("Chip Anda tidak mencukupi!", "error");
          return;
        } 

        if(needVip){
          if(!isVIP){
            showNotification("Hanya untuk member VIP!", "error");
            return;
          }
        }

        fetch('api/exchangeChipToCash', {
          method: "POST",
          headers: {
            'Content-Type' : 'application/json',
            Authorization : `Bearer ${token}`
          },
          body: JSON.stringify({cashAmount, chipAmount, needVip})
        })
        .then(res => res.json())
        .then(data => {
          if(data.success){
            cashBalance += cashAmount;
            chipBalance -= chipAmount;
            updateBalanceDisplay();
            showNotification(
              `Berhasil menukar ${chipAmount} Cash menjadi ${cashAmount} Chip!`,
              "success"
            );
          } else {
            showNotification(data.message, "error");
            if(data.error) console.error(data.error);
          }
        })
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

      // Countdown ke jam 12 malam
      function startCountdown() {
        function updateCountdown() {
          const now = new Date();

          const utc = now.getTime() + now.getTimezoneOffset() * 60000; // waktu UTC
          const gmt7 = new Date(utc + 7 * 3600000); //GMT +7

          // Target jam 00:00 besok
          const midnight = new Date(utc + 7 * 3600000);
          midnight.setHours(24, 0, 0, 0);

          let diff = Math.floor((midnight.getTime() - gmt7.getTime()) / 1000);

          if (canClaimDaily) {
            document.getElementById("countdown").style.display = "none";
            document.getElementById("claimReady").style.display = "flex";

            // Enable tombol klaim lagi
            const dailyBtn = document.getElementById("dailyLoginBtn");
            dailyBtn.disabled = false;
            dailyBtn.textContent = "Klaim Sekarang";
            return;
          }

          const hours = Math.floor(diff / 3600);
          const minutes = Math.floor((diff % 3600) / 60);
          const seconds = diff % 60;

          document.getElementById("countdown").style.display = "flex";
          document.getElementById("claimReady").style.display = "none";

          document.getElementById("hours").textContent = hours
            .toString()
            .padStart(2, "0");
          document.getElementById("minutes").textContent = minutes
            .toString()
            .padStart(2, "0");
          document.getElementById("seconds").textContent = seconds
            .toString()
            .padStart(2, "0");
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
      }

      // Check if user is already VIP
      if (isVIP) {
        document.getElementById("buyVipBtn").textContent = "Anda adalah VIP";
        document.getElementById("buyVipBtn").disabled = true;
        document.getElementById('exchangeVIPtoChip').disabled = false;
        document.getElementById('exchangeVIPtoCash').disabled = false;
      }
    } else {
      console.error(data.message);
      return null;
    }
  } catch (err){
    console.error(err);
    return null;
  }
});
