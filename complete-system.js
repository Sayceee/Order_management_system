const express = require('express');
const { sendSMS, getSMSLog } = require('./mockSMSService');

const app = express();
app.use(express.json());

// ========== SIMPLE ORDER SYSTEM ==========

// In-memory "database" (orders will disappear when server stops)
// But for demo, this is perfect!
let orders = [];
let orderCounter = 1;

// 1. CREATE ORDER endpoint
app.post('/api/orders', async (req, res) => {
  try {
    const { customerName, customerPhone, items } = req.body;
    
    // Calculate total
    let total = 0;
    const itemsWithTotal = items.map(item => {
      const itemTotal = item.price * item.quantity;
      total += itemTotal;
      return {
        name: item.name,
        quantity: item.quantity,
        price: item.price,
        total: itemTotal
      };
    });
    
    // Create order object
    const order = {
      id: orderCounter++,
      customerName,
      customerPhone,
      items: itemsWithTotal,
      total: total,
      status: 'pending',
      createdAt: new Date().toISOString()
    };
    
    // Save to "database"
    orders.push(order);
    
    // 🔥 SEND SMS CONFIRMATION 🔥
    await sendSMS(
      customerPhone,
      `✅ Hi ${customerName}! Order #${order.id} received for $${total}. We'll notify you when it's confirmed.`
    );
    
    res.status(201).json({
      success: true,
      message: 'Order created and SMS sent',
      order
    });
    
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// 2. GET ALL ORDERS
app.get('/api/orders', (req, res) => {
  res.json({
    success: true,
    count: orders.length,
    orders
  });
});

// 3. GET SINGLE ORDER
app.get('/api/orders/:id', (req, res) => {
  const order = orders.find(o => o.id === parseInt(req.params.id));
  
  if (!order) {
    return res.status(404).json({ error: 'Order not found' });
  }
  
  res.json({ success: true, order });
});

// 4. UPDATE ORDER STATUS (confirm, ship, deliver)
app.put('/api/orders/:id/status', async (req, res) => {
  try {
    const { status } = req.body;
    const order = orders.find(o => o.id === parseInt(req.params.id));
    
    if (!order) {
      return res.status(404).json({ error: 'Order not found' });
    }
    
    // Update status
    order.status = status;
    order.updatedAt = new Date().toISOString();
    
    // Send different SMS based on status
    let message = '';
    switch(status) {
      case 'confirmed':
        message = `👍 Order #${order.id} confirmed! We're preparing your items.`;
        break;
      case 'shipped':
        message = `🚚 Order #${order.id} has shipped! It's on the way.`;
        break;
      case 'delivered':
        message = `🎉 Order #${order.id} delivered! Enjoy your purchase.`;
        break;
      default:
        message = `Order #${order.id} status updated to: ${status}`;
    }
    
    await sendSMS(order.customerPhone, message);
    
    res.json({
      success: true,
      message: 'Order updated and SMS sent',
      order
    });
    
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// 5. YOUR EXISTING SMS ENDPOINTS
app.post('/api/sms/send', async (req, res) => {
  const { to, message } = req.body;
  if (!to || !message) {
    return res.status(400).json({ error: 'Missing to or message' });
  }
  try {
    const result = await sendSMS(to, message);
    res.json({ success: true, ...result });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

app.get('/api/sms/log', (req, res) => {
  res.json({ 
    success: true, 
    messages: getSMSLog() 
  });
});

// Start server
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`🚀 COMPLETE SYSTEM RUNNING ON PORT ${PORT}`);
  console.log(`📱 Mock SMS active - messages appear in terminal`);
  console.log(`\n📋 Available endpoints:`);
  console.log(`   POST  /api/orders           - Create order`);
  console.log(`   GET   /api/orders           - List orders`);
  console.log(`   GET   /api/orders/:id       - Get order`);
  console.log(`   PUT   /api/orders/:id/status - Update status`);
  console.log(`   POST  /api/sms/send          - Send SMS (test)`);
  console.log(`   GET   /api/sms/log            - View SMS log`);
});