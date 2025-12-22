<?php
// ===============================
// BACKEND PROCESSING SECTION
// ===============================
session_start();

// Check if this is a form submission (backend mode)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set JSON header for AJAX responses
    header('Content-Type: application/json');
    
    // Response array
    $response = [
        'success' => false,
        'message' => '',
        'subscription_id' => null,
        'subscription_type' => null
    ];
    
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
            $adminId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;
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
                ':description' => "Created subscription plan: {$planName} (ID: {$subscriptionId}) for user type: {$userType}",
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
        $response['message'] = '❌ Database Error: ' . $e->getMessage();
    } catch (Exception $e) {
        $response['message'] = '❌ Error: ' . $e->getMessage();
    }
    
    // Send JSON response
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
    <title>Add New Subscription - MovieLab Admin</title>
    <style>
        :root {
            --primary-red: #d32f2f;
            --dark-red: #b71c1c;
            --gold: #ffd700;
            --silver: #c0c0c0;
            --bronze: #cd7f32;
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
            max-width: 1000px;
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
            content: '💎';
            background: var(--primary-red);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
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
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 14px 18px;
            background: var(--light-gray);
            border: 2px solid transparent;
            border-radius: 8px;
            color: var(--text-light);
            font-size: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
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

        /* Price Display */
        .price-display {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 10px;
        }

        .price-tag {
            padding: 8px 15px;
            background: rgba(211, 47, 47, 0.1);
            border-radius: 6px;
            border: 1px solid var(--primary-red);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .price-tag span {
            color: var(--primary-red);
            font-weight: bold;
        }

        /* Features Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 10px;
        }

        .feature-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            background: var(--light-gray);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .feature-checkbox:hover {
            background: #3a3a3a;
            transform: translateY(-2px);
        }

        .feature-checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-red);
            cursor: pointer;
        }

        .feature-checkbox label {
            cursor: pointer;
            margin: 0;
            flex: 1;
        }

        .feature-checkbox label::before {
            content: none;
        }

        /* User Type Cards */
        .user-type-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .type-card {
            padding: 20px;
            background: var(--light-gray);
            border: 2px solid transparent;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .type-card:hover {
            transform: translateY(-5px);
            background: #3a3a3a;
        }

        .type-card.active {
            border-color: var(--primary-red);
            background: rgba(211, 47, 47, 0.1);
        }

        .type-icon {
            font-size: 32px;
            margin-bottom: 10px;
            display: block;
        }

        .type-name {
            font-weight: 600;
            color: var(--text-light);
            margin-bottom: 5px;
            font-size: 16px;
        }

        .type-desc {
            font-size: 12px;
            color: var(--text-gray);
        }

        /* Status Toggle */
        .status-toggle {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: var(--light-gray);
            border-radius: 8px;
        }

        .toggle-switch {
            position: relative;
            width: 60px;
            height: 30px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #555;
            transition: .4s;
            border-radius: 34px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .toggle-slider {
            background-color: var(--primary-red);
        }

        input:checked + .toggle-slider:before {
            transform: translateX(30px);
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

        /* Preview Card */
        .preview-card {
            background: var(--dark-gray);
            border-radius: 12px;
            padding: 25px;
            margin-top: 30px;
            border: 2px solid var(--light-gray);
        }

        .preview-title {
            color: var(--primary-red);
            margin-bottom: 15px;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .preview-item {
            padding: 10px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 6px;
        }

        .preview-item strong {
            color: var(--text-gray);
            display: block;
            font-size: 12px;
            margin-bottom: 5px;
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
            
            .user-type-cards,
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .preview-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <div class="logo">
                <div class="logo-icon">💎</div>
                <div>
                    <h1>Movie<span>Lab</span> Admin</h1>
                    <p class="subtitle">Add New Subscription Plan | Red & Black Theme</p>
                </div>
            </div>
        </header>

        <!-- Database Connection Status -->
        <div class="db-status">
            <div class="status-dot"></div>
            <span>Connected to: <strong>movielab</strong> database | <strong>subscriptions</strong> table</span>
        </div>

        <!-- Success/Error Messages -->
        <div id="successMessage" class="message success"></div>
        <div id="errorMessage" class="message error"></div>
        
        <!-- Loading Spinner -->
        <div id="loading" class="loading">
            <div class="spinner"></div>
            <p>Creating subscription plan...</p>
        </div>

        <!-- Add Subscription Form -->
        <section class="form-container">
            <h2 class="section-title">Create New Subscription Plan</h2>
            <form id="addSubscriptionForm">
                
                <!-- Plan Name & Description -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="planName">Plan Name *</label>
                        <input type="text" id="planName" name="planName" required 
                               placeholder="e.g., Premium Pro, VIP Cinema" maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="userType">User Type *</label>
                        <select id="userType" name="userType" required>
                            <option value="" disabled selected>Select associated user type</option>
                            <option value="normal">Normal User</option>
                            <option value="premium">Premium User</option>
                            <option value="admin">Admin User</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Plan Description *</label>
                    <textarea id="description" name="description" required 
                              placeholder="Describe what this subscription offers..."></textarea>
                </div>

                <!-- User Type Selection Cards -->
                <div class="form-group">
                    <label>User Type Preview</label>
                    <div class="user-type-cards">
                        <div class="type-card" data-type="normal">
                            <span class="type-icon">👤</span>
                            <div class="type-name">Normal</div>
                            <div class="type-desc">Basic access, limited features</div>
                        </div>
                        <div class="type-card" data-type="premium">
                            <span class="type-icon">⭐</span>
                            <div class="type-name">Premium</div>
                            <div class="type-desc">HD quality, no ads</div>
                        </div>
                        <div class="type-card" data-type="admin">
                            <span class="type-icon">👑</span>
                            <div class="type-name">Admin</div>
                            <div class="type-desc">Full system control</div>
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="priceMonthly">Monthly Price ($)</label>
                        <input type="number" id="priceMonthly" name="priceMonthly" 
                               step="0.01" min="0" value="9.99" 
                               placeholder="0.00">
                        <div class="price-display">
                            <div class="price-tag">
                                <span id="monthlyDisplay">$9.99</span> / month
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="priceYearly">Yearly Price ($)</label>
                        <input type="number" id="priceYearly" name="priceYearly" 
                               step="0.01" min="0" value="99.99" 
                               placeholder="0.00">
                        <div class="price-display">
                            <div class="price-tag">
                                <span id="yearlyDisplay">$99.99</span> / year
                            </div>
                            <small style="color: var(--text-gray);">
                                Save <span id="savingsPercent">17%</span>
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Features & Limits -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="durationDays">Duration (Days)</label>
                        <input type="number" id="durationDays" name="durationDays" 
                               min="1" value="30" required>
                        <small style="color: var(--text-gray); display: block; margin-top: 5px;">
                            Subscription validity period
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="maxMovies">Maximum Movies/Month</label>
                        <input type="number" id="maxMovies" name="maxMovies" 
                               min="0" value="100" placeholder="0 for unlimited">
                    </div>
                    <div class="form-group">
                        <label for="maxQuality">Maximum Quality</label>
                        <select id="maxQuality" name="maxQuality">
                            <option value="SD">SD (480p)</option>
                            <option value="HD" selected>HD (720p)</option>
                            <option value="FHD">Full HD (1080p)</option>
                            <option value="UHD">4K UHD (2160p)</option>
                        </select>
                    </div>
                </div>

                <!-- Features Selection -->
                <div class="form-group">
                    <label>Plan Features</label>
                    <div class="features-grid">
                        <div class="feature-checkbox">
                            <input type="checkbox" id="feature1" name="features[]" value="ad_free" checked>
                            <label for="feature1">Ad-free Streaming</label>
                        </div>
                        <div class="feature-checkbox">
                            <input type="checkbox" id="feature2" name="features[]" value="offline_download">
                            <label for="feature2">Offline Downloads</label>
                        </div>
                        <div class="feature-checkbox">
                            <input type="checkbox" id="feature3" name="features[]" value="multiple_devices" checked>
                            <label for="feature3">Multiple Devices</label>
                        </div>
                        <div class="feature-checkbox">
                            <input type="checkbox" id="feature4" name="features[]" value="early_access">
                            <label for="feature4">Early Access to Movies</label>
                        </div>
                        <div class="feature-checkbox">
                            <input type="checkbox" id="feature5" name="features[]" value="ultra_hd" checked>
                            <label for="feature5">Ultra HD Available</label>
                        </div>
                        <div class="feature-checkbox">
                            <input type="checkbox" id="feature6" name="features[]" value="family_sharing">
                            <label for="feature6">Family Sharing</label>
                        </div>
                        <div class="feature-checkbox">
                            <input type="checkbox" id="feature7" name="features[]" value="priority_support">
                            <label for="feature7">Priority Support</label>
                        </div>
                        <div class="feature-checkbox">
                            <input type="checkbox" id="feature8" name="features[]" value="exclusive_content">
                            <label for="feature8">Exclusive Content</label>
                        </div>
                    </div>
                </div>

                <!-- Status Toggle -->
                <div class="form-group">
                    <div class="status-toggle">
                        <div>
                            <strong>Plan Status</strong>
                            <p style="color: var(--text-gray); font-size: 14px; margin-top: 5px;">
                                Active plans are available for users to subscribe
                            </p>
                        </div>
                        <div class="toggle-switch">
                            <input type="checkbox" id="isActive" name="isActive" checked>
                            <label class="toggle-slider" for="isActive"></label>
                        </div>
                    </div>
                </div>

                <!-- Plan Preview -->
                <div class="preview-card">
                    <h3 class="preview-title">📋 Plan Preview</h3>
                    <div class="preview-grid">
                        <div class="preview-item">
                            <strong>Plan Name</strong>
                            <span id="previewName">-</span>
                        </div>
                        <div class="preview-item">
                            <strong>User Type</strong>
                            <span id="previewType">-</span>
                        </div>
                        <div class="preview-item">
                            <strong>Monthly Price</strong>
                            <span id="previewMonthly">-</span>
                        </div>
                        <div class="preview-item">
                            <strong>Yearly Price</strong>
                            <span id="previewYearly">-</span>
                        </div>
                        <div class="preview-item">
                            <strong>Duration</strong>
                            <span id="previewDuration">-</span>
                        </div>
                        <div class="preview-item">
                            <strong>Max Quality</strong>
                            <span id="previewQuality">-</span>
                        </div>
                        <div class="preview-item" style="grid-column: span 2;">
                            <strong>Selected Features</strong>
                            <span id="previewFeatures" style="font-size: 12px;">-</span>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="btn-group">
                    <button type="submit" class="btn btn-full">
                        <span>💎</span> Create Subscription Plan
                    </button>
                    <button type="reset" class="btn btn-secondary btn-full">
                        <span>🗑️</span> Clear Form
                    </button>
                </div>

                <!-- Navigation -->
                <div style="margin-top: 25px; text-align: center;">
                    <a href="manage_subscriptions.php" 
                       style="color: var(--primary-red); text-decoration: none; font-weight: 500; margin-right: 20px;">
                        ← Back to Subscription Management
                    </a>
                    <a href="manage_users.php" 
                       style="color: var(--text-gray); text-decoration: none; font-weight: 500;">
                        👥 Manage User Types
                    </a>
                </div>

            </form>
        </section>

        <!-- Database Instructions -->
        <div class="form-container" style="margin-top: 30px;">
            <h3 class="section-title">📚 Database Information</h3>
            <div style="color: var(--text-gray); line-height: 1.8;">
                <p><strong>Table Structure:</strong> <code>subscriptions</code> (will be created automatically)</p>
                <div style="background: var(--black); padding: 15px; border-radius: 8px; margin: 15px 0; font-family: monospace; font-size: 13px;">
                    CREATE TABLE subscriptions (<br>
                    &nbsp;&nbsp;subscription_id INT AUTO_INCREMENT PRIMARY KEY,<br>
                    &nbsp;&nbsp;plan_name VARCHAR(100) NOT NULL,<br>
                    &nbsp;&nbsp;description TEXT NOT NULL,<br>
                    &nbsp;&nbsp;price_monthly DECIMAL(10,2) DEFAULT 0.00,<br>
                    &nbsp;&nbsp;price_yearly DECIMAL(10,2) DEFAULT 0.00,<br>
                    &nbsp;&nbsp;duration_days INT DEFAULT 30,<br>
                    &nbsp;&nbsp;max_movies INT DEFAULT 0,<br>
                    &nbsp;&nbsp;max_quality VARCHAR(10) DEFAULT 'HD',<br>
                    &nbsp;&nbsp;user_type ENUM('normal','premium','admin') DEFAULT 'normal',<br>
                    &nbsp;&nbsp;features TEXT,<br>
                    &nbsp;&nbsp;is_active BOOLEAN DEFAULT TRUE,<br>
                    &nbsp;&nbsp;created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,<br>
                    &nbsp;&nbsp;updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP<br>
                    );
                </div>
                <p><strong>User Type Mapping:</strong> Each subscription plan is linked to a user type in your existing <code>users</code> table.</p>
                <p><strong>Integration:</strong> When users subscribe to a plan, their <code>user_type</code> can be updated automatically.</p>
            </div>
        </div>

        <footer class="footer">
            <p>MovieLab Subscription Management &copy; 2025 | Red & Black Theme</p>
            <p style="margin-top: 5px; font-size: 12px;">
                <strong>Note:</strong> This single file contains both frontend and backend. No external files needed!
            </p>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('addSubscriptionForm');
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');
            const loading = document.getElementById('loading');
            
            // User type card selection
            document.querySelectorAll('.type-card').forEach(card => {
                card.addEventListener('click', function() {
                    const type = this.dataset.type;
                    document.getElementById('userType').value = type;
                    
                    // Update active state
                    document.querySelectorAll('.type-card').forEach(c => {
                        c.classList.remove('active');
                    });
                    this.classList.add('active');
                    
                    updatePreview();
                });
            });
            
            // Price calculations
            const monthlyInput = document.getElementById('priceMonthly');
            const yearlyInput = document.getElementById('priceYearly');
            const monthlyDisplay = document.getElementById('monthlyDisplay');
            const yearlyDisplay = document.getElementById('yearlyDisplay');
            const savingsPercent = document.getElementById('savingsPercent');
            
            function calculateSavings() {
                const monthly = parseFloat(monthlyInput.value) || 0;
                const yearly = parseFloat(yearlyInput.value) || 0;
                
                // Update displays
                monthlyDisplay.textContent = `$${monthly.toFixed(2)}`;
                yearlyDisplay.textContent = `$${yearly.toFixed(2)}`;
                
                // Calculate savings percentage
                if (monthly > 0 && yearly > 0) {
                    const yearlyFromMonthly = monthly * 12;
                    const savings = ((yearlyFromMonthly - yearly) / yearlyFromMonthly) * 100;
                    savingsPercent.textContent = `${Math.max(0, savings).toFixed(1)}%`;
                } else {
                    savingsPercent.textContent = '0%';
                }
                
                updatePreview();
            }
            
            monthlyInput.addEventListener('input', calculateSavings);
            yearlyInput.addEventListener('input', calculateSavings);
            
            // Update preview
            function updatePreview() {
                document.getElementById('previewName').textContent = document.getElementById('planName').value || '-';
                document.getElementById('previewType').textContent = document.getElementById('userType').value || '-';
                document.getElementById('previewMonthly').textContent = `$${(parseFloat(monthlyInput.value) || 0).toFixed(2)}`;
                document.getElementById('previewYearly').textContent = `$${(parseFloat(yearlyInput.value) || 0).toFixed(2)}`;
                document.getElementById('previewDuration').textContent = `${document.getElementById('durationDays').value || 0} days`;
                document.getElementById('previewQuality').textContent = document.getElementById('maxQuality').value || '-';
                
                // Update selected features
                const selectedFeatures = Array.from(document.querySelectorAll('input[name="features[]"]:checked'))
                    .map(cb => cb.nextElementSibling.textContent);
                document.getElementById('previewFeatures').textContent = selectedFeatures.join(', ') || '-';
            }
            
            // Update preview on input
            document.querySelectorAll('#planName, #userType, #durationDays, #maxQuality, input[name="features[]"]').forEach(input => {
                input.addEventListener('input', updatePreview);
                input.addEventListener('change', updatePreview);
            });
            
            // Form submission
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
                    
                    // Send to current page (self-processing)
                    const controller = new AbortController();
                    const timeoutId = setTimeout(() => controller.abort(), 30000);
                    
                    const response = await fetch(window.location.href, {
                        method: 'POST',
                        body: formData,
                        signal: controller.signal
                    });
                    
                    clearTimeout(timeoutId);
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
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
                        document.querySelectorAll('.type-card').forEach(c => {
                            c.classList.remove('active');
                        });
                        updatePreview();
                        
                        // Show database instructions
                        setTimeout(() => {
                            showMessage('success', `
                                <strong>✅ Subscription Plan Created!</strong><br><br>
                                <strong>Database Information:</strong><br>
                                • Plan ID: <code>${result.subscription_id}</code><br>
                                • User Type: <code>${result.subscription_type}</code><br>
                                • Table: <code>subscriptions</code><br><br>
                                <strong>Next Steps:</strong><br>
                                1. Users can now subscribe to this plan<br>
                                2. Their user type will be updated automatically<br>
                                3. Manage plans from Subscription Management Panel
                            `);
                        }, 100);
                    } else {
                        showMessage('error', result.message);
                    }
                    
                } catch (error) {
                    loading.style.display = 'none';
                    
                    let errorMsg = '❌ Error: ';
                    if (error.name === 'AbortError') {
                        errorMsg += 'Request timeout. Please try again.';
                    } else if (error.message.includes('HTTP error')) {
                        errorMsg += 'Server error. Please check your web server is running.';
                    } else if (error.message.includes('non-JSON')) {
                        errorMsg += 'Invalid server response. Please check for PHP errors.';
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
                document.querySelectorAll('.type-card').forEach(c => {
                    c.classList.remove('active');
                });
                updatePreview();
            });
            
            // Validation
            function validateForm() {
                let isValid = true;
                const requiredFields = form.querySelectorAll('[required]');
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        showFieldError(field, 'This field is required');
                        isValid = false;
                    } else {
                        clearFieldError(field);
                    }
                });
                
                // Validate prices
                const monthlyPrice = parseFloat(monthlyInput.value);
                const yearlyPrice = parseFloat(yearlyInput.value);
                
                if (monthlyPrice < 0) {
                    showFieldError(monthlyInput, 'Price cannot be negative');
                    isValid = false;
                }
                
                if (yearlyPrice < 0) {
                    showFieldError(yearlyInput, 'Price cannot be negative');
                    isValid = false;
                }
                
                return isValid;
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
                
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                setTimeout(() => {
                    successMessage.style.display = 'none';
                    errorMessage.style.display = 'none';
                }, 10000);
            }
            
            // Initialize preview
            calculateSavings();
            updatePreview();
        });
    </script>
</body>
</html>