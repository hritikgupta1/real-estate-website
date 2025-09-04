<?php
session_start();
require 'db.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role     = strtolower(trim($_POST['role']));

    // Password Validation
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/', $password)) {
        $error = "Password must be at least 8 characters and include uppercase, lowercase, number, and special character.";
    } else {
        try {
            // Check duplicate in users table
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email=? AND role=?");
            $stmt->execute([$email, $role]);
            if ($stmt->fetch()) {
                $error = "Email and Role already exist!";
            } else {
                // Check if already in pending_users
                $stmt = $pdo->prepare("SELECT * FROM pending_users WHERE email=? AND role=?");
                $stmt->execute([$email, $role]);
                if ($stmt->fetch()) {
                    $error = "This email and role is already under verification. Please use another.";
                } else {
                    // Generate OTP (valid 5 min)
                    $otp = rand(100000, 999999);
                    $otp_expiry = time() + (5 * 60);

                    // Store data in SESSION instead of DB
                    session_start();
                    $_SESSION['pending_user'] = [
                        'name'     => $name,
                        'email'    => $email,
                        'password' => $password, // plain password
                        'role'     => $role,
                        'otp'      => $otp,
                        'otp_expiry' => $otp_expiry
                    ];

                    // Send OTP Email
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = "smtp.gmail.com";
                        $mail->SMTPAuth = true;
                        $mail->Username = "dubaispace1234@gmail.com";   // your Gmail
                        $mail->Password = "qocz bivr nowk aetw";       // your App Password
                        $mail->SMTPSecure = "tls";
                        $mail->Port = 587;

                        $mail->setFrom("dubaispace1234@gmail.com", "Dubai Space");
                        $mail->addAddress($email, $name);

                        $mail->isHTML(true);
                        $mail->Subject = "Your OTP Code";
                        $mail->Body = "<h3>Your OTP is: <b>$otp</b></h3><p>It will expire in 5 minutes.</p>";

                        $mail->send();

                        // Redirect to verification page
                        header("Location: verify.php?first=1");
                        exit;
                    } catch (Exception $e) {
                        $error = "OTP Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                }
            }
        } catch (PDOException $e) {
            $error = "Registration failed: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        /* Auth Styling */
        body.auth-body {
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
            margin-bottom: 5px;
            color: #333;
        }

        .form-group input,
        .form-group select {
            width: 90%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: border 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
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

        .success-message {
            color: green;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .error-message {
            color: red;
            font-weight: bold;
            margin-bottom: 15px;
        }

        @media (max-width: 480px) {
            .auth-container {
                background: #fff;
                padding: 30px 40px;
                border-radius: 15px;
                box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.2);
                width: 100%;
                max-width: 400px;
                text-align: center;
                margin: 20px;
            }
        }
    </style>
</head>

<body class="auth-body">
    <div class="auth-container">
        <h2>Create an Account</h2>

        <!-- Success/Error Messages -->
        <?php if (!empty($success)): ?>
            <p class="success-message"><?= $success ?></p>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <p class="error-message"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password"
                    required
                    pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}" `
                    title="Must be at least 8 characters, include uppercase, lowercase, number and special character">
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="user">User</option>
                    <option value="agent">Agent</option>
                    <option value="developer">Real-Estate Developer</option>
                </select>
            </div>

            <button type="submit" class="btn-auth">Register</button>
        </form>

        <!-- Back button -->
        <form action="index.php" method="get">
            <button type="submit" class="btn-back">⬅ Back to Home</button>
        </form>

        <p class="switch-auth">Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>

</html>