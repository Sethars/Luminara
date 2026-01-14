export function showPistol(img, target) {
  img.src =
    target === "self"
      ? "../../assets/img/roulette/kiri.png"
      : "../../assets/img/roulette/kanan.png";

  img.className = "fadeIn";

  if (target === "self") {
    img.classList.add("shake_scared");
  } else {
    img.classList.remove("shake_scared");
  }
}

export function fadeOutPistol(img) {
  img.className = "fadeOut";
}