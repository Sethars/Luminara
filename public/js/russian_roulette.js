import { getQueryParam } from "../../module_js/get_query.js";
import { Encoder } from "../../module_js/encrypt.js";
import { fetchWithAuth } from "../module_js/fetch_with_auth.js";

const encoder = new Encoder();
const lobby = getQueryParam("lobby")

const decodedLobby = (() => {
  const decoded = encoder.decode(lobby);
  return decoded ? new URLSearchParams(decoded) : new URLSearchParams();
})();

const id = decodedLobby.get("id");
const mode = decodedLobby.get("mode");

//element dom
const muteBtn = document.getElementById("muteBtn");

let isMuted = false;

document.addEventListener("DOMContentLoaded", async function(){
  if(!lobby || !mode || !id){
      document.getElementById("reddirectModal").style.display = "flex";
      document.getElementById("reddirectText").textContent = "Link tidak valid";
      return;
  }

  checkRole();

  window.removeEventListener("beforeunload", () => {});
  document.querySelectorAll("button").forEach(btn => {
    const clone = btn.cloneNode(true);
    btn.replaceWith(clone);
  });

  if (mode === "bot") {
    const { Vsbot } = await import("./roulette/vsbot.js");
    Vsbot.initDisplayBot(id);
  } else if (mode === "pvp") {
    const { pvp } = await import("./roulette/pvp.js");
    pvp.initDisplayPvp(id);
  }

  muteBtn.addEventListener("click", toggleMute);

  updateLastSeenInGame();
  setInterval(updateLastSeenInGame, 5000);
})

function checkRole(){
  fetchWithAuth("../api/checkRole")
  .then(res => res.json())
  .then(data => {
    const role = data.role;
    if(role === "admin" || role === "owner"){
      document.getElementById("revealBtn").style.removeProperty("display");
      if(mode === "bot"){
        document.getElementById("btnBotSmart").style.removeProperty("display");
        document.getElementById("btnBotSuicide").style.removeProperty("display");
      }
    } else if(role === "gacor"){
      botSmartMode = false;
      botSuicideLast = false;
    }
  })
  .catch(() => {});
}

function updateLastSeenInGame(id){
  fetchWithAuth("../api/updateLastSeenInGame", {
    method: "POST",
    headers: {"Content-Type" : "application/json"},
    body: JSON.stringify({id})
  })
  .catch(() => {});
}

function toggleMute() {
  isMuted = !isMuted;
  if (isMuted) {
    bgm.pause();
    muteBtn.textContent = "🔇";
  } else {
    bgm.play();
    muteBtn.textContent = "🔊";
  }
}
