<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verifikasi OTP - Bloom Deluxe</title>
<link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --primary: #ff63fcff;
    --primary-dark: #ff4ff3ff;
    --primary-light: #ff00fbff;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    min-height: 100vh;
    background: linear-gradient(135deg, #fff 0%, #fff 50%, #fff 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.container {
    max-width: 500px;
    width: 100%;
    background: white;
    border-radius: 30px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    padding: 60px 40px;
    text-align: center;
}

.icon {
    width: 100px;
    height: 100px;
    margin: 0 auto 30px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 50px;
    color: white;
}

h1 {
    font-size: 32px;
    color: #333;
    margin-bottom: 15px;
}

p {
    color: #666;
    font-size: 16px;
    line-height: 1.6;
    margin-bottom: 30px;
}

.otp-inputs {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-bottom: 30px;
}

.otp-input {
    width: 60px;
    height: 60px;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    font-size: 24px;
    text-align: center;
    font-weight: 600;
    outline: none;
    transition: all 0.3s ease;
}

.otp-input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(247, 0, 255, 0.1);
}

.alert {
    padding: 12px 16px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-size: 13px;
    display: none;
}

.alert.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert.show { display: block; }

.btn {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    border: none;
    border-radius: 12px;
    color: white;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 8px 20px rgba(206, 15, 216, 0.4);
    margin-bottom: 15px;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(255, 0, 200, 0.5);
}

.btn.loading {
    opacity: 0.7;
    pointer-events: none;
}

.resend-text {
    color: #666;
    font-size: 14px;
    margin-top: 20px;
}

.resend-btn {
    background: none;
    border: none;
    color: var(--primary);
    font-weight: 600;
    cursor: pointer;
    text-decoration: underline;
}

.resend-btn:hover {
    color: var(--primary-dark);
}

.resend-btn:disabled {
    color: #999;
    cursor: not-allowed;
    text-decoration: none;
}

.timer {
    font-weight: 600;
    color: var(--primary);
}
</style>
</head>
<body>
<div class="container">
    <div class="icon">
        <i class='bx bx-mobile'></i>
    </div>
    <h1>Verifikasi OTP</h1>
    <p>Masukkan kode 6 digit yang telah dikirim ke nomor HP Anda</p>
    
    <div id="alertBox" class="alert"></div>
    
    <div class="otp-inputs">
        <input type="text" maxlength="1" class="otp-input" id="otp1" />
        <input type="text" maxlength="1" class="otp-input" id="otp2" />
        <input type="text" maxlength="1" class="otp-input" id="otp3" />
        <input type="text" maxlength="1" class="otp-input" id="otp4" />
        <input type="text" maxlength="1" class="otp-input" id="otp5" />
        <input type="text" maxlength="1" class="otp-input" id="otp6" />
    </div>
    
    <button class="btn" id="verifyBtn" onclick="verifyOTP()">Verifikasi</button>
    
    <div class="resend-text">
        Tidak menerima kode? 
        <button class="resend-btn" id="resendBtn" onclick="resendOTP()">
            Kirim Ulang <span class="timer" id="timer"></span>
        </button>
    </div>
</div>

<script>
const API_URL = 'http://localhost:3306/api';
const urlParams = new URLSearchParams(window.location.search);
const userId = urlParams.get('userId');

// Auto focus dan auto move ke input berikutnya
const inputs = document.querySelectorAll('.otp-input');
inputs.forEach((input, index) => {
    input.addEventListener('input', (e) => {
        if (e.target.value.length === 1 && index < inputs.length - 1) {
            inputs[index + 1].focus();
        }
    });
    
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !e.target.value && index > 0) {
            inputs[index - 1].focus();
        }
    });
});

// Auto focus input pertama
inputs[0].focus();

function showAlert(message, type) {
    const alertBox = document.getElementById('alertBox');
    alertBox.textContent = message;
    alertBox.className = `alert ${type} show`;
    setTimeout(() => {
        alertBox.classList.remove('show');
    }, 5000);
}

async function verifyOTP() {
    // Ambil nilai OTP
    let otp = '';
    inputs.forEach(input => {
        otp += input.value;
    });
    
    if (otp.length !== 6) {
        showAlert('Masukkan kode OTP lengkap!', 'error');
        return;
    }
    
    const btn = document.getElementById('verifyBtn');
    btn.classList.add('loading');
    btn.textContent = 'Memverifikasi...';
    
    try {
        const response = await fetch(`${API_URL}/auth/verify-otp`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ userId, otp })
        });
        
        const data = await response.json();
        
        if (response.ok && data.verified) {
            showAlert('Verifikasi berhasil!', 'success');
            
            // Simpan token
            localStorage.setItem('token', data.token);
            localStorage.setItem('user', JSON.stringify(data.user));
            
            // Redirect ke dashboard
            setTimeout(() => {
                window.location.href = 'dashboard.html';
            }, 1500);
        } else {
            showAlert(data.message || 'Kode OTP salah', 'error');
        }
    } catch (error) {
        showAlert('Terjadi kesalahan koneksi', 'error');
    } finally {
        btn.classList.remove('loading');
        btn.textContent = 'Verifikasi';
    }
}

// Timer resend OTP
let countdown = 60;
const resendBtn = document.getElementById('resendBtn');
const timerSpan = document.getElementById('timer');

function startTimer() {
    resendBtn.disabled = true;
    countdown = 60;
    
    const interval = setInterval(() => {
        countdown--;
        timerSpan.textContent = `(${countdown}s)`;
        
        if (countdown <= 0) {
            clearInterval(interval);
            resendBtn.disabled = false;
            timerSpan.textContent = '';
        }
    }, 1000);
}

async function resendOTP() {
    try {
        const response = await fetch(`${API_URL}/auth/resend-otp`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ userId })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showAlert('Kode OTP baru telah dikirim!', 'success');
            startTimer();
            
            // Clear input
            inputs.forEach(input => input.value = '');
            inputs[0].focus();
        } else {
            showAlert(data.message, 'error');
        }
    } catch (error) {
        showAlert('Terjadi kesalahan koneksi', 'error');
    }
}

// Start timer saat halaman load
startTimer();
</script>
</body>
</html>