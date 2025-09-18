// Game state
let deck = [];
let playerHand = [];
let dealerHand = [];
let gameOver = false;
let dealerHiddenCard = true;
let currentBet = 0;
let insuranceBet = 0;
let hasInsurance = false;
let isSplit = false;
let splitHand1 = [];
let splitHand2 = [];
let currentSplitHand = 1;
let splitBet = 0;
let doubleDown = false;

// Game stats
let stats = {
  wins: 0,
  losses: 0,
  ties: 0,
  gamesPlayed: 0,
  blackjacks: 0,
  chips: 1000,
};

// DOM elements
const dealerHandEl = document.getElementById("dealer-hand");
const playerHandEl = document.getElementById("player-hand");
const dealerScoreEl = document.getElementById("dealer-score");
const playerScoreEl = document.getElementById("player-score");
const messageEl = document.getElementById("message");
const hitBtn = document.getElementById("hit-btn");
const standBtn = document.getElementById("stand-btn");
const doubleBtn = document.getElementById("double-btn");
const splitBtn = document.getElementById("split-btn");
const newGameBtn = document.getElementById("new-game-btn");
const bettingSection = document.getElementById("betting-section");
const betAmountInput = document.getElementById("bet-amount");
const resetBetBtn = document.getElementById("reset-bet");
const insuranceSection = document.getElementById("insurance-section");
const takeInsuranceBtn = document.getElementById("take-insurance");
const declineInsuranceBtn = document.getElementById("decline-insurance");
const splitHandsEl = document.getElementById("split-hands");
const splitHand1El = document.getElementById("split-hand-1");
const splitHand2El = document.getElementById("split-hand-2");
const splitHand1ControlsEl = document.getElementById("split-hand-1-controls");
const splitHand2ControlsEl = document.getElementById("split-hand-2-controls");

// Stats elements
const winsEl = document.getElementById("wins");
const lossesEl = document.getElementById("losses");
const tiesEl = document.getElementById("ties");
const gamesPlayedEl = document.getElementById("games-played");
const blackjacksEl = document.getElementById("blackjacks");
const winRateEl = document.getElementById("win-rate");

// Panel elements
const chipCountPanelEl = document.getElementById("chip-count-panel");
const betAmountPanelEl = document.getElementById("bet-amount-panel");

// Rules modal elements
const rulesBtn = document.getElementById("rules-btn");
const rulesModal = document.getElementById("rules-modal");
const closeRulesBtn = document.getElementById("close-rules");

// Initialize the game
function initGame() {
  // Load stats from localStorage
  const savedStats = localStorage.getItem("blackjackStats");
  if (savedStats) {
    stats = JSON.parse(savedStats);
    updateStatsDisplay();
  }

  // Event listeners
  hitBtn.addEventListener("click", playerHit);
  standBtn.addEventListener("click", playerStand);
  doubleBtn.addEventListener("click", playerDouble);
  splitBtn.addEventListener("click", playerSplit);
  newGameBtn.addEventListener("click", placeBetAndStart);
  resetBetBtn.addEventListener("click", resetBet);
  takeInsuranceBtn.addEventListener("click", takeInsurance);
  declineInsuranceBtn.addEventListener("click", declineInsurance);
  rulesBtn.addEventListener("click", showRules);
  closeRulesBtn.addEventListener("click", hideRules);

  // Chip button event listeners
  const chipButtons = document.querySelectorAll(".chip-button");
  chipButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const value = parseInt(button.getAttribute("data-value"));
      setBetAmount(value);
    });
  });

  // Initial bet validation
  validateBetAmount();
}

// Set bet amount
function setBetAmount(amount) {
  const newBet = parseInt(betAmountInput.value) + amount;

  if (newBet) {
    betAmountInput.value = newBet;
    validateBetAmount();
  }
}

// Reset bet amount
function resetBet() {
  betAmountInput.value = 0;
  validateBetAmount();
}

