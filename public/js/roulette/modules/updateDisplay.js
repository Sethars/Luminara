export function renderChambers(chambers, currentIndex, reveal) {
  const container = document.getElementById("chambers");

  if (container.children.length === 0) {
    chambers.forEach(() => {
      const div = document.createElement("div");
      div.className = "chamber";
      container.appendChild(div);
    });
  }

  Array.from(container.children).forEach((div, i) => {
    if (i === currentIndex) {
      div.className = "chamber active";
    } else if (reveal && chambers[i]) {
      div.className = "chamber bullet";
    } else {
      div.className = "chamber";
    }
  });
}

export function updateProgress(timeLeft, turn) {
  let percent = (timeLeft / 20) * 100;
  const bar = document.getElementById("turnProgressBar");
  bar.style.width = percent + "%";
  bar.style.background = turn === "player" ? "limegreen" : "orange";
}

export function updateButtons(turn) {
  document.getElementById("btnSelf").disabled = turn !== "player";
  document.getElementById("btnEnemy").disabled = turn !== "player";
}

export function updateTurnIndicator(turn) {
  const turnIndicator = document.getElementById("turnIndicator");
  turnIndicator.textContent =
    turn === "player" ? "PLAYER'S TURN" : "BOT'S TURN";
  turnIndicator.style.backgroundColor =
    turn === "player" ? "#3498db" : "#e74c3c";
}

export function closeModal() {
  document.getElementById("confirmModal").style.display = "none";
}