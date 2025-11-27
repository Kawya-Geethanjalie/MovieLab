<?php
include_once "../include/connection.php";
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Set to 0 in production, 1 for debugging

// CORS headers (if needed)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

function sendError($message) {
    echo json_encode(["status" => "error", "message" => $message]);
    exit;
}

function sendSuccess($message, $userData = []) {
    $response = ["status" => "success", "message" => $message];
    if (!empty($userData)) {
        $response["user"] = $userData;
    }
    echo json_encode($response);
    exit;
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError("Invalid request method");
}

// Get POST data
$identifier = trim($_POST['identifier'] ?? "");
$password = trim($_POST['password'] ?? "");

// Basic validation
if (empty($identifier) || empty($password)) {
    sendError("Username/Email and password are required");
}

// Validate identifier length
if (strlen($identifier) < 3) {
    sendError("Username/Email must be at least 3 characters long");
}

// Validate password length
if (strlen($password) < 6) {
    sendError("Password must be at least 6 characters long");
}

try {
    // Test database connection
    if (!$pdo) {
        error_log("Database connection failed");
        sendError("Database connection error. Please try again later.");
    }

    // Check if user exists by email or username
    $stmt = $pdo->prepare("SELECT user_id, username, email, password_hash, first_name, last_name, user_type, is_active FROM users WHERE email = ? OR username = ?");
    
    if (!$stmt) {
        error_log("Prepare statement failed: " . implode(", ", $pdo->errorInfo()));
        sendError("Database error. Please try again.");
    }

    $stmt->execute([$identifier, $identifier]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        error_log("Login attempt failed - User not found: " . $identifier);
        sendError("Invalid username/email or password");
    }

    // Check if account is active
    if (!$user['is_active']) {
        error_log("Login attempt failed - Account inactive: " . $identifier);
        sendError("Account is deactivated. Please contact support.");
    }

    // Verify password
    if (!password_verify($password, $user['password_hash'])) {
        error_log("Login attempt failed - Invalid password for: " . $identifier);
        sendError("Invalid username/email or password");
    }

    // Regenerate session ID for security
    session_regenerate_id(true);

    // Set session variables
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['last_name'] = $user['last_name'];
    $_SESSION['user_type'] = $user['user_type'];
    $_SESSION['logged_in'] = true;
    $_SESSION['login_time'] = time();

    // Update last login timestamp
    $updateStmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
    $updateStmt->execute([$user['user_id']]);

    // Prepare user data for response
    $userData = [
        "first_name" => $user['first_name'],
        "last_name" => $user['last_name'],
        "username" => $user['username'],
        "user_type" => $user['user_type']
    ];

    error_log("Login successful for user: " . $user['username']);
    sendSuccess("Login successful! Welcome back, " . $user['first_name'] . "!", $userData);

} catch (PDOException $e) {
    error_log("Login PDO Error: " . $e->getMessage());
    sendError("Database error. Please try again later.");
} catch (Exception $e) {
    error_log("Login General Error: " . $e->getMessage());
    sendError("System error. Please try again.");
}
?>