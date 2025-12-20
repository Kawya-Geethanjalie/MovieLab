<?php
ob_start();
session_start();

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Access denied. Admin login required.'
    ]);
    exit;
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
    'subscription_id' => null,
    'subscription_type' => null
];

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method. Use POST.';
    echo json_encode($response);
    exit;
}

try {
    // Database connection - using your existing database credentials
    define('DB_HOST', '127.0.0.1');
    define('DB_NAME', 'movielab');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_TIMEOUT => 5
        ]
    );
    
    // Test connection
    $pdo->query("SELECT 1");
    
    // Check if subscriptions table exists, create if not
    $checkTable = $pdo->query("SHOW TABLES LIKE 'subscriptions'")->fetch();
    if (!$checkTable) {
        // Create subscriptions table that matches your existing structure
        $createTableSQL = "
        CREATE TABLE subscriptions (
            subscription_id INT AUTO_INCREMENT PRIMARY KEY,
            plan_name VARCHAR(100) NOT NULL,
            description TEXT NOT NULL,
            price_monthly DECIMAL(10,2) DEFAULT 0.00,
            price_yearly DECIMAL(10,2) DEFAULT 0.00,
            duration_days INT DEFAULT 30,
            max_movies INT DEFAULT 0,
            max_quality VARCHAR(10) DEFAULT 'HD',
            user_type ENUM('normal','premium','admin') DEFAULT 'normal',
            features TEXT,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY unique_plan_name (plan_name)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        ";
        $pdo->exec($createTableSQL);
    }
    
    // Get form data
    $planName = filter_input(INPUT_POST, 'planName', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
    $priceMonthly = filter_input(INPUT_POST, 'priceMonthly', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?? 0;
    $priceYearly = filter_input(INPUT_POST, 'priceYearly', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?? 0;
    $durationDays = filter_input(INPUT_POST, 'durationDays', FILTER_SANITIZE_NUMBER_INT) ?? 30;
    $maxMovies = filter_input(INPUT_POST, 'maxMovies', FILTER_SANITIZE_NUMBER_INT) ?? 0;
    $maxQuality = filter_input(INPUT_POST, 'maxQuality', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? 'HD';
    $userType = filter_input(INPUT_POST, 'userType', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? 'normal';
    $isActive = isset($_POST['isActive']) ? 1 : 0;
    $features = $_POST['features'] ?? [];
    
    // Trim all inputs
    $planName = trim($planName);
    $description = trim($description);
    $maxQuality = trim($maxQuality);
    $userType = trim($userType);
    
    // Validate required fields
    $missingFields = [];
    
    if (empty($planName)) $missingFields[] = 'Plan Name';
    if (empty($description)) $missingFields[] = 'Description';
    if (empty($userType)) $missingFields[] = 'User Type';
    
    if (!empty($missingFields)) {
        $response['message'] = 'Missing required fields: ' . implode(', ', $missingFields);
        echo json_encode($response);
        exit;
    }
    
    // Validate numeric fields
    if (!is_numeric($priceMonthly) || $priceMonthly < 0) {
        $response['message'] = 'Invalid monthly price.';
        echo json_encode($response);
        exit;
    }
    
    if (!is_numeric($priceYearly) || $priceYearly < 0) {
        $response['message'] = 'Invalid yearly price.';
        echo json_encode($response);
        exit;
    }
    
    if (!is_numeric($durationDays) || $durationDays <= 0) {
        $response['message'] = 'Invalid duration. Must be positive number of days.';
        echo json_encode($response);
        exit;
    }
    
    if (!is_numeric($maxMovies) || $maxMovies < 0) {
        $response['message'] = 'Invalid maximum movies value.';
        echo json_encode($response);
        exit;
    }
    
    // Validate user type (must match your users table enum)
    $allowedTypes = ['normal', 'premium', 'admin'];
    if (!in_array($userType, $allowedTypes)) {
        $response['message'] = 'Invalid user type. Must be one of: ' . implode(', ', $allowedTypes);
        echo json_encode($response);
        exit;
    }
    
    // Validate quality
    $allowedQualities = ['SD', 'HD', 'FHD', 'UHD'];
    if (!in_array($maxQuality, $allowedQualities)) {
        $response['message'] = 'Invalid quality. Must be one of: ' . implode(', ', $allowedQualities);
        echo json_encode($response);
        exit;
    }
    
    // Check if plan name already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM subscriptions WHERE plan_name = ?");
    $stmt->execute([$planName]);
    if ($stmt->fetchColumn() > 0) {
        $response['message'] = 'Subscription plan "' . htmlspecialchars($planName) . '" already exists. Please choose another name.';
        echo json_encode($response);
        exit;
    }
    
    // Handle features array (convert to comma-separated string)
    $featuresStr = implode(', ', $features);
    
    // Insert into database
    $sql = "INSERT INTO subscriptions (
        plan_name, 
        description, 
        price_monthly, 
        price_yearly, 
        duration_days, 
        max_movies, 
        max_quality, 
        user_type, 
        features, 
        is_active
    ) VALUES (
        :plan_name, 
        :description, 
        :price_monthly, 
        :price_yearly, 
        :duration_days, 
        :max_movies, 
        :max_quality, 
        :user_type, 
        :features, 
        :is_active
    )";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':plan_name' => $planName,
        ':description' => $description,
        ':price_monthly' => $priceMonthly,
        ':price_yearly' => $priceYearly,
        ':duration_days' => $durationDays,
        ':max_movies' => $maxMovies,
        ':max_quality' => $maxQuality,
        ':user_type' => $userType,
        ':features' => $featuresStr,
        ':is_active' => $isActive
    ]);
    
    $subscriptionId = $pdo->lastInsertId();
    
    // Log the activity in user_activity table
    try {
        $adminId = $_SESSION['user_id'];
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
            ':user_id' => $adminId,
            ':activity_type' => 'subscription_created',
            ':description' => "Admin created subscription plan: {$planName} (ID: {$subscriptionId}) for user type: {$userType}",
            ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);
    } catch (PDOException $e) {
        // If user_activity table doesn't exist or error, just continue
    }
    
    // Prepare success response
    $response['success'] = true;
    $response['message'] = "✅ Subscription plan <strong>{$planName}</strong> has been successfully added!";
    $response['message'] .= "<br><br><strong>Plan Details:</strong><br>";
    $response['message'] .= "• Plan Name: <code>{$planName}</code><br>";
    $response['message'] .= "• User Type: <code>{$userType}</code><br>";
    $response['message'] .= "• Monthly Price: <code>\${$priceMonthly}</code><br>";
    $response['message'] .= "• Yearly Price: <code>\${$priceYearly}</code><br>";
    $response['message'] .= "• Duration: <code>{$durationDays} days</code><br>";
    $response['message'] .= "• Max Quality: <code>{$maxQuality}</code><br>";
    $response['message'] .= "• Status: <code>" . ($isActive ? 'Active' : 'Inactive') . "</code><br>";
    $response['message'] .= "• Features: <code>{$featuresStr}</code>";
    $response['subscription_id'] = $subscriptionId;
    $response['subscription_type'] = $userType;
    
} catch (PDOException $e) {
    // Database error with improved error messages
    $errorCode = $e->getCode();
    
    switch ($errorCode) {
        case 1045:
            $response['message'] = '🔒 Database Access Denied: Invalid username or password.';
            break;
        case 1049:
            $response['message'] = '🗄️ Database Not Found: The "movielab" database does not exist.';
            break;
        case 2002:
            $response['message'] = '🔌 Connection Failed: Cannot connect to MySQL server.';
            break;
        case 'HY000':
            if (strpos($e->getMessage(), 'timeout') !== false) {
                $response['message'] = '⏱️ Connection Timeout: Database server is not responding.';
            } else {
                $response['message'] = '❌ Database Error: ' . $e->getMessage();
            }
            break;
        default:
            if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) {
                $response['message'] = '❌ Database Error [Code: ' . $errorCode . ']: ' . $e->getMessage();
            } else {
                $response['message'] = '❌ Database Error: Unable to complete the operation.';
            }
    }
    
} catch (Exception $e) {
    $response['message'] = '❌ Error: ' . $e->getMessage();
}

// Send JSON response
echo json_encode($response);
exit;
?>