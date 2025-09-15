async function checkAuth() {
  document.getElementById('main-content').classList.add('d-none');

  const token = localStorage.getItem("token");
  if (!token) {
    window.location.href = "/login";
    return;
  }

  try {
    const res = await fetch("/api/auth", {
      headers: { "Authorization": `Bearer ${token}` }
    });

    const data = await res.json();

    if (data.status === "error") {
      localStorage.removeItem("token");
      localStorage.removeItem("user");
      window.location.href = "/login";
    } else {
      document.getElementById('main-content').classList.remove('d-none');
      document.getElementById('loading').classList.add('d-none');

      // update localStorage user
      localStorage.setItem("user", JSON.stringify(data.user));
      return data.user;
    }
  } catch (err) {
    console.error("Auth check gagal:", err);
    window.location.href = "/login";
  }
}

document.addEventListener("DOMContentLoaded", async () => {
  const user = await checkAuth();
});