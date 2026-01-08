<?php
// library/setting_Backend.php
session_start();
require_once '../include/connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../pages/login.php');
    exit();
}

// Debug: Log request
error_log("=== SETTINGS BACKEND STARTED ===");
error_log("Request Method: " . $_SERVER['REQUEST_METHOD']);
error_log("POST Data: " . print_r($_POST, true));

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error_message'] = "Invalid request method! Expected POST but got " . $_SERVER['REQUEST_METHOD'];
    error_log("Invalid request method");
    header('Location: ../pages/setting.php');
    exit();
}

// Check if save_settings is set
if (!isset($_POST['save_settings'])) {
    $_SESSION['error_message'] = "Save action not specified!";
    error_log("Save settings not set in POST");
    header('Location: ../pages/setting.php');
    exit();
}

// Get settings from POST
$settings = $_POST['settings'] ?? [];

if (empty($settings)) {
    $_SESSION['error_message'] = "No settings data received!";
    error_log("Empty settings array");
    header('Location: ../pages/setting.php');
    exit();
}

// Log each setting
foreach ($settings as $key => $value) {
    error_log("Setting received - $key: $value");
}

try {
    // Check database connection
    if (!$pdo) {
        throw new Exception("Database connection failed!");
    }
    
    // Start transaction
    $pdo->beginTransaction();
    error_log("Transaction started");
    
    $successCount = 0;
    $errorCount = 0;
    
    foreach ($settings as $key => $value) {
        try {
            // Clean the key
            $cleanKey = preg_replace('/[^a-zA-Z0-9_]/', '', $key);
            
            // Check if setting exists
            $checkSql = "SELECT COUNT(*) as count FROM admin_settings WHERE setting_key = ?";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->execute([$cleanKey]);
            $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
            $exists = ($result['count'] > 0);
            
            if ($exists) {
                // Update existing setting
                $updateSql = "UPDATE admin_settings SET setting_value = ?, updated_at = NOW() WHERE setting_key = ?";
                $updateStmt = $pdo->prepare($updateSql);
                $updateStmt->execute([$value, $cleanKey]);
                $affected = $updateStmt->rowCount();
                
                if ($affected > 0) {
                    error_log("✓ Updated: $cleanKey = $value");
                    $successCount++;
                } else {
                    error_log("⚠ No rows updated for: $cleanKey");
                    $errorCount++;
                }
            } else {
                // Insert new setting
                $insertSql = "INSERT INTO admin_settings (setting_key, setting_value, updated_at) VALUES (?, ?, NOW())";
                $insertStmt = $pdo->prepare($insertSql);
                $insertStmt->execute([$cleanKey, $value]);
                
                error_log("✓ Inserted: $cleanKey = $value");
                $successCount++;
            }
            
        } catch (PDOException $e) {
            error_log("❌ Error processing $key: " . $e->getMessage());
            $errorCount++;
            // Continue with next setting
            continue;
        }
    }
    
    // Commit transaction
    $pdo->commit();
    error_log("Transaction committed successfully");
    
    // Update session theme
    if (isset($settings['theme_mode'])) {
        $_SESSION['theme_mode'] = $settings['theme_mode'];
        error_log("Session theme updated to: " . $settings['theme_mode']);
    }
    
    // Set success message
    $_SESSION['success_message'] = "Settings saved successfully! ($successCount updated, $errorCount errors)";
    error_log("=== SETTINGS SAVED: $successCount successful, $errorCount errors ===");
    
} catch (Exception $e) {
    // Rollback on error
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
        error_log("Transaction rolled back");
    }
    
    $_SESSION['error_message'] = "Error saving settings: " . $e->getMessage();
    error_log("❌ CRITICAL ERROR: " . $e->getMessage());
}

// Redirect back
header('Location: ../pages/setting.php');
exit();
?>