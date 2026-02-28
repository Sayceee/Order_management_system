<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart - Faith's Crochet</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial; background: #f5f5f5; }
        .navbar { background: #232f3e; padding: 1rem 2rem; color: white; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 1.5rem; font-weight: bold; color: #ff9900; text-decoration: none; }
        .nav-links a { color: white; text-decoration: none; margin-left: 1.5rem; }
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 2rem; }
        h1 { margin-bottom: 2rem; }
        .cart-table { background: white; border-radius: 10px; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 1rem; background: #f8f9fa; }
        td { padding: 1rem; border-bottom: 1px solid #eee; }
        .quantity-input { width: 60px; padding: 0.3rem; border: 1px solid #ddd; border-radius: 4px; }
        .btn-remove { color: #dc3545; text-decoration: none; }
        .btn-remove:hover { text-decoration: underline; }
        .cart-total { margin-top: 2rem; text-align: right; font-size: 1.3rem; }
        .checkout-btn { 
            background: #ffd814; 
            border: none; 
            padding: 1rem 3rem; 
            border-radius: 25px; 
            font-size: 1.1rem; 
            font-weight: bold; 
            cursor: pointer; 
            margin-top: 1rem;
            width: 100%;
            transition: background 0.3s;
        }
        .checkout-btn:hover { background: #f7ca00; }
        .continue-shopping { display: inline-block; margin-top: 1rem; color: #007bff; text-decoration: none; }
        .alert { padding: 1rem; border-radius: 5px; margin-bottom: 1rem; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
        
        /* Phone display */
        .phone-info {
            background: #e7f3ff;
            padding: 1rem;
            border-radius: 5px;
            margin: 1rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border-left: 4px solid #007bff;
        }
        .phone-info strong {
            color: #007bff;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/" class="logo">🧶 Faith's Crochet</a>
        <div class="nav-links">
            <a href="/shop">Shop</a>
            <a href="/cart">Cart ({{ \App\Models\Cart::where('user_id', auth()->id())->count() }})</a>
            <a href="/dashboard">Dashboard</a>
            <a href="/profile">Profile</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" style="background: none; border: none; color: white; cursor: pointer;">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <h1>🛒 Shopping Cart</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if($cartItems->isEmpty())
            <div style="text-align: center; padding: 3rem; background: white; border-radius: 10px;">
                <p style="font-size: 1.2rem; margin-bottom: 1rem;">Your cart is empty</p>
                <a href="/shop" class="continue-shopping">Continue Shopping →</a>
            </div>
        @else
            <div class="cart-table">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>KES {{ number_format($item->price) }}</td>
                            <td>
                                <input type="number" class="quantity-input" value="{{ $item->quantity }}" min="1" 
                                       onchange="updateQuantity({{ $item->id }}, this.value)">
                            </td>
                            <td>KES {{ number_format($item->quantity * $item->price) }}</td>
                            <td>
                                <a href="{{ route('cart.remove', $item->id) }}" class="btn-remove">Remove</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Show registered phone number -->
                <div class="phone-info">
                    <span>📱</span>
                    <span>M-Pesa will be sent to: <strong>{{ auth()->user()->phone }}</strong></span>
                    <a href="/profile" style="margin-left: auto; color: #007bff; text-decoration: none;">Change?</a>
                </div>

                <div class="cart-total">
                    <strong>Total: KES {{ number_format($total) }}</strong>
                </div>

                <!-- M-Pesa Checkout Form -->
                <form action="{{ route('cart.checkout') }}" method="POST">
                    @csrf
                    <button type="submit" class="checkout-btn">
                        📱 Pay with M-Pesa (STK Push)
                    </button>
                </form>
                
                <a href="/shop" class="continue-shopping">← Continue Shopping</a>
            </div>
        @endif
    </div>

    <script>
        function updateQuantity(id, quantity) {
            if (quantity < 1) quantity = 1;
            
            fetch('/cart/' + id, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ quantity: quantity })
            }).then(() => location.reload());
        }
    </script>
</body>
</html>