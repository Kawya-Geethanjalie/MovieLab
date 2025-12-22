<?php
// ===============================
// BACKEND PROCESSING SECTION
// ===============================
ob_start();
session_start();

// Check if this is a form submission (backend mode)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set JSON header for AJAX responses
    header('Content-Type: application/json');
    
    // Response array
    $response = [
        'success' => false,
        'message' => '',
        'user_id' => null,
        'user_type' => null
    ];
    
    try {
        // Database connection
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
        
        // Get form data
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
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $maxSize = 2 * 1024 * 1024; // 2MB
            
            $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            if (!in_array($fileExtension, $allowedExtensions)) {
                $response['message'] = 'Invalid file type. Only JPG, PNG, and GIF images are allowed.';
                echo json_encode($response);
                exit;
            }
            
            if ($file['size'] > $maxSize) {
                $response['message'] = 'File too large. Maximum size is 2MB.';
                echo json_encode($response);
                exit;
            }
            
            // Generate unique filename
            $uniqueId = uniqid();
            $filename = "profile_{$uniqueId}.{$fileExtension}";
            
            // Create uploads directory if it doesn't exist
            $uploadDir = 'uploads/profiles/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $uploadPath = $uploadDir . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $profileImage = $uploadPath;
            }
        }
        
        // Hash password
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        
        // Insert into database
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
            ':is_active' => 1,
            ':email_verified' => 0
        ]);
        
        $userId = $pdo->lastInsertId();
        
        // Prepare success response
        $response['success'] = true;
        $response['message'] = "✅ User <strong>{$firstName} {$lastName}</strong> added successfully!";
        $response['message'] .= "<br><br><strong>User Details:</strong><br>";
        $response['message'] .= "• Username: <code>{$username}</code><br>";
        $response['message'] .= "• Email: <code>{$email}</code><br>";
        $response['message'] .= "• User Type: <code>{$userType}</code><br>";
        $response['message'] .= "• User ID: <code>{$userId}</code>";
        $response['user_id'] = $userId;
        $response['user_type'] = $userType;
        
    } catch (PDOException $e) {
        $response['message'] = '❌ Database Error: ' . $e->getMessage();
    } catch (Exception $e) {
        $response['message'] = '❌ Error: ' . $e->getMessage();
    }
    
    echo json_encode($response);
    exit;
}

