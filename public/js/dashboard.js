//ambil data
const user = JSON.parse(localStorage.getItem('user'));

//ambil username
document.addEventListener('DOMContentLoaded', function(){
    document.getElementById('username').textContent = user ? user.username : 'Demo';
})
