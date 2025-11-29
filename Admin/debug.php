<?php
session_start();
echo "<pre>";

echo "=== DEBUG INFORMATION ===\n\n";

// Check session
echo "Session ID: " . session_id() . "\n";
echo "Session data: ";
print_r($_SESSION);

// Check file paths
echo "\n=== FILE PATHS ===\n";
$paths = [
    '../include/connection.php',
    'include/connection.php',
    dirname(__DIR__) . '/include/connection.php'
];

foreach ($paths as $path) {
    echo "$path: " . (file_exists($path) ? "EXISTS" : "NOT FOUND") . "\n";
}

// Test database connection
echo "\n=== DATABASE TEST ===\n";
try {
    require_once dirname(__DIR__) . '/include/connection.php';
    echo "Database connection: SUCCESS\n";
    
    // Test admin user
    $stmt = $pdo->prepare("SELECT user_id, username, user_type FROM users WHERE user_type = 'admin'");
    $stmt->execute();
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Admin users: " . count($admins) . "\n";
    foreach ($admins as $admin) {
        echo "- {$admin['username']} ({$admin['user_type']})\n";
    }
    
} catch (Exception $e) {
    echo "Database connection: FAILED - " . $e->getMessage() . "\n";
}

echo "</pre>";
?>