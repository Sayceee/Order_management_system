<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faith's Crochet Marketplace</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .navbar { background: #232f3e; padding: 1rem 2rem; color: white; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 1.8rem; font-weight: bold; color: #ff9900; text-decoration: none; }
        .nav-links a { color: white; text-decoration: none; margin-left: 2rem; padding: 0.5rem 1rem; border-radius: 5px; transition: background 0.3s; }
        .nav-links a:hover { background: #3a4a5e; }
        .btn-login { background: #ff9900; color: #232f3e !important; font-weight: bold; }
        .hero { text-align: center; padding: 5rem 2rem; color: white; }
        .hero h1 { font-size: 3.5rem; margin-bottom: 1rem; }
        .hero p { font-size: 1.3rem; margin-bottom: 2rem; opacity: 0.9; }
        .btn-shop { background: #ff9900; color: #232f3e; padding: 1rem 3rem; border-radius: 50px; text-decoration: none; font-weight: bold; font-size: 1.2rem; transition: transform 0.3s; display: inline-block; }
        .btn-shop:hover { transform: scale(1.05); }
        .features { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; padding: 4rem 2rem; max-width: 1200px; margin: 0 auto; }
        .feature-card { background: white; padding: 2rem; border-radius: 10px; text-align: center; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .feature-icon { font-size: 3rem; margin-bottom: 1rem; }
        .feature-card h3 { margin-bottom: 1rem; color: #333; }
        .feature-card p { color: #666; line-height: 1.6; }
        .footer { text-align: center; padding: 2rem; color: white; background: #232f3e; margin-top: 2rem; }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/" class="logo">🧶 Faith's Crochet</a>
        <div class="nav-links">
            @auth
                <a href="/shop">Shop</a>
                <a href="/dashboard">Dashboard</a>
                <a href="/profile">Profile</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: white; cursor: pointer; margin-left: 2rem;">Logout</button>
                </form>
            @else
                <a href="/login">Sign In</a>
                <a href="/register" class="btn-login">Register</a>
            @endauth
        </div>
    </nav>

    <div class="hero">
        <h1>Handmade Crochet,<br>Made with ❤️ in Kenya</h1>
        <p>Pay with M-Pesa • Instant Invoices • Track Your Order</p>
        <a href="/shop" class="btn-shop">🛍️ Start Shopping</a>
    </div>

    <div class="features">
        <div class="feature-card">
            <div class="feature-icon">📱</div>
            <h3>M-Pesa Payments</h3>
            <p>Secure mobile payments via STK Push. Just enter your PIN and you're done!</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📄</div>
            <h3>Instant PDF Invoices</h3>
            <p>Get a professional invoice with QR code immediately after payment.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔍</div>
            <h3>Real-time Tracking</h3>
            <p>Scan the QR code to track your order status anytime.</p>
        </div>
    </div>

    <div class="footer">
        <p>© 2026 Faith's Crochet Marketplace • All rights reserved</p>
    </div>
</body>
</html>