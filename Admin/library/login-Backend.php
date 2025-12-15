<?php
session_start();

// 1. Helper function to send JSON response and terminate script
function sendJsonResponse(array $response) {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Fix the connection path - adjust based on your actual structure
$connection_path = dirname(__DIR__) . '/include/connection.php';
if (!file_exists($connection_path)) {
    error_log("Connection file not found at: " . $connection_path);
    // Return a server error JSON response
    sendJsonResponse([
        'status' => 'error',
        'message' => 'Server configuration error. Connection file missing.'
    ]);
}

require_once $connection_path;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // 2. Validate inputs - Return JSON on validation failure
    if (empty($username)) {
        sendJsonResponse([
            'status' => 'error',
            'message' => 'Please enter your Username or Email.'
        ]);
    }

    if (empty($password)) {
        sendJsonResponse([
            'status' => 'error',
            'message' => 'Please enter your Password.'
        ]);
    }

    try {
        // Check if user exists and is Admin - INCLUDE profile_image and other fields
        $stmt = $pdo->prepare("
            SELECT user_id, username, email, password_hash, user_type, first_name, last_name, profile_image
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
                    // Regenerate session ID for Security
                    session_regenerate_id(true);
                    
                    // Set session variables INCLUDING profile image and names
                    $_SESSION['admin_id'] = $user['user_id'];
                    $_SESSION['admin_username'] = $user['username'];
                    $_SESSION['admin_email'] = $user['email'];
                    $_SESSION['admin_first_name'] = $user['first_name'];
                    $_SESSION['admin_last_name'] = $user['last_name'];
                    $_SESSION['admin_profile_image'] = $user['profile_image'];
                    $_SESSION['admin_full_name'] = $user['first_name'] . ' ' . $user['last_name'];
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['last_activity'] = time();
                    
                    // Update last login
                    $update_stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE user_id = :user_id");
                    $update_stmt->execute([':user_id' => $user['user_id']]);
                    
                    // 3. SUCCESS: Return JSON object for the frontend to handle the redirect
                    sendJsonResponse([
                        'status' => 'success',
                        'message' => 'Authentication successful. Redirecting...',
                        'redirect' => '../pages/dashboard.php'
                    ]);
                    
                } else {
                    // Not an admin account
                    sendJsonResponse([
                        'status' => 'error',
                        'message' => 'Access denied. Only administrators can log in here.'
                    ]);
                }
            } else {
                // Invalid password
                sendJsonResponse([
                    'status' => 'error',
                    'message' => 'Invalid username or password.'
                ]);
            }
        } else {
            // User not found or inactive
            sendJsonResponse([
                'status' => 'error',
                'message' => 'Invalid username or password.'
            ]);
        }
        
    } catch (PDOException $e) {
        error_log("Login database error: " . $e->getMessage());
        // Return database error
        sendJsonResponse([
            'status' => 'error',
            'message' => 'A database error occurred. Please try again later.'
        ]);
    }
} else {
    // Direct access
    sendJsonResponse([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}
?>