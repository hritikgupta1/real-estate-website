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

// When user comes from register.php (after initial OTP sent)
if (isset($_GET['first']) && $_GET['first'] === "1") {
    $success = "OTP has been sent to your email. Please check your inbox.";
}

// Make sure pending_user session exists
if (!isset($_SESSION['pending_user'])) {
    $error = "Session expired. Please register again.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['pending_user'])) {
    $pending = &$_SESSION['pending_user']; // shortcut reference

    if (isset($_POST['verify'])) {
        $otp = trim($_POST['otp']);

        if ($otp == $pending['otp']) {
            if (time() > $pending['otp_expiry']) {
                $error = "OTP expired. Please request a new one.";
            } else {
                //  Insert into pending_users now (no otp/expiry in DB anymore)
                $stmt = $pdo->prepare("INSERT INTO pending_users (name, email, password, role, is_verified) 
                                       VALUES (?, ?, ?, ?, 1)");
                $stmt->execute([
                    $pending['name'],
                    $pending['email'],
                    $pending['password'],
                    $pending['role']
                ]);

                // clear session
                unset($_SESSION['pending_user']);

                $success = "Email verified successfully! Your registration is under review.";
            }
        } else {
            $error = "Invalid OTP. Try again.";
        }
    }

    if (isset($_POST['resend'])) {
        $otp = rand(100000, 999999);
        $otp_expiry = time() + (5 * 60);

        // update session
        $pending['otp'] = $otp;
        $pending['otp_expiry'] = $otp_expiry;

        // Send mail
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "dubaispace1234@gmail.com";
            $mail->Password = "qocz bivr nowk aetw";       // Gmail App Password
            $mail->SMTPSecure = "tls";
            $mail->Port = 587;

            $mail->setFrom("dubaispace1234@gmail.com", "Dubai Space");
            $mail->addAddress($pending['email'], $pending['name']);

            $mail->isHTML(true);
            $mail->Subject = "Resent OTP Code";
            $mail->Body = "<h3>Your new OTP is: <b>$otp</b></h3><p>It will expire in 5 minutes.</p>";

            $mail->send();
            $success = "New OTP sent to your email.";
        } catch (Exception $e) {
            $error = "Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Verify OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }
        input {
            width: 90%;
            padding: 10px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            width: 45%;
            padding: 10px;
            border: none;
            border-radius: 8px;
            background: #1d3557;
            color: white;
            cursor: pointer;
            margin: 5px;
        }
        button:disabled {
            background: #999;
            cursor: not-allowed;
        }
        .error { color: red; margin: 10px 0; }
        .success { color: green; margin: 10px 0; }
        .btn-back {
            background: #e63946;
            margin-top: 12px;
            width: 95%;
        }
        .btn-back:hover {
            background: #c92a35;
        }
        #timer {
            margin-top: 8px;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>Verify Your Email</h2>
        <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>
        <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>

        <?php if (isset($_SESSION['pending_user'])): ?>
        <!-- OTP form -->
        <form method="POST">
            <input type="text" name="otp" placeholder="Enter OTP" required><br>
            <button type="submit" name="verify">Verify</button>
        </form>

        <!-- Resend OTP with timer -->
        <form method="POST">
            <button type="submit" name="resend" id="resendBtn" disabled>Resend OTP</button>
            <p id="timer">You can resend in 60s</p>
        </form>
        <?php endif; ?>

        <!-- Back to Register.php -->
        <form action="register.php" method="get">
            <button type="submit" class="btn-back">⬅ Back to registration</button>
        </form>
    </div>

    <script>
        let timeLeft = 60;
        let timer = document.getElementById("timer");
        let resendBtn = document.getElementById("resendBtn");

        let countdown = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(countdown);
                timer.innerHTML = "You can resend OTP now";
                resendBtn.disabled = false;
            } else {
                timer.innerHTML = "You can resend in " + timeLeft + "s";
                resendBtn.disabled = true;
            }
            timeLeft -= 1;
        }, 1000);
    </script>
</body>
</html>
