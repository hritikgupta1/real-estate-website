<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? AND password = ?");
  $stmt->execute([$username, $password]);
  $admin = $stmt->fetch();

  if ($admin) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username'] = $admin['username'];
    header("Location: admin_dashboard.php");
    exit;
  } else {
    $error = "Invalid Username or Password";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- ✅ Mobile scaling -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #1f2937, #111827);
      color: #fff;
    }

    .login-card {
      background: #fff;
      color: #333;
      border-radius: 12px;
      padding: 30px;
      width: 100%;
      max-width: 380px;
    }

    .login-logo {
      height: 50px;

      max-width: 120px;

      object-fit: contain;

    }

    .login-card h3 {
      font-weight: 600;
      font-size: 22px;
      color: #111827;
    }

    .form-control {
      border-radius: 8px;
      padding: 12px;
    }

    .btn-login {
      border-radius: 8px;
      padding: 12px;
      font-weight: 600;
      background: #111827;
      color: white;
    }

    .btn-login:hover {
      background: #2563eb;
    }

    @media (max-width: 576px) {
      .login-card {
        margin: 0 15px;
        padding: 25px 20px;
      }

      .login-logo {
        height: 40px;
      }

      .login-card h3 {
        font-size: 20px;
      }

      .form-control {
        font-size: 16px;
      }
    }
  </style>
</head>

<body class="d-flex justify-content-center align-items-center vh-100">

  <div class="login-card shadow-lg">
    <h3 class="text-center mb-4 d-flex flex-column align-items-center">
      <img src="images/logo.svg" alt="Dwell Properties logo" class="login-logo mb-2" />
      <span>Admin Login</span>
    </h3>
    <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST">
      <div class="mb-3">
        <input type="text" name="username" class="form-control" placeholder="Username" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
      </div>
      <button class="btn btn-login w-100">Login</button>
    </form>
  </div>

</body>

</html>