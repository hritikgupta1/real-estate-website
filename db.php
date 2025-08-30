<?php

// This is for phpmyAdmin of cPanel
// $host = 'localhost';
// $db   = 'skgstjiteshji_realestate';
// $user = 'skgstjiteshji_admin'; // change if needed
// $pass = 'Admin@1@2@3';
// $charset = 'utf8mb4';

// this is for local system
$host = 'localhost';
$db   = 'realestate';
$user = 'root'; // change if needed
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo 'Database connection failed: ' . htmlspecialchars($e->getMessage());
    exit;
}
?>