// Validate bet amount
function validateBetAmount() {
  const betValue = parseInt(betAmountInput.value) || 0;

  if (betValue > stats.chips) {
    betAmountInput.value = stats.chips;
  }

  if (betValue < 5) {
    messageEl.textContent = "Minimum bet is 5 chips";
    return;
  } else {
    messageEl.textContent = "Place your bet to start playing";
  }
}

// Place bet and start new game
function placeBetAndStart() {
  const betValue = parseInt(betAmountInput.value) || 0;

  if (betValue < 5) {
    messageEl.textContent = "Minimum bet is 5 chips";
    return;
  }

  if (betValue > stats.chips) {
    messageEl.textContent = "You don't have enough chips";
    return;
  }

  currentBet = betValue;
  stats.chips -= currentBet;
  updateStatsDisplay();
  updateBetPanel();

  // Reset game state
  insuranceBet = 0;
  hasInsurance = false;
  isSplit = false;
  splitHand1 = [];
  splitHand2 = [];
  currentSplitHand = 1;
  splitBet = 0;
  doubleDown = false;

  bettingSection.classList.remove("active");
  insuranceSection.classList.remove("active");
  splitHandsEl.style.display = "none";
  startNewGame();
}

// Update bet panel
function updateBetPanel() {
  if (chipCountPanelEl) {
    chipCountPanelEl.textContent = stats.chips;
  }
  if (betAmountPanelEl) {
    betAmountPanelEl.textContent = currentBet;
  }
}

// Create a new deck of cards
function createDeck() {
  const suits = ["hearts", "diamonds", "clubs", "spades"];
  const values = [
    "2",
    "3",
    "4",
    "5",
    "6",
    "7",
    "8",
    "9",
    "10",
    "J",
    "Q",
    "K",
    "A",
  ];
  deck = [];

  for (const suit of suits) {
    for (const value of values) {
      deck.push({ suit, value });
    }
  }

  // Shuffle the deck
  for (let i = deck.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [deck[i], deck[j]] = [deck[j], deck[i]];
  }
}

// Start a new game
function startNewGame() {
  createDeck();
  playerHand = [];
  dealerHand = [];
  gameOver = false;
  dealerHiddenCard = true;

  // Clear hands
  dealerHandEl.innerHTML = "";
  playerHandEl.innerHTML = "";

  // Deal initial cards
  setTimeout(() => {
    playerHand.push(deck.pop());
    renderCard(playerHandEl, playerHand[playerHand.length - 1], false);

    setTimeout(() => {
      dealerHand.push(deck.pop());
      renderCard(dealerHandEl, dealerHand[dealerHand.length - 1], true);

      setTimeout(() => {
        playerHand.push(deck.pop());
        renderCard(playerHandEl, playerHand[playerHand.length - 1], false);

        setTimeout(() => {
          dealerHand.push(deck.pop());
          renderCard(dealerHandEl, dealerHand[dealerHand.length - 1], false);

          // Update UI
          updateScores();

          // Check for insurance opportunity
          if (dealerHand[1].value === "A") {
            showInsuranceOption();
          } else {
            // Enable buttons
            hitBtn.disabled = false;
            standBtn.disabled = false;

            // Check for split opportunity
            if (
              playerHand[0].value === playerHand[1].value &&
              stats.chips >= currentBet
            ) {
              splitBtn.disabled = false;
            } else {
              splitBtn.disabled = true;
            }

            // Check for double down opportunity
            if (stats.chips >= currentBet) {
              doubleBtn.disabled = false;
            } else {
              doubleBtn.disabled = true;
            }

            newGameBtn.disabled = true;

            // Check for blackjack
            if (calculateScore(playerHand) === 21) {
              messageEl.textContent = "Blackjack! You win!";
              endGame("blackjack");
            } else {
              messageEl.textContent = "Make your move";
            }
          }
        }, 300);
      }, 300);
    }, 300);
  }, 300);
}

