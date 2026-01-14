import { fetchWithAuth } from "../module_js/fetch_with_auth.js";

// Elemen DOM
const lotteryTitle = document.getElementById("lottery-title")
const jackpotDisplayEl = document.getElementById("jackpot-display");
const ticketPriceEl = document.getElementById("ticket-price")
const buyButtonEl = document.getElementById("buy-ticket");
const ticketDisplayEl = document.getElementById("ticket-display");
const ticketTitleEl = document.getElementById("ticket-title");
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

let lottery = {}
let tickets = {}
let isActive = false
let isLottery = false

//Ambil data lotre
document.addEventListener('DOMContentLoaded', async function(){
  const res = await fetchWithAuth('api/getLotteryData')
  const data = await res.json();

  if(!data.success){
    if(data.message === "Event belum dimulai"){
      showNotification(data.message)
    }
    showNotification(data.message);
    if(data.error) console.error(data.error)
  }

  lottery = data.lottery || {};
  tickets = data.user_tickets || {};
  isLottery = data.success;
  isActive = data.is_active;

  console.log(data);
  console.log(lottery);
  console.log(tickets);

  // Event listener untuk tombol beli
  buyButtonEl.disabled = !isActive
  buyButtonEl.addEventListener("click", buyTicket);

  // Buat sparkles setiap 300ms
  setInterval(createSparkle, 300);

  // Update countdown setiap detik
  isActive ?
  setInterval(updateCountdown, 1000)
  :
  clearInterval()

  // Inisialisasi tampilan
  lotteryTitle.textContent = isLottery ? lottery.event_name : ""
  updateJackpotDisplay(isLottery, isActive);
  updateTicketPrice(isActive);
  updateCountdown(isLottery);
  updatePurchaseHistory()
})

