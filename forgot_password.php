<?php
session_start();
require 'db.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Check if user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $otp = rand(100000, 999999);
        $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

        // Store OTP in session (or better: a reset table)
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_otp'] = $otp;
        $_SESSION['reset_expiry'] = $expiry;

        // Send OTP via email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "dubaispace1234@gmail.com";
            $mail->Password = "qocz bivr nowk aetw"; // app password
            $mail->SMTPSecure = "tls";
            $mail->Port = 587;

            $mail->setFrom("dubaispace1234@gmail.com", "Dubai Space");
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "Password Reset OTP - Dubai Space";
            $mail->Body = "<p>Your OTP to reset password is: <b>$otp</b>. It expires in 10 minutes.</p>";

            $mail->send();
            header("Location: reset_password.php");
            exit;
        } catch (Exception $e) {
            $message = "Failed to send OTP. Try again later.";
        }
    } else {
        $message = "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        body { font-family: Arial, sans-serif; background:#f4f6f8; display:flex; justify-content:center; align-items:center; height:100vh; }
        .box { background:white; padding:20px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.2); width:auto; display: flex; flex-direction:column; align-items: center; }
        h2 { margin-bottom:15px; }
        input { width:auto; padding:10px; margin:10px 0; border-radius:6px; border:1px solid #ccc; }
        button { background:#1d3557; color:white; padding:10px; width:auto; border:none; border-radius:6px; cursor:pointer; }
        button:hover { background:#457b9d; }
        .msg { color:red; margin:10px 0; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Forgot Password</h2>
        <?php if($message) echo "<p class='msg'>$message</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit">Send OTP</button>
        </form>
        <!--Back to Login -->
        <form action="login.php" method="get">

            <button type="submit" class="btn-back" >Back to Login</button>

        </form>

    </div>
</body>
</html>
