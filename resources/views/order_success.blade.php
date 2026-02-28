<!DOCTYPE html>
<html>
<head>
    <title>Order Placed - Faith's Crochet</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial; background: #f5f5f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .card { background: white; padding: 3rem; border-radius: 15px; text-align: center; max-width: 500px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .icon { font-size: 4rem; margin-bottom: 1rem; }
        h1 { color: #28a745; margin-bottom: 1rem; }
        p { color: #666; margin-bottom: 2rem; line-height: 1.6; }
        .order-id { background: #f8f9fa; padding: 1rem; border-radius: 8px; font-size: 1.2rem; margin: 1.5rem 0; }
        .btn-group { display: flex; gap: 1rem; justify-content: center; }
        .btn { padding: 0.8rem 1.5rem; border-radius: 25px; text-decoration: none; font-weight: bold; transition: 0.3s; }
        .btn-pdf { background: #28a745; color: white; }
        .btn-pdf:hover { background: #218838; }
        .btn-track { background: #007bff; color: white; }
        .btn-track:hover { background: #0069d9; }
        .btn-shop { background: #ffd814; color: #111; }
        .btn-shop:hover { background: #f7ca00; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">📱</div>
        <h1>STK Push Sent!</h1>
        <p>We've sent an M-Pesa payment request to <strong>{{ auth()->user()->phone }}</strong>.<br>Please enter your PIN to complete the transaction.</p>
        
        <div class="order-id">
            Order #{{ $order_id }}
        </div>

        <div class="btn-group">
            <a href="{{ route('track.order', $order_id) }}" class="btn btn-track">🔍 Track Order</a>
            <a href="/shop" class="btn btn-shop">🛍️ Continue Shopping</a>
        </div>
        
        <p style="margin-top: 2rem; font-size: 0.9rem; color: #999;">
            Invoice will be available after payment confirmation.
        </p>
    </div>
</body>
</html>