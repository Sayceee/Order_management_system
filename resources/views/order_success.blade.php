<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Initiated | Faith's Crochet Shop</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f9fbfd; color: #333; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); text-align: center; max-width: 400px; width: 100%; }
        .icon { font-size: 50px; color: #28a745; margin-bottom: 20px; }
        h1 { margin: 0 0 10px; color: #2c3e50; }
        p { color: #7f8c8d; line-height: 1.6; }
        .order-id { font-weight: bold; color: #007bff; font-size: 1.2em; }
        .btn-group { margin-top: 30px; display: flex; flex-direction: column; gap: 10px; }
        .btn { padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: 0.3s; }
        .btn-pdf { background-color: #28a745; color: white; }
        .btn-pdf:hover { background-color: #218838; }
        .btn-home { background-color: #f1f3f5; color: #495057; }
        .btn-home:hover { background-color: #e9ecef; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">🧶</div>
        <h1>Prompt Sent!</h1>
        <p>We've sent an M-Pesa STK Push to your phone. Please enter your PIN to complete the payment for:</p>
        <div class="order-id">Order #{{ $order_id }}</div>
        
        <div class="btn-group">
            <a href="{{ url('/invoice/'.$order_id) }}" class="btn btn-pdf">📥 Download Invoice (PDF)</a>
            <a href="{{ url('/storefront') }}" class="btn btn-home">Back to Shop</a>
        </div>
    </div>
</body>
</html>