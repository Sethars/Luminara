export function randomBotDisplay(){
  const rand = parseInt(Math.random() * 7);
  const botNames = [
    "Tachyon Jumpscare",
    "Kecoa Kekar",
    "Hawking Backflip",
    "Herta Ragebaiter",
    "Pesut Mahakam",
    "Machester United",
    "Master Goonway"
  ]
  const botPhoto = [
    "tachyon-jumpscare",
    "kecoa-kekar",
    "hawking-backflip",
    "herta-ragebaiter",
    "pesut-mahakam",
    "mu",
    "mr-goonway"
  ]

  document.getElementById("bot-name").textContent = botNames[rand];
  document.getElementById("bot-photo").src = `../assets/img/roulette/${botPhoto[rand]}.jpg`;
}