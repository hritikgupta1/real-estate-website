<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="style.css">
    <style>
        /* Auth Pages Styling */
        .auth-body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #1d3557, #457b9d, #a8dadc);
            font-family: 'Poppins', sans-serif;
        }

        .auth-container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .auth-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #1d3557;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: border 0.3s;
        }

        .form-group input:focus {
            border-color: #457b9d;
        }

        .btn-auth {
            width: 100%;
            background: #1d3557;
            color: #fff;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: 0.3s;
            margin-bottom: 10px;
        }

        .btn-auth:hover {
            background: #457b9d;
        }

        .btn-back {
            width: 100%;
            background: #e63946;
            color: #fff;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-back:hover {
            background: #c92a35;
        }

        .switch-auth {
            margin-top: 15px;
            font-size: 14px;
        }

        .switch-auth a {
            color: #e63946;
            text-decoration: none;
            font-weight: 600;
        }

        .switch-auth a:hover {
            text-decoration: underline;
        }

        /* Error message */
        .error-message {
            background: #ffe5e5;
            color: #e63946;
            padding: 10px;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 15px;
            border: 1px solid #e63946;
        }

        /* Mobile Responsive */
        @media (max-width: 480px) {
            .auth-container {
                padding: 20px;
                margin: 20px;
            }

            .auth-container h2 {
                font-size: 20px;
            }

            .btn-auth,
            .btn-back {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
</head>

<body class="auth-body">
    <div class="auth-container">
        <h2>Login</h2>

        <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid'): ?>
            <p class="error-message">Invalid Email or Password or Role!</p>
        <?php endif; ?>

        <form method="POST" action="login_process.php">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <p class="switch-auth" style="text-align: right;">
                <a href="forgot_password.php">Forgot Password?</a>
            </p>


            <div class="form-group">
                <label>Role</label>
                <select name="role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="user">User</option>
                    <option value="agent">Agent</option>
                    <option value="developer">Real-Estate Developer</option>
                </select>
            </div>

            <button type="submit" class="btn-auth">Login</button>
        </form>

        <!-- Back button -->
        <form action="index.php" method="get">
            <button type="submit" class="btn-back">⬅ Back to Home</button>
        </form>

        <p class="switch-auth">Don't have an account? <a href="register.php">Register</a></p>
    </div>
</body>

</html>