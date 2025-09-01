<!-- admin_nav.php -->
<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<style>
  .logo {
    height: 20px;
  }

  .navbar {
    background: #111827;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 1000;
  }

  .navbar .logo {
    color: #fff;
    font-size: 20px;
    font-weight: bold;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .navbar ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    gap: 15px;
  }

  .navbar ul li {
    display: inline;
  }

  .navbar ul li a {
    color: #fff;
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 6px;
    transition: background 0.3s;
    display: block;
  }

  .navbar ul li a:hover,
  .navbar ul li a.active {
    background: #2563eb;
  }

  .logout {
    background: #dc2626;
  }

  .logout:hover {
    background: #b91c1c;
  }

  /* ✅ Hamburger menu */
  .hamburger {
    display: none;
    flex-direction: column;
    gap: 5px;
    cursor: pointer;
    z-index: 1100;
  }

  .hamburger span {
    width: 28px;
    height: 3px;
    background: #fff;
    border-radius: 2px;
    transition: all 0.3s ease;
  }

  /* Animate to X when open */
  .hamburger.active span:nth-child(1) {
    transform: rotate(45deg) translate(5px, 5px);
  }
  .hamburger.active span:nth-child(2) {
    opacity: 0;
  }
  .hamburger.active span:nth-child(3) {
    transform: rotate(-45deg) translate(5px, -5px);
  }

  /* ✅ Mobile styles */
  @media (max-width: 768px) {
    .navbar ul {
      display: none;
      flex-direction: column;
      background: #1f2937;
      position: absolute;
      top: 50px;
      left: 0;
      width: 100%;
      padding: 15px 0;
      box-shadow: 0 4px 6px rgba(0,0,0,0.2);
      animation: slideDown 0.3s ease forwards;
    }

    .navbar ul li {
      margin: 10px 0;
      text-align: center;
    }

    .navbar ul li a {
      font-size: 16px;
      padding: 12px;
    }

    .navbar ul.show {
      display: flex;
    }

    .hamburger {
      display: flex;
    }
  }

  /* Slide down effect */
  @keyframes slideDown {
    from {opacity: 0; transform: translateY(-10px);}
    to {opacity: 1; transform: translateY(0);}
  }
</style>

<div class="navbar">
  <a href="admin_dashboard.php" class="logo">
    <img src="images/logo.svg" alt="Dwell Properties logo" class="logo" /> Admin Panel
  </a>

  <ul id="nav-links">
    <li><a href="manage_agents.php" class="<?= ($currentPage == 'manage_agents.php') ? 'active' : '' ?>">Agents</a></li>
    <li><a href="manage_properties.php" class="<?= ($currentPage == 'manage_properties.php') ? 'active' : '' ?>">Properties</a></li>
    <li><a href="admin_dashboard.php" class="<?= ($currentPage == 'admin_dashboard.php') ? 'active' : '' ?>">Dashboard</a></li>
    <li><a href="admin_logout.php" class="logout">Logout</a></li>
  </ul>

  <!-- Hamburger -->
  <div class="hamburger" id="hamburger">
    <span></span>
    <span></span>
    <span></span>
  </div>
</div>

<script>
  const hamburger = document.getElementById('hamburger');
  const navLinks = document.getElementById('nav-links');

  hamburger.addEventListener('click', () => {
    navLinks.classList.toggle('show');
    hamburger.classList.toggle('active');
  });
</script>
