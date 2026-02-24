// mockSMSService.js
// This simulates sending SMS without any external API

const fs = require('fs');
const path = require('path');

// File to store SMS history
const logFile = path.join(__dirname, 'sms-log.json');

// Initialize log file if it doesn't exist
if (!fs.existsSync(logFile)) {
  fs.writeFileSync(logFile, JSON.stringify([]));
}

/**
 * Send a mock SMS (looks just like Twilio's response)
 * @param {string} to - recipient phone number
 * @param {string} message - SMS content
 * @returns {Promise<object>} Simulated Twilio response
 */
async function sendSMS(to, message) {
  try {
    // Log to console (visible in your terminal)
    console.log('\n' + '='.repeat(60));
    console.log('✅ MOCK SMS SENT SUCCESSFULLY');
    console.log('='.repeat(60));
    console.log(`📱 To: ${to}`);
    console.log(`📝 Message: ${message}`);
    console.log(`⏱️  Time: ${new Date().toLocaleString()}`);
    console.log('='.repeat(60) + '\n');
    
    // Create a response that looks like Twilio's format
    const mockResponse = {
      sid: `SM${Date.now()}${Math.floor(Math.random() * 1000)}`,
      status: 'delivered',
      to: to,
      from: '+19134210714', // Your Twilio number (for show)
      body: message,
      dateCreated: new Date().toISOString(),
      dateSent: new Date().toISOString(),
      price: '-0.0075',
      priceUnit: 'USD',
      direction: 'outbound-api'
    };
    
    // Save to log file for later viewing
    const smsLog = JSON.parse(fs.readFileSync(logFile));
    smsLog.push({
      ...mockResponse,
      timestamp: new Date().toISOString()
    });
    fs.writeFileSync(logFile, JSON.stringify(smsLog, null, 2));
    
    return mockResponse;
  } catch (error) {
    console.error('Mock SMS error:', error);
    throw error;
  }
}

/**
 * Get all sent SMS messages (for demo purposes)
 * @returns {Array} List of sent messages
 */
function getSMSLog() {
  return JSON.parse(fs.readFileSync(logFile));
}

/**
 * Clear the SMS log (optional)
 */
function clearSMSLog() {
  fs.writeFileSync(logFile, JSON.stringify([]));
  console.log('📋 SMS log cleared');
}

module.exports = { sendSMS, getSMSLog, clearSMSLog };