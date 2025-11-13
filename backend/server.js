const express = require('express');
const cors = require('cors');
const rateLimit = require('express-rate-limit');
require('dotenv').config();

const authRoutes = require('./routes/authRoutes');

const app = express();

// ==================== MIDDLEWARE ====================
app.use(cors({
    origin: process.env.FRONTEND_URL,
    credentials: true
}));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Rate limiting - maksimal 100 request per 15 menit
const limiter = rateLimit({
    windowMs: 15 * 60 * 1000,
    max: 100,
    message: 'Terlalu banyak request, coba lagi nanti'
});
app.use('/api/', limiter);

// ==================== ROUTES ====================
app.use('/api/auth', authRoutes);

// Health check
app.get('/', (req, res) => {
    res.json({
        status: 'OK',
        message: 'Bloom Deluxe API is running!'
    });
});

// ==================== ERROR HANDLER ====================
app.use((err, req, res, next) => {
    console.error(err.stack);
    res.status(500).json({ message: 'Terjadi kesalahan server' });
});

// ==================== START SERVER ====================
const PORT = process.env.PORT || 3306;
app.listen(PORT, () => {
    console.log('');
    console.log('🚀 ================================');
    console.log(`🚀 Server running on http://localhost:${PORT}`);
    console.log('🚀 ================================');
    console.log('');
});
```

---

## ✅ CHECKLIST - Pastikan Struktur Folder Seperti Ini:
```
// backend/
// ├── config/
// │   └── db.js ✅
// ├── controllers/
// │   └── authController.js ✅
// ├── models/
// │   └── User.js ✅
// ├── routes/
// │   └── authRoutes.js ✅
// ├── utils/
// │   ├── emailService.js ✅
// │   └── smsService.js ✅
// ├── node_modules/ ✅
// ├── .env ✅
// ├── server.js ✅
// ├── package.json ✅
// └── package-lock.json ✅