document.getElementById('resetPasswordForm').addEventListener('submit', async (event) => {
    event.preventDefault(); // Prevent the default form submission

    const email = document.getElementById('email').value;
    setLoading(true, 'sendEmail');

    try {
        const res = await fetch('/api/resetPassword', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email: email })
        });
        const result = await res.json();
        if (result.success) {
            document.getElementById('messageSuccess').textContent = 'Email reset password telah dikirim.';
            setLoading(false, 'sendEmail');
        } else {
            document.getElementById('messageFailed').textContent = 'Gagal mengirim email';
            console.error(result.error)
            setLoading(false, 'sendEmail');
        }
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('message').textContent = 'Terjadi kesalahan saat mengirim email.';
        setLoading(false, 'sendEmail');
    }
});