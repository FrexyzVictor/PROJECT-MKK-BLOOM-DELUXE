const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const crypto = require('crypto');
const User = require('../models/User');
const { sendVerificationEmail } = require('../utils/emailService');
const { generateOTP, sendOTP } = require('../utils/smsService');

// ==================== REGISTER ====================
exports.register = async (req, res) => {
    try {
        const { name, email, phone, password } = req.body;
        
        // Validasi input
        if (!name || !password || (!email && !phone)) {
            return res.status(400).json({ 
                message: 'Nama, password, dan email atau nomor HP harus diisi' 
            });
        }
        
        // Cek apakah user sudah ada
        if (email) {
            const existingEmail = await User.findByEmail(email);
            if (existingEmail) {
                return res.status(400).json({ message: 'Email sudah terdaftar' });
            }
        }
        
        if (phone) {
            const existingPhone = await User.findByPhone(phone);
            if (existingPhone) {
                return res.status(400).json({ message: 'Nomor HP sudah terdaftar' });
            }
        }
        
        // Hash password
        const hashedPassword = await bcrypt.hash(password, 10);
        
        // Buat user baru
        const userId = await User.create({
            name,
            email: email || null,
            phone: phone || null,
            password: hashedPassword
        });
        
        // Kirim verifikasi email
        if (email) {
            const emailToken = crypto.randomBytes(32).toString('hex');
            const expiresAt = new Date(Date.now() + 24 * 60 * 60 * 1000); // 24 jam
            
            await User.saveVerificationToken(userId, emailToken, 'email', expiresAt);
            await sendVerificationEmail(email, emailToken, name);
        }
        
        // Kirim OTP ke nomor HP
        if (phone) {
            const otp = generateOTP();
            const expiresAt = new Date(Date.now() + 5 * 60 * 1000); // 5 menit
            
            await User.saveVerificationToken(userId, otp, 'phone', expiresAt);
            const smsResult = await sendOTP(phone, otp);
            console.log('OTP Result:', smsResult);
        }
        
        res.status(201).json({
            success: true,
            message: email 
                ? 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi.'
                : 'Registrasi berhasil! Kode OTP telah dikirim ke nomor HP Anda.',
            userId,
            needsVerification: true,
            verificationType: email ? 'email' : 'phone'
        });
        
    } catch (error) {
        console.error('Register error:', error);
        res.status(500).json({ message: 'Terjadi kesalahan server' });
    }
};

// ==================== LOGIN ====================
exports.login = async (req, res) => {
    try {
        const { identifier, password } = req.body;
        
        // Cari user berdasarkan email atau phone
        let user;
        if (identifier.includes('@')) {
            user = await User.findByEmail(identifier);
        } else {
            user = await User.findByPhone(identifier);
        }
        
        if (!user) {
            return res.status(401).json({ message: 'Email/nomor HP atau password salah' });
        }
        
        // Cek password
        const validPassword = await bcrypt.compare(password, user.password);
        if (!validPassword) {
            return res.status(401).json({ message: 'Email/nomor HP atau password salah' });
        }
        
        // Cek apakah sudah terverifikasi
        if (user.email && !user.email_verified) {
            return res.status(403).json({ 
                message: 'Email belum diverifikasi. Silakan cek email Anda.',
                needsVerification: true,
                verificationType: 'email'
            });
        }
        
        if (user.phone && !user.phone_verified) {
            // Kirim OTP baru
            const otp = generateOTP();
            const expiresAt = new Date(Date.now() + 5 * 60 * 1000);
            
            await User.saveVerificationToken(user.id, otp, 'phone', expiresAt);
            await sendOTP(user.phone, otp);
            
            return res.status(403).json({ 
                message: 'Nomor HP belum diverifikasi. Kode OTP baru telah dikirim.',
                needsVerification: true,
                verificationType: 'phone',
                userId: user.id
            });
        }
        
        // Generate JWT token
        const token = jwt.sign(
            { userId: user.id, email: user.email, phone: user.phone },
            process.env.JWT_SECRET,
            { expiresIn: '7d' }
        );
        
        res.json({
            success: true,
            message: 'Login berhasil!',
            token,
            user: {
                id: user.id,
                name: user.name,
                email: user.email,
                phone: user.phone
            }
        });
        
    } catch (error) {
        console.error('Login error:', error);
        res.status(500).json({ message: 'Terjadi kesalahan server' });
    }
};

// ==================== VERIFY EMAIL ====================
exports.verifyEmail = async (req, res) => {
    try {
        const { token } = req.query;
        
        const verification = await User.findVerificationToken(token, 'email');
        
        if (!verification) {
            return res.status(400).json({ 
                message: 'Token tidak valid atau sudah kadaluarsa' 
            });
        }
        
        await User.verifyEmail(verification.user_id);
        await User.markTokenAsUsed(token);
        
        res.json({
            success: true,
            message: 'Email berhasil diverifikasi! Anda sekarang dapat login.',
            verified: true
        });
        
    } catch (error) {
        console.error('Verify email error:', error);
        res.status(500).json({ message: 'Terjadi kesalahan server' });
    }
};

// ==================== VERIFY OTP ====================
exports.verifyOTP = async (req, res) => {
    try {
        const { userId, otp } = req.body;
        
        const verification = await User.findVerificationToken(otp, 'phone');
        
        if (!verification || verification.user_id !== parseInt(userId)) {
            return res.status(400).json({ 
                message: 'Kode OTP tidak valid atau sudah kadaluarsa' 
            });
        }
        
        await User.verifyPhone(verification.user_id);
        await User.markTokenAsUsed(otp);
        
        // Generate JWT token setelah verifikasi
        const user = await User.findById(verification.user_id);
        const token = jwt.sign(
            { userId: user.id, email: user.email, phone: user.phone },
            process.env.JWT_SECRET,
            { expiresIn: '7d' }
        );
        
        res.json({
            success: true,
            message: 'Nomor HP berhasil diverifikasi!',
            verified: true,
            token,
            user: {
                id: user.id,
                name: user.name,
                email: user.email,
                phone: user.phone
            }
        });
        
    } catch (error) {
        console.error('Verify OTP error:', error);
        res.status(500).json({ message: 'Terjadi kesalahan server' });
    }
};

// ==================== RESEND OTP ====================
exports.resendOTP = async (req, res) => {
    try {
        const { userId } = req.body;
        
        const user = await User.findById(userId);
        if (!user || !user.phone) {
            return res.status(404).json({ message: 'User tidak ditemukan' });
        }
        
        const otp = generateOTP();
        const expiresAt = new Date(Date.now() + 5 * 60 * 1000);
        
        await User.saveVerificationToken(userId, otp, 'phone', expiresAt);
        await sendOTP(user.phone, otp);
        
        res.json({
            success: true,
            message: 'Kode OTP baru telah dikirim'
        });
        
    } catch (error) {
        console.error('Resend OTP error:', error);
        res.status(500).json({ message: 'Terjadi kesalahan server' });
    }
};

module.exports = exports;