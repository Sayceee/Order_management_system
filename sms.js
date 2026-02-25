const express = require('express');
const router = express.Router();
const { sendSMS } = require('../services/smsService');

// POST /api/sms/send
router.post('/send', async (req, res) => {
  const { to, message } = req.body;

  if (!to || !message) {
    return res.status(400).json({ error: 'Missing to or message' });
  }

  try {
    const result = await sendSMS(to, message);
    res.json({ success: true, sid: result.sid });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

module.exports = router;