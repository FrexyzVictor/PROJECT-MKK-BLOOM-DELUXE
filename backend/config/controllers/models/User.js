const db = require('../config/db');

class User {
    // Buat user baru
    static async create(userData) {
        const { name, email, phone, password } = userData;
        const query = `
            INSERT INTO users (name, email, phone, password, email_verified, phone_verified, created_at)
            VALUES (?, ?, ?, ?, false, false, NOW())
        `;
        const [result] = await db.execute(query, [name, email, phone, password]);
        return result.insertId;
    }
    
    // Cari user berdasarkan email
    static async findByEmail(email) {
        const query = 'SELECT * FROM users WHERE email = ?';
        const [rows] = await db.execute(query, [email]);
        return rows[0];
    }
    
    // Cari user berdasarkan nomor HP
    static async findByPhone(phone) {
        const query = 'SELECT * FROM users WHERE phone = ?';
        const [rows] = await db.execute(query, [phone]);
        return rows[0];
    }
    
    // Cari user berdasarkan ID
    static async findById(id) {
        const query = 'SELECT * FROM users WHERE id = ?';
        const [rows] = await db.execute(query, [id]);
        return rows[0];
    }
    
    // Verifikasi email user
    static async verifyEmail(userId) {
        const query = 'UPDATE users SET email_verified = true WHERE id = ?';
        await db.execute(query, [userId]);
    }
    
    // Verifikasi nomor HP user
    static async verifyPhone(userId) {
        const query = 'UPDATE users SET phone_verified = true WHERE id = ?';
        await db.execute(query, [userId]);
    }
    
    // Simpan token verifikasi
    static async saveVerificationToken(userId, token, type, expiresAt) {
        const query = `
            INSERT INTO verification_tokens (user_id, token, type, expires_at, created_at)
            VALUES (?, ?, ?, ?, NOW())
        `;
        await db.execute(query, [userId, token, type, expiresAt]);
    }
    
    // Cari token verifikasi
    static async findVerificationToken(token, type) {
        const query = `
            SELECT vt.*, u.id as user_id, u.email, u.phone, u.name
            FROM verification_tokens vt
            JOIN users u ON vt.user_id = u.id
            WHERE vt.token = ? AND vt.type = ? AND vt.expires_at > NOW() AND vt.used = false
        `;
        const [rows] = await db.execute(query, [token, type]);
        return rows[0];
    }
    
    // Tandai token sudah digunakan
    static async markTokenAsUsed(token) {
        const query = 'UPDATE verification_tokens SET used = true WHERE token = ?';
        await db.execute(query, [token]);
    }
}

module.exports = User;