<?php
session_start();

// Check if session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Database connection
require_once '../include/connection.php';

// Set response type
header('Content-Type: application/json');

// Handle different actions based on request
$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'add_user':
        handleAddUser();
        break;
    
    case 'get_user_details':
        handleGetUserDetails();
        break;
    
    case 'delete_user':
        handleDeleteUser();
        break;
    
    case 'export':
        handleExportUsers();
        break;
    
    case 'search':
        handleSearchUsers();
        break;
    
    case 'get_stats':
        handleGetStats();
        break;
    
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}

// Function to handle adding a new user
function handleAddUser() {
    global $pdo;
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit();
    }
    
    // Get form data
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $birthday = $_POST['birthday'] ?? null;
    $country = trim($_POST['country'] ?? '');
    $user_type = $_POST['user_type'] ?? 'normal';
    $is_active = intval($_POST['is_active'] ?? 1);
    $email_verified = intval($_POST['email_verified'] ?? 0);
    
    // Validate required fields
    $errors = [];
    
    if (empty($first_name)) {
        $errors[] = 'First name is required';
    } elseif (strlen($first_name) > 50) {
        $errors[] = 'First name must be less than 50 characters';
    }
    
    if (empty($last_name)) {
        $errors[] = 'Last name is required';
    } elseif (strlen($last_name) > 50) {
        $errors[] = 'Last name must be less than 50 characters';
    }
    
    if (empty($username)) {
        $errors[] = 'Username is required';
    } elseif (strlen($username) > 50) {
        $errors[] = 'Username must be less than 50 characters';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = 'Username can only contain letters, numbers, and underscores';
    }
    
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (strlen($email) > 100) {
        $errors[] = 'Email must be less than 100 characters';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    
    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters long';
    }
    
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }
    
    // Check if username or email already exists
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = 'Username or email already exists';
            }
        } catch (Exception $e) {
            $errors[] = 'Database error while checking existing users';
        }
    }
    
    if (!empty($errors)) {
        echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
        exit();
    }
    
    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Set default values for empty optional fields
    if (empty($country)) {
        $country = 'Not specified';
    }
    
    // Insert user into database
    try {
        $sql = "INSERT INTO users (
                    username, 
                    email, 
                    password_hash, 
                    first_name, 
                    last_name, 
                    birthday, 
                    country, 
                    user_type, 
                    is_active, 
                    email_verified
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $success = $stmt->execute([
            $username,
            $email,
            $password_hash,
            $first_name,
            $last_name,
            $birthday,
            $country,
            $user_type,
            $is_active,
            $email_verified
        ]);
        
        if ($success) {
            $user_id = $pdo->lastInsertId();
            
            // Log the activity
            logActivity($_SESSION['user_id'], 'add_user', "Added new user: $username (ID: $user_id)");
            
            echo json_encode([
                'success' => true,
                'message' => 'User added successfully!',
                'user_id' => $user_id
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add user to database']);
        }
    } catch (Exception $e) {
        error_log('Add user error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

// Function to get user details
function handleGetUserDetails() {
    global $pdo;
    
    $user_id = intval($_GET['user_id'] ?? 0);
    
    if ($user_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
        exit();
    }
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Remove sensitive data
            unset($user['password_hash']);
            
            // Format dates for better display
            if ($user['birthday']) {
                $user['birthday_formatted'] = date('F j, Y', strtotime($user['birthday']));
            }
            
            if ($user['created_at']) {
                $user['created_at_formatted'] = date('F j, Y, g:i a', strtotime($user['created_at']));
            }
            
            if ($user['updated_at']) {
                $user['updated_at_formatted'] = date('F j, Y, g:i a', strtotime($user['updated_at']));
            }
            
            if ($user['last_login']) {
                $user['last_login_formatted'] = date('F j, Y, g:i a', strtotime($user['last_login']));
                $user['last_login_relative'] = getRelativeTime($user['last_login']);
            } else {
                $user['last_login_formatted'] = 'Never';
                $user['last_login_relative'] = 'Never';
            }
            
            // Get user type display name
            $user['user_type_display'] = ucfirst($user['user_type']);
            
            echo json_encode(['success' => true, 'user' => $user]);
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }
    } catch (Exception $e) {
        error_log('Get user details error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
}

// Function to delete a user
function handleDeleteUser() {
    global $pdo;
    
    $user_id = intval($_GET['user_id'] ?? 0);
    
    if ($user_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
        exit();
    }
    
    try {
        // Prevent deleting own account
        if ($user_id == $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'Cannot delete your own account']);
            exit();
        }
        
        // First, get user details for logging
        $stmt = $pdo->prepare("SELECT username, email FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'User not found']);
            exit();
        }
        
        // Delete the user
        $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
        $success = $stmt->execute([$user_id]);
        
        if ($success && $stmt->rowCount() > 0) {
            // Log the activity
            logActivity($_SESSION['user_id'], 'delete_user', "Deleted user: {$user['username']} ({$user['email']})");
            
            echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete user']);
        }
    } catch (Exception $e) {
        error_log('Delete user error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
}

// Function to export users to CSV
function handleExportUsers() {
    global $pdo;
    
    try {
        // Get all users
        $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
        $users = $stmt->fetchAll();
        
        // Set headers for download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="users_export_' . date('Y-m-d_H-i-s') . '.csv"');
        
        // Create output stream
        $output = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($output, [
            'User ID',
            'Username',
            'Email',
            'First Name',
            'Last Name',
            'Birthday',
            'Country',
            'User Type',
            'Status',
            'Email Verified',
            'Registration Date',
            'Last Login',
            'Last Updated'
        ]);
        
        // Add data rows
        foreach ($users as $user) {
            fputcsv($output, [
                $user['user_id'],
                $user['username'],
                $user['email'],
                $user['first_name'],
                $user['last_name'],
                $user['birthday'] ?? 'N/A',
                $user['country'],
                ucfirst($user['user_type']),
                $user['is_active'] ? 'Active' : 'Inactive',
                $user['email_verified'] ? 'Verified' : 'Not Verified',
                $user['created_at'],
                $user['last_login'] ?: 'Never',
                $user['updated_at']
            ]);
        }
        
        // Log the activity
        logActivity($_SESSION['user_id'], 'export_users', 'Exported users list to CSV');
        
        fclose($output);
        exit();
        
    } catch (Exception $e) {
        error_log('Export users error: ' . $e->getMessage());
        
        // If headers already sent, output JSON error
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }
        
        echo json_encode(['success' => false, 'message' => 'Failed to export users: ' . $e->getMessage()]);
    }
}

// Function to search users
function handleSearchUsers() {
    global $pdo;
    
    $search = trim($_GET['search'] ?? '');
    $status = $_GET['status'] ?? '';
    $subscription = $_GET['subscription'] ?? '';
    $page = intval($_GET['page'] ?? 1);
    $limit = intval($_GET['limit'] ?? 20);
    $offset = ($page - 1) * $limit;
    
    // Build query
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM users WHERE 1=1";
    $params = [];
    
    if (!empty($search)) {
        $sql .= " AND (username LIKE ? OR email LIKE ? OR first_name LIKE ? OR last_name LIKE ?)";
        $search_term = "%$search%";
        $params = array_merge($params, [$search_term, $search_term, $search_term, $search_term]);
    }
    
    if (!empty($status)) {
        if ($status == 'active') {
            $sql .= " AND is_active = 1";
        } elseif ($status == 'inactive') {
            $sql .= " AND is_active = 0";
        } elseif ($status == 'suspended') {
            $sql .= " AND user_type = 'suspended'";
        }
    }
    
    if (!empty($subscription)) {
        if ($subscription == 'premium') {
            $sql .= " AND user_type = 'premium'";
        } elseif ($subscription == 'basic') {
            $sql .= " AND user_type = 'normal'";
        } elseif ($subscription == 'free') {
            $sql .= " AND user_type = 'normal'";
        }
    }
    
    $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $users = $stmt->fetchAll();
        
        // Get total count
        $stmt = $pdo->query("SELECT FOUND_ROWS()");
        $total = $stmt->fetchColumn();
        
        // Format user data for response
        $formatted_users = [];
        foreach ($users as $user) {
            $formatted_users[] = [
                'user_id' => $user['user_id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'full_name' => $user['first_name'] . ' ' . $user['last_name'],
                'user_type' => $user['user_type'],
                'user_type_display' => ucfirst($user['user_type']),
                'is_active' => $user['is_active'],
                'status' => $user['is_active'] ? 'active' : 'inactive',
                'status_display' => $user['is_active'] ? 'Active' : 'Inactive',
                'email_verified' => $user['email_verified'],
                'country' => $user['country'],
                'birthday' => $user['birthday'],
                'created_at' => $user['created_at'],
                'last_login' => $user['last_login'],
                'created_at_formatted' => date('Y-m-d', strtotime($user['created_at'])),
                'last_login_formatted' => $user['last_login'] ? date('Y-m-d H:i', strtotime($user['last_login'])) : 'Never'
            ];
        }
        
        echo json_encode([
            'success' => true,
            'users' => $formatted_users,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => ceil($total / $limit)
        ]);
        
    } catch (Exception $e) {
        error_log('Search users error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error during search']);
    }
}

// Function to get statistics
function handleGetStats() {
    global $pdo;
    
    try {
        // Total users
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
        $total_users = $stmt->fetchColumn();
        
        // Premium users
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE user_type = 'premium'");
        $premium_users = $stmt->fetchColumn();
        
        // Active today (users who logged in today)
        $today = date('Y-m-d');
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM users WHERE DATE(last_login) = ?");
        $stmt->execute([$today]);
        $active_today = $stmt->fetchColumn();
        
        // Suspended/inactive users
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE is_active = 0");
        $suspended_users = $stmt->fetchColumn();
        
        // New users this month
        $first_day_month = date('Y-m-01');
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM users WHERE created_at >= ?");
        $stmt->execute([$first_day_month]);
        $new_this_month = $stmt->fetchColumn();
        
        // New users last month (for comparison)
        $first_day_last_month = date('Y-m-01', strtotime('-1 month'));
        $last_day_last_month = date('Y-m-t', strtotime('-1 month'));
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM users WHERE created_at BETWEEN ? AND ?");
        $stmt->execute([$first_day_last_month, $last_day_last_month]);
        $new_last_month = $stmt->fetchColumn();
        
        // Calculate percentage change
        $monthly_change = 0;
        if ($new_last_month > 0) {
            $monthly_change = (($new_this_month - $new_last_month) / $new_last_month) * 100;
        } elseif ($new_this_month > 0) {
            $monthly_change = 100; // Infinite growth from 0
        }
        
        echo json_encode([
            'success' => true,
            'stats' => [
                'total_users' => (int)$total_users,
                'premium_users' => (int)$premium_users,
                'active_today' => (int)$active_today,
                'suspended_users' => (int)$suspended_users,
                'new_this_month' => (int)$new_this_month,
                'monthly_change' => round($monthly_change, 1)
            ]
        ]);
        
    } catch (Exception $e) {
        error_log('Get stats error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error fetching statistics']);
    }
}

// Helper function to log activities
function logActivity($user_id, $activity_type, $description = '') {
    global $pdo;
    
    try {
        $sql = "INSERT INTO user_activity (user_id, activity_type, description, ip_address) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $user_id,
            $activity_type,
            $description,
            $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
        ]);
    } catch (Exception $e) {
        // Silent fail for logging errors
        error_log('Activity logging failed: ' . $e->getMessage());
    }
}

// Helper function to get relative time
function getRelativeTime($datetime) {
    $time = strtotime($datetime);
    $time_diff = time() - $time;
    
    if ($time_diff < 60) {
        return 'Just now';
    } elseif ($time_diff < 3600) {
        $minutes = floor($time_diff / 60);
        return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
    } elseif ($time_diff < 86400) {
        $hours = floor($time_diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($time_diff < 604800) {
        $days = floor($time_diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } elseif ($time_diff < 2592000) {
        $weeks = floor($time_diff / 604800);
        return $weeks . ' week' . ($weeks > 1 ? 's' : '') . ' ago';
    } elseif ($time_diff < 31536000) {
        $months = floor($time_diff / 2592000);
        return $months . ' month' . ($months > 1 ? 's' : '') . ' ago';
    } else {
        $years = floor($time_diff / 31536000);
        return $years . ' year' . ($years > 1 ? 's' : '') . ' ago';
    }
}

// Close database connection
$pdo = null;
?>