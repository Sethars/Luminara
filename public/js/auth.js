async function checkAuth() {
  document.getElementById('main-content').classList.add('d-none');
  document.getElementById('loading').classList.remove('d-none');

  if(JSON.parse(localStorage.getItem('demo') || 'false')){
    const expired = JSON.parse(localStorage.getItem('expired'));
    const token = localStorage.getItem('token');
    if(token && Date.now() <= expired){
      document.getElementById('main-content').classList.remove('d-none');
      document.getElementById('loading').classList.add('d-none');
      return{demo : true, user : null, token};
    } else {
      localStorage.removeItem('demo');
      localStorage.removeItem("token");
      localStorage.removeItem("expired");
      window.location.href = "/login";
      return;
    }
  }

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
      return {demo : false, user : data.user, to};
    }
  } catch (err) {
    console.error("Auth check gagal:", err);
    window.location.href = "/login";
  }
}

document.addEventListener("DOMContentLoaded", async () => {
  const user = localStorage.getItem('user');
  const isDemo = JSON.parse(localStorage.getItem('demo') || 'false');
  if(isDemo){
    await checkAuth();
    return;
  }
  
  if(!user){
    const user = await checkAuth();
    window.location.reload();
    return;
  }
});