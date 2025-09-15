let timeLeft = 5 * 60;

const timerEl = document.getElementById("timer");

function updateTimer() {
  const minutes = String(Math.floor(timeLeft / 60)).padStart(2, "0");
  const seconds = String(timeLeft % 60).padStart(2, "0");
  timerEl.textContent = `${minutes}:${seconds}`;

  if (timeLeft <= 0) {
    clearInterval(countdown);
    // lakukan aksi saat habis
    timerEl.textContent = "Waktu habis!";
    // contoh redirect otomatis:
    // window.location.href = "/selesai.html";
  }

  timeLeft--;
}

document.getElementById("resendOTP").addEventListener("click", function () {
  let btn = this;
  let countdown = 60; // detik
  btn.disabled = true; // disable button
  btn.classList.remove("gradient-gold");
  btn.classList.add("btn-secondary");

  let originalText = btn.innerHTML;

  // Update text tiap detik
  let timer = setInterval(function () {
    btn.innerHTML = `<strong>Tunggu ${countdown}s</strong>`;
    countdown--;

    if (countdown < 0) {
      clearInterval(timer);
      btn.disabled = false; // aktifkan lagi
      btn.classList.remove("btn-secondary");
      btn.classList.add("gradient-gold");
      btn.innerHTML = originalText;
    }
  }, 1000);
});

// langsung jalan saat halaman dibuka
updateTimer();
const countdown = setInterval(updateTimer, 1000);

const formData = JSON.parse(localStorage.getItem("formData"));

document
  .getElementById("resendOTP")
  .addEventListener("click", async function () {
    try {
      const res = await fetch("/api/sendOTP", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(formData),
      });

      const result = await res.json();

      //BUAT TAMPILKAN PESAN BERHASIL ATAU GAGAL --NANTI--

      // if (result.success) {
      //   alert("OTP terkirim! Silakan cek email Anda.");
      // } else {
      //   alert("Gagal mengirim OTP: " + result.message);
      // }
    } catch (err) {
      console.error("Error:", err);
    }
  });

document
  .getElementById("verificationForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const otp = document.getElementById("otp").value;
    formData.otp = otp; // tambahkan OTP ke formData

    document.getElementById("warningText").textContent = ""; // reset pesan peringatan

    try {
      const res = await fetch("/api/verifyOTP", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(formData),
      });

      const result = await res.json();

      if (result.success) {
        // alert('Verifikasi berhasil! Anda akan dialihkan ke halaman utama.');
        localStorage.removeItem("formData");
        window.location.href = "/login";
      } else {
        // alert('Verifikasi gagal: ' + result.message);
        document.getElementById("warningText").textContent =
          "Verifikasi gagal: " + result.message;
      }
    } catch (err) {
      console.error("Error:", err);
    }
  });