// Show insurance option
function showInsuranceOption() {
  insuranceSection.classList.add("active");
  messageEl.textContent = "Dealer shows an Ace. Insurance?";
}

// Take insurance
function takeInsurance() {
  const insuranceAmount = Math.floor(currentBet / 2);

  if (stats.chips >= insuranceAmount) {
    insuranceBet = insuranceAmount;
    stats.chips -= insuranceBet;
    hasInsurance = true;
    updateStatsDisplay();
    updateBetPanel();

    insuranceSection.classList.remove("active");

    // Check if dealer has blackjack
    const dealerScore = calculateScore(dealerHand);
    if (dealerScore === 21) {
      // Dealer has blackjack
      messageEl.textContent = "Dealer has Blackjack!";

      // Check if player also has blackjack
      if (calculateScore(playerHand) === 21) {
        // Both have blackjack - push
        messageEl.textContent = "Both have Blackjack! It's a push.";
        endGame("tie");
      } else {
        // Only dealer has blackjack
        endGame("lose");
      }
    } else {
      // Dealer doesn't have blackjack, insurance bet is lost
      messageEl.textContent = "Dealer doesn't have Blackjack. Insurance lost.";

      // Enable buttons
      hitBtn.disabled = false;
      standBtn.disabled = false;

      // Check for split opportunity
      if (
        playerHand[0].value === playerHand[1].value &&
        stats.chips >= currentBet
      ) {
        splitBtn.disabled = false;
      } else {
        splitBtn.disabled = true;
      }

      // Check for double down opportunity
      if (stats.chips >= currentBet) {
        doubleBtn.disabled = false;
      } else {
        doubleBtn.disabled = true;
      }

      newGameBtn.disabled = true;
    }
  } else {
    messageEl.textContent = "Not enough chips for insurance";
  }
}

// Decline insurance
function declineInsurance() {
  insuranceSection.classList.remove("active");

  // Check if dealer has blackjack
  const dealerScore = calculateScore(dealerHand);
  if (dealerScore === 21) {
    // Dealer has blackjack
    messageEl.textContent = "Dealer has Blackjack!";

    // Check if player also has blackjack
    if (calculateScore(playerHand) === 21) {
      // Both have blackjack - push
      messageEl.textContent = "Both have Blackjack! It's a push.";
      endGame("tie");
    } else {
      // Only dealer has blackjack
      endGame("lose");
    }
  } else {
    // Dealer doesn't have blackjack
    messageEl.textContent = "Dealer doesn't have Blackjack.";

    // Enable buttons
    hitBtn.disabled = false;
    standBtn.disabled = false;

    // Check for split opportunity
    if (
      playerHand[0].value === playerHand[1].value &&
      stats.chips >= currentBet
    ) {
      splitBtn.disabled = false;
    } else {
      splitBtn.disabled = true;
    }

    // Check for double down opportunity
    if (stats.chips >= currentBet) {
      doubleBtn.disabled = false;
    } else {
      doubleBtn.disabled = true;
    }

    newGameBtn.disabled = true;
  }
}

// Player doubles down
function playerDouble() {
  if (gameOver || stats.chips < currentBet) return;

  // Double the bet
  stats.chips -= currentBet;
  currentBet *= 2;
  updateStatsDisplay();
  updateBetPanel();

  doubleDown = true;

  // Take one card and end turn
  playerHand.push(deck.pop());
  renderCard(playerHandEl, playerHand[playerHand.length - 1], false);
  updateScores();

  const playerScore = calculateScore(playerHand);
  if (playerScore > 21) {
    messageEl.textContent = "Bust! You went over 21.";
    endGame("lose");
  } else {
    messageEl.textContent = "You doubled down.";
    playerStand();
  }
}

