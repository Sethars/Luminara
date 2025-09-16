const passwordInput = document.getElementById("password");
const togglePassword = document.getElementById("togglePassword");

togglePassword.addEventListener("click", function () {
  const type =
    passwordInput.getAttribute("type") === "password" ? "text" : "password";
  passwordInput.setAttribute("type", type);

  // ganti ikon
  this.innerHTML =
    type === "password"
      ? '<i class="bi bi-eye"></i>'
      : '<i class="bi bi-eye-slash"></i>';
});

//Login Btn
document
  .getElementById("loginForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();
    const formData = {
      email: document.getElementById("email").value,
      password: document.getElementById("password").value,
    };

    try {
      const res = await fetch("/api/login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(formData),
      });
      const data = await res.json();

      if (data.success) {
        localStorage.setItem("token", data.token);
        showModal("loginSuccess");
      } else {
        showModal("loginFailed");
        document.getElementById("warningText").textContent = data.message;
      }
    } catch (err) {
      console.error("Error:", err);
    }
  });

//Demo Btn
document.getElementById("demoLoginBtn").addEventListener("click", function (e) {
  e.preventDefault();

  localStorage.setItem("demo", JSON.stringify(true));

  const tokenDemo = generateRandomString(32);
  localStorage.setItem("token", tokenDemo);

  const now = new Date();
  const expired = now.getTime() + 2 * 60 * 60 * 1000; //2 jam
  localStorage.setItem("expired", JSON.stringify(expired));

  showModal("loginSuccess");
});
