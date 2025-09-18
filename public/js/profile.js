document.addEventListener("DOMContentLoaded", function () {
  document.getElementById('preview-username').textContent = user ? user.username : 'Demo';

  new Sortable(document.getElementById("used-badges"), {
    group: "badges",
    animation: 150,
    ghostClass: "sortable-ghost",
  });

  new Sortable(document.getElementById("unused-badges"), {
    group: "badges",
    animation: 150,
    ghostClass: "sortable-ghost",
  });

  // Simpan hasil perubahan ke JSON
  document
    .getElementById("saveBadgesBtn")
    .addEventListener("click", function () {
      const used = [];
      const unused = [];

      document.querySelectorAll("#used-badges .badge-item").forEach((el) => {
        used.push(el.dataset.badge);
      });
      document.querySelectorAll("#unused-badges .badge-item").forEach((el) => {
        unused.push(el.dataset.badge);
      });

      const badgeConfig = { used, unused };
      console.log("Badge JSON:", badgeConfig);

      fetch("save_badges.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(badgeConfig),
      })
        .then((res) => res.json())
        .then((data) => {
          alert("Badge berhasil disimpan!");
        })
        .catch((err) => console.error(err));
    });
});

//Change username
document.getElementById('changeNameForm').addEventListener('submit', async function (e) {
  e.preventDefault();

  const newUsername = document.getElementById('newUsername').value;
  const msg = document.getElementById('changeUsernameMsg');

  msg.textContent = ''
  msg.className = ''

  if(!username){
    msg.textContent = "Form harus diisi";
    msg.classList.add('text-danger');
    return;
  }
  try{
    const res = await fetch('api/changeUsername', {
      method: "POST",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify({userId, newUsername})
    });

    const result = await res.json();
    if(result.success){
      msg.textContent = 'Berhasil ganti username';
      msg.classList.add('text-success');
      updateUserData('username', newUsername);
      showUsername();
      document.getElementById('preview-username').textContent = newUsername;
    } else{
      msg.textContent = result.message || "Gagal ganti username";
      msg.classList.add('text-danger');
      if (result.error) console.error("Server error:", result.error);
    }
  }catch(err){
    console.error(err);
    msg.textContent = "Terjadi kesalahan koneksi atau server.";
    msg.classList.add('text-danger');
  }
})

//Change Password
document.getElementById('changePasswordForm').addEventListener('submit', async function (e) {
  e.preventDefault();
  setLoading(true, 'changePasswordBtn');

  const oldPassword = document.getElementById('oldPassword').value;
  const newPassword = document.getElementById('newPassword').value;
  const confirmPassword = document.getElementById('confirmPassword').value;
  const msg = document.getElementById('messageChangePassword');

  msg.textContent = "";
  msg.classList.remove("text-danger", "text-success");

  if(!oldPassword || !newPassword || !confirmPassword){
    msg.textContent = "Form tidak boleh kosong";
    msg.classList.add('text-danger');
    return;
  }

  if(oldPassword === newPassword){
    msg.textContent = "Password baru tidak boleh sama dengan password lama";
    msg.classList.add('text-danger');
  }
  
  if(newPassword !== confirmPassword){
    msg.textContent = "Konfirmasi password tidak sesuai"
    msg.classList.add('text-danger');
    return;
  }
  
  try{
    const response = await fetch('api/changePassword',{
      method: "POST",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify({userId, oldPassword, newPassword})
    });
    
    const result = await response.json();
    
    if(result.success){
      msg.textContent = "Berhasil ganti password";
      msg.classList.add('text-success');
    } else {
      msg.textContent = result.message || "Gagal ganti password";
      msg.classList.add('text-danger');
      if (result.error) console.error("Server error:", result.error);
    }
  } catch(err){
    console.error(err);
    msg.textContent = "Terjadi kesalahan koneksi atau server.";
    msg.classList.add('text-danger');
  } finally{
    setLoading(false, 'changePasswordBtn');
  }
})

//Delete Akun
document.getElementById('deleteAccountForm').addEventListener('submit', async function (e) {
  e.preventDefault();

  const password = document.getElementById('deletePassword').value;
  const msg = document.getElementById('deleteAccountMsg');
  setLoading(true, 'deleteAccountBtn');

  try{
    const response = await fetch('/api/deleteAccount', {
      method: "POST",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify({userId, password})
    });

    const result = await response.json();

    if(result.success){
      showModal('deleteSuccess');
    } else {
      msg.textContent = result.message || "Gagal hapus akun";
      msg.classList.add('text-danger');
      if (result.error) console.error("Server error:", result.error);
    }
  } catch(err){
    console.error(err);
    msg.textContent = "Terjadi kesalahan koneksi atau server.";
    msg.classList.add('text-danger');
  } finally {
    setLoading(false, 'deleteAccountBtn');
  }
});