<?php
ob_start();
session_start();

// Set the correct path for your connection file
// Adjust the path based on where this file is located relative to your library folder
$connectionPath = dirname(__DIR__) . '/include/connection.php';

if (file_exists($connectionPath)) {
    require_once($connectionPath);
} else {
    // If connection.php doesn't exist, use direct database connection
    define('DB_HOST', '127.0.0.1');
    define('DB_NAME', 'movielab');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    
    // Try to connect with timeout
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_TIMEOUT => 5 // 5 second connection timeout
            ]
        );
        // Store connection in global variable for consistency
        $GLOBALS['pdo'] = $pdo;
    } catch (PDOException $e) {
        // Connection error will be handled in the main try-catch block
        $GLOBALS['connection_error'] = $e->getMessage();
    }
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set JSON header
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Response array
$response = [
    'success' => false,
    'message' => '',
    'user_id' => null,
    'user_type' => null
];

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method. Use POST.';
    echo json_encode($response);
    exit;
}

// Check if admin is logged in (optional security)
// Uncomment if you want to restrict to logged-in admins only
/*
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    $response['message'] = 'Access denied. Admin login required.';
    echo json_encode($response);
    exit;
}
*/

try {
    // Check if we have a database connection
    if (isset($GLOBALS['connection_error'])) {
        throw new Exception('Database connection failed: ' . $GLOBALS['connection_error']);
    }
    
    if (isset($GLOBALS['pdo']) && $GLOBALS['pdo'] instanceof PDO) {
        // Use existing connection from connection.php
        $pdo = $GLOBALS['pdo'];
    } elseif (isset($connection) && $connection instanceof PDO) {
        // Alternative: if your connection.php uses $connection variable
        $pdo = $connection;
    } else {
        // Create new database connection with timeout
        $pdo = new PDO(
            "mysql:host=127.0.0.1;dbname=movielab;charset=utf8mb4",
            "root",
            "",
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_TIMEOUT => 5 // 5 second connection timeout
            ]
        );
    }
    
    // Test connection
    $pdo->query("SELECT 1");
    
    // Get form data - use FILTER_SANITIZE_FULL_SPECIAL_CHARS for PHP 8.1+ compatibility
    $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
    $lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '';
    $password = $_POST['password'] ?? '';
    $birthday = filter_input(INPUT_POST, 'birthday', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
    $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
    $userType = filter_input(INPUT_POST, 'userType', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? 'normal';
    
    // Trim all inputs
    $firstName = trim($firstName);
    $lastName = trim($lastName);
    $username = trim($username);
    $email = trim($email);
    $country = trim($country);
    $userType = trim($userType);
    
    // Validate required fields
    $missingFields = [];
    
    if (empty($firstName)) $missingFields[] = 'First Name';
    if (empty($lastName)) $missingFields[] = 'Last Name';
    if (empty($username)) $missingFields[] = 'Username';
    if (empty($email)) $missingFields[] = 'Email';
    if (empty($password)) $missingFields[] = 'Password';
    if (empty($birthday)) $missingFields[] = 'Birthday';
    if (empty($country)) $missingFields[] = 'Country';
    if (empty($userType)) $missingFields[] = 'User Type';
    
    if (!empty($missingFields)) {
        $response['message'] = 'Missing required fields: ' . implode(', ', $missingFields);
        echo json_encode($response);
        exit;
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Invalid email format.';
        echo json_encode($response);
        exit;
    }
    
    // Validate password length
    if (strlen($password) < 8) {
        $response['message'] = 'Password must be at least 8 characters.';
        echo json_encode($response);
        exit;
    }
    
    // Validate user type
    $allowedTypes = ['normal', 'premium', 'admin'];
    if (!in_array($userType, $allowedTypes)) {
        $response['message'] = 'Invalid user type. Must be normal, premium, or admin.';
        echo json_encode($response);
        exit;
    }
    
    // Validate birthday format
    $birthdayDate = DateTime::createFromFormat('Y-m-d', $birthday);
    if (!$birthdayDate || $birthdayDate->format('Y-m-d') !== $birthday) {
        $response['message'] = 'Invalid birthday format. Use YYYY-MM-DD.';
        echo json_encode($response);
        exit;
    }
    
    // Check if username already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetchColumn() > 0) {
        $response['message'] = 'Username "' . htmlspecialchars($username) . '" already exists. Please choose another.';
        echo json_encode($response);
        exit;
    }
    
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        $response['message'] = 'Email "' . htmlspecialchars($email) . '" already exists. Please use another email.';
        echo json_encode($response);
        exit;
    }
    
    // Handle profile image upload
    $profileImage = null;
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profileImage'];
        
        // Validate file type
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        // Get file extension
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        // Check extension
        if (!in_array($fileExtension, $allowedExtensions)) {
            $response['message'] = 'Invalid file type. Only JPG, PNG, and GIF images are allowed.';
            echo json_encode($response);
            exit;
        }
        
        // Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedMimeTypes)) {
            $response['message'] = 'Invalid file type. Only JPG, PNG, and GIF images are allowed.';
            echo json_encode($response);
            exit;
        }
        
        // Validate file size
        if ($file['size'] > $maxSize) {
            $response['message'] = 'File too large. Maximum size is 2MB.';
            echo json_encode($response);
            exit;
        }
        
        // Generate unique filename
        $uniqueId = uniqid();
        $timestamp = time();
        $filename = "profile_{$uniqueId}_{$timestamp}.{$fileExtension}";
        
        // Set upload directory - corrected path
        $uploadDir = dirname(__DIR__) . '/uploads/profiles/';
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                $response['message'] = 'Failed to create upload directory. Please check permissions.';
                echo json_encode($response);
                exit;
            }
        }
        
        $uploadPath = $uploadDir . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $profileImage = 'uploads/profiles/' . $filename;
        } else {
            $response['message'] = 'Failed to upload profile image. Please check directory permissions and try again.';
            echo json_encode($response);
            exit;
        }
    }
    
    // Hash password using bcrypt (matching your existing password_hash format)
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    
    // Prepare SQL insert statement matching your users table structure
    $sql = "INSERT INTO users (
        username, 
        email, 
        password_hash, 
        first_name, 
        last_name, 
        birthday, 
        country, 
        profile_image, 
        user_type, 
        is_active, 
        email_verified, 
        created_at, 
        updated_at
    ) VALUES (
        :username, 
        :email, 
        :password_hash, 
        :first_name, 
        :last_name, 
        :birthday, 
        :country, 
        :profile_image, 
        :user_type, 
        :is_active, 
        :email_verified, 
        NOW(), 
        NOW()
    )";
    
    $stmt = $pdo->prepare($sql);
    
    // Execute with parameters
    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':password_hash' => $passwordHash,
        ':first_name' => $firstName,
        ':last_name' => $lastName,
        ':birthday' => $birthday,
        ':country' => $country,
        ':profile_image' => $profileImage,
        ':user_type' => $userType,
        ':is_active' => 1, // Automatically activate since admin is creating
        ':email_verified' => 0 // Set to 0, admin can verify manually if needed
    ]);
    
    // Get the inserted user ID
    $userId = $pdo->lastInsertId();
    
    // Log the activity in user_activity table (if table exists)
    try {
        $activitySql = "INSERT INTO user_activity (
            user_id, 
            activity_type, 
            description, 
            ip_address, 
            created_at
        ) VALUES (
            :user_id, 
            :activity_type, 
            :description, 
            :ip_address, 
            NOW()
        )";
        
        $activityStmt = $pdo->prepare($activitySql);
        $activityStmt->execute([
            ':user_id' => $userId,
            ':activity_type' => 'user_created',
            ':description' => "Admin created new user: {$username} (ID: {$userId}) as {$userType} user",
            ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);
    } catch (PDOException $e) {
        // If user_activity table doesn't exist, just continue
        // The user was still created successfully
    }
    
    // Prepare success response
    $response['success'] = true;
    $response['message'] = "✅ User <strong>{$firstName} {$lastName}</strong> has been successfully added to the MovieLab database! ";
    $response['message'] .= "<br><br>";
    $response['message'] .= "<strong>User Details:</strong><br>";
    $response['message'] .= "• Username: <code>{$username}</code><br>";
    $response['message'] .= "• Email: <code>{$email}</code><br>";
    $response['message'] .= "• User Type: <code>{$userType}</code><br>";
    $response['message'] .= "• Country: <code>{$country}</code><br>";
    $response['message'] .= "<br>";
    $response['message'] .= "<strong>Database Information:</strong><br>";
    $response['message'] .= "• Table: <code>users</code><br>";
    $response['message'] .= "• User ID: <code>{$userId}</code><br>";
    $response['user_id'] = $userId;
    $response['user_type'] = $userType;
    
} catch (PDOException $e) {
    // Database error with improved error messages
    $errorCode = $e->getCode();
    
    // More specific error handling
    switch ($errorCode) {
        case 1045:
            $response['message'] = '🔒 Database Access Denied: Invalid username or password for database connection. Please check your database credentials.';
            break;
        case 1049:
            $response['message'] = '🗄️ Database Not Found: The "movielab" database does not exist. Please create it first or check the database name.';
            break;
        case 2002:
            $response['message'] = '🔌 Connection Failed: Cannot connect to MySQL server. Please ensure MySQL is running on 127.0.0.1.';
            break;
        case 1146:
            $response['message'] = '📋 Table Not Found: The "users" table does not exist in the movielab database. Please create the table first.';
            break;
        case 'HY000':
            if (strpos($e->getMessage(), 'timeout') !== false) {
                $response['message'] = '⏱️ Connection Timeout: Database server is not responding. Please check if MySQL is running.';
            } else {
                $response['message'] = '❌ Database Error: ' . $e->getMessage();
            }
            break;
        default:
            if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) {
                // Show detailed error on localhost for debugging
                $response['message'] = '❌ Database Error [Code: ' . $errorCode . ']: ' . $e->getMessage();
            } else {
                $response['message'] = '❌ Database Error: Unable to complete the operation. Please contact the system administrator.';
            }
    }
    
} catch (Exception $e) {
    // General error with improved messages
    if (strpos($e->getMessage(), 'connection') !== false) {
        $response['message'] = '🔌 Connection Error: ' . $e->getMessage();
    } else {
        $response['message'] = '❌ Error: ' . $e->getMessage();
    }
}

// Send JSON response
echo json_encode($response);
exit;
?>