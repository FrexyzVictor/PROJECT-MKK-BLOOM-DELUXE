const nodemailer = require('nodemailer');
require('dotenv').config();

const transporter = nodemailer.createTransport({
    host: process.env.EMAIL_HOST,
    port: process.env.EMAIL_PORT,
    secure: false,
    auth: {
        user: process.env.EMAIL_USER,
        pass: process.env.EMAIL_PASSWORD
    }
});

const sendVerificationEmail = async (email, token, name) => {
    const verificationUrl = `${process.env.FRONTEND_URL}/verify-email.html?token=${token}`;
    
    const mailOptions = {
        from: `"Bloom Deluxe" <${process.env.EMAIL_USER}>`,
        to: email,
        subject: '✨ Verifikasi Email Anda - Bloom Deluxe',
        html: `
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
                    .container { max-width: 600px; margin: 30px auto; background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
                    .header { background: linear-gradient(135deg, #ff63fc, #ff00fb); padding: 40px; text-align: center; color: white; }
                    .header h1 { margin: 0; font-size: 28px; }
                    .content { padding: 40px; }
                    .button { display: inline-block; padding: 15px 40px; background: linear-gradient(135deg, #ff63fc, #ff00fb); color: white; text-decoration: none; border-radius: 10px; font-weight: bold; margin: 20px 0; }
                    .footer { background: #f9f9f9; padding: 20px; text-align: center; color: #666; font-size: 12px; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h1>🌸Bloom Deluxe</h1>
                    </div>
                    <div class="content">
                        <h2>Halo, ${name}! 👋</h2>
                        <p>Terima kasih telah mendaftar di <strong>Bloom Deluxe</strong>!</p>
                        <p>Untuk mengaktifkan akun Anda, silakan klik tombol di bawah ini:</p>
                        <center>
                            <a href="${verificationUrl}" class="button">Verifikasi Email Saya</a>
                        </center>
                        <p>Atau salin link berikut ke browser Anda:</p>
                        <p style="background: #f5f5f5; padding: 15px; border-radius: 8px; word-break: break-all;">
                            ${verificationUrl}
                        </p>
                        <p><strong>Link ini akan kadaluarsa dalam 24 jam.</strong></p>
                    </div>
                    <div class="footer">
                        <p>© 2025 Bloom Deluxe. All rights reserved.</p>
                    </div>
                </div>
            </body>
            </html>
        `
    };
    
    try {
        await transporter.sendMail(mailOptions);
        console.log('✅ Email sent to:', email);
        return { success: true };
    } catch (error) {
        console.error('❌ Email send error:', error);
        return { success: false, error: error.message };
    }
};

module.exports = { sendVerificationEmail };