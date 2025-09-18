//ambil data
const user = JSON.parse(localStorage.getItem('user'));
const userId = user.id;

window.getQueryParam = function (param) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(param);
};

window.showModal = function (modalId) {
  const modalElement = document.getElementById(modalId);
  if (modalElement) {
    const modal =
      bootstrap.Modal.getInstance(modalElement) ||
      new bootstrap.Modal(modalElement);
    modal.show();
  } else {
    console.error(`Modal with ID ${modalId} not found.`);
  }
};

window.closeModal = function (modalId) {
  const modalElement = document.getElementById(modalId);
  if (modalElement) {
    const modal =
      bootstrap.Modal.getInstance(modalElement) ||
      new bootstrap.Modal(modalElement);
    modal.hide();
  } else {
    console.error(`Modal with ID ${modalId} not found.`);
  }
};

function setLoading(isLoading, btnId) {
  const btn = document.getElementById(btnId);

  if (isLoading) {
    btn.disabled = true;
    btn.dataset.originalText = btn.innerHTML;
    btn.innerHTML = `
      <span class="spinner-border spinner-border-sm me-2" role="status"></span>`;
  } else {
    btn.disabled = false;
    btn.innerHTML = btn.dataset.originalText || "Submit";
  }
}


function generateRandomString(length) {
  const chars =
    "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  let result = "";

  for (let i = 0; i < length; i++) {
    const randomIndex = Math.floor(Math.random() * chars.length);
    result += chars[randomIndex];
  }

  return result;
}

function updateUserData(key, value) {
  if (!user) {
    console.error("User tidak ditemukan di localStorage");
    return;
  }

  try {
    user[key] = value; // update field sesuai parameter
    localStorage.setItem("user", JSON.stringify(user)); // simpan lagi
    console.log(`User ${key} berhasil diupdate jadi:`, value);
  } catch (err) {
    console.error("Gagal parse data user:", err);
  }
}

//ambil username
function showUsername(){
  document.getElementById('username').textContent = user ? user.username : 'Demo';
}

document.addEventListener('DOMContentLoaded', function(){
    showUsername();
})
