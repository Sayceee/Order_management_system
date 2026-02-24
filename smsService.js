const twilio = require('twilio');
require('dotenv').config();

const accountSid = process.env.TWILIO_ACCOUNT_SID;
const authToken = process.env.TWILIO_AUTH_TOKEN;
const client = twilio(accountSid, authToken);

/**
 * Send an SMS
 * @param {string} to - recipient phone number (E.164 format, e.g., +254712345678)
 * @param {string} body - message content
 * @returns {Promise<object>} Twilio message object
 */
async function sendSMS(to, body) {
  try {
    const message = await client.messages.create({
      body: body,
      from: process.env.TWILIO_PHONE_NUMBER,
      to: to
    });
    console.log(`SMS sent: ${message.sid}`);
    return message;
  } catch (error) {
    console.error('Twilio error:', error);
    throw error; // rethrow so caller can handle
  }
}

module.exports = { sendSMS };