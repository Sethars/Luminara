import { fetchWithAuth } from "../module_js/fetch_with_auth.js";

// ==================== STATE ====================
let isSpinning = false;
let winRate = 25; // Default win rate
let chips = 1000;
const symbols = ["🍒", "🍋", "🍊", "🍇", "🔔", "💎", "7️⃣"];
const SPIN_COST = 10;

// multiplier mapping (symbol => multiplier)
const multiplierMap = {
  "🍒": 1,
  "🍋": 0.5,
  "🍊": 2,
  "🍇": 3,
  "🔔": 5,
  "7️⃣": 10,
  // 💎 not in mapping => treated as no-winning symbol (mult 0)
};

// win distribution when a win occurs (percent)
// 0.5:10%, 1:45%, 2:25%, 3:14%, 5:5%, 10:1%
const winDistribution = [
  { mult: 0.5, prob: 10 },
  { mult: 1, prob: 45 },
  { mult: 2, prob: 25 },
  { mult: 3, prob: 14 },
  { mult: 5, prob: 5 },
  { mult: 10, prob: 1 },
];

// ==================== ELEMENTS ====================
const spinSound = document.getElementById("spinSound");
const winSound = document.getElementById("winSound");
const jackpotSound = document.getElementById("jackpotSound");
const chipAmountEl = document.getElementById("chipAmount");
const winRateToggle = document.getElementById("winRateToggle");
const winRateText = document.getElementById("winRateText");
const rulesModal = document.getElementById("rulesModal");
const spinBtn = document.getElementById("spinBtn");
const jackpotContainer = document.getElementById("jackpotContainer");

// Initialize chip display
chipAmountEl.textContent = chips;

// ============================================================
// EVENT LISTENERS (gantikan onclick di HTML)
// ============================================================
document.addEventListener("DOMContentLoaded", async () => {
  // tombol2
  document.querySelector(".rules-btn")?.addEventListener("click", showRules);
  document.querySelector(".close-rules")?.addEventListener("click", hideRules);
  document.querySelector(".close-btn")?.addEventListener("click", goToGameList);
  spinBtn?.addEventListener("click", spin);
  winRateToggle?.addEventListener("click", toggleWinRate);

  // Ambil chip dari backend
  await loadChipsFromBackend();
});

// ============================================================
// FUNCTION UTAMA
// ============================================================

// Toggle win rate
function toggleWinRate() {
  winRateToggle.classList.toggle("active");
  if (winRateToggle.classList.contains("active")) {
    winRate = 75;
    winRateText.textContent = "75%";
  } else {
    winRate = 25;
    winRateText.textContent = "25%";
  }
}

// Go to game list
function goToGameList() {
  window.location.href = "/game_list";
}

// Show rules
function showRules() {
  rulesModal.classList.add("active");
}

// Hide rules
function hideRules() {
  rulesModal.classList.remove("active");
}

// Show notification (minimal, only win messages like "anda menang x 2")
function showNotification(message, isJackpot = false) {
  const notification = document.getElementById("notification");
  const notificationMessage = document.getElementById("notificationMessage");
  const notificationChip = document.getElementById("notificationChip");

  // tampilkan teks sederhana (lowercase)
  notificationMessage.textContent = String(message).toLowerCase();
  notificationChip.textContent = "";

  // show / animate
  notification.classList.add("show", "pop");
  if (isJackpot) notification.classList.add("jackpot");

  setTimeout(() => notification.classList.remove("pop"), 700);
  setTimeout(() => notification.classList.remove("show", "jackpot"), 3000);
}

// Play sound (suppress console output)
function playSound(audioElement) {
  audioElement.currentTime = 0;
  audioElement.play().catch(() => {}); // ignore play errors silently
}

// Update chip amount
function updateChipAmount(amount) {
  chips += amount;
  chipAmountEl.textContent = chips;

  // Persist ke backend (async, tidak men-block UI) - jangan log ke console
  updateChipToDB(chips).catch(() => {});
}

// choose multiplier according to winDistribution
function chooseMultiplierByProb() {
  const r = Math.random() * 100;
  let acc = 0;
  for (const item of winDistribution) {
    acc += item.prob;
    if (r <= acc) return item.mult;
  }
  // fallback
  return 1;
}

