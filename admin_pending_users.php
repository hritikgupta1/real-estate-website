<?php
session_start();
require 'db.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Optional: check if logged in as admin
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Handle activation
if (isset($_GET['activate'])) {
    $id = (int) $_GET['activate'];

    // Fetch user
    $stmt = $pdo->prepare("SELECT * FROM pending_users WHERE id=? AND is_verified=1");
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if ($user) {
        // Insert into users
        $stmt = $pdo->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)");
        $stmt->execute([$user['name'], $user['email'], $user['password'], $user['role']]);

        // Delete from pending_users
        $pdo->prepare("DELETE FROM pending_users WHERE id=?")->execute([$id]);

        // Send activation email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "dubaispace1234@gmail.com"; // Your Gmail
            $mail->Password = "qocz bivr nowk aetw";      // App Password
            $mail->SMTPSecure = "tls";
            $mail->Port = 587;

            $mail->setFrom("dubaispace1234@gmail.com", "Dubai Space");
            $mail->addAddress($user['email'], $user['name']);

            $mail->isHTML(true);
            $mail->Subject = "Dubai Space - Account Activated";
            $mail->Body = "<h3>Dear {$user['name']},</h3>
                          <p>Your email is now active. You can login into <b>Dubai Space</b>.</p>
                          <p><a href='http://skgst.in/login.php'>Click here to login</a></p>";

            $mail->send();
            $message = "User activated and email sent successfully!";
        } catch (Exception $e) {
            $message = "User activated but email failed: {$mail->ErrorInfo}";
        }
    } else {
        $message = "Invalid user or user not verified.";
    }
}

// Fetch all verified pending users
$stmt = $pdo->query("SELECT * FROM pending_users WHERE is_verified=1 ORDER BY created_at DESC");
$pending_users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Pending Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            padding: 0;
            margin: 0;
        }

        .container {
            padding: 20px;
            

        }

        h2 {
            color: #1d3557;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #1d3557;
            color: white;
        }

        a.activate-btn {
            background: #2a9d8f;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 5px;
        }

        a.activate-btn:hover {
            background: #21867a;
        }

        .msg {
            margin-bottom: 15px;
            color: green;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php include 'admin_nav.php'; ?>
    <div class="container">
        <h2>Pending Users (Verified)</h2>
        <?php if (!empty($message)) echo "<p class='msg'>$message</p>"; ?>

        <?php if (count($pending_users) > 0): ?>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Registered At</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($pending_users as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['name']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= ucfirst($u['role']) ?></td>
                        <td><?= $u['created_at'] ?></td>
                        <td><a class="activate-btn" href="?activate=<?= $u['id'] ?>">Activate</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No verified users waiting for activation.</p>
        <?php endif; ?>
    </div>
</body>

</html>