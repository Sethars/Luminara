window.getQueryParam = function (param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

window.showModal = function (modalId) {
    const modalElement = document.getElementById(modalId);
    if (modalElement) {
        const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
        modal.show();
    } else {
        console.error(`Modal with ID ${modalId} not found.`);
    }
}

window.closeModal = function (modalId) {
    const modalElement = document.getElementById(modalId);
    if (modalElement) {
        const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
        modal.hide();
    } else {
        console.error(`Modal with ID ${modalId} not found.`);
    }
}

function setLoading(isLoading, btnId) {
  const btn = document.getElementById(btnId); // id tombol submit
  const btnSpinner = document.getElementById("btnSpinner");
  const btnText = document.getElementById("btnText");

  if (isLoading) {
    btn.disabled = true;
    btnSpinner.classList.remove("d-none");
    btnText.classList.add("d-none");
  } else {
    btn.disabled = false;
    btnSpinner.classList.add("d-none");
    btnText.classList.remove("d-none");
  }
}

function generateRandomString(length) {
  const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  let result = "";
  
  for (let i = 0; i < length; i++) {
    const randomIndex = Math.floor(Math.random() * chars.length);
    result += chars[randomIndex];
  }
  
  return result;
}