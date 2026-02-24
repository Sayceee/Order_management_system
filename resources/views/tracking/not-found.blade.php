<!DOCTYPE html>
<html>
<head>
    <title>Order Not Found</title>
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
        .error-card {
            background-color: white;
            border-radius: 10px;
            padding: 40px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        h1 {
            color: #dc3545;
        }
        p {
            color: #666;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <h1>🔍 Order Not Found</h1>
        <p>The order you're looking for doesn't exist or has been removed.</p>
        <a href="/" class="btn">Go Home</a>
    </div>
</body>
</html>