// ===============================
// FRONTEND DISPLAY SECTION
// ===============================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User - MovieLab Admin</title>
    <style>
        :root {
            --primary-red: #d32f2f;
            --dark-red: #b71c1c;
            --black: #121212;
            --dark-gray: #1e1e1e;
            --light-gray: #333333;
            --text-light: #f5f5f5;
            --text-gray: #aaaaaa;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--black);
            color: var(--text-light);
            line-height: 1.6;
            min-height: 100vh;
            padding: 20px;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(211, 47, 47, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(183, 28, 28, 0.1) 0%, transparent 50%);
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Header Styles */
        .header {
            background: linear-gradient(135deg, var(--dark-gray) 0%, #2a2a2a 100%);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            border-left: 6px solid var(--primary-red);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, var(--primary-red) 0%, transparent 70%);
            opacity: 0.1;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-red), var(--dark-red));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: bold;
        }

        .logo h1 {
            color: var(--primary-red);
            font-size: 28px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .logo span {
            color: var(--text-light);
        }

        .subtitle {
            color: var(--text-gray);
            font-size: 16px;
            margin-left: 55px;
        }

        /* Form Container */
        .form-container {
            background: linear-gradient(135deg, var(--dark-gray) 0%, #2a2a2a 100%);
            border-radius: 15px;
            padding: 35px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
            border-top: 4px solid var(--primary-red);
            position: relative;
            overflow: hidden;
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(211, 47, 47, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .section-title {
            color: var(--primary-red);
            margin-bottom: 25px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--light-gray);
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title::before {
            content: '+';
            background: var(--primary-red);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: bold;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: var(--text-light);
            font-weight: 600;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group label::before {
            content: '•';
            color: var(--primary-red);
            font-size: 20px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 14px 18px;
            background: var(--light-gray);
            border: 2px solid transparent;
            border-radius: 8px;
            color: var(--text-light);
            font-size: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-red);
            box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.2);
            background: #3a3a3a;
            transform: translateY(-2px);
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-row .form-group {
            flex: 1;
        }

        /* Password Strength Indicator */
        .password-strength {
            height: 4px;
            background: var(--light-gray);
            border-radius: 2px;
            margin-top: 8px;
            overflow: hidden;
            position: relative;
        }

        .strength-bar {
            height: 100%;
            width: 0%;
            background: #ff5252;
            border-radius: 2px;
            transition: all 0.3s;
        }

        /* File Upload */
        .file-upload {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-upload input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 14px;
            background: var(--light-gray);
            border: 2px dashed #555;
            border-radius: 8px;
            color: var(--text-gray);
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-label:hover {
            border-color: var(--primary-red);
            background: #3a3a3a;
        }

        .file-label i {
            margin-right: 10px;
            color: var(--primary-red);
        }

        /* Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 16px 32px;
            background: linear-gradient(135deg, var(--primary-red), var(--dark-red));
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(211, 47, 47, 0.3);
        }

        .btn:active {
            transform: translateY(-1px);
        }

        .btn-full {
            width: 100%;
            margin-top: 10px;
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--light-gray), #444);
        }

        .btn-secondary:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .btn-group {
            display: flex;
            gap: 20px;
            margin-top: 35px;
        }

        /* User Type Preview */
        .user-type-preview {
            display: flex;
            gap: 15px;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .type-option {
            flex: 1;
            min-width: 120px;
            padding: 15px;
            background: var(--light-gray);
            border: 2px solid transparent;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .type-option:hover {
            transform: translateY(-3px);
            background: #3a3a3a;
        }

        .type-option.active {
            border-color: var(--primary-red);
            background: rgba(211, 47, 47, 0.1);
        }

        .type-icon {
            font-size: 24px;
            margin-bottom: 8px;
            display: block;
        }

        .type-name {
            font-weight: 600;
            color: var(--text-light);
            margin-bottom: 5px;
        }

        .type-desc {
            font-size: 12px;
            color: var(--text-gray);
        }

        /* Messages */
        .message {
            padding: 18px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: none;
            animation: slideDown 0.3s ease-out;
            border-left: 5px solid;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .success {
            background: rgba(46, 125, 50, 0.15);
            border-left-color: #2e7d32;
            color: #a5d6a7;
        }

        .error {
            background: rgba(211, 47, 47, 0.15);
            border-left-color: var(--primary-red);
            color: #ffabab;
        }

        /* Loading Spinner */
        .loading {
            display: none;
            text-align: center;
            margin: 25px 0;
        }

        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.1);
            border-left-color: var(--primary-red);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Database Connection Status */
        .db-status {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #4caf50;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            text-align: center;
            color: var(--text-gray);
            font-size: 14px;
            padding: 20px;
            border-top: 1px solid var(--light-gray);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .user-type-preview {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <div class="logo">
                <div class="logo-icon">M</div>
                <div>
                    <h1>Movie<span>Lab</span> Admin</h1>
                    <p class="subtitle">Add New User to Database | Red & Black Theme</p>
                </div>
            </div>
        </header>

        <!-- Database Connection Status -->
        <div class="db-status">
            <div class="status-dot"></div>
            <span>Connected to: <strong>movielab</strong> database | <strong>users</strong> table</span>
        </div>

        <!-- Success/Error Messages -->
        <div id="successMessage" class="message success"></div>
        <div id="errorMessage" class="message error"></div>
        
        <!-- Loading Spinner -->
        <div id="loading" class="loading">
            <div class="spinner"></div>
            <p>Connecting to database...</p>
        </div>

        <!-- Add User Form -->
        <section class="form-container">
            <h2 class="section-title">Add New User</h2>
            <form id="addUserForm" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="firstName" required 
                               placeholder="Enter user's first name" maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastName" required 
                               placeholder="Enter user's last name" maxlength="50">
                    </div>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required 
                           placeholder="Choose a unique username" maxlength="50">
                    <div id="usernameCheck" style="font-size: 12px; margin-top: 5px;"></div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required 
                           placeholder="user@movielab.com" maxlength="100">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required 
                               placeholder="Minimum 8 characters" minlength="8">
                        <div class="password-strength">
                            <div class="strength-bar" id="strengthBar"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" required 
                               placeholder="Re-enter password">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="birthday">Birthday</label>
                        <input type="date" id="birthday" name="birthday" required>
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country" required 
                               placeholder="User's country" list="countries">
                        <datalist id="countries">
                            <option value="Sri Lanka">
                            <option value="United States">
                            <option value="United Kingdom">
                            <option value="Canada">
                            <option value="Australia">
                            <option value="India">
                        </datalist>
                    </div>
                </div>

                <div class="form-group">
                    <label>User Type</label>
                    <select id="userType" name="userType" required>
                        <option value="" disabled selected>Select user type</option>
                        <option value="normal">Normal User</option>
                        <option value="premium">Premium User</option>
                        <option value="admin">Admin User</option>
                    </select>
                    
                    <!-- User Type Preview -->
                    <div class="user-type-preview">
                        <div class="type-option" data-type="normal">
                            <span class="type-icon">👤</span>
                            <div class="type-name">Normal</div>
                            <div class="type-desc">Basic access</div>
                        </div>
                        <div class="type-option" data-type="premium">
                            <span class="type-icon">⭐</span>
                            <div class="type-name">Premium</div>
                            <div class="type-desc">Extra features</div>
                        </div>
                        <div class="type-option" data-type="admin">
                            <span class="type-icon">👑</span>
                            <div class="type-name">Admin</div>
                            <div class="type-desc">Full control</div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Profile Image (Optional)</label>
                    <div class="file-upload">
                        <input type="file" id="profileImage" name="profileImage" 
                               accept="image/jpeg,image/png,image/gif">
                        <div class="file-label">
                            <span>📷 Click to upload profile image (Max: 2MB)</span>
                        </div>
                    </div>
                    <div id="filePreview" style="margin-top: 10px;"></div>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-full">
                        <span>➕</span> Add User to Database
                    </button>
                    <button type="reset" class="btn btn-secondary btn-full">
                        <span>🗑️</span> Clear Form
                    </button>
                </div>

                <div style="margin-top: 25px; text-align: center;">
                    <a href="User_management.php" 
                       style="color: var(--primary-red); text-decoration: none; font-weight: 500;">
                        ← Back to User Management Panel
                    </a>
                </div>
            </form>
        </section>

        <footer class="footer">
            <p>MovieLab Admin Panel &copy; 2025 | User Management System</p>
            <p style="margin-top: 5px; font-size: 12px;">
                <strong>Note:</strong> After adding users, you can change their types in phpMyAdmin 
                by editing the <code>user_type</code> field in the <code>users</code> table.
            </p>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('addUserForm');
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');
            const loading = document.getElementById('loading');
            const strengthBar = document.getElementById('strengthBar');
            const usernameCheck = document.getElementById('usernameCheck');
            
            // Set today as max date for birthday
            const today = new Date().toISOString().split("T")[0];
            document.getElementById('birthday').max = today;
            
            // Set default birthday (18 years ago)
            const defaultDate = new Date();
            defaultDate.setFullYear(defaultDate.getFullYear() - 18);
            document.getElementById('birthday').value = defaultDate.toISOString().split("T")[0];
            
            // User type selection
            document.querySelectorAll('.type-option').forEach(option => {
                option.addEventListener('click', function() {
                    const type = this.dataset.type;
                    document.getElementById('userType').value = type;
                    
                    // Update active state
                    document.querySelectorAll('.type-option').forEach(opt => {
                        opt.classList.remove('active');
                    });
                    this.classList.add('active');
                });
            });
            
            // Password strength indicator
            document.getElementById('password').addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                
                if (password.length >= 8) strength += 25;
                if (/[A-Z]/.test(password)) strength += 25;
                if (/[0-9]/.test(password)) strength += 25;
                if (/[^A-Za-z0-9]/.test(password)) strength += 25;
                
                strengthBar.style.width = strength + '%';
                
                // Color coding
                if (strength < 50) {
                    strengthBar.style.background = '#ff5252';
                } else if (strength < 75) {
                    strengthBar.style.background = '#ff9800';
                } else {
                    strengthBar.style.background = '#4caf50';
                }
            });
            
            // File preview
            document.getElementById('profileImage').addEventListener('change', function(e) {
                const file = e.target.files[0];
                const preview = document.getElementById('filePreview');
                
                if (file) {
                    if (file.size > 2 * 1024 * 1024) {
                        showMessage('error', 'File size must be less than 2MB');
                        this.value = '';
                        preview.innerHTML = '';
                        return;
                    }
                    
                    if (!file.type.match('image.*')) {
                        showMessage('error', 'Please select an image file');
                        this.value = '';
                        preview.innerHTML = '';
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML = `
                            <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: var(--light-gray); border-radius: 8px;">
                                <img src="${e.target.result}" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                                <div>
                                    <div style="color: var(--text-light); font-weight: 500;">${file.name}</div>
                                    <div style="color: var(--text-gray); font-size: 12px;">${(file.size / 1024).toFixed(2)} KB</div>
                                </div>
                            </div>
                        `;
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            // Username availability check (simulated)
            document.getElementById('username').addEventListener('blur', function() {
                const username = this.value.trim();
                if (username.length < 3) {
                    usernameCheck.innerHTML = '<span style="color: #ff5252;">Username must be at least 3 characters</span>';
                    return;
                }
                
                // Simulate checking against database
                setTimeout(() => {
                    // In real app, this would be an AJAX call to check database
                    const takenUsernames = ['admin', 'sasindu', 'lochana', 'samudi'];
                    if (takenUsernames.includes(username.toLowerCase())) {
                        usernameCheck.innerHTML = '<span style="color: #ff5252;">Username already taken</span>';
                    } else {
                        usernameCheck.innerHTML = '<span style="color: #4caf50;">Username available</span>';
                    }
                }, 500);
            });
            
            // Form submission with improved error handling
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Hide previous messages
                successMessage.style.display = 'none';
                errorMessage.style.display = 'none';
                
                // Validate form
                if (!validateForm()) {
                    return;
                }
                
                // Show loading spinner
                loading.style.display = 'block';
                
                try {
                    // Create FormData object
                    const formData = new FormData(form);
                    
                    // Debug: Log current page
                    console.log('Current page URL:', window.location.href);
                    
                    // Send to current page (self-processing)
                    const controller = new AbortController();
                    const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout
                    
                    const response = await fetch(window.location.href, {
                        method: 'POST',
                        body: formData,
                        signal: controller.signal
                    });
                    
                    clearTimeout(timeoutId);
                    
                    // Check if response is OK
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    // Parse JSON response
                    const contentType = response.headers.get("content-type");
                    if (!contentType || !contentType.includes("application/json")) {
                        throw new Error("Server returned non-JSON response");
                    }
                    
                    const result = await response.json();
                    
                    // Hide loading spinner
                    loading.style.display = 'none';
                    
                    if (result.success) {
                        showMessage('success', result.message);
                        form.reset();
                        strengthBar.style.width = '0%';
                        document.getElementById('filePreview').innerHTML = '';
                        document.querySelectorAll('.type-option').forEach(opt => {
                            opt.classList.remove('active');
                        });
                        
                        // Show database instructions
                        setTimeout(() => {
                            showMessage('success', `
                                <strong>✅ User Added Successfully!</strong><br><br>
                                <strong>Database Information:</strong><br>
                                • User ID: ${result.user_id}<br>
                                • Table: <code>users</code><br>
                                • User Type: <code>${result.user_type}</code><br><br>
                                <strong>To Change User Type:</strong><br>
                                1. Open phpMyAdmin<br>
                                2. Select <code>movielab</code> database<br>
                                3. Edit <code>users</code> table<br>
                                4. Find user ID ${result.user_id}<br>
                                5. Change <code>user_type</code> field to: normal, premium, or admin
                            `);
                        }, 100);
                    } else {
                        showMessage('error', result.message);
                    }
                    
                } catch (error) {
                    loading.style.display = 'none';
                    
                    // Provide specific error messages
                    let errorMsg = '❌ Error: ';
                    if (error.name === 'AbortError') {
                        errorMsg += 'Request timeout. The server took too long to respond. Please try again.';
                    } else if (error.message.includes('HTTP error')) {
                        errorMsg += 'Server error (' + error.message + '). Please check if the web server is running correctly.';
                        errorMsg += '<br><br><strong>Troubleshooting:</strong><br>';
                        errorMsg += '1. Ensure your web server (Apache/XAMPP/WAMP) is running<br>';
                        errorMsg += '2. Check that PHP is properly installed and configured<br>';
                        errorMsg += '3. Verify file permissions are correct';
                    } else if (error.message.includes('non-JSON')) {
                        errorMsg += 'Server returned an invalid response. Please check for PHP errors.';
                    } else if (error.message.includes('Failed to fetch')) {
                        errorMsg += 'Cannot connect to server. Please check if your web server is running.';
                    } else {
                        errorMsg += error.message;
                    }
                    
                    showMessage('error', errorMsg);
                    console.error('Error details:', error);
                }
            });
            
            // Form reset
            form.querySelector('button[type="reset"]').addEventListener('click', function() {
                successMessage.style.display = 'none';
                errorMessage.style.display = 'none';
                loading.style.display = 'none';
                strengthBar.style.width = '0%';
                usernameCheck.innerHTML = '';
                document.getElementById('filePreview').innerHTML = '';
                document.querySelectorAll('.type-option').forEach(opt => {
                    opt.classList.remove('active');
                });
            });
            
            // Real-time validation
            form.querySelectorAll('input, select').forEach(input => {
                input.addEventListener('blur', validateField);
                input.addEventListener('input', function() {
                    // Clear field error on input
                    const errorDiv = this.parentElement.querySelector('.field-error');
                    if (errorDiv) errorDiv.remove();
                    this.style.borderColor = '';
                });
            });
            
            function validateForm() {
                let isValid = true;
                const requiredFields = form.querySelectorAll('[required]');
                
                requiredFields.forEach(field => {
                    if (!validateField({target: field})) {
                        isValid = false;
                    }
                });
                
                // Check passwords match
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirmPassword').value;
                
                if (password !== confirmPassword) {
                    showFieldError('confirmPassword', 'Passwords do not match');
                    isValid = false;
                }
                
                // Check password strength
                if (password.length < 8) {
                    showFieldError('password', 'Password must be at least 8 characters');
                    isValid = false;
                }
                
                return isValid;
            }
            
            function validateField(e) {
                const field = e.target;
                const value = field.value.trim();
                const fieldName = field.name;
                
                // Clear previous error
                clearFieldError(field);
                
                if (field.required && !value) {
                    showFieldError(field, 'This field is required');
                    return false;
                }
                
                switch (fieldName) {
                    case 'email':
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(value)) {
                            showFieldError(field, 'Please enter a valid email address');
                            return false;
                        }
                        break;
                        
                    case 'username':
                        if (value.length < 3) {
                            showFieldError(field, 'Username must be at least 3 characters');
                            return false;
                        }
                        break;
                        
                    case 'birthday':
                        const birthday = new Date(value);
                        const today = new Date();
                        if (birthday > today) {
                            showFieldError(field, 'Birthday cannot be in the future');
                            return false;
                        }
                        break;
                }
                
                return true;
            }
            
            function showFieldError(field, message) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'field-error';
                errorDiv.style.color = 'var(--primary-red)';
                errorDiv.style.fontSize = '13px';
                errorDiv.style.marginTop = '8px';
                errorDiv.style.padding = '5px 10px';
                errorDiv.style.background = 'rgba(211, 47, 47, 0.1)';
                errorDiv.style.borderRadius = '4px';
                errorDiv.innerHTML = `⚠️ ${message}`;
                
                field.parentElement.appendChild(errorDiv);
                field.style.borderColor = 'var(--primary-red)';
            }
            
            function clearFieldError(field) {
                const errorDiv = field.parentElement.querySelector('.field-error');
                if (errorDiv) errorDiv.remove();
                field.style.borderColor = '';
            }
            
            function showMessage(type, text) {
                if (type === 'success') {
                    successMessage.innerHTML = text;
                    successMessage.style.display = 'block';
                    errorMessage.style.display = 'none';
                } else {
                    errorMessage.innerHTML = text;
                    errorMessage.style.display = 'block';
                    successMessage.style.display = 'none';
                }
                
                // Scroll to message
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                // Auto-hide after 10 seconds
                setTimeout(() => {
                    successMessage.style.display = 'none';
                    errorMessage.style.display = 'none';
                }, 10000);
            }
        });
    </script>
</body>
</html>