// Data lotre
const lotteryData = {
  ticketPrice: 5000,
  jackpotPercentage: 0.5, // Diubah menjadi 50%
  initialJackpot: 50000,
  currentJackpot: 50000,
  ticketsSold: 0,
  purchaseHistory: [],
  isLotteryEnded: false,
  winnerTicket: null,
};

// Elemen DOM
const jackpotDisplayEl = document.getElementById("jackpot-display");
const jackpotAmountEl = document.getElementById("jackpot-amount");
const buyButtonEl = document.getElementById("buy-ticket");
const ticketDisplayEl = document.getElementById("ticket-display");
const ticketNumbersEl = document.getElementById("ticket-numbers");
const ticketIdEl = document.getElementById("ticket-id");
const ticketDateEl = document.getElementById("ticket-date");
const historyListEl = document.getElementById("history-list");
const notificationEl = document.getElementById("notification");
const winnerAnnouncementEl = document.getElementById("winner-announcement");
const winnerNameEl = document.getElementById("winner-name");

// Elemen modal
const rulesButton = document.getElementById("rules-button");
const rulesModal = document.getElementById("rules-modal");
const closeModal = document.getElementById("close-modal");

// Format angka dengan pemisah ribuan
function formatNumber(amount) {
  return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Update tampilan jackpot
function updateJackpotDisplay() {
  jackpotAmountEl.textContent = formatNumber(lotteryData.currentJackpot);
}

// Generate nomor tiket acak
function generateRandomNumbers() {
  const numbers = [];
  for (let i = 0; i < 5; i++) {
    numbers.push(Math.floor(Math.random() * 10));
  }
  return numbers;
}

// Generate ID tiket unik
function generateTicketId() {
  return "TIX-" + Math.random().toString(36).substr(2, 9).toUpperCase();
}

// Generate username acak
function generateRandomUsername() {
  const adjectives = [
    "Cool",
    "Super",
    "Mega",
    "Ultra",
    "Hyper",
    "Great",
    "Fast",
    "Quick",
  ];
  const nouns = [
    "Player",
    "Gamer",
    "Winner",
    "Champ",
    "Hero",
    "Star",
    "Legend",
    "Master",
  ];
  const numbers = Math.floor(Math.random() * 1000);

  const adjective = adjectives[Math.floor(Math.random() * adjectives.length)];
  const noun = nouns[Math.floor(Math.random() * nouns.length)];

  return adjective + noun + numbers;
}

// Tampilkan notifikasi
function showNotification(message) {
  notificationEl.textContent = message;
  notificationEl.classList.add("show");

  setTimeout(() => {
    notificationEl.classList.remove("show");
  }, 3000);
}

// Update riwayat pembelian
function updatePurchaseHistory() {
  if (lotteryData.purchaseHistory.length === 0) {
    historyListEl.innerHTML =
      '<p style="text-align: center; color: #ccc;">Belum ada pembelian</p>';
    return;
  }

  historyListEl.innerHTML = "";

  lotteryData.purchaseHistory.forEach((ticket) => {
    const historyItem = document.createElement("div");
    historyItem.className = "history-item";

    const numbersContainer = document.createElement("div");
    numbersContainer.className = "history-numbers";

    ticket.numbers.forEach((num) => {
      const numberBall = document.createElement("div");
      numberBall.className = "history-number";
      if (
        lotteryData.isLotteryEnded &&
        lotteryData.winnerTicket &&
        lotteryData.winnerTicket.id === ticket.id
      ) {
        numberBall.classList.add("winner");
      }
      numberBall.textContent = num;
      numbersContainer.appendChild(numberBall);
    });

    const ticketInfo = document.createElement("div");
    ticketInfo.innerHTML = `
                    <div>${ticket.id}</div>
                    <div>${ticket.date}</div>
                    ${
                      lotteryData.isLotteryEnded &&
                      lotteryData.winnerTicket &&
                      lotteryData.winnerTicket.id === ticket.id
                        ? '<div style="color: #ffd700; font-weight: bold;">🏆 PEMENANG</div>'
                        : ""
                    }
                `;

    historyItem.appendChild(numbersContainer);
    historyItem.appendChild(ticketInfo);

    historyListEl.appendChild(historyItem);
  });
}

// Event listener untuk tombol beli
buyButtonEl.addEventListener("click", function () {
  // Cek apakah lotre sudah berakhir
  if (lotteryData.isLotteryEnded) {
    showNotification("Lotre sudah berakhir! Tunggu lotre berikutnya.");
    return;
  }

  // Generate nomor tiket
  const numbers = generateRandomNumbers();
  const ticketId = generateTicketId();
  const currentDate = new Date().toLocaleDateString("id-ID");

  // Tambah ke riwayat
  const newTicket = {
    id: ticketId,
    numbers: numbers,
    date: currentDate,
    username: generateRandomUsername(),
  };

  lotteryData.purchaseHistory.unshift(newTicket);

  // Update jackpot
  const jackpotIncrease =
    lotteryData.ticketPrice * lotteryData.jackpotPercentage;
  lotteryData.currentJackpot += jackpotIncrease;
  lotteryData.ticketsSold += 1;

  // Update tampilan
  updateJackpotDisplay();

  // Tampilkan tiket
  ticketDisplayEl.style.display = "block";
  ticketNumbersEl.innerHTML = "";

  numbers.forEach((num, index) => {
    const numberBall = document.createElement("div");
    numberBall.className = "number-ball";
    numberBall.textContent = num;
    ticketNumbersEl.appendChild(numberBall);

    // Animasi muncul
    setTimeout(() => {
      numberBall.classList.add("animate");
    }, index * 100);
  });

  ticketIdEl.textContent = ticketId;
  ticketDateEl.textContent = currentDate;

  // Update riwayat
  updatePurchaseHistory();

  // Tampilkan notifikasi
  showNotification(
    `Tiket berhasil dibeli! Jackpot bertambah ${formatNumber(jackpotIncrease)}`
  );
});

// Event listener untuk modal
rulesButton.addEventListener("click", function () {
  rulesModal.style.display = "block";
  document.body.style.overflow = "hidden"; // Mencegah scrolling di background
});

closeModal.addEventListener("click", function () {
  rulesModal.style.display = "none";
  document.body.style.overflow = "auto"; // Mengembalikan scrolling
});

// Tutup modal jika klik di luar konten
window.addEventListener("click", function (event) {
  if (event.target === rulesModal) {
    rulesModal.style.display = "none";
    document.body.style.overflow = "auto";
  }
});

// Membuat efek sparkles
function createSparkle() {
  const sparkle = document.createElement("div");
  sparkle.classList.add("sparkle");

  // Posisi acak
  const posX = Math.random() * window.innerWidth;
  const posY = Math.random() * window.innerHeight;

  sparkle.style.left = `${posX}px`;
  sparkle.style.top = `${posY}px`;

  // Ukuran acak
  const size = Math.random() * 5 + 2;
  sparkle.style.width = `${size}px`;
  sparkle.style.height = `${size}px`;

  document.body.appendChild(sparkle);

  // Hapus sparkle setelah animasi selesai
  setTimeout(() => {
    sparkle.remove();
  }, 3000);
}

// Buat sparkles setiap 300ms
setInterval(createSparkle, 300);

// Fungsi untuk memilih pemenang secara acak
function selectRandomWinner() {
  // ========== FUNGSI UNTUK MEMILIH PEMENANG SECARA ACAK ==========
  // Jika tidak ada tiket yang terjual, tidak ada pemenang
  if (lotteryData.purchaseHistory.length === 0) {
    showNotification("Tidak ada tiket yang terjual. Tidak ada pemenang.");
    return;
  }

  // Pilih tiket secara acak dari daftar pembelian
  const randomIndex = Math.floor(
    Math.random() * lotteryData.purchaseHistory.length
  );
  lotteryData.winnerTicket = lotteryData.purchaseHistory[randomIndex];

  // Tampilkan pemenang
  winnerNameEl.textContent = lotteryData.winnerTicket.username;
  winnerAnnouncementEl.classList.add("show");

  // Sembunyikan tampilan jackpot
  jackpotDisplayEl.style.display = "none";

  // Update riwayat untuk menandai pemenang
  updatePurchaseHistory();

  // Nonaktifkan tombol beli
  buyButtonEl.disabled = true;
  buyButtonEl.textContent = "Lotre Berakhir";

  // Tampilkan notifikasi
  showNotification(
    `Pemenang telah dipilih: ${
      lotteryData.winnerTicket.username
    } dengan hadiah ${formatNumber(lotteryData.currentJackpot)}!`
  );

  // ========== AKHIR FUNGSI PEMILIH PEMENANG ==========
}

// Hitung mundur untuk 3 hari
function updateCountdown() {
  // Waktu sekarang dalam GMT+7
  const now = new Date();
  const gmt7 = new Date(
    now.getTime() + 7 * 60 * 60 * 1000 + now.getTimezoneOffset() * 60 * 1000
  );

  // Set waktu mulai (hari ini jam 00:00:00 GMT+7)
  const startDate = new Date(gmt7);
  startDate.setHours(0, 0, 0, 0);

  // Set waktu akhir (3 hari dari sekarang jam 00:00:00 GMT+7)
  const endDate = new Date(startDate);
  endDate.setDate(endDate.getDate() + 3);

  // Hitung selisih waktu
  const diff = endDate - gmt7;

  // Jika countdown sudah selesai
  if (diff <= 0) {
    document.getElementById("days").textContent = "00";
    document.getElementById("hours").textContent = "00";
    document.getElementById("minutes").textContent = "00";
    document.getElementById("seconds").textContent = "00";

    // Jika lotre belum berakhir, akhiri lotre dan pilih pemenang
    if (!lotteryData.isLotteryEnded) {
      lotteryData.isLotteryEnded = true;
      selectRandomWinner();
    }

    return;
  }

  // Hitung hari, jam, menit, detik
  const days = Math.floor(diff / (1000 * 60 * 60 * 24));
  const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
  const seconds = Math.floor((diff % (1000 * 60)) / 1000);

  // Update tampilan
  document.getElementById("days").textContent = days
    .toString()
    .padStart(2, "0");
  document.getElementById("hours").textContent = hours
    .toString()
    .padStart(2, "0");
  document.getElementById("minutes").textContent = minutes
    .toString()
    .padStart(2, "0");
  document.getElementById("seconds").textContent = seconds
    .toString()
    .padStart(2, "0");
}

// Update countdown setiap detik
setInterval(updateCountdown, 1000);

// Inisialisasi tampilan
updateJackpotDisplay();
updateCountdown();