// Format angka dengan pemisah ribuan
function formatNumber(amount) {
  return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Update tampilan jackpot
function updateJackpotDisplay(isLottery, isActive) {
  if(isLottery){
    isActive ?
    jackpotDisplayEl.innerHTML = `
      <i class="fas fa-trophy"></i> HADIAH UTAMA
      <span id="jackpot-amount">${": " + formatNumber(lottery.reward) + " Cash"}</span>
    `
    :
    jackpotDisplayEl.innerHTML = `
      <i class="fas fa-trophy"></i> PEMENANG HADIAH UTAMA
      <span id="jackpot-amount">${": " + lottery.username}</span>
    `
  } else {
    jackpotDisplayEl.innerHTML = `<span>Event belum dimulai</span>`
  }
}

// Update tampilan harga tiket
function updateTicketPrice(isActive){
  isActive ? 
  ticketPriceEl.innerHTML = `${" " + formatNumber(lottery.ticket_price) + " Cash"}`
  :
  ticketPriceEl.innerHTML = "—"
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
  if (tickets.length === 0) {
    historyListEl.innerHTML =
      '<p style="text-align: center; color: #ccc;">Belum ada pembelian</p>';
    return;
  }

  let displayTicket = tickets[0];
  let winner = false;
  if (isLottery && !isActive) {
    winner = tickets.find(
      (t) => t.ticket === lottery.winner_ticket
    );
    if (winner) {
      displayTicket = winner;
    }
  }

  // Tampilkan tiket pilihan (pemenang atau index-0)
  ticketDisplayEl.style.display = "block";
  ticketNumbersEl.innerHTML = "";

  JSON.parse(displayTicket.lottery_number).forEach((num, index) => {
    const numberBall = document.createElement("div");
    numberBall.className = "number-ball";
    if(winner){ 
      numberBall.classList.add("winner")
      ticketTitleEl.textContent = "Tiket Anda Yang Berhasil Memenangkan Lottery"
    }
    numberBall.textContent = num;
    ticketNumbersEl.appendChild(numberBall);

    // Animasi muncul
    setTimeout(() => {
      numberBall.classList.add("animate");
    }, index * 100);
  });

  ticketIdEl.textContent = displayTicket.ticket;
  ticketDateEl.textContent = displayTicket.purchased_at;

  historyListEl.innerHTML = "";

  tickets.forEach((ticket) => {
    const historyItem = document.createElement("div");
    historyItem.className = "history-item";

    const numbersContainer = document.createElement("div");
    numbersContainer.className = "history-numbers";

    JSON.parse(ticket.lottery_number).forEach((num) => {
      const numberBall = document.createElement("div");
      numberBall.className = "history-number";
      if (
        isLottery &&
        !isActive &&
        lottery.winner_ticket === ticket.ticket
      ) {
        numberBall.classList.add("winner");
      }
      numberBall.textContent = num;
      numbersContainer.appendChild(numberBall);
    });
    historyItem.appendChild(numbersContainer);

    if(isLottery && !isActive && lottery.winner_ticket === ticket.ticket){
      const winnerInfo = document.createElement("div");
      winnerInfo.innerHTML = '<div style="color: #ffd700; font-weight: bold;">🏆 PEMENANG</div>'               
      historyItem.appendChild(winnerInfo);
    }

    const ticketInfo = document.createElement("div");
    ticketInfo.innerHTML = `
                    <div>${ticket.ticket}</div>
                    <div>${ticket.purchased_at}</div>
                `;
    historyItem.appendChild(ticketInfo);

    historyListEl.appendChild(historyItem);
  });
}

//Beli tiket
function buyTicket() {
  // Cek apakah lotre sudah berakhir
  if (!isActive) {
    showNotification("Lotre sudah berakhir! Tunggu lotre berikutnya.");
    return;
  }

  let id = lottery.id
  fetchWithAuth("api/buyTicketLottery",{
    method: "POST",
    headers: {"Content-Type" : "application/json"},
    body: JSON.stringify({id})
  })
  .then(res => res.json())
  .then(data => {
    if(data.success){
      // Update jackpot
      const jackpotIncrease = lottery.ticket_price / 2;
      lottery.reward += jackpotIncrease;
      lottery.ticket_sold += 1;

      // Update tampilan
      updateJackpotDisplay(isLottery, isActive);

      // Update riwayat
      tickets.unshift(data.new_ticket)
      updatePurchaseHistory();

      // Tampilkan notifikasi
      showNotification(
        `Tiket berhasil dibeli!`
      );
    } else {
      showNotification(data.message);
    }
  })
  .catch(() => {});
}

// Fungsi untuk memilih pemenang
function selectWinner() {
  // ========== FUNGSI UNTUK MEMILIH PEMENANG SECARA ACAK ==========
  // Jika tidak ada tiket yang terjual, tidak ada pemenang
  if (lottery.purchaseHistory.length === 0) {
    showNotification("Tidak ada tiket yang terjual. Tidak ada pemenang.");
    return;
  }

  // Pilih tiket secara acak dari daftar pembelian
  const randomIndex = Math.floor(
    Math.random() * lottery.purchaseHistory.length
  );
  lottery.winnerTicket = lottery.purchaseHistory[randomIndex];

  // Tampilkan pemenang
  winnerNameEl.textContent = lottery.winnerTicket.username;
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
      lottery.winnerTicket.username
    } dengan hadiah ${formatNumber(lottery.currentJackpot)}!`
  );

  // ========== AKHIR FUNGSI PEMILIH PEMENANG ==========
}

function updateCountdown(isLotteryEnded) {
  if(isLotteryEnded) return;

  // Waktu sekarang dalam GMT+7
  const now = new Date();
  const gmt7 = new Date(
    now.getTime() + 7 * 60 * 60 * 1000 + now.getTimezoneOffset() * 60 * 1000
  );

  // Set waktu akhir
  const endDate = new Date(lottery.ended_at || gmt7);

  // Hitung selisih waktu
  const diff = endDate - gmt7;

  // Jika countdown sudah selesai
  if (diff <= 0) {
    document.getElementById("days").textContent = "00";
    document.getElementById("hours").textContent = "00";
    document.getElementById("minutes").textContent = "00";
    document.getElementById("seconds").textContent = "00";
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