document.getElementById("logoutForm").addEventListener("submit", async (e) => {
    e.preventDefault();
    localStorage.removeItem("token");
    window.location.href = "/login";
});