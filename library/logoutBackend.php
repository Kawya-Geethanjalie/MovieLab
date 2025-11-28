<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Set to 0 in production, 1 for debugging

// CORS headers (if needed)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");

function sendResponse($status, $message = "", $data = []) {
    $response = ["status" => $status];
    if (!empty($message)) {
        $response["message"] = $message;
    }
    if (!empty($data)) {
        $response["data"] = $data;
    }
    echo json_encode($response);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    sendResponse("success", "You are already logged out");
}

try {
    // Optional: Log the logout activity if you have user_activity table
    if (isset($_SESSION['user_id'])) {
        include_once "../include/connection.php";
        
        // Log logout activity
        $stmt = $pdo->prepare("INSERT INTO user_activity (user_id, activity_type, description, ip_address) VALUES (?, 'logout', 'User logged out successfully', ?)");
        $stmt->execute([
            $_SESSION['user_id'],
            $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
        ]);
        
        // Optional: Invalidate user session in database if you have user_sessions table
        // This is useful if you're tracking sessions in the database
        /*
        if (isset($_COOKIE[session_name()])) {
            $sessionId = $_COOKIE[session_name()];
            $stmt = $pdo->prepare("UPDATE user_sessions SET is_active = FALSE WHERE session_id = ? AND user_id = ?");
            $stmt->execute([$sessionId, $_SESSION['user_id']]);
        }
        */
    }
} catch (Exception $e) {
    // Continue with logout even if logging fails
    error_log("Logout logging error: " . $e->getMessage());
}

// Store user data for response before destroying session
$userData = [];
if (isset($_SESSION['username'])) {
    $userData = [
        "username" => $_SESSION['username'],
        "first_name" => $_SESSION['first_name'] ?? '',
        "last_name" => $_SESSION['last_name'] ?? ''
    ];
}

// Clear all session variables
$_SESSION = [];

// If it's desired to kill the session, also delete the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session
session_destroy();

// Send success response
sendResponse("success", "You have been logged out successfully. See you soon! 👋", $userData);
?>