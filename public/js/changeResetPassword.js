import { showModal } from "../module_js/show_modal.js";
import { getQueryParam } from "../module_js/get_query.js";

const identifier = getQueryParam("identifier");
const token = getQueryParam("token");

console.log("Identifier:", identifier);
console.log("Token:", token);

//Check token
document.addEventListener("DOMContentLoaded", async () => {
    if (!token) {
        document.getElementById("warningMessage").textContent = "Token tidak valid.";
        showModal("warningModal");
        return;
    } else {
        try {
            const res = await fetch(`/api/verifyToken`, 
            {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ token: token, identifier: identifier }),
            }
            );
            const result = await res.json();
            if (!result.valid) {
                document.getElementById("warningMessage").textContent = result.message;
                showModal("warningModal");
            }
        } catch (error) {
            console.error("Error:", error);
            document.getElementById("warningMessage").textContent = "Terjadi kesalahan saat memverifikasi token.";
            showModal("warningModal");
        }
    }
});

document.getElementById("changePasswordForm").addEventListener("submit", async (e) => {
    e.preventDefault();
    const newPassword = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    if (newPassword !== confirmPassword) {
        document.getElementById("warningMessage").textContent = "Password dan konfirmasi password tidak sesuai.";
        return;
    } else {
        document.getElementById("warningMessage").textContent = "";
    }

    try {
        const res = await fetch("/api/changeResetPassword", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ token: token, identifier: identifier,newPassword: newPassword, confirmPassword: confirmPassword }),
        });
        const result = await res.json();

        if (result.success) {
            alert("Password berhasil diubah! Silakan login dengan password baru Anda.");
            window.location.href = "/login";
        } else {
            document.getElementById("message").textContent = "Gagal mengubah password: " + result.error;
        }
    } catch (error) {
        console.error("Error:", error);
        document.getElementById("message").textContent = "Terjadi kesalahan saat mengubah password.";
    }
});