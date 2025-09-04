<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'agent') {
    header("Location: login.php");
    exit();
}
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Dashboard</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #457b9d, #1d3557);
            margin: 0;
            padding: 0;
            color: #fff;
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #1d3557;
            padding: 15px 25px;
            border-radius: 0 0 15px 15px;
            color: #fff;
            flex-wrap: wrap;
        }

        .navbar h1 {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
            color: #f1faee;
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            text-decoration: none;
            color: #f1faee;
            font-weight: 500;
            transition: 0.3s;
        }

        .nav-links a:hover {
            color: #a8dadc;
        }

        /* Mobile toggle */
        .menu-toggle {
            display: none;
            font-size: 24px;
            cursor: pointer;
            color: #fff;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
                flex-direction: column;
                width: 100%;
                background: #1d3557;
                margin-top: 15px;
                padding: 15px;
                border-radius: 10px;
            }

            .nav-links.active {
                display: flex;
            }

            .menu-toggle {
                display: block;
            }
        }

        /* Dashboard content */
        .container {
            padding: 30px;
        }

        .welcome-box {
            background: #e63946;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.3);
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .card {
            background: #2a9d8f;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            margin: 0 0 10px;
            font-size: 18px;
            color: #f1faee;
        }

        .card p {
            font-size: 14px;
            color: #f8f9fa;
        }

        .card a {
            display: inline-block;
            margin-top: 10px;
            background: #1d3557;
            color: #fff;
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s;
        }

        .card a:hover {
            background: #457b9d;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <h1>Agent Dashboard</h1>
        <span class="menu-toggle" onclick="toggleMenu()">☰</span>
        <div class="nav-links" id="menu">
            <a href="#">Dashboard</a>
            <a href="#">Add Property</a>
            <a href="#">Manage Listings</a>
            <a href="#">Leads</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="welcome-box">
            <h2>Hello, <?= htmlspecialchars($user['name']); ?></h2>
            <p>Manage your properties, track leads, and grow your real estate business.</p>
        </div>

        <div class="cards">
            <div class="card">
                <h3>Add Property</h3>
                <p>List a new property for sale or rent.</p>
                <a href="#">Add Now</a>
            </div>

            <div class="card">
                <h3>Manage Listings</h3>
                <p>Edit or remove your existing property listings.</p>
                <a href="#">Manage</a>
            </div>

            <div class="card">
                <h3>Leads</h3>
                <p>Track interested buyers and renters.</p>
                <a href="#">View Leads</a>
            </div>

            <div class="card">
                <h3>Profile</h3>
                <p>Update your account and agency details.</p>
                <a href="#">Edit Profile</a>
            </div>
        </div>
    </div>

    <script>
        function toggleMenu() {
            document.getElementById("menu").classList.toggle("active");
        }
    </script>
</body>

</html>
