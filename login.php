<!DOCTYPE html>
<html>
<head>
    <title>Login - eBook Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: white;
            max-width: 450px;
            width: 90%;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        h2 {
            color: #2d3436;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            color: #2d3436;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 15px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #ffd32a;
            box-shadow: 0 0 0 3px rgba(255, 211, 42, 0.2);
        }

        .login-btn {
            background: #ffd32a;
            color: #2d3436;
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .login-btn:hover {
            background: #ffd700;
            transform: translateY(-2px);
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
            color: #636e72;
            font-size: 14px;
        }

        .register-link a {
            color: #ffd32a;
            text-decoration: none;
            font-weight: 500;
            margin-left: 5px;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-container {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        
        <form action="process_login.php" method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="Enter your email">
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Enter your password">
            </div>
            
            <button type="submit" class="login-btn">Login</button>
        </form>
        
        <div class="register-link">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </div>
</body>
</html> 