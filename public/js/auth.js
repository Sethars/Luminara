import { decode } from "../module_js/encrypt.js";
import { fetchWithAuth } from "../module_js/fetch_with_auth.js";

export async function checkAuth() {
  document.getElementById("main-content").classList.add("d-none");
  document.getElementById("loading").classList.remove("d-none");
  const encodedToken = localStorage.getItem('token');
  const token = decode(encodedToken)

  if (JSON.parse(localStorage.getItem("demo")) || false) {
    const expired = JSON.parse(localStorage.getItem("expired"));
    token = localStorage.getItem("token");
    if (token && Date.now() <= expired) {
      document.getElementById("main-content").classList.remove("d-none");
      document.getElementById("loading").classList.add("d-none");
      return { demo: true, user: null, token };
    } else {
      localStorage.removeItem("demo");
      localStorage.removeItem("token");
      localStorage.removeItem("expired");
      window.location.href = "/login";
      return;
    }
  }
  
  if (!token) {
    window.location.href = "/login";
    return;
  }

  try {
    const res = await fetchWithAuth("/api/auth", {});

    const data = await res.json();

    if (data.status === "error") {
      localStorage.removeItem("token");
      localStorage.removeItem("user");
      window.location.href = "/login";
    } else {
      document.getElementById("main-content").classList.remove("d-none");
      document.getElementById("loading").classList.add("d-none");

      // update localStorage user
      localStorage.setItem("user", JSON.stringify(data.user));
      localStorage.setItem("profile", JSON.stringify(data.profile));
      return {demo : false, user : data.user, token};
    }
  } catch (err) {
    console.error("Auth check gagal:", err.message);
    console.error("Stack:", err.stack);
    window.location.href = "/login";
  }
}