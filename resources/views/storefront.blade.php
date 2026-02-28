<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Faith's Crochet</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; }
        .navbar { background: #232f3e; padding: 1rem 2rem; color: white; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 1.5rem; font-weight: bold; color: #ff9900; text-decoration: none; }
        .nav-links { display: flex; align-items: center; gap: 1.5rem; }
        .nav-links a { color: white; text-decoration: none; }
        .cart-badge { 
            background: #ff9900; 
            color: #232f3e; 
            padding: 0.2rem 0.5rem; 
            border-radius: 20px; 
            font-weight: bold;
            margin-left: 0.3rem;
        }
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 2rem; }
        h1 { color: #333; margin-bottom: 2rem; }
        
        /* Cart Summary Bar */
        .cart-summary { 
            background: white; 
            padding: 1rem 2rem; 
            border-radius: 10px; 
            margin-bottom: 2rem;
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .view-cart-btn {
            background: #232f3e;
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
        }
        .view-cart-btn:hover { background: #3a4a5e; }
        
        .product-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; }
        .product-card { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 5px 20px rgba(0,0,0,0.15); }
        .product-image { 
            width: 100%; 
            height: 200px; 
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 4rem;
        }
        .product-details { padding: 1.5rem; }
        .product-name { font-size: 1.3rem; font-weight: bold; margin-bottom: 0.5rem; color: #333; }
        .product-price { font-size: 1.6rem; color: #b12704; font-weight: bold; margin-bottom: 1rem; }
        .product-price small { font-size: 0.9rem; color: #666; font-weight: normal; }
        
        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 1rem 0;
        }
        .quantity-selector label {
            font-weight: 600;
            color: #555;
        }
        .quantity-selector select {
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            background: white;
        }
        
        .btn-add-cart { 
            width: 100%; 
            padding: 1rem; 
            background: #ffd814; 
            border: none; 
            border-radius: 25px; 
            font-size: 1rem; 
            font-weight: bold; 
            cursor: pointer; 
            transition: background 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .btn-add-cart:hover { background: #f7ca00; }
        .btn-add-cart:disabled { background: #ccc; cursor: not-allowed; }
        
        .alert { padding: 1rem; border-radius: 5px; margin-bottom: 1rem; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        
        .cart-count {
            font-size: 0.9rem;
            color: #ff9900;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/" class="logo">🧶 Faith's Crochet</a>
        <div class="nav-links">
            <a href="/shop">Shop</a>
            <a href="/cart">
                🛒 Cart 
                @php
                    $cartCount = App\Models\Cart::where('user_id', auth()->id())->count();
                @endphp
                @if($cartCount > 0)
                    <span class="cart-badge">{{ $cartCount }}</span>
                @endif
            </a>
            <a href="/dashboard">Dashboard</a>
            <a href="/profile">Profile</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" style="background: none; border: none; color: white; cursor: pointer;">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <h1>🛍️ Handmade Crochet Collection</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <!-- Cart Summary -->
        @php
            $cartItems = App\Models\Cart::where('user_id', auth()->id())->get();
            $cartTotal = $cartItems->sum(function($item) {
                return $item->quantity * $item->price;
            });
        @endphp
        
        @if($cartItems->count() > 0)
            <div class="cart-summary">
                <div>
                    <strong>{{ $cartItems->count() }} item(s) in cart</strong>
                    <span style="margin-left: 1rem; color: #b12704;">Total: KES {{ number_format($cartTotal) }}</span>
                </div>
                <a href="/cart" class="view-cart-btn">View Cart →</a>
            </div>
        @endif

        <div class="product-grid">
            <!-- Product 1 - Sweater -->
            <div class="product-card">
                <div class="product-image">🧶</div>
                <div class="product-details">
                    <div class="product-name">Handmade Sweater</div>
                    <div class="product-price">KES 5,000 <small>warm & cozy</small></div>
                    
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_name" value="Handmade Sweater">
                        <input type="hidden" name="price" value="5000">
                        
                        <div class="quantity-selector">
                            <label>Quantity:</label>
                            <select name="quantity" class="quantity-select">
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <button type="submit" class="btn-add-cart">
                            🛒 Add to Cart
                        </button>
                    </form>
                </div>
            </div>

            <!-- Product 2 - Beanie -->
            <div class="product-card">
                <div class="product-image">🧣</div>
                <div class="product-details">
                    <div class="product-name">Crochet Beanie</div>
                    <div class="product-price">KES 1,500 <small>one size</small></div>
                    
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_name" value="Crochet Beanie">
                        <input type="hidden" name="price" value="1500">
                        
                        <div class="quantity-selector">
                            <label>Quantity:</label>
                            <select name="quantity" class="quantity-select">
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <button type="submit" class="btn-add-cart">
                            🛒 Add to Cart
                        </button>
                    </form>
                </div>
            </div>

            <!-- Product 3 - Gloves -->
            <div class="product-card">
                <div class="product-image">🧤</div>
                <div class="product-details">
                    <div class="product-name">Fingerless Gloves</div>
                    <div class="product-price">KES 800 <small>pair</small></div>
                    
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_name" value="Fingerless Gloves">
                        <input type="hidden" name="price" value="800">
                        
                        <div class="quantity-selector">
                            <label>Quantity:</label>
                            <select name="quantity" class="quantity-select">
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <button type="submit" class="btn-add-cart">
                            🛒 Add to Cart
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>