<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
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
    <title>User Dashboard</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1d3557, #2a9d8f);
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
            background: #264653;
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
        <h1>User Dashboard</h1>
        <span class="menu-toggle" onclick="toggleMenu()">☰</span>
        <div class="nav-links" id="menu">
            <a href="#">Home</a>
            <a href="#">My Profile</a>
            <a href="#">Saved Properties</a>
            <a href="#">Bookings</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="welcome-box">
            <h2>Welcome, <?= htmlspecialchars($user['name']); ?></h2>
            <p>Explore properties, manage your bookings, and view your saved listings.</p>
        </div>

        <div class="cards">
            <div class="card">
                <h3>Browse Properties</h3>
                <p>Explore the latest properties available for rent and sale.</p>
                <a href="#">View Properties</a>
            </div>

            <div class="card">
                <h3>Saved Listings</h3>
                <p>Quickly access properties you’ve saved for later.</p>
                <a href="#">Go to Saved</a>
            </div>

            <div class="card">
                <h3>My Bookings</h3>
                <p>Check the status of your scheduled property visits.</p>
                <a href="#">View Bookings</a>
            </div>

            <div class="card">
                <h3>Profile Settings</h3>
                <p>Update your account information and preferences.</p>
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
