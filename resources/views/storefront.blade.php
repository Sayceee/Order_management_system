<h1>Order Management System</h1>

<div style="border: 1px solid #ccc; padding: 20px; margin-bottom: 20px;">
    <style>
        .product-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; padding: 20px; }
        .card { border: 1px solid #ddd; padding: 15px; border-radius: 8px; text-align: center; box-shadow: 2px 2px 10px #eee; }
        .buy-btn { background: #28a745; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px; width: 100%; }
        input[type="text"] { width: 90%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; }
    </style>

    <h2 style="text-align:center;">Faith's Crochet Shop</h2>
    
    <div class="product-grid">
        <div class="card">
            <h3>Handmade Sweater</h3>
            <p>Price: KES 5,000</p>
            <form action="/api/orders" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="product_name" value="Handmade Sweater">
                <input type="hidden" name="amount" value="5000">
                <input type="text" name="phone" placeholder="2547XXXXXXXX" required>
                <button type="submit" class="buy-btn">Buy via M-Pesa</button>
            </form>
        </div>
        
        <div class="card">
            <h3>Crochet Beanie</h3>
            <p>Price: KES 1,500</p>
            <form action="/api/orders" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="product_name" value="Crochet Beanie">
                <input type="hidden" name="amount" value="1500">
                <input type="text" name="phone" placeholder="2547XXXXXXXX" required>
                <button type="submit" class="buy-btn">Buy via M-Pesa</button>
            </form>
        </div>
    </div>
</div>

<div style="border: 1px solid #ccc; padding: 20px;">
    <h3>2. Track My Items</h3>
    <input type="text" id="orderId" placeholder="Enter Order ID">
    <button onclick="window.location.href='/track/' + document.getElementById('orderId').value">Track</button>
</div>