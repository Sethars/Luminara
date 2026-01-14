export function restartGame(bet, mode) {
  document.getElementById("gameOverModal").style.display = "none";
  setTimeout(() => window.location.href = `/RRLobby_list?action=addLobby&mode=${mode}${bet ? `&bet=${bet}` : ""}`, 200);
}