// Player splits
function playerSplit() {
  if (gameOver || stats.chips < currentBet) return;

  // Set up split
  isSplit = true;
  splitBet = currentBet;
  stats.chips -= currentBet;
  updateStatsDisplay();
  updateBetPanel();

  // Create two hands from the two cards
  splitHand1 = [playerHand[0]];
  splitHand2 = [playerHand[1]];

  // Clear original hand
  playerHandEl.innerHTML = "";

  // Show split hands section
  splitHandsEl.style.display = "flex";

  // Render split hands
  renderCard(splitHand1El, splitHand1[0], false);
  renderCard(splitHand2El, splitHand2[0], false);

  // Deal second card to first hand
  setTimeout(() => {
    splitHand1.push(deck.pop());
    renderCard(splitHand1El, splitHand1[splitHand1.length - 1], false);
    updateSplitHandScore(1);

    // Create controls for first hand
    splitHand1ControlsEl.innerHTML = `
                    <button id="split1-hit-btn">Hit</button>
                    <button id="split1-stand-btn">Stand</button>
                `;

    document
      .getElementById("split1-hit-btn")
      .addEventListener("click", () => splitHit(1));
    document
      .getElementById("split1-stand-btn")
      .addEventListener("click", () => splitStand(1));

    // Disable main game controls
    hitBtn.disabled = true;
    standBtn.disabled = true;
    doubleBtn.disabled = true;
    splitBtn.disabled = true;

    messageEl.textContent = "Playing first hand";
  }, 500);
}

// Split hand hit
function splitHit(handNumber) {
  if (gameOver) return;

  if (handNumber === 1) {
    splitHand1.push(deck.pop());
    renderCard(splitHand1El, splitHand1[splitHand1.length - 1], false);
    updateSplitHandScore(1);

    if (calculateScore(splitHand1) > 21) {
      messageEl.textContent = "First hand busts!";
      document.getElementById("split1-hit-btn").disabled = true;
      document.getElementById("split1-stand-btn").disabled = true;

      // Move to second hand
      setTimeout(() => {
        playSecondHand();
      }, 1000);
    }
  } else {
    splitHand2.push(deck.pop());
    renderCard(splitHand2El, splitHand2[splitHand2.length - 1], false);
    updateSplitHandScore(2);

    if (calculateScore(splitHand2) > 21) {
      messageEl.textContent = "Second hand busts!";
      document.getElementById("split2-hit-btn").disabled = true;
      document.getElementById("split2-stand-btn").disabled = true;

      // End game
      setTimeout(() => {
        checkSplitWinner();
      }, 1000);
    }
  }
}

// Split hand stand
function splitStand(handNumber) {
  if (gameOver) return;

  if (handNumber === 1) {
    document.getElementById("split1-hit-btn").disabled = true;
    document.getElementById("split1-stand-btn").disabled = true;

    // Move to second hand
    setTimeout(() => {
      playSecondHand();
    }, 1000);
  } else {
    document.getElementById("split2-hit-btn").disabled = true;
    document.getElementById("split2-stand-btn").disabled = true;

    // End game
    setTimeout(() => {
      checkSplitWinner();
    }, 1000);
  }
}

// Play second hand
function playSecondHand() {
  // Deal second card to second hand
  setTimeout(() => {
    splitHand2.push(deck.pop());
    renderCard(splitHand2El, splitHand2[splitHand2.length - 1], false);
    updateSplitHandScore(2);

    // Create controls for second hand
    splitHand2ControlsEl.innerHTML = `
                    <button id="split2-hit-btn">Hit</button>
                    <button id="split2-stand-btn">Stand</button>
                `;

    document
      .getElementById("split2-hit-btn")
      .addEventListener("click", () => splitHit(2));
    document
      .getElementById("split2-stand-btn")
      .addEventListener("click", () => splitStand(2));

    messageEl.textContent = "Playing second hand";
  }, 500);
}

