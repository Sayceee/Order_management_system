<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order #{{ $order->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; min-height: 100vh; display: flex; flex-direction: column; }
        .navbar { background: #232f3e; padding: 1rem 2rem; color: white; }
        .navbar a { color: #ff9900; text-decoration: none; font-weight: bold; font-size: 1.2rem; }
        .container { max-width: 600px; margin: 2rem auto; padding: 0 1rem; width: 100%; }
        .card { background: white; border-radius: 10px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 1.5rem; border-bottom: 2px solid #f0f0f0; padding-bottom: 1rem; }
        .status-badge { display: inline-block; padding: 0.5rem 1rem; border-radius: 25px; font-weight: bold; margin-bottom: 1.5rem; }
        .status-paid { background: #d4edda; color: #155724; }
        .status-pending { background: #fff3cd; color: #856404; }
        .detail-row { display: flex; justify-content: space-between; padding: 0.8rem 0; border-bottom: 1px solid #eee; }
        .label { font-weight: bold; color: #666; }
        .value { color: #333; }
        .mpesa-code { font-family: monospace; background: #f5f5f5; padding: 0.2rem 0.5rem; border-radius: 3px; }
        .actions { margin-top: 2rem; display: flex; gap: 1rem; }
        .btn { flex: 1; text-align: center; padding: 1rem; border-radius: 25px; text-decoration: none; font-weight: bold; transition: background 0.3s; }
        .btn-pdf { background: #28a745; color: white; }
        .btn-pdf:hover { background: #218838; }
        .btn-shop { background: #ffd814; color: #111; }
        .btn-shop:hover { background: #f7ca00; }
        .tracking-note { margin-top: 1.5rem; padding: 1rem; border-radius: 5px; text-align: center; }
        .note-paid { background: #d4edda; color: #155724; }
        .note-pending { background: #fff3cd; color: #856404; }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="/">🧶 Faith's Crochet</a>
    </div>

    <div class="container">
        <div class="card">
            <h1>📦 Order Tracking #{{ $order->id }}</h1>

            <div style="text-align: center;">
                <span class="status-badge status-{{ $order->status }}">
                    {{ strtoupper($order->status) }}
                </span>
            </div>

            <div class="detail-row">
                <span class="label">Customer:</span>
                <span class="value">{{ $order->customer_name }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Product:</span>
                <span class="value">{{ $order->product_name }} (x{{ $order->quantity }})</span>
            </div>
            <div class="detail-row">
                <span class="label">Amount:</span>
                <span class="value">KES {{ number_format($order->amount, 2) }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Date:</span>
                <span class="value">{{ $order->created_at->format('d M Y, H:i') }}</span>
            </div>

            @if($order->payment && $order->payment->mpesa_receipt)
                <div class="detail-row">
                    <span class="label">M-Pesa Code:</span>
                    <span class="value mpesa-code">{{ $order->payment->mpesa_receipt }}</span>
                </div>
            @endif

            @if($order->status == 'paid')
                <div class="tracking-note note-paid">
                    ✅ Payment confirmed! Your order is being prepared.
                </div>
            @else
                <div class="tracking-note note-pending">
                    ⏳ Waiting for M-Pesa payment. Please check your phone.
                </div>
            @endif

            <div class="actions">
                <a href="{{ route('invoice.generate', $order->id) }}" class="btn btn-pdf">📥 Download Invoice</a>
                <a href="/shop" class="btn btn-shop">🛍️ Continue Shopping</a>
            </div>
        </div>
    </div>
</body>
</html>