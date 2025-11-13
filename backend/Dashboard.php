<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - Bloom Deluxe</title>
<link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: linear-gradient(135deg, #ff63fc 0%, #ff00fb 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.dashboard {
    background: white;
    border-radius: 30px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    padding: 60px 40px;
    text-align: center;
    max-width: 600px;
    width: 100%;
}

.welcome-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 30px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff63fc, #ff00fb);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 60px;
    color: white;
}

h1 {
    font-size: 36px;
    color: #333;
    margin-bottom: 15px;
}

.user-info {
    background: #f5f7fb;
    padding: 20px;
    border-radius: 15px;
    margin: 30px 0;
    text-align: left;
}

.user-info p {
    margin: 10px 0;
    color: #666;
}

.user-info strong {
    color: #333;
}

.logout-btn {
    padding: 15px 40px;
    background: linear-gradient(135deg, #ff63fc, #ff00fb);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.logout-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(255, 0, 200, 0.5);
}
</style>
</head>
<body>
<div class="dashboard">
    <div class="welcome-icon">
        <i class='bx bxs-dashboard'></i>
    </div>
    <h1>Selamat Datang! 🎉</h1>
    <p style="color: #666; margin-bottom: 30px;">Anda berhasil login ke Bloom Deluxe</p>
    
    <div class="user-info" id="userInfo">
        <p><strong>Loading...</strong></p>
    </div>
    
    <button class="logout-btn" onclick="logout()">
        <i class='bx bx-log-out'></i> Logout
    </button>
</div>

<script>
// Cek apakah user sudah login
const token = localStorage.getItem('token');
const user = JSON.parse(localStorage.getItem('user') || '{}');

if (!token) {
    // Jika belum login, redirect ke halaman login
    window.location.href = 'auth.html';
} else {
    // Tampilkan info user
    document.getElementById('userInfo').innerHTML = `
        <p><strong>Nama:</strong> ${user.name || '-'}</p>
        <p><strong>Email:</strong> ${user.email || '-'}</p>
        <p><strong>Nomor HP:</strong> ${user.phone || '-'}</p>
        <p><strong>User ID:</strong> ${user.id}</p>
    `;
}

function logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    window.location.href = 'auth.html';
}
</script>
</body>
</html>
```

---

## ✅ CHECKLIST FINAL - Pastikan Sudah Ada:
```
frontend/
├── auth.html ✅ (sudah di-update)
├── verify-otp.html ✅ (baru dibuat)
├── dashboard.html ✅ (baru dibuat)
└── bloom_logo.jpg

backend/
├── (semua file sudah ada)
└── server.js (running)