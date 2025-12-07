<?php
header('Content-Type: application/json');
session_start();

// Database සම්බන්ධතා විස්තර (ඔබගේ විස්තර ඇතුළත් කරන්න)
define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_username');
define('DB_PASS', 'your_db_password');
define('DB_NAME', 'your_database_name');

// Profile Images ගබඩා කරන ස්ථානය
$upload_dir = '../uploads/profile_images/';

// Function to connect to the database using PDO
function connectDB() {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (\PDOException $e) {
        // Production environment එකකදී මෙවැනි දෝෂ පෙන්වීමෙන් වළකින්න
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()]);
        exit;
    }
}

// ----------------------------------------------------------------------
// 1. පරිශීලක සත්‍යාපනය (Authentication)
// ----------------------------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated. Please sign in again.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$pdo = connectDB();

// ----------------------------------------------------------------------
// 2. Form දත්ත ලබා ගැනීම
// ----------------------------------------------------------------------
$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$new_password = $_POST['password'] ?? '';
$current_password = $_POST['current_password'] ?? '';

// ----------------------------------------------------------------------
// 3. මූලික දත්ත වලංගුකරණය (Basic Validation)
// ----------------------------------------------------------------------
if (empty($first_name) || empty($last_name) || empty($username) || empty($email)) {
    echo json_encode(['status' => 'error', 'message' => 'All profile fields must be filled.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
    exit;
}

// ----------------------------------------------------------------------
// 4. වර්තමාන පරිශීලක දත්ත ලබා ගැනීම
// ----------------------------------------------------------------------
$stmt = $pdo->prepare("SELECT password, profile_image FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$current_user_data = $stmt->fetch();

if (!$current_user_data) {
    echo json_encode(['status' => 'error', 'message' => 'User profile not found in database.']);
    exit;
}

$db_hashed_password = $current_user_data['password'];
$old_profile_image = $current_user_data['profile_image'];


// ----------------------------------------------------------------------
// 5. මුරපද වෙනස් කිරීමේ තර්කනය (Password Change Logic)
// ----------------------------------------------------------------------

$password_update_sql = "";
$hashed_password = null;

if (!empty($new_password)) {
    // Current Password අනිවාර්යයෙන්ම අවශ්‍යයි
    if (empty($current_password)) {
        echo json_encode(['status' => 'error', 'message' => 'Current Password is required to set a new password.']);
        exit;
    }

    // Current Password නිවැරදිදැයි පරීක්ෂා කිරීම
    if (!password_verify($current_password, $db_hashed_password)) {
        echo json_encode(['status' => 'error', 'message' => 'Incorrect current password. Profile update failed.']);
        exit;
    }
    
    // නව මුරපදය Hash කර යාවත්කාලීන කිරීමට සූදානම් කිරීම
    if (strlen($new_password) < 6) {
        echo json_encode(['status' => 'error', 'message' => 'New password must be at least 6 characters long.']);
        exit;
    }
    
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $password_update_sql = ", password = :password";
}

// ----------------------------------------------------------------------
// 6. Profile Image Upload Logic
// ----------------------------------------------------------------------
$image_update_sql = "";
$new_image_filename = $old_profile_image;

if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['profile_image'];
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $max_size = 1 * 1024 * 1024; // 1MB

    if (!in_array($file_extension, $allowed_extensions)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid image format. Only JPG, PNG, and GIF are allowed.']);
        exit;
    }

    if ($file['size'] > $max_size) {
        echo json_encode(['status' => 'error', 'message' => 'Image size exceeds 1MB limit.']);
        exit;
    }

    // නව unique filename එකක් ජනනය කිරීම
    $new_image_filename = uniqid('profile_') . '.' . $file_extension;
    $target_file = $upload_dir . $new_image_filename;

    // ගොනුව Upload කිරීම
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        $image_update_sql = ", profile_image = :profile_image";
        
        // පැරණි Image එක මකා දැමීම (if it exists and is not the default)
        if (!empty($old_profile_image) && file_exists($upload_dir . $old_profile_image)) {
            // Note: If you use a default image name, add a check here to prevent deleting the default.
            unlink($upload_dir . $old_profile_image);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error uploading new profile image.']);
        exit;
    }
}


// ----------------------------------------------------------------------
// 7. Database Update Query
// ----------------------------------------------------------------------

// 7.1. SQL Query එක සකස් කිරීම
$sql = "UPDATE users SET 
            first_name = :first_name, 
            last_name = :last_name, 
            username = :username, 
            email = :email 
            {$password_update_sql}
            {$image_update_sql}
        WHERE id = :id";
        
// 7.2. Parameters සකස් කිරීම
$params = [
    ':first_name' => $first_name,
    ':last_name' => $last_name,
    ':username' => $username,
    ':email' => $email,
    ':id' => $user_id,
];

// අවශ්‍ය නම්, නව parameters එකතු කිරීම
if ($hashed_password !== null) {
    $params[':password'] = $hashed_password;
}

if (!empty($image_update_sql)) {
    $params[':profile_image'] = $new_image_filename;
}


// 7.3. Query එක Execute කිරීම
try {
    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute($params);

    if ($success) {
        // 8. යාවත්කාලීන කළ දත්ත නැවත ලබාගෙන Session එක යාවත්කාලීන කිරීම
        $stmt = $pdo->prepare("SELECT id, first_name, last_name, username, email, profile_image FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $updated_user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Session යාවත්කාලීන කිරීම
        $_SESSION['user_data'] = $updated_user; 

        echo json_encode([
            'status' => 'success', 
            'message' => 'Your profile has been updated successfully!',
            'user' => $updated_user
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database update failed. No changes were made.']);
    }

} catch (\PDOException $e) {
    // Username/Email duplicate වැනි දෝෂ හැසිරවීම
    if ($e->getCode() === '23000') {
        echo json_encode(['status' => 'error', 'message' => 'Username or Email already exists. Please choose another.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'An unexpected database error occurred.']);
    }
    // Debugging සඳහා: error_log($e->getMessage());
}
?>