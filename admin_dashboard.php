<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: admin_login.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <style>
    body,
    html {
      margin: 0px;
      padding: 0px;
      font-family: Arial, sans-serif;

    }


    /* Dashboard */
    .dashboard-container {
      padding: 30px;
    }

    .welcome-card {
      background: #e0f2fe;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 30px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .welcome-card h1 {
      margin: 0;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }

    .card {
      background: #fff;
      border-radius: 12px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform .2s ease;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card-icon {
      font-size: 40px;
      margin-bottom: 10px;
    }

    .btn {
      display: inline-block;
      margin-top: 15px;
      padding: 10px 20px;
      background: #2563eb;
      color: #fff;
      border-radius: 6px;
      text-decoration: none;
    }

    .btn:hover {
      background: #1d4ed8;
    }

    /* ✅ Mobile Responsiveness */
    @media (max-width: 768px) {
      .dashboard-container {
        padding: 15px;
      }

      .grid {
        grid-template-columns: 1fr;
      }

      .card {
        padding: 15px;
      }

      .btn {
        width: 90%;
        text-align: center;
      }

      .welcome-card h1 {
        font-size: 22px;
      }
    }

    @media (max-width: 480px) {
      .welcome-card h1 {
        font-size: 18px;
      }

      .card h2 {
        font-size: 16px;
      }

      .btn {
        font-size: 14px;
        padding: 8px;
      }
    }
  </style>
</head>

<body>
  <?php include 'admin_nav.php'; ?>

  <!-- Dashboard -->
  <div class="dashboard-container">
    <div class="welcome-card">
      <h1> Welcome, <?= htmlspecialchars($_SESSION['admin_username']); ?>!</h1>
      <p>Manage your real estate platform from here.</p>
    </div>

    <div class="grid">
      <!-- Agents -->
      <div class="card">
        <div class="card-icon">🧑</div>
        <h2>Manage Agents</h2>
        <p>Add, Edit, and Delete Agents.</p>
        <a href="manage_agents.php" class="btn">Go →</a>
      </div>

      <!-- Properties -->
      <div class="card">
        <div class="card-icon">🏠</div>
        <h2>Manage Properties</h2>
        <p>Add, Edit, and Delete Properties.</p>
        <a href="manage_properties.php" class="btn">Go →</a>
      </div>
    </div>
  </div>
</body>

</html>