document.getElementById("logoutForm").addEventListener("submit", async (e) => {
    e.preventDefault();
    localStorage.clear();
    window.location.href = "/login";
});