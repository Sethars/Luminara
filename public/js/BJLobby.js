function joinLobby(creator) {
  alert("Kamu bergabung ke lobby milik " + creator);
}

function createLobby() {
  alert("Buat lobby baru!");
}

// Search lobby (filter by creator/desc)
document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById("searchLobby");
  const lobbyList = document.getElementById("lobbyList");

  searchInput.addEventListener("input", () => {
    const keyword = searchInput.value.toLowerCase();
    const cards = lobbyList.getElementsByClassName("lobby-card");

    for (let card of cards) {
      const text = card.innerText.toLowerCase();
      card.style.display = text.includes(keyword) ? "flex" : "none";
    }
  });
});