// Update split hand score
function updateSplitHandScore(handNumber) {
  const hand = handNumber === 1 ? splitHand1 : splitHand2;
  const handEl = handNumber === 1 ? splitHand1El : splitHand2El;
  const score = calculateScore(hand);

  // Remove existing score badge if any
  const existingBadge = handEl.querySelector(".card-score-badge");
  if (existingBadge) {
    existingBadge.remove();
  }

  // Add new score badge
  const badge = document.createElement("div");
  badge.className = "card-score-badge";
  badge.textContent = score;
  handEl.appendChild(badge);
}

// Check winner for split hands
function checkSplitWinner() {
  // Reveal dealer's hidden card
  dealerHiddenCard = false;
  renderHands();
  updateScores();

  // Dealer's turn
  dealerTurnSplit();
}

// Dealer's turn for split game
function dealerTurnSplit() {
  const dealerScore = calculateScore(dealerHand);

  if (dealerScore < 17) {
    messageEl.textContent = "Dealer is drawing cards...";

    setTimeout(() => {
      dealerHand.push(deck.pop());
      renderCard(dealerHandEl, dealerHand[dealerHand.length - 1], false);
      updateScores();
      dealerTurnSplit();
    }, 1000);
  } else {
    // Check winners for both hands
    const dealerScore = calculateScore(dealerHand);
    const hand1Score = calculateScore(splitHand1);
    const hand2Score = calculateScore(splitHand2);

    let hand1Result = "";
    let hand2Result = "";

    // Hand 1 result
    if (hand1Score > 21) {
      hand1Result = "lose";
    } else if (dealerScore > 21) {
      hand1Result = "win";
    } else if (hand1Score > dealerScore) {
      hand1Result = "win";
    } else if (hand1Score < dealerScore) {
      hand1Result = "lose";
    } else {
      hand1Result = "tie";
    }

    // Hand 2 result
    if (hand2Score > 21) {
      hand2Result = "lose";
    } else if (dealerScore > 21) {
      hand2Result = "win";
    } else if (hand2Score > dealerScore) {
      hand2Result = "win";
    } else if (hand2Score < dealerScore) {
      hand2Result = "lose";
    } else {
      hand2Result = "tie";
    }

    // Process results
    let totalWin = 0;

    if (hand1Result === "win") {
      totalWin += splitBet * 2;
    } else if (hand1Result === "tie") {
      totalWin += splitBet;
    }

    if (hand2Result === "win") {
      totalWin += splitBet * 2;
    } else if (hand2Result === "tie") {
      totalWin += splitBet;
    }

    // Add insurance win if applicable
    if (hasInsurance && calculateScore(dealerHand) === 21) {
      totalWin += insuranceBet * 3; // 2:1 payout + original bet
    }

    // Update message
    let message =
      "Hand 1: " +
      (hand1Result === "win"
        ? "Win!"
        : hand1Result === "lose"
        ? "Lose"
        : "Push");
    message +=
      " | Hand 2: " +
      (hand2Result === "win"
        ? "Win!"
        : hand2Result === "lose"
        ? "Lose"
        : "Push");

    messageEl.textContent = message;

    // Update stats and chips
    stats.gamesPlayed++;

    if (hand1Result === "win" && hand2Result === "win") {
      stats.wins++;
    } else if (hand1Result === "lose" && hand2Result === "lose") {
      stats.losses++;
    } else if (hand1Result === "tie" && hand2Result === "tie") {
      stats.ties++;
    }

    stats.chips += totalWin;

    // Check if player is out of chips
    if (stats.chips <= 0) {
      messageEl.textContent +=
        " | Game Over! You're out of chips. Refreshing your chips...";
      setTimeout(() => {
        stats.chips = 1000;
        updateStatsDisplay();
        updateBetPanel();
        messageEl.textContent = "Your chips have been refreshed to 1000";
      }, 3000);
    }

    // Save stats to localStorage
    localStorage.setItem("blackjackStats", JSON.stringify(stats));
    updateStatsDisplay();
    updateBetPanel();

    // Show betting section for next game
    setTimeout(() => {
      bettingSection.classList.add("active");
      resetBet();
    }, 1500);
  }
}

