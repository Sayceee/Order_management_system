<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial; background: #f5f5f5; }
        .navbar { background: #232f3e; padding: 1rem; color: white; display: flex; justify-content: space-between; }
        .navbar a { color: #ff9900; text-decoration: none; }
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        h1 { margin-bottom: 1.5rem; }
        .orders-table { background: white; border-radius: 8px; padding: 1rem; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 1rem; background: #f8f9fa; }
        td { padding: 1rem; border-bottom: 1px solid #eee; }
        .status-paid { color: green; font-weight: bold; }
        .status-pending { color: orange; font-weight: bold; }
        .btn { padding: 0.3rem 0.8rem; border-radius: 4px; text-decoration: none; color: white; font-size: 0.9rem; }
        .btn-pdf { background: #28a745; }
        .btn-track { background: #007bff; }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="/">🏠 Home</a>
        <div>
            <span>{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" style="background: none; border: none; color: white; cursor: pointer;">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        <h1>Welcome, {{ Auth::user()->name }}!</h1>

        @php
            $orders = App\Models\Order::where('user_id', auth()->id())
                       ->orWhere('customer_name', auth()->user()->name)
                       ->latest()
                       ->get();
        @endphp

        <div class="orders-table">
            <h2>Your Orders</h2>

            @if($orders->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->product_name }} (x{{ $order->quantity }})</td>
                            <td>KES {{ number_format($order->amount) }}</td>
                            <td class="status-{{ $order->status }}">{{ strtoupper($order->status) }}</td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('track.order', $order->id) }}" class="btn btn-track">Track</a>
                                <a href="{{ route('invoice.generate', $order->id) }}" class="btn btn-pdf">PDF</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>You haven't placed any orders yet. <a href="/shop">Start shopping!</a></p>
            @endif
        </div>
    </div>
</body>
</html>