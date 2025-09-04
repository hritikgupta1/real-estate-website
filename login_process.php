<?php
session_start();
require 'db.php';

$email    = trim($_POST['email']);
$password = trim($_POST['password']);
$role     = strtolower(trim($_POST['role'])); // ✅ force lowercase

try {
    // Query also checks role
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
    $stmt->execute([$email, $role]);
    $user = $stmt->fetch();

    if ($user && trim($password) === trim($user['password'])) {
        // Save session
        $_SESSION['user'] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role']
        ];

        // Redirect by role
        switch ($user['role']) {
            case 'user':
                header("Location: user_dashboard.php");
                break;
            case 'agent':
                header("Location: agent_dashboard.php");
                break;
            case 'developer':
                header("Location: developer_dashboard.php");
                break;
            default:
                header("Location: login.php?error=invalid");
        }
        exit();
    } else {
        header("Location: login.php?error=invalid");
        exit();
    }
} catch (PDOException $e) {
    error_log("Login Error: " . $e->getMessage());
    header("Location: login.php?error=server");
    exit();
}