// Render a single card with animation
function renderCard(handElement, card, hidden = false) {
  const cardEl = createCardElement(card, hidden);
  handElement.appendChild(cardEl);

  // Trigger animation
  setTimeout(() => {
    cardEl.style.animationDelay = `${handElement.children.length * 0.1}s`;
  }, 10);
}

// Render hands on the UI
function renderHands() {
  // Render dealer hand
  dealerHandEl.innerHTML = "";
  dealerHand.forEach((card, index) => {
    const isHidden = index === 0 && dealerHiddenCard;
    renderCard(dealerHandEl, card, isHidden);
  });

  // Render player hand if not split
  if (!isSplit) {
    playerHandEl.innerHTML = "";
    playerHand.forEach((card) => {
      renderCard(playerHandEl, card, false);
    });
  }
}

// Create a card element
function createCardElement(card, hidden = false) {
  const cardEl = document.createElement("div");
  cardEl.className = "card";

  if (hidden) {
    cardEl.classList.add("hidden");
  } else {
    const isRed = card.suit === "hearts" || card.suit === "diamonds";
    const colorClass = isRed ? "red" : "black";

    cardEl.innerHTML = `
                    <div class="top ${colorClass}">${card.value}</div>
                    <div class="center ${colorClass}">${getSuitSymbol(
      card.suit
    )}</div>
                    <div class="bottom ${colorClass}">${card.value}</div>
                `;
  }

  return cardEl;
}

// Get suit symbol
function getSuitSymbol(suit) {
  switch (suit) {
    case "hearts":
      return "♥";
    case "diamonds":
      return "♦";
    case "clubs":
      return "♣";
    case "spades":
      return "♠";
    default:
      return "";
  }
}

// Calculate hand score
function calculateScore(hand) {
  let score = 0;
  let aces = 0;

  for (const card of hand) {
    if (card.value === "A") {
      aces += 1;
      score += 11;
    } else if (["K", "Q", "J"].includes(card.value)) {
      score += 10;
    } else {
      score += parseInt(card.value);
    }
  }

  // Adjust for aces
  while (score > 21 && aces > 0) {
    score -= 10;
    aces -= 1;
  }

  return score;
}

// Update scores on UI
function updateScores() {
  if (!isSplit) {
    const playerScore = calculateScore(playerHand);
    if (playerScoreEl) {
      playerScoreEl.textContent = `Score: ${playerScore}`;
    }
  }

  if (dealerHiddenCard) {
    // Only show the visible card's score
    const visibleCard = dealerHand[1];
    let visibleScore = 0;

    if (visibleCard.value === "A") {
      visibleScore = 11;
    } else if (["K", "Q", "J"].includes(visibleCard.value)) {
      visibleScore = 10;
    } else {
      visibleScore = parseInt(visibleCard.value);
    }

    if (dealerScoreEl) {
      dealerScoreEl.textContent = `Score: ${visibleScore}`;
    }
  } else {
    const dealerScore = calculateScore(dealerHand);
    if (dealerScoreEl) {
      dealerScoreEl.textContent = `Score: ${dealerScore}`;
    }
  }
}

// Player hits
function playerHit() {
  if (gameOver) return;

  playerHand.push(deck.pop());
  renderCard(playerHandEl, playerHand[playerHand.length - 1], false);
  updateScores();

  const playerScore = calculateScore(playerHand);
  if (playerScore > 21) {
    messageEl.textContent = "Bust! You went over 21.";
    endGame("lose");
  } else if (playerScore === 21) {
    messageEl.textContent = "You have 21!";
    playerStand();
  }
}

// Player stands
function playerStand() {
  if (gameOver) return;

  // Reveal dealer's hidden card
  dealerHiddenCard = false;
  renderHands();
  updateScores();

  // Dealer's turn
  dealerTurn();
}

