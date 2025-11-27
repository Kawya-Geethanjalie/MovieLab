<?php
include_once "../include/connection.php"; 
header('Content-Type: application/json');

// POST data
$first_name  = trim($_POST['first_name'] ?? "");
$last_name   = trim($_POST['last_name'] ?? "");
$email       = trim($_POST['email'] ?? "");
$username    = trim($_POST['username'] ?? "");
$birthday    = trim($_POST['birthday'] ?? "");
$country     = trim($_POST['country'] ?? "");
$password    = trim($_POST['password'] ?? "");
$confirm     = trim($_POST['confirm_password'] ?? "");
$agree       = trim($_POST['agree'] ?? ""); 

// Basic validation
if (!$first_name || !$last_name || !$email || !$username || !$birthday || !$country || !$password || !$confirm) {
    echo json_encode(["status"=>"error","message"=>"All fields are required"]);
    exit;
}

// Name validation
if (!preg_match("/^[a-zA-Z]{2,30}$/", $first_name)) {
    echo json_encode(["status"=>"error","message"=>"First name must contain only letters"]);
    exit;
}
if (!preg_match("/^[a-zA-Z]{2,30}$/", $last_name)) {
    echo json_encode(["status"=>"error","message"=>"Last name must contain only letters"]);
    exit;
}

// Email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status"=>"error","message"=>"Invalid email format"]);
    exit;
}

// Username length
if (strlen($username) < 5) {
    echo json_encode(["status"=>"error","message"=>"Username must be at least 5 characters long"]);
    exit;
}

// Birthday / Age check
try {
    $dob = new DateTime($birthday);
    $now = new DateTime();
    $age = $now->diff($dob)->y;
    if ($age < 13) {
        echo json_encode(["status"=>"error","message"=>"You must be at least 13 years old"]);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(["status"=>"error","message"=>"Invalid birthday format"]);
    exit;
}

// Password check
if (strlen($password) < 8) {
    echo json_encode(["status"=>"error","message"=>"Password must be at least 8 characters long"]);
    exit;
}
if ($password !== $confirm) {
    echo json_encode(["status"=>"error","message"=>"Passwords do not match"]);
    exit;
}

// Terms agreement
if ($agree !== 'on') {
    echo json_encode(["status"=>"error","message"=>"You must agree to the Terms and Conditions"]);
    exit;
}

// Check email
try {
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email=?");
    $stmt->execute([$email]);
    if ($stmt->rowCount()>0) {
        echo json_encode(["status"=>"error","message"=>"Email already registered"]);
        exit;
    }
} catch (PDOException $e) {
    error_log("Email check error: ".$e->getMessage());
    echo json_encode(["status"=>"error","message"=>"Server error"]);
    exit;
}

// Check username
try {
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE username=?");
    $stmt->execute([$username]);
    if ($stmt->rowCount()>0) {
        echo json_encode(["status"=>"error","message"=>"Username already taken"]);
        exit;
    }
} catch (PDOException $e) {
    error_log("Username check error: ".$e->getMessage());
    echo json_encode(["status"=>"error","message"=>"Server error"]);
    exit;
}

// Hash password
$password_hash = password_hash($password, PASSWORD_BCRYPT);

// Insert user
try {
    $stmt = $pdo->prepare("INSERT INTO users (username,email,password_hash,first_name,last_name,birthday,user_type,country,is_active,created_at) VALUES (?,?,?,?,?,?, 'normal', ?,1,NOW())");
    $success = $stmt->execute([$username,$email,$password_hash,$first_name,$last_name,$birthday,$country]);
    
    if ($success) {
        echo json_encode(["status"=>"success","message"=>"Registration successful"]);
    } else {
        echo json_encode(["status"=>"error","message"=>"Failed to create account"]);
    }
} catch (PDOException $e) {
    error_log("Insert user failed: ".$e->getMessage());
    echo json_encode(["status"=>"error","message"=>"Internal server error"]);
}
?>