// Spin function
function spin() {
  if (isSpinning) return;
  if (chips < SPIN_COST) {
    showNotification("Chip tidak cukup!", 0);
    return;
  }

  isSpinning = true;
  spinBtn.disabled = true;
  updateChipAmount(-SPIN_COST);
  jackpotContainer.classList.remove("active");
  playSound(spinSound);

  const shouldWin = Math.random() * 100 < winRate;
  let result1, result2, result3;

  if (shouldWin) {
    // pilih multiplier berdasarkan probabilitas, lalu pilih simbol yang sesuai
    const chosenMult = chooseMultiplierByProb();
    // cari symbol yang memiliki multiplier tersebut
    const targetSymbol = Object.keys(multiplierMap).find(
      (s) => multiplierMap[s] === chosenMult
    );
    const winIndex = symbols.indexOf(targetSymbol ?? "🍒");
    // jika symbol tidak ditemukan (safety), gunakan 🍒
    result1 = result2 = result3 = winIndex >= 0 ? winIndex : 0;
  } else {
    result1 = Math.floor(Math.random() * symbols.length);
    result2 = Math.floor(Math.random() * symbols.length);
    result3 = Math.floor(Math.random() * symbols.length);
    while (result1 === result2 && result2 === result3)
      result3 = (result3 + 1) % symbols.length;
  }

  animateReel("reel1", result1, 0, () =>
    checkSpinResults(result1, result2, result3)
  );
  animateReel("reel2", result2, 200);
  animateReel("reel3", result3, 400);
}

// Check spin results after animation
function checkSpinResults(result1, result2, result3) {
  isSpinning = false;
  spinBtn.disabled = false;

  if (result1 === result2 && result2 === result3) {
    const sym = symbols[result1];
    const mult = multiplierMap[sym] ?? 0;

    if (mult > 0) {
      const winAmount = Math.round(SPIN_COST * mult);
      if (mult === 10) {
        // jackpot
        jackpotContainer.classList.add("active");
        playSound(jackpotSound);
        updateChipAmount(winAmount);
        // tampilkan notifikasi sederhana
        setTimeout(() => showNotification(`anda menang x ${mult}`, true), 2100);
      } else {
        // regular win with multiplier
        playSound(winSound);
        updateChipAmount(winAmount);
        showNotification(`anda menang x ${mult}`);
      }
    } else {
      // triple symbol tapi tanpa payout -> tidak menampilkan notifikasi
    }
  } else {
    // kalah -> tidak menampilkan notifikasi
  }
}

// Reel animation
function animateReel(reelId, finalIndex, delay, callback) {
  const reel = document.getElementById(reelId);
  const reelHeight = 200;
  const totalItems = symbols.length;
  const finalPosition = -finalIndex * reelHeight;

  setTimeout(() => {
    reel.style.transition = "transform 0.1s ease-out";
    let spinCount = 0;
    const maxSpins = 10 + Math.floor(Math.random() * 5);

    const spinInterval = setInterval(() => {
      const randomOffset = Math.floor(Math.random() * totalItems);
      reel.style.transform = `translateY(${-randomOffset * reelHeight}px)`;
      spinCount++;
      if (spinCount >= maxSpins) {
        clearInterval(spinInterval);
        setTimeout(() => {
          reel.style.transition = "transform 0.5s ease-out";
          reel.style.transform = `translateY(${finalPosition}px)`;
          if (reelId === "reel1" && callback) setTimeout(callback, 500);
        }, 100);
      }
    }, 100);
  }, delay);
}

// ============================================================
// BACKEND CHIP HANDLER
// ============================================================

async function loadChipsFromBackend() {
  try {
    const res = await fetchWithAuth("api/getChip", { method: "GET" });
    const data = await res.json();

    if (data.success && data.data && data.data.chip !== undefined) {
      chips = parseInt(data.data.chip, 10) || 0;
    } else {
      chips = 0;
    }
  } catch (err) {
    chips = 0;
  }

  chipAmountEl.textContent = chips;
}

// Tambahan: update saldo chip ke backend (tanpa console.log/error)
async function updateChipToDB(newBalance) {
  try {
    await fetchWithAuth("api/CoinFlip", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ chip: newBalance }),
    });
    // intentionally silent
  } catch (err) {
    // intentionally silent
  }
}
