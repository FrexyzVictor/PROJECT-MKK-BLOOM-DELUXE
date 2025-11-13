<?php
session_start();
include 'Koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auth - Bloom Deluxe</title>
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #ff63fcff;
            --primary-dark: #ff4ff3ff;
            --primary-light: #ff00fbff;
            --light: #fff;
            --grey: #F5F7FB;
            --dark: #333;
            --shadow: rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #ffffffff 0%, #ffffffff 50%, #ffffffff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15), transparent);
            border-radius: 50%;
            top: -100px;
            right: -100px;
            animation: float 15s ease-in-out infinite;
        }

        body::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1), transparent);
            border-radius: 50%;
            bottom: -80px;
            left: -80px;
            animation: float 12s ease-in-out infinite reverse;
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg);
            }

            33% {
                transform: translate(30px, -30px) rotate(120deg);
            }

            66% {
                transform: translate(-20px, 20px) rotate(240deg);
            }
        }

        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: rise 10s infinite ease-in;
        }

        .particle:nth-child(1) {
            width: 10px;
            height: 10px;
            left: 10%;
            animation-delay: 0s;
        }

        .particle:nth-child(2) {
            width: 15px;
            height: 15px;
            left: 20%;
            animation-delay: 2s;
        }

        .particle:nth-child(3) {
            width: 8px;
            height: 8px;
            left: 30%;
            animation-delay: 4s;
        }

        .particle:nth-child(4) {
            width: 12px;
            height: 12px;
            left: 50%;
            animation-delay: 1s;
        }

        .particle:nth-child(5) {
            width: 10px;
            height: 10px;
            left: 70%;
            animation-delay: 3s;
        }

        .particle:nth-child(6) {
            width: 14px;
            height: 14px;
            left: 80%;
            animation-delay: 5s;
        }

        .particle:nth-child(7) {
            width: 9px;
            height: 9px;
            left: 90%;
            animation-delay: 2.5s;
        }

        @keyframes rise {
            0% {
                bottom: -50px;
                opacity: 0;
                transform: translateX(0);
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                bottom: 110vh;
                opacity: 0;
                transform: translateX(100px);
            }
        }

        /* GAMBAR MASCOT */
        .mascot-container {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .mascot-image {
            width: 120px;
            height: 120px;
            object-fit: contain;
            cursor: pointer;
            filter: drop-shadow(0 10px 25px rgba(255, 0, 255, 0.3));
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            animation: bounce 2s ease-in-out infinite;
        }

        .mascot-image:hover {
            transform: scale(1.15) rotate(5deg);
            filter: drop-shadow(0 15px 35px rgba(255, 0, 255, 0.5));
        }

        .mascot-image:active {
            transform: scale(0.95);
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        /* DIALOG SELAMAT DATANG */
        .welcome-dialog {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            background: white;
            padding: 40px 50px;
            border-radius: 25px;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.3);
            z-index: 2000;
            text-align: center;
            max-width: 450px;
            width: 90%;
            opacity: 0;
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .welcome-dialog.show {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }

        .welcome-dialog-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .welcome-dialog-overlay.show {
            opacity: 1;
            pointer-events: all;
        }

        .welcome-dialog-icon {
            font-size: 80px;
            color: var(--primary);
            margin-bottom: 20px;
            animation: scaleIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .welcome-dialog h2 {
            font-size: 32px;
            color: var(--dark);
            margin-bottom: 15px;
            font-weight: 700;
        }

        .welcome-dialog p {
            font-size: 16px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .welcome-dialog-btn {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            border: none;
            padding: 14px 40px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(255, 0, 255, 0.3);
        }

        .welcome-dialog-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(255, 0, 255, 0.4);
        }

        @keyframes scaleIn {
            0% {
                transform: scale(0) rotate(-180deg);
            }

            100% {
                transform: scale(1) rotate(0deg);
            }
        }

        .container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 900px;
            background: white;
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            display: flex;
            min-height: 550px;
            transition: min-height 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .container.register-mode {
            min-height: 650px;
        }

        .left-side {
            flex: 1;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
            color: white;
        }

        .left-side::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1), transparent);
            border-radius: 50%;
            top: -100px;
            right: -100px;
        }

        .left-side::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08), transparent);
            border-radius: 50%;
            bottom: -50px;
            left: -50px;
        }

        .left-side .logo {
            width: 120px;
            height: 120px;
            margin-bottom: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 2;
            object-fit: cover;
        }

        .left-side h1 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 15px;
            position: relative;
            z-index: 2;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.5s ease;
        }

        .left-side p {
            font-size: 16px;
            text-align: center;
            opacity: 0.95;
            line-height: 1.6;
            position: relative;
            z-index: 2;
            max-width: 300px;
            transition: all 0.5s ease;
        }

        .left-side .features {
            margin-top: 40px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            position: relative;
            z-index: 2;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .feature-item i {
            font-size: 24px;
            color: rgba(255, 255, 255, 0.9);
        }

        .feature-item span {
            font-size: 14px;
            opacity: 0.9;
        }

        .right-side {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            display: none;
            animation: slideDown 0.3s ease;
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

        .alert.show {
            display: block;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-wrapper {
            position: relative;
            width: 100%;
            overflow: hidden;
        }

        .form-container {
            display: flex;
            width: 200%;
            transition: transform 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .form-container.show-register {
            transform: translateX(-50%);
        }

        .form-section {
            width: 50%;
            padding: 0 5px;
        }

        .form-header {
            margin-bottom: 35px;
        }

        .form-header h2 {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .form-header p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            position: relative;
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i.left-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 18px;
            transition: all 0.3s ease;
            z-index: 1;
            pointer-events: none;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 15px 50px 15px 50px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 15px;
            outline: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #fafafa;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(247, 0, 255, 0.1);
        }

        .form-group input:focus~i.left-icon,
        .form-group select:focus~i.left-icon {
            color: var(--primary);
        }

        .toggle-password {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            font-size: 18px;
            transition: all 0.3s ease;
            z-index: 2;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-password:hover {
            color: var(--primary);
        }

        .phone-group {
            display: flex;
            gap: 10px;
        }

        .phone-group select {
            width: 110px;
            padding-left: 18px;
            padding-right: 10px;
        }

        .phone-group .input-wrapper {
            flex: 1;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .forgot-password {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .forgot-password:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 20px rgba(206, 15, 216, 0.63);
            position: relative;
            overflow: hidden;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(255, 0, 200, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-submit.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border: 3px solid white;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
        }

        @keyframes spin {
            to {
                transform: translateY(-50%) rotate(360deg);
            }
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: #999;
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e0e0e0;
        }

        .divider::before {
            margin-right: 15px;
        }

        .divider::after {
            margin-left: 15px;
        }

        .social-login {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .social-btn {
            flex: 1;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
        }

        .social-btn i {
            font-size: 20px;
        }

        .social-btn.google {
            color: #18c40cff;
        }

        .social-btn.phone {
            color: #1c1d1eff;
        }

        .social-btn:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .toggle-form {
            text-align: center;
            font-size: 14px;
            color: #666;
        }

        .toggle-form button {
            background: none;
            border: none;
            color: var(--primary);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 0;
            height: 50px;
        }

        .toggle-form button:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                max-width: 450px;
                min-height: auto;
            }

            .container.register-mode {
                min-height: auto;
            }

            .left-side {
                padding: 40px 30px;
            }

            .left-side h1 {
                font-size: 28px;
            }

            .left-side .features {
                display: none;
            }

            .right-side {
                padding: 40px 30px;
            }

            .form-header h2 {
                font-size: 26px;
            }

            .mascot-container {
                bottom: 20px;
                right: 20px;
            }

            .mascot-image {
                width: 90px;
                height: 90px;
            }

            .welcome-dialog {
                padding: 30px 25px;
            }
        }

        @media (max-width: 480px) {
            .container {
                margin: 10px;
            }

            .right-side {
                padding: 30px 20px;
            }

            .social-login {
                flex-direction: column;
            }

            .phone-group {
                flex-direction: column;
            }

            .phone-group select {
                width: 100%;
            }

            .mascot-image {
                width: 70px;
                height: 70px;
            }
        }
    </style>
</head>

<body>

    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <!-- DIALOG OVERLAY -->
    <div class="welcome-dialog-overlay" id="dialogOverlay" onclick="closeWelcomeDialog()"></div>

    <!-- DIALOG SELAMAT DATANG -->
    <div class="welcome-dialog" id="welcomeDialog">
        <div class="welcome-dialog-icon">
            <i class='bx bxs-party'></i>
        </div>
        <h2>Selamat Datang! 🎉</h2>
        <p>Terima kasih telah mengunjungi Bloom Deluxe. Kami siap membantu Anda mengelola bisnis dengan lebih mudah dan efisien!</p>
        <button class="welcome-dialog-btn" onclick="closeWelcomeDialog()">Mulai Sekarang</button>
    </div>

    <!-- MASCOT GAMBAR -->
    <div class="mascot-container">
        <img src="KitzuneANjay.svg" alt="Mascot Bloom Deluxe" class="mascot-image" onclick="showWelcomeDialog()">
    </div>

    <div class="container" id="mainContainer">
        <div class="left-side">
            <img src="bloom_logo.jpg" alt="Logo" class="logo">
            <h1 id="leftTitle">Bloom Deluxe</h1>
            <p id="leftDesc">Selamat datang kembali! Masuk untuk mengakses Dashboard.</p>

            <div class="features">
                <div class="feature-item">
                    <i class='bx bxs-dashboard'></i>
                    <span>Dashboard Modern</span>
                </div>
                <div class="feature-item">
                    <i class='bx bxs-lock-alt'></i>
                    <span>Keamanan Terjamin</span>
                </div>
                <div class="feature-item">
                    <i class='bx bxs-zap'></i>
                    <span>Performa Cepat</span>
                </div>
            </div>
        </div>

        <div class="right-side">
            <div id="alertBox" class="alert"></div>

            <div class="form-wrapper">
                <div class="form-container" id="formContainer">
                    <!-- LOGIN FORM -->
                    <div class="form-section">
                        <div class="form-header">
                            <h2>Login</h2>
                            <p>Masukkan Data Diri tuk melanjutkan</p>
                        </div>

                        <form id="loginForm">
                            <div class="form-group">
                                <label>Email atau Nomor HP</label>
                                <div class="input-wrapper">
                                    <input type="text" id="loginIdentifier" placeholder="contoh@email.com atau 08123456789" required>
                                    <i class='bx bxs-user left-icon'></i>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <div class="input-wrapper">
                                    <input type="password" id="loginPassword" placeholder="Masukkan password" required>
                                    <i class='bx bxs-lock-alt left-icon'></i>
                                    <span class="toggle-password" onclick="togglePassword('loginPassword', 'loginToggle')">
                                        <i class='bx bx-hide' id="loginToggle"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="form-options">
                                <label class="remember-me">
                                    <input type="checkbox" name="remember">
                                    <span>Ingat Saya</span>
                                </label>
                                <a href="#" class="forgot-password">Lupa Password?</a>
                            </div>

                            <button type="submit" class="btn-submit">Masuk</button>

                            <div class="divider">atau masuk dengan</div>

                            <div class="social-login">
                                <button type="button" class="social-btn google" onclick="socialLogin('google')">
                                    <i class='bx bxl-google'></i>
                                    Google
                                </button>
                                <button type="button" class="social-btn phone" onclick="socialLogin('phone')">
                                    <i class='bx bx-phone'></i>
                                    Phone
                                </button>
                            </div>

                            <div class="toggle-form">
                                Belum punya akun? <button type="button" onclick="toggleToRegister()">Daftar Sekarang</button>
                            </div>
                        </form>
                    </div>

                    <!-- REGISTER FORM -->
                    <div class="form-section">
                        <div class="form-header">
                            <h2>Register</h2>
                            <p>Buat akun baru untuk memulai</p>
                        </div>

                        <form id="registerForm">
                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <div class="input-wrapper">
                                    <input type="text" id="registerName" placeholder="Masukkan nama lengkap" required>
                                    <i class='bx bxs-user left-icon'></i>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <div class="input-wrapper">
                                    <input type="email" id="registerEmail" placeholder="contoh@email.com" required>
                                    <i class='bx bxs-envelope left-icon'></i>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Nomor HP</label>
                                <div class="phone-group">
                                    <select id="countryCode" required>
                                        <option value="+62">+62 (ID)</option>
                                        <option value="+60">+60 (MY)</option>
                                        <option value="+65">+65 (SG)</option>
                                        <option value="+1">+1 (US)</option>
                                        <option value="+44">+44 (UK)</option>
                                    </select>
                                    <div class="input-wrapper">
                                        <input type="tel" id="registerPhone" placeholder="8123456789" required>
                                        <i class='bx bxs-phone left-icon'></i>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <div class="input-wrapper">
                                    <input type="password" id="registerPassword" placeholder="Min. 8 karakter" required minlength="8">
                                    <i class='bx bxs-lock-alt left-icon'></i>
                                    <span class="toggle-password" onclick="togglePassword('registerPassword', 'registerToggle')">
                                        <i class='bx bx-hide' id="registerToggle"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Konfirmasi Password</label>
                                <div class="input-wrapper">
                                    <input type="password" id="confirmPassword" placeholder="Ulangi password" required>
                                    <i class='bx bxs-lock-alt left-icon'></i>
                                    <span class="toggle-password" onclick="togglePassword('confirmPassword', 'confirmToggle')">
                                        <i class='bx bx-hide' id="confirmToggle"></i>
                                    </span>
                                </div>
                            </div>

                            <button type="submit" class="btn-submit">Daftar Sekarang</button>

                            <div class="toggle-form">
                                Sudah punya akun? <button type="button" onclick="toggleToLogin()">Masuk Sekarang</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        // ========== TARUH DI BAGIAN BAWAH auth.html (ganti script yang lama) ==========

        const API_URL = 'http://localhost:3306/api';

        function toggleToRegister() {
            document.getElementById('formContainer').classList.add('show-register');
            document.getElementById('mainContainer').classList.add('register-mode');
            document.getElementById('leftTitle').textContent = 'Bergabung Bersama Kami';
            document.getElementById('leftDesc').textContent = 'Daftar sekarang dan nikmati semua fitur premium Bloom Deluxe!';
            hideAlert();
        }

        function toggleToLogin() {
            document.getElementById('formContainer').classList.remove('show-register');
            document.getElementById('mainContainer').classList.remove('register-mode');
            document.getElementById('leftTitle').textContent = 'Bloom Deluxe';
            document.getElementById('leftDesc').textContent = 'Selamat datang kembali! Masuk untuk mengakses dashboard admin Anda.';
            hideAlert();
        }

        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            } else {
                input.type = 'password';
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            }
        }

        function showAlert(message, type) {
            const alertBox = document.getElementById('alertBox');
            alertBox.textContent = message;
            alertBox.className = `alert ${type} show`;
            setTimeout(() => hideAlert(), 5000);
        }

        function hideAlert() {
            const alertBox = document.getElementById('alertBox');
            alertBox.classList.remove('show');
        }

        function socialLogin(provider) {
            showAlert(`Login dengan ${provider} akan segera tersedia!`, 'success');
        }

        // ==================== LOGIN FORM ====================
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = e.target.querySelector('.btn-submit');
            btn.classList.add('loading');
            btn.textContent = 'Memproses...';

            const identifier = document.getElementById('loginIdentifier').value;
            const password = document.getElementById('loginPassword').value;

            try {
                const response = await fetch(`${API_URL}/auth/login`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        identifier,
                        password
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Login berhasil
                    showAlert(data.message || 'Login berhasil!', 'success');

                    // Simpan token
                    localStorage.setItem('token', data.token);
                    localStorage.setItem('user', JSON.stringify(data.user));

                    // Redirect ke dashboard setelah 1.5 detik
                    setTimeout(() => {
                        window.location.href = 'dashboard.html';
                    }, 1500);

                } else if (data.needsVerification) {
                    // Perlu verifikasi
                    showAlert(data.message, 'error');

                    if (data.verificationType === 'phone') {
                        // Redirect ke halaman OTP
                        setTimeout(() => {
                            window.location.href = `verify-otp.html?userId=${data.userId}`;
                        }, 2000);
                    }

                } else {
                    showAlert(data.message || 'Login gagal!', 'error');
                }
            } catch (error) {
                console.error('Login error:', error);
                showAlert('Terjadi kesalahan koneksi', 'error');
            } finally {
                btn.classList.remove('loading');
                btn.textContent = 'Masuk';
            }
        });

        // ==================== REGISTER FORM ====================
        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = e.target.querySelector('.btn-submit');

            const password = document.getElementById('registerPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            // Validasi password
            if (password !== confirmPassword) {
                showAlert('Password tidak cocok!', 'error');
                return;
            }

            if (password.length < 8) {
                showAlert('Password minimal 8 karakter!', 'error');
                return;
            }

            btn.classList.add('loading');
            btn.textContent = 'Mendaftar...';

            const name = document.getElementById('registerName').value;
            const email = document.getElementById('registerEmail').value;
            const countryCode = document.getElementById('countryCode').value;
            const phone = document.getElementById('registerPhone').value;
            const fullPhone = countryCode + phone;

            try {
                const response = await fetch(`${API_URL}/auth/register`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        name,
                        email: email || null,
                        phone: fullPhone || null,
                        password
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showAlert(data.message, 'success');

                    if (data.verificationType === 'email') {
                        // Registrasi dengan email - user harus cek email
                        setTimeout(() => {
                            alert('Silakan cek inbox email Anda untuk verifikasi!');
                            toggleToLogin();
                        }, 2000);

                    } else if (data.verificationType === 'phone') {
                        // Registrasi dengan phone - redirect ke halaman OTP
                        setTimeout(() => {
                            window.location.href = `verify-otp.html?userId=${data.userId}`;
                        }, 1500);
                    }

                } else {
                    showAlert(data.message || 'Registrasi gagal!', 'error');
                }
            } catch (error) {
                console.error('Register error:', error);
                showAlert('Terjadi kesalahan koneksi', 'error');
            } finally {
                btn.classList.remove('loading');
                btn.textContent = 'Daftar Sekarang';
            }
        });

        // Input Animation
        const inputs = document.querySelectorAll('.form-group input, .form-group select');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.01)';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>

</html>