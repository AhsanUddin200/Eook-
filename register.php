<!DOCTYPE html>
<html>
<head>
    <title>Sign Up - eBook Store</title>
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

        .signup-container {
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

        .form-group input, 
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-group input:focus, 
        .form-group select:focus {
            outline: none;
            border-color: #ffd32a;
            box-shadow: 0 0 0 3px rgba(255, 211, 42, 0.2);
        }

        .submit-btn {
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
        }

        .submit-btn:hover {
            background: #ffd700;
            transform: translateY(-2px);
        }

        .login-link {
            text-align: center;
            margin-top: 25px;
            color: #636e72;
            font-size: 14px;
        }

        .login-link a {
            color: #ffd32a;
            text-decoration: none;
            font-weight: 500;
            margin-left: 5px;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* Add some animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .signup-container {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Create Account</h2>
        
        <form action="process_signup.php" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required placeholder="Enter your username">
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="Enter your email">
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Create a password">
            </div>
            
            <div class="form-group">
                <label>Register as</label>
                <select name="role" required>
                    <option value="">Select role</option>
                    <option value="customer">Customer</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            
            <button type="submit" class="submit-btn">Sign Up</button>
        </form>
        
        <div class="login-link">
            Already have an account?<a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html> 