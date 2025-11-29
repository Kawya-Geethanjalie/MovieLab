<?php
session_start();
require_once '../include/connection.php'; // Adjust path

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validate inputs
    if (empty($username)) {
        header('Location: ../pages/login.php?error=User_Name');
        exit();
    }

    if (empty($password)) {
        header('Location: ../pages/login.php?error=Password');
        exit();
    }

    try {
        // Check if user exists and is admin
        $stmt = $pdo->prepare("
            SELECT user_id, username, email, password_hash, user_type 
            FROM users 
            WHERE (username = :username OR email = :email) AND is_active = 1
        ");
        
        $stmt->execute([
            ':username' => $username,
            ':email' => $username
        ]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password_hash'])) {
                // Check if user is admin
                if ($user['user_type'] === 'admin') {
                    // Set session variables
                    $_SESSION['admin_id'] = $user['user_id'];
                    $_SESSION['admin_username'] = $user['username'];
                    $_SESSION['admin_email'] = $user['email'];
                    $_SESSION['admin_logged_in'] = true;
                    
                    // Redirect to admin dashboard
                    header('Location: ../include/header.php');
                    exit();
                } else {
                    header('Location: ../pages/login.php?error=account_error');
                    exit();
                }
            } else {
                header('Location: ../pages/login.php?error=login_error');
                exit();
            }
        } else {
            header('Location: ../pages/login.php?error=login_error');
            exit();
        }
        
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        header('Location: ../pages/login.php?error=login_error');
        exit();
    }
} else {
    header('Location: ../pages/login.php');
    exit();
}
?>