<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Redirecting to Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
        }
        
        .container {
            text-align: center;
            background: white;
            padding: 3rem;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 90%;
        }
        
        h1 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 2rem;
        }
        
        p {
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .login-link {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 1rem 2rem;
            border-radius: 5px;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .login-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .login-link:active {
            transform: translateY(0);
        }
        
        .info-text {
            font-size: 0.9rem;
            color: #999;
            margin-top: 1.5rem;
        }
        
        .info-text a {
            color: #667eea;
            text-decoration: none;
        }
        
        .info-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome! 👋</h1>
        <p>You're just one step away from accessing your dashboard.</p>
        <a href="http://127.0.0.1:8000/login" class="login-link">
            Go to Login Page →
        </a>
        <div class="info-text">
            Need help? <a href="#">Contact support</a>
        </div>
    </div>
</body>
</html>