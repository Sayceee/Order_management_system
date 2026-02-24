// Load environment variables from .env file
require('dotenv').config();

const express = require('express');
const { sendSMS, getSMSLog } = require('./mockSMSService');

const app = express();
const port = process.env.PORT || 3000;

// Middleware to parse JSON bodies
app.use(express.json());

// Health check endpoint (optional)
app.get('/', (req, res) => {
  res.send('SMS Service is running!');
});

// POST /api/sms/send – test endpoint to send an SMS
app.post('/api/sms/send', async (req, res) => {
  const { to, message } = req.body;

  // Basic validation
  if (!to || !message) {
    return res.status(400).json({ error: 'Missing "to" or "message" in request body' });
  }

  try {
    const result = await sendSMS(to, message);
    res.json({ success: true, sid: result.sid });
  } catch (error) {
    console.error('Error sending SMS:', error);
    res.status(500).json({ error: error.message });
  }
  
app.get('/api/sms/log', (req, res) => {
  const log = getSMSLog();
  res.json({ 
    success: true, 
    count: log.length,
    messages: log 
  });
});
});

// Start the server
app.listen(port, () => {
  console.log(`Server listening at http://localhost:${port}`);
});