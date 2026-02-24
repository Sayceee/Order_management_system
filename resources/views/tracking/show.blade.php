<!DOCTYPE html>
<html>
<head>
    <title>Order Tracking #{{ $order['id'] }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .tracking-card {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
            margin: 20px 0;
        }
        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .label {
            font-weight: bold;
            color: #666;
        }
        .value {
            color: #333;
        }
        .tracking-note {
            margin-top: 30px;
            padding: 15px;
            background-color: #e3f2fd;
            border-radius: 5px;
            text-align: center;
            color: #0c5460;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="tracking-card">
        <h1>📦 Order Tracking</h1>
        
        <div class="detail-row">
            <span class="label">Order ID:</span>
            <span class="value">#{{ $order['id'] }}</span>
        </div>
        
        <div class="detail-row">
            <span class="label">Date:</span>
            <span class="value">{{ date('d M Y', strtotime($order['created_at'])) }}</span>
        </div>
        
        <div class="detail-row">
            <span class="label">Product:</span>
            <span class="value">{{ $order['product_name'] }}</span>
        </div>
        
        <div class="detail-row">
            <span class="label">Quantity:</span>
            <span class="value">{{ $order['quantity'] }}</span>
        </div>
        
        <div class="detail-row">
            <span class="label">Total Amount:</span>
            <span class="value">KES {{ number_format($order['amount'], 2) }}</span>
        </div>
        
        <div style="text-align: center;">
            <div class="status-badge status-{{ $order['status'] }}">
                @if($order['status'] == 'paid')
                    ✅ PAID
                @else
                    ⏳ PENDING
                @endif
            </div>
        </div>
        
        @if($order['status'] == 'paid')
            <div class="tracking-note">
                Your payment has been confirmed. Your order is being processed.
            </div>
        @else
            <div class="tracking-note">
                Awaiting payment. Please complete M-Pesa payment to proceed.
            </div>
        @endif
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="/invoice/{{ $order['id'] }}" class="btn">Download Invoice</a>
        </div>
    </div>
</body>
</html>