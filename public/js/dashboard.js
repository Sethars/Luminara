//ambil data
const data = localStorage.getItem('user');
const user = JSON.parse(data);

//ambil username
document.getElementById('username').textContent = user ? user.username : 'Demo';
