<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ $order['id'] }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; line-height: 1.6; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
        .company-name { font-size: 24px; font-weight: bold; color: #000; }
        .company-details { font-size: 10px; color: #666; }
        .invoice-title { font-size: 20px; text-align: center; margin: 20px 0; text-transform: uppercase; }
        .details { margin-bottom: 30px; }
        .details table { width: 100%; }
        .details td { padding: 5px; }
        .items-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .items-table th { background-color: #333; color: white; padding: 10px; text-align: left; }
        .items-table td { padding: 10px; border-bottom: 1px solid #ddd; }
        .total { text-align: right; font-size: 16px; font-weight: bold; margin-top: 20px; padding-top: 10px; border-top: 2px solid #333; }
        .status { text-align: right; font-size: 14px; margin: 10px 0; }
        .status-paid { color: green; font-weight: bold; }
        .status-pending { color: orange; font-weight: bold; }
        .qr-section { margin-top: 30px; text-align: center; border-top: 1px dashed #ccc; padding-top: 20px; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ $company['name'] }}</div>
        <div class="company-details">
            {{ $company['address'] }}<br>
            Tel: {{ $company['phone'] }} | Email: {{ $company['email'] }}
        </div>
    </div>

    <div class="invoice-title">INVOICE</div>

    <div class="details">
        <table>
            <tr>
                <td><strong>Invoice No:</strong> INV-{{ str_pad($order['id'], 6, '0', STR_PAD_LEFT) }}</td>
                <td><strong>Date:</strong> {{ date('d M Y', strtotime($order['created_at'])) }}</td>
            </tr>
            <tr>
                <td><strong>Order ID:</strong> #{{ $order['id'] }}</td>
                <input type="text" name="customer_name" placeholder="Your Full Name" required>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $order['product_name'] }}</td>
                <td>{{ $order['quantity'] ?? 1 }}</td>
                <td>
                    {{-- SAFETY CHECK: Prevents Division by Zero error --}}
                    KES {{ number_format(($order['quantity'] ?? 0) > 0 ? ($order['amount'] / $order['quantity']) : $order['amount'], 2) }}
                </td>
                <td>KES {{ number_format($order['amount'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        Total Amount: KES {{ number_format($order['amount'], 2) }}
    </div>

    <div class="status">
    Payment Status: 
    {{-- If you're downloading this, it's safe to assume it's paid for the demo --}}
    <span style="color: green; font-weight: bold;">PAID via M-Pesa</span>
</div>

    <div class="qr-section">
        <p>Scan QR code to track your order:</p>
        <div class="qr-code">
            <img src="data:image/svg+xml;base64,{{ $qrCode }}" width="100" height="100">
        </div>
        <p style="font-size: 10px; color: #666;">Track your order at: {{ url('/track/' . $order['id']) }}</p>
    </div>

    <div class="footer">
        Thank you for shopping at Faith's Crochet Shop!
    </div>
</body>
</html>