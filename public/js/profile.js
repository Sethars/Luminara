import { showModal } from "../module_js/show_modal.js";
import { setLoading } from "../module_js/setLoading.js";
import { updateLocalData } from "../module_js/update_local_data.js";
import { formatMoney } from "../module_js/format_money.js";

const photoDefault = "/assets/img/photo_profile/ppkosong.jpg";
document.addEventListener("DOMContentLoaded", async function () {
  //Cash Money
  fetch("api/getMoneyData", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Authorization: `Bearer ${token}`,
    },
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        document.getElementById("profile-money").textContent = formatMoney(
          data.money
        );
      }
    });

  //Username
  document.getElementById("preview-username").textContent = user
    ? user.username
    : "Demo";
  document.getElementById("newUsername").value = user ? user.username : "";

  document.getElementById("public-preview-username").textContent = user
    ? user.username
    : "Demo";
  document.getElementById("newUsername").value = user ? user.username : "";

  //Photo Profile
  document.getElementById("preview-photo").src =
    profile && profile.photo ? profile.photo : photoDefault;
  document.getElementById("photo-preview-mini").src = 
    profile && profile.photo ? profile.photo : photoDefault;

  document.getElementById("public-preview-photo").src =
    profile && profile.photo ? profile.photo : photoDefault;

  //Bio
  if (profile && profile.bio !== null) {
    document.getElementById("preview-bio").textContent = profile.bio;
    document.getElementById("public-preview-bio").textContent = profile.bio;

    document.getElementById("newBio").value = profile.bio;
  } else {
    document.getElementById("preview-bio").textContent =
      "Pengguna belum mengatur bio";
    document.getElementById("public-preview-bio").textContent =
      "Pengguna belum mengatur bio";
  }
  //Gender
  document.getElementById("preview-gender").textContent = profile
    ? profile.gender
    : "Dragunov";
  document.getElementById("newGender").value = profile ? profile.gender : "";

  //Badges
  const badges = profile.badges || { used: [], unused: [] };
  renderBadges(badges);

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
    .addEventListener("click", async function (e) {
      e.preventDefault();

      const msg = document.getElementById("changeBadgeMsg");

      msg.textContent = "";
      msg.className = "";

      const used = [];
      const unused = [];
      let totalUsed = 0;

      document.querySelectorAll("#used-badges .badge-item").forEach((el) => {
        used.push(el.dataset.badge);
        totalUsed++;
      });

      if (totalUsed > 3) {
        msg.textContent = "Maksimal badge yang digunakan hanya 3";
        msg.classList.add("text-danger");
        return;
      }

      document.querySelectorAll("#unused-badges .badge-item").forEach((el) => {
        unused.push(el.dataset.badge);
      });

      try {
        const res = await fetch("api/updateBadges", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${token}`,
          },
          body: JSON.stringify({ used, unused }),
        });

        const result = await res.json();
        if (result.success) {
          msg.textContent = "Berhasil ganti badges";
          msg.classList.add("text-success");
          renderBadges(result.badge);
          updateLocalData("profile", "badges",JSON.stringify(result.badge));
        } else {
          msg.textContent = result.message || "Gagal ganti badges";
          msg.classList.add("text-danger");
          if (result.error) console.error("Server error:", result.error);
        }
      } catch (err) {
        console.error(err);
        msg.textContent = "Terjadi kesalahan koneksi atau server.";
        msg.classList.add("text-danger");
      }
    });

  const btnView = document.getElementById("viewprofile");
  const backBtn = document.getElementById("backBtn");

  const profileSetting = document.getElementById("profile_setting");
  const publicProfile = document.getElementById("public_profile");

  function showPublicProfile() {
    profileSetting.classList.remove("show");
    setTimeout(() => {
      publicProfile.classList.add("show");
    }, 400);
  }

  function showProfileSetting() {
    publicProfile.classList.remove("show");
    setTimeout(() => {
      profileSetting.classList.add("show");
    }, 400);
  }

  btnView.addEventListener("click", showPublicProfile);
  backBtn.addEventListener("click", showProfileSetting);

  //ujung dom
});

//Change username
document
  .getElementById("changeNameForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const newUsername = document.getElementById("newUsername").value;
    const msg = document.getElementById("changeUsernameMsg");

    if (isDemo()) {
      msg.textContent = "Anda harus login terlebih dahulu";
      msg.classList.add("text-danger");
      return;
    }

    msg.textContent = "";
    msg.className = "";

    if (!username) {
      msg.textContent = "Form harus diisi";
      msg.classList.add("text-danger");
      return;
    }
    try {
      const res = await fetch("api/changeUsername", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({ newUsername }),
      });

      const result = await res.json();
      if (result.success) {
        msg.textContent = "Berhasil ganti username";
        msg.classList.add("text-success");
        updateLocalData("user", "username", newUsername);
        document.getElementById("username").textContent = newUsername;
        document.getElementById("preview-username").textContent = newUsername;
      } else {
        msg.textContent = result.message || "Gagal ganti username";
        msg.classList.add("text-danger");
        if (result.error) console.error("Server error:", result.error);
      }
    } catch (err) {
      console.error(err);
      msg.textContent = "Terjadi kesalahan koneksi atau server.";
      msg.classList.add("text-danger");
    }
  });

//Change Photo Profile
//Preview
document
  .getElementById("profilePhoto")
  .addEventListener("change", function (e) {
    const file = e.target.files[0];
    if (file) {
      document.getElementById("photo-preview-mini").src =
        URL.createObjectURL(file);
    }
  });

//Update
document
  .getElementById("changePPBtn")
  .addEventListener("click", async function (e) {
    e.preventDefault();

    const msg = document.getElementById("changePPMsg");
    const file = document.getElementById("profilePhoto").files[0];

    msg.textContent = "";
    msg.className = "";

    if (!file) {
      msg.textContent = "Masukkan foto terlebih dahulu";
      msg.classList.add("text-danger");
      return;
    }

    const formData = new FormData();
    formData.append("pp", file);

    try {
      const res = await fetch("api/changePhotoProfile", {
        method: "POST",
        headers: {
          Authorization: `Bearer ${token}`,
        },
        body: formData,
      });

      const result = await res.json();
      if (result.success) {
        msg.textContent = "Berhasil mengganti foto profil Anda";
        msg.classList.add("text-success");
        updateLocalData("profile", "photo", result.file_url);
        document.getElementById("preview-photo").src = result.file_url;
        document.getElementById("navbar-profile-photo").src = result.file_url;
      } else {
        msg.textContent = "Upload gagal: " + result.message;
        msg.classList.add("text-danger");
      }
    } catch (err) {
      console.error("ERROR: " + err)
    }
  });

//Change Bio
document
  .getElementById("changeBioForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const bio = document.getElementById("preview-bio");
    const newBio = document.getElementById("newBio").value;
    const msg = document.getElementById("changeBioMsg");

    if (isDemo()) {
      msg.textContent = "Anda harus login terlebih dahulu";
      msg.classList.add("text-danger");
      return;
    }

    msg.textContent = "";
    msg.className = "";

    try {
      const res = await fetch("api/changeBio", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({ newBio }),
      });

      const result = await res.json();
      if (result.success) {
        msg.textContent = "Berhasil ganti bio";
        msg.classList.add("text-success");
        updateLocalData("profile", "bio", newBio);
        bio.textContent = newBio;
      }
    } catch (err) {
      console.error(err);
      msg.textContent = "Terjadi kesalahan koneksi atau server.";
      msg.classList.add("text-danger");
    }
  });

//Change Gender
document
  .getElementById("changeGenderBtn")
  .addEventListener("click", async function (e) {
    e.preventDefault();

    const gender = document.getElementById("preview-gender");
    const newGender = document.getElementById("newGender").value;
    const msg = document.getElementById("changeGenderMsg");

    msg.textContent = "";
    msg.className = "";

    if (!newGender) {
      return;
    }

    try {
      const response = await fetch("api/changeGender", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({ newGender }),
      });

      const result = await response.json();

      if (result.success) {
        msg.textContent = "Gender berhasil diperbarui";
        msg.classList.add("text-success");
        updateLocalData("profile", "gender", newGender);
        gender.textContent = newGender;
      }
    } catch (err) {
      console.error("Error:", err);
      msg.textContent = "Terjadi kesalahan koneksi atau server.";
      msg.classList.add("text-danger");
    }
  });

//Change Password
document
  .getElementById("changePasswordForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const oldPassword = document.getElementById("oldPassword").value;
    const newPassword = document.getElementById("newPassword").value;
    const confirmPassword = document.getElementById("confirmPassword").value;
    const msg = document.getElementById("messageChangePassword");

    if (isDemo()) {
      msg.textContent = "Anda harus login terlebih dahulu";
      msg.classList.add("text-danger");
      return;
    }

    msg.textContent = "";
    msg.classList.remove("text-danger", "text-success");

    if (!oldPassword || !newPassword || !confirmPassword) {
      msg.textContent = "Form tidak boleh kosong";
      msg.classList.add("text-danger");
      return;
    }

    if (oldPassword === newPassword) {
      msg.textContent = "Password baru tidak boleh sama dengan password lama";
      msg.classList.add("text-danger");
    }

    if (newPassword !== confirmPassword) {
      msg.textContent = "Konfirmasi password tidak sesuai";
      msg.classList.add("text-danger");
      return;
    }

    try {
      setLoading(true, "changePasswordBtn");

      const response = await fetch("api/changePassword", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({ oldPassword, newPassword }),
      });

      const result = await response.json();

      if (result.success) {
        msg.textContent = "Berhasil ganti password";
        msg.classList.add("text-success");
      } else {
        msg.textContent = result.message || "Gagal ganti password";
        msg.classList.add("text-danger");
        if (result.error) console.error("Server error:", result.error);
      }
    } catch (err) {
      console.error(err);
      msg.textContent = "Terjadi kesalahan koneksi atau server.";
      msg.classList.add("text-danger");
    } finally {
      setLoading(false, "changePasswordBtn");
    }
  });

//Delete Akun
document
  .getElementById("deleteAccountForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const password = document.getElementById("deletePassword").value;
    const msg = document.getElementById("deleteAccountMsg");
    setLoading(true, "deleteAccountBtn");

    if (isDemo()) {
      msg.textContent = "Anda harus login terlebih dahulu";
      msg.classList.add("text-danger");
      setLoading(false, "deleteAccountBtn");
      return;
    }

    try {
      const response = await fetch("/api/deleteAccount", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({ password }),
      });

      const result = await response.json();

      if (result.success) {
        showModal("deleteSuccess");
      } else {
        msg.textContent = result.message || "Gagal hapus akun";
        msg.classList.add("text-danger");
        if (result.error) console.error("Server error:", result.error);
      }
    } catch (err) {
      console.error(err);
      msg.textContent = "Terjadi kesalahan koneksi atau server.";
      msg.classList.add("text-danger");
    } finally {
      setLoading(false, "deleteAccountBtn");
    }
  });

document.addEventListener("DOMContentLoaded", () => {
  const btnView = document.querySelector(".btn.btn-success");
  const profileSetting = document.getElementById("profile_setting");
  const publicProfile = document.getElementById("public_profile");

  btnView.addEventListener("click", () => {
    profileSetting.style.display = "none";
    publicProfile.style.display = "block";
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const backBtn = document.getElementById("backBtn");
  if (backBtn) {
    backBtn.addEventListener("click", () => {
      document.getElementById("public_profile").style.display = "none";
      document.getElementById("profile_setting").style.display = "block";
    });
  }
});
