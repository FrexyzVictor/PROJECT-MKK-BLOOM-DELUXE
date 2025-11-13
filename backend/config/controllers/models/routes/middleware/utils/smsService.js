const twilio = require('twilio');
require('dotenv').config();

const generateOTP = () => {
    return Math.floor(100000 + Math.random() * 900000).toString();
};

const sendOTP = async (phone, otp) => {
    try {
        // Jika Anda belum setup Twilio, return dummy success
        console.log(`📱 OTP untuk ${phone}: ${otp}`);
        
        // Uncomment ini jika sudah setup Twilio
        /*
        const client = twilio(
            process.env.TWILIO_ACCOUNT_SID,
            process.env.TWILIO_AUTH_TOKEN
        );
        
        const message = await client.messages.create({
            body: `🌸 Bloom Deluxe - Kode OTP: ${otp}\n\nBerlaku 5 menit.`,
            from: process.env.TWILIO_PHONE_NUMBER,
            to: phone
        });
        
        return { success: true, messageId: message.sid };
        */
        
        return { success: true, otp }; // Dummy response
    } catch (error) {
        console.error('❌ SMS send error:', error);
        return { success: false, error: error.message };
    }
};

module.exports = { generateOTP, sendOTP };