// Dealer's turn
function dealerTurn() {
  hitBtn.disabled = true;
  standBtn.disabled = true;
  doubleBtn.disabled = true;
  splitBtn.disabled = true;

  const dealerScore = calculateScore(dealerHand);

  if (dealerScore < 17) {
    messageEl.textContent = "Dealer is drawing cards...";

    setTimeout(() => {
      dealerHand.push(deck.pop());
      renderCard(dealerHandEl, dealerHand[dealerHand.length - 1], false);
      updateScores();
      dealerTurn();
    }, 1000);
  } else {
    checkWinner();
  }
}

// Check the winner
function checkWinner() {
  const playerScore = calculateScore(playerHand);
  const dealerScore = calculateScore(dealerHand);

  if (dealerScore > 21) {
    messageEl.textContent = "Dealer busts! You win!";
    endGame("win");
  } else if (playerScore > dealerScore) {
    messageEl.textContent = "You win!";
    endGame("win");
  } else if (playerScore < dealerScore) {
    messageEl.textContent = "Dealer wins!";
    endGame("lose");
  } else {
    messageEl.textContent = "It's a tie!";
    endGame("tie");
  }
}

// End the game
function endGame(result) {
  gameOver = true;
  hitBtn.disabled = true;
  standBtn.disabled = true;
  doubleBtn.disabled = true;
  splitBtn.disabled = true;
  newGameBtn.disabled = false;
  newGameBtn.textContent = "Place Bet & Play";

  // Update stats and chips based on result
  stats.gamesPlayed++;

  if (result === "blackjack") {
    stats.blackjacks++;
    stats.wins++;
    // Blackjack pays 1.5:1
    stats.chips += Math.floor(currentBet * 2.5);
  } else if (result === "win") {
    stats.wins++;
    // Regular win pays 1:1
    stats.chips += currentBet * 2;
  } else if (result === "lose") {
    stats.losses++;
    // Bet already deducted, no additional action needed
  } else if (result === "tie") {
    stats.ties++;
    // Return the bet on a tie
    stats.chips += currentBet;
  }

  // Add insurance win if applicable
  if (hasInsurance && calculateScore(dealerHand) === 21) {
    stats.chips += insuranceBet * 3; // 2:1 payout + original bet
  }

  // Check if player is out of chips
  if (stats.chips <= 0) {
    messageEl.textContent +=
      " Game Over! You're out of chips. Refreshing your chips...";
    setTimeout(() => {
      stats.chips = 1000;
      updateStatsDisplay();
      updateBetPanel();
      messageEl.textContent = "Your chips have been refreshed to 1000";
    }, 3000);
  }

  // Save stats to localStorage
  localStorage.setItem("blackjackStats", JSON.stringify(stats));
  updateStatsDisplay();
  updateBetPanel();

  // Show betting section for next game
  setTimeout(() => {
    bettingSection.classList.add("active");
    resetBet();
  }, 1500);
}

// Update stats display
function updateStatsDisplay() {
  // Check if elements exist before updating
  if (winsEl) winsEl.textContent = stats.wins;
  if (lossesEl) lossesEl.textContent = stats.losses;
  if (tiesEl) tiesEl.textContent = stats.ties;
  if (gamesPlayedEl) gamesPlayedEl.textContent = stats.gamesPlayed;
  if (blackjacksEl) blackjacksEl.textContent = stats.blackjacks;

  const winRate =
    stats.gamesPlayed > 0
      ? Math.round((stats.wins / stats.gamesPlayed) * 100)
      : 0;
  if (winRateEl) winRateEl.textContent = `${winRate}%`;
  if (chipCountPanelEl) chipCountPanelEl.textContent = stats.chips;
}

// Show rules modal
function showRules() {
  rulesModal.classList.add("active");
}

// Hide rules modal
function hideRules() {
  rulesModal.classList.remove("active");
}

// Initialize the game when the page loads
window.onload = initGame;
