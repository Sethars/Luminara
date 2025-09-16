function togglePasswordVisibility(togglePassword, passwordInput) {
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
}

togglePasswordVisibility(
  document.getElementById("togglePassword"),
  document.getElementById("password")
);
togglePasswordVisibility(
  document.getElementById("toggleVerificationPassword"),
  document.getElementById("verificationPassword")
); 

document
  .getElementById("registerForm")
  .addEventListener("submit", async (e) => {
    e.preventDefault();

    const username =
      document.getElementById("firstName").value +
      " " +
      document.getElementById("lastName").value;
    const email = document.getElementById("email").value;

    if (
      document.getElementById("password").value ===
      document.getElementById("verificationPassword").value
    ) {
      document.getElementById("warningText").textContent = "";

      const formData = {
        username: username,
        email: email,
        password: document.getElementById("password").value,
      };
      localStorage.setItem("formData", JSON.stringify(formData));

      setLoading(true, 'registerBtn');
      
      try{
        const res = await fetch("/api/sendOTP", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(formData),
        });
        
        const result = await res.json();
        
        if (result.success) {
          // alert("OTP terkirim!");
          window.location.href = window.location.origin + "/verification";
        } else {
          document.getElementById("warningText").textContent = result.message;
          setLoading(false, 'registerBtn');
        }
      } catch (err) {
        console.error("Error: ", err);
        setLoading(false, 'registerBtn');
      }
    } else {
      document.getElementById("warningText").textContent =
      "Password tidak sama";
    }
  });
  
  