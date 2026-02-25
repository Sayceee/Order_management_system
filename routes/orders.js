const express = require('express');
const router = express.Router();
const { sendSMS } = require('../mockSMSService');

// In-memory storage
let orders = [];
let orderCounter = 1;

// CREATE order
router.post('/', async (req, res) => {
  try {
    const { customerName, customerPhone, items } = req.body;
    
    // Calculate total
    let total = 0;
    items.forEach(item => {
      total += item.price * item.quantity;
    });
    
    // Create order
    const order = {
      id: orderCounter++,
      customerName,
      customerPhone,
      items,
      total,
      status: 'pending',
      createdAt: new Date()
    };
    
    orders.push(order);
    
    // Send SMS
    await sendSMS(
      customerPhone,
      `✅ Order #${order.id} confirmed for ${customerName}! Total: $${total}`
    );
    
    res.status(201).json({ success: true, order });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// GET all orders
router.get('/', (req, res) => {
  res.json({ success: true, orders });
});

// GET single order
router.get('/:id', (req, res) => {
  const order = orders.find(o => o.id === parseInt(req.params.id));
  if (!order) {
    return res.status(404).json({ error: 'Order not found' });
  }
  res.json({ success: true, order });
});

// UPDATE order status
router.put('/:id/status', async (req, res) => {
  const order = orders.find(o => o.id === parseInt(req.params.id));
  if (!order) {
    return res.status(404).json({ error: 'Order not found' });
  }
  
  order.status = req.body.status;
  order.updatedAt = new Date();
  
  await sendSMS(
    order.customerPhone,
    `🔄 Order #${order.id} status updated to: ${order.status}`
  );
  
  res.json({ success: true, order });
});

module.exports = router;
