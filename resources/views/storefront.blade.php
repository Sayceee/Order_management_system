<h1>Order Management System</h1>

<div style="border: 1px solid #ccc; padding: 20px; margin-bottom: 20px;">
    <style>
    .product-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; padding: 20px; }
    .card { border: 1px solid #ddd; padding: 15px; border-radius: 8px; text-align: center; box-shadow: 2px 2px 10px #eee; }
    .buy-btn { background: #28a745; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px; }
</style>

<h2 style="text-align:center;">Faith's Crochet Shop</h2>
<div class="product-grid">
    <div class="card">
        <h3>Handmade Sweater</h3>
        <p>Price: KES 5,000</p>
        <form action="/api/mpesa/stkpush" method="POST">
            <input type="hidden" name="order_id" value="101">
            <input type="hidden" name="amount" value="5000">
            <input type="text" name="phone" placeholder="2547XXXXXXXX" required style="margin-bottom:10px;">
            <button type="submit" class="buy-btn">Buy via M-Pesa</button>
        </form>
    </div>
    
    <div class="card">
        <h3>Crochet Beanie</h3>
        <p>Price: KES 1,500</p>
        <form action="/api/mpesa/stkpush" method="POST">
            <input type="hidden" name="order_id" value="102">
            <input type="hidden" name="amount" value="1500">
            <input type="text" name="phone" placeholder="2547XXXXXXXX" required style="margin-bottom:10px;">
            <button type="submit" class="buy-btn">Buy via M-Pesa</button>
        </form>
    </div>
</div>
    <form action="/api/orders" method="POST">
        <input type="text" name="product_name" value="Faiths Crochet Design" readonly>
        <input type="number" name="amount" value="5000" readonly>
        <button type="submit">Order Now</button>
    </form>
</div>

<div style="border: 1px solid #ccc; padding: 20px;">
    <h3>2. Track My Items</h3>
    <input type="text" id="orderId" placeholder="Enter Order ID">
    <button onclick="window.location.href='/track/' + document.getElementById('orderId').value">Track</button>
</div>
