<h1>Order Management System</h1>

<div style="border: 1px solid #ccc; padding: 20px; margin-bottom: 20px;">
    <h3>1. Place an Order</h3>
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
