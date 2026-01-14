import { fetchWithAuth } from "../module_js/fetch_with_auth.js";

// Game state
let balance = 1000; // Akan diganti dari DB lewat fetch
let currentBet = 0;
let selectedSide = null;
let isFlipping = false;
let history = [];
let isGacorOn = false;

// DOM elements
const balanceElement = document.getElementById("balance");
const betAmountElement = document.getElementById("bet-amount");
const coinElement = document.getElementById("coin");
const flipButton = document.getElementById("flip-button");
const messageElement = document.getElementById("message");
const headsOption = document.getElementById("heads-option");
const tailsOption = document.getElementById("tails-option");
const historyList = document.getElementById("history-list");
const chips = document.querySelectorAll(".chip");
const gacorSwitch = document.getElementById("gacor-switch");
const gacorStatus = document.getElementById("gacor-status");
const rulesButton = document.getElementById("rules-button");
const rulesModal = document.getElementById("rules-modal");
const closeModal = document.querySelector(".close");

// Format chips
function formatChips(amount) {
  return amount.toLocaleString("id-ID") + " Chips";
}

// Update balance display
function updateBalance() {
  balanceElement.textContent = formatChips(balance);
}

// Update bet amount display
function updateBetAmount() {
  betAmountElement.textContent = formatChips(currentBet);
}

// Select bet side
headsOption.addEventListener("click", () => {
  selectedSide = "heads";
  headsOption.classList.add("selected");
  tailsOption.classList.remove("selected");
  checkCanFlip();
});

tailsOption.addEventListener("click", () => {
  selectedSide = "tails";
  tailsOption.classList.add("selected");
  headsOption.classList.remove("selected");
  checkCanFlip();
});

// Check if flip button can be enabled
function checkCanFlip() {
  if (currentBet > 0 && selectedSide && !isFlipping) {
    flipButton.disabled = false;
  } else {
    flipButton.disabled = true;
  }
}

// Handle chip selection
chips.forEach((chip) => {
  chip.addEventListener("click", () => {
    const value = parseInt(chip.dataset.value);

    // Remove selected class from all chips
    chips.forEach((c) => c.classList.remove("selected"));

    // Add selected class to clicked chip
    chip.classList.add("selected");

    // Set bet amount
    currentBet = value;
    updateBetAmount();
    checkCanFlip();
  });
});

// Handle gacor switch
gacorSwitch.addEventListener("change", () => {
  isGacorOn = gacorSwitch.checked;
  if (isGacorOn) {
    gacorStatus.textContent = "ON (75% Win)";
    gacorStatus.className = "gacor-status gacor-on";
  } else {
    gacorStatus.textContent = "OFF (25% Win)";
    gacorStatus.className = "gacor-status gacor-off";
  }
});

// Flip coin
flipButton.addEventListener("click", () => {
  if (isFlipping || currentBet === 0 || !selectedSide) return;

  // Check if player has enough balance
  if (balance < currentBet) {
    messageElement.textContent = "Chip tidak cukup!";
    messageElement.className = "message lose";
    return;
  }

  // Deduct bet from balance
  balance -= currentBet;
  updateBalance();

  // Reset message
  messageElement.textContent = "";
  messageElement.className = "message";

  // Disable controls during flip
  isFlipping = true;
  flipButton.disabled = true;
  headsOption.style.pointerEvents = "none";
  tailsOption.style.pointerEvents = "none";
  chips.forEach((chip) => (chip.style.pointerEvents = "none"));

  // Add flipping animation
  coinElement.classList.add("flipping");

  // Determine result after animation
  setTimeout(() => {
    let result;

    // Determine win probability based on gacor mode
    if (isGacorOn) {
      // 75% chance to win
      result =
        Math.random() < 0.75
          ? selectedSide
          : selectedSide === "heads"
          ? "tails"
          : "heads";
    } else {
      // 25% chance to win
      result =
        Math.random() < 0.25
          ? selectedSide
          : selectedSide === "heads"
          ? "tails"
          : "heads";
    }

    // Rotate coin to show result
    if (result === "tails") {
      coinElement.style.transform = "rotateY(180deg)";
    } else {
      coinElement.style.transform = "rotateY(0deg)";
    }

    // Check if player won
    if (result === selectedSide) {
      // Player wins
      const winAmount = currentBet * 2;
      balance += winAmount;
      updateBalance();

      messageElement.textContent = `Anda menang! +${currentBet} Chips`;
      messageElement.className = "message win";
      updateChipToDB(balance);
    } else {
      // Player loses
      messageElement.textContent = `Anda kalah! -${currentBet} Chips`;
      messageElement.className = "message lose";
      updateChipToDB(balance);
    }

    // Add to history
    addToHistory(result);

    // Reset for next round
    setTimeout(() => {
      coinElement.classList.remove("flipping");
      isFlipping = false;
      headsOption.style.pointerEvents = "auto";
      tailsOption.style.pointerEvents = "auto";
      chips.forEach((chip) => (chip.style.pointerEvents = "auto"));

      // Reset selections
      currentBet = 0;
      selectedSide = null;
      headsOption.classList.remove("selected");
      tailsOption.classList.remove("selected");
      chips.forEach((chip) => chip.classList.remove("selected"));
      updateBetAmount();
      checkCanFlip();

      // Check if player is out of chips
      if (balance <= 0) {
        messageElement.textContent = "Game Over! Chip habis.";
        messageElement.className = "message lose";
        flipButton.disabled = true;
      }
    }, 1000);
  }, 1000);
});

// Add result to history
function addToHistory(result) {
  history.unshift(result);
  if (history.length > 10) {
    history.pop();
  }

  // Update history display
  historyList.innerHTML = "";
  history.forEach((item) => {
    const historyItem = document.createElement("div");
    historyItem.className = `history-item history-${item}`;
    historyItem.textContent = item === "heads" ? "H" : "T";
    historyList.appendChild(historyItem);
  });
}

// Rules modal
rulesButton.addEventListener("click", (e) => {
  e.preventDefault();
  rulesModal.style.display = "block";
});

closeModal.addEventListener("click", () => {
  rulesModal.style.display = "none";
});

window.addEventListener("click", (e) => {
  if (e.target === rulesModal) {
    rulesModal.style.display = "none";
  }
});

// ============================================================
// ============== FETCH CHIP DARI BACKEND (BARU) ==============
// ============================================================

document.addEventListener("DOMContentLoaded", async function () {
  try {
    const res = await fetchWithAuth("api/getChip", {
      method: "GET",
    });

    const data = await res.json();

    if (data.success && data.data && data.data.chip !== undefined) {
      balance = parseInt(data.data.chip); // Ganti balance awal dari DB
      updateBalance(); // Tampilkan ke <div id="balance">
    } else {
      console.error("Gagal ambil chip:", data.message);
      balance = 0;
      updateBalance();
    }

    // Tetap update tampilan bet dan tombol
    updateBetAmount();
    checkCanFlip();
  } catch (err) {
    console.error("Error saat fetch chip:", err);
    balance = 0;
    updateBalance();
    updateBetAmount();
    checkCanFlip();
  }
});

// ============================================================
// ============== UPDATE CHIP KE BACKEND (BARU) ===============
// ============================================================
async function updateChipToDB(newBalance) {
  try {
    const res = await fetchWithAuth("api/CoinFlip", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ chip: newBalance }),
    });

    const data = await res.json();
    if (!data.success) {
      console.error("Gagal update chip:", data.message);
    } else {
      console.log("Chip berhasil diupdate:", newBalance);
    }
  } catch (err) {
    console.error("Error saat update chip:", err);
  }
}
