<?php
session_start();
require 'db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp']);
    $new_pass = trim($_POST['password']);
    $email = $_SESSION['reset_email'] ?? null;

    if ($email && $otp == $_SESSION['reset_otp'] && strtotime($_SESSION['reset_expiry']) > time()) {
        // Update password (plain text as per your choice)
        $stmt = $pdo->prepare("UPDATE users SET password=? WHERE email=?");
        $stmt->execute([$new_pass, $email]);

        // Clear session
        unset($_SESSION['reset_email'], $_SESSION['reset_otp'], $_SESSION['reset_expiry']);

        $message = "Password reset successful! <a href='login.php'>Login</a>";
    } else {
        $message = "Invalid or expired OTP.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            width: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        h2 {
            margin-bottom: 15px;
        }

        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            background: #1d3557;
            color: white;
            padding: 10px;
            width: auto;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #457b9d;
        }

        .msg {
            color: green;
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <div class="box">
        <h2>Reset Password</h2>
        <?php if ($message) echo "<p class='msg'>$message</p>"; ?>
        <form method="POST">
            <input type="text" name="otp" placeholder="Enter OTP" required>
            <input type="password" name="password" placeholder="New Password" required>
            <div style="text-align: center;">
                <button type="submit" class="btn-back">Reset Password</button>

            </div>
        </form>
        <!--Back to Login -->
        <form action="login.php" method="get">

            <button type="submit" class="btn-back">Back to Login</button>

        </form>

    </div>
</body>

</html>