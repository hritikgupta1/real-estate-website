<?php if (!isset($page_title)) { $page_title = "Dubai Space"; } ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($page_title) ?></title>
  <link rel="stylesheet" href="style.css" />
  <script defer src="script.js"></script>
</head>
<body>
<header class="site-header">
  <div class="container nav">
    <a class="brand" href="index.php">
      <img src="images/logo.svg" alt="Dwell Properties logo" class="logo" />
    </a>
    <button class="menu-toggle" aria-label="Open Menu">&#9776;</button>
    <nav class="menu">
      <a href="index.php">Home</a>
      <a href="about.php">About Us</a> 
      <a href="properties.php">Properties</a>
      <!-- Removed Agents link -->
      <a href="contact.php">Contact</a>
      <a href="admin_login.php">Admin Login</a> <!-- Added Admin Login -->
    </nav>
  </div>
</header>
