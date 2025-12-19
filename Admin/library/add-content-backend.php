<?php
// Start output buffering
ob_start();
include("../library/connection.php");
session_start();

// Debug mode - set to false in production
define('DEBUG', true);
if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 0); // Keep errors from displaying to browser
    ini_set('log_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Increase upload limits
ini_set('upload_max_filesize', '20M');
ini_set('post_max_size', '25M');
ini_set('max_execution_time', '300');
ini_set('max_input_time', '300');

// Helper function for JSON responses
function sendJsonResponse($success, $message, $data = []) {
    // Clear any previous output
    if (ob_get_length()) ob_clean();
    
    http_response_code($success ? 200 : 400);
    header('Content-Type: application/json');
    
    $response = [
        'success' => $success,
        'message' => $message
    ];
    
    if (!empty($data)) {
        $response['data'] = $data;
    }
    
    echo json_encode($response);
    exit();
}

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    sendJsonResponse(false, 'Unauthorized access. Please login.');
}

// Session timeout (30 minutes)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    sendJsonResponse(false, 'Session expired. Please login again.');
}
$_SESSION['last_activity'] = time();

// Database connection
$connectionPath = dirname(__DIR__) . '/include/connection.php';
if (!file_exists($connectionPath)) {
    error_log('Connection file not found at: ' . $connectionPath);
    sendJsonResponse(false, 'Server configuration error.');
}

require_once $connectionPath;

// Check database connection
if (!isset($pdo)) {
    sendJsonResponse(false, 'Database connection failed');
}

// Set response header
header('Content-Type: application/json');

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Invalid request method. Use POST.');
}

// Log received data for debugging
if (DEBUG) {
    error_log('=== DEBUG INFO ===');
    error_log('POST data: ' . print_r($_POST, true));
    error_log('FILES data count: ' . count($_FILES));
    foreach ($_FILES as $key => $file) {
        error_log("File {$key}: {$file['name']} ({$file['size']} bytes, error: {$file['error']})");
    }
    error_log('Content-Type header: ' . ($_SERVER['CONTENT_TYPE'] ?? 'Not set'));
    error_log('==================');
}

// Check if data is received
if (empty($_POST) && empty($_FILES)) {
    error_log('WARNING: No POST or FILES data received');
    sendJsonResponse(false, 'No data received. Please fill in the form and try again.');
}

// Get content type
$contentType = $_POST['content_type'] ?? '';
if (empty($contentType)) {
    sendJsonResponse(false, 'Content type is required. Specify "movie" or "song".');
}

// Initialize response
$response = [
    'success' => false,
    'message' => ''
];

try {
    if ($contentType === 'movie') {
        $response = handleMovieSubmission($pdo);
    } elseif ($contentType === 'song') {
        $response = handleSongSubmission($pdo);
    } else {
        sendJsonResponse(false, 'Invalid content type. Please specify movie or song.');
    }
} catch (Exception $e) {
    error_log('Uncaught Exception in add-content.php:');
    error_log('Message: ' . $e->getMessage());
    error_log('File: ' . $e->getFile());
    error_log('Line: ' . $e->getLine());
    error_log('Trace: ' . $e->getTraceAsString());
    
    sendJsonResponse(false, DEBUG ? 'Server error: ' . $e->getMessage() : 'An unexpected error occurred. Please try again.');
}

// Clean output buffer and send response
if (ob_get_length()) ob_clean();
echo json_encode($response);
exit();

// Handle movie submission
function handleMovieSubmission($pdo) {
    // Validate required fields
    $requiredFields = ['title', 'release_year', 'genre', 'duration'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            return [
                'success' => false,
                'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required'
            ];
        }
    }
    
    // Sanitize and validate data
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $release_year = intval($_POST['release_year'] ?? 0);
    $genre = trim($_POST['genre'] ?? '');
    $rating = !empty($_POST['rating']) ? floatval($_POST['rating']) : null;
    $duration = intval($_POST['duration'] ?? 0);
    $trailer_url = trim($_POST['trailer_url'] ?? '');
    
    // Clean trailer URL (fix the &l issue from screenshot)
    if (!empty($trailer_url)) {
        $trailer_url = rtrim($trailer_url, "&l");
        $trailer_url = filter_var($trailer_url, FILTER_SANITIZE_URL);
    }
    
    // Validate title
    if (empty($title) || strlen($title) > 255) {
        return [
            'success' => false,
            'message' => 'Title must be between 1 and 255 characters'
        ];
    }
    
    // Validate year
    $current_year = date('Y');
    if ($release_year < 1900 || $release_year > $current_year + 5) {
        return [
            'success' => false,
            'message' => "Please enter a valid release year (1900-" . ($current_year + 5) . ")"
        ];
    }
    
    // Validate duration (in minutes)
    if ($duration < 1 || $duration > 500) {
        return [
            'success' => false,
            'message' => 'Please enter a valid duration (1-500 minutes)'
        ];
    }
    
    // Validate rating
    if ($rating !== null && ($rating < 0 || $rating > 10)) {
        return [
            'success' => false,
            'message' => 'Rating must be between 0 and 10'
        ];
    }
    
    // Validate trailer URL format
    if (!empty($trailer_url) && !filter_var($trailer_url, FILTER_VALIDATE_URL)) {
        return [
            'success' => false,
            'message' => 'Please enter a valid trailer URL'
        ];
    }
    
    // Handle poster image upload
    $poster_image_path = null;
    if (isset($_FILES['poster_image']) && $_FILES['poster_image']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = uploadFile($_FILES['poster_image'], 'movies', ['jpg', 'jpeg', 'png', 'gif', 'webp'], 5 * 1024 * 1024);
        
        if ($uploadResult['success']) {
            // Check image dimensions
            $image_info = @getimagesize($uploadResult['full_path']);
            if ($image_info && ($image_info[0] > 1024 || $image_info[1] > 1024)) {
                @unlink($uploadResult['full_path']);
                return [
                    'success' => false,
                    'message' => 'Image dimensions must be 1024x1024 pixels or smaller'
                ];
            }
            $poster_image_path = $uploadResult['path'];
        } else {
            return [
                'success' => false,
                'message' => 'Error uploading poster image: ' . $uploadResult['message']
            ];
        }
    } elseif (isset($_FILES['poster_image'])) {
        // File upload error occurred
        $errorCode = $_FILES['poster_image']['error'];
        if ($errorCode !== UPLOAD_ERR_NO_FILE) {
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE => 'File exceeds server upload limit',
                UPLOAD_ERR_FORM_SIZE => 'File exceeds form upload limit',
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
            ];
            return [
                'success' => false,
                'message' => 'File upload error: ' . ($errorMessages[$errorCode] ?? 'Unknown error')
            ];
        }
    }
    
    // Prepare SQL statement
    $sql = "INSERT INTO movies (
                title, 
                description, 
                release_year, 
                genre, 
                rating, 
                duration, 
                poster_image, 
                trailer_url,
                view_count,
                created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    try {
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $title,
            $description,
            $release_year,
            $genre,
            $rating,
            $duration,
            $poster_image_path,
            $trailer_url,
            0
        ]);
        
        if ($result && $stmt->rowCount() > 0) {
            $movie_id = $pdo->lastInsertId();
            
            // Log activity if user_id exists in session
            if (isset($_SESSION['user_id'])) {
                logUserActivity($pdo, $_SESSION['user_id'], 'add_movie', "Added movie: $title (ID: $movie_id)");
            }
            
            // Log success
            error_log("Movie added successfully: $title (ID: $movie_id)");
            
            return [
                'success' => true,
                'message' => 'Movie added successfully!',
                'movie_id' => $movie_id
            ];
        } else {
            error_log('Failed to execute movie insert statement');
            return [
                'success' => false,
                'message' => 'Failed to add movie to database.'
            ];
        }
    } catch (PDOException $e) {
        error_log('Database error in handleMovieSubmission: ' . $e->getMessage());
        error_log('SQL: ' . $sql);
        return [
            'success' => false,
            'message' => 'Database error: Could not add movie. ' . (DEBUG ? $e->getMessage() : '')
        ];
    }
}

// Handle song submission
function handleSongSubmission($pdo) {
    // Validate required fields
    $requiredFields = ['title', 'artist', 'genre', 'duration'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            return [
                'success' => false,
                'message' => ucfirst($field) . ' is required'
            ];
        }
    }
    
    // Sanitize and validate data
    $title = trim($_POST['title'] ?? '');
    $artist = trim($_POST['artist'] ?? '');
    $album = trim($_POST['album'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $duration = intval($_POST['duration'] ?? 0);
    $language = trim($_POST['language'] ?? '');
    
    // Validate title
    if (empty($title) || strlen($title) > 255) {
        return [
            'success' => false,
            'message' => 'Title must be between 1 and 255 characters'
        ];
    }
    
    // Validate artist
    if (empty($artist) || strlen($artist) > 255) {
        return [
            'success' => false,
            'message' => 'Artist name must be between 1 and 255 characters'
        ];
    }
    
    // Validate duration (in seconds)
    if ($duration < 1 || $duration > 1800) {
        return [
            'success' => false,
            'message' => 'Please enter a valid duration (1-1800 seconds)'
        ];
    }
    
    // Handle cover image upload
    $cover_image_path = null;
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = uploadFile($_FILES['cover_image'], 'songs', ['jpg', 'jpeg', 'png', 'gif', 'webp'], 5 * 1024 * 1024);
        
        if ($uploadResult['success']) {
            // Check image dimensions
            $image_info = @getimagesize($uploadResult['full_path']);
            if ($image_info && ($image_info[0] > 1024 || $image_info[1] > 1024)) {
                @unlink($uploadResult['full_path']);
                return [
                    'success' => false,
                    'message' => 'Image dimensions must be 1024x1024 pixels or smaller'
                ];
            }
            $cover_image_path = $uploadResult['path'];
        } else {
            return [
                'success' => false,
                'message' => 'Error uploading cover image: ' . $uploadResult['message']
            ];
        }
    }
    
    // Handle audio file upload
    $audio_file_path = null;
    if (isset($_FILES['audio_file']) && $_FILES['audio_file']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = uploadFile($_FILES['audio_file'], 'songs/audio', ['mp3', 'wav', 'ogg', 'm4a', 'aac', 'flac'], 20 * 1024 * 1024);
        
        if ($uploadResult['success']) {
            $audio_file_path = $uploadResult['path'];
        } else {
            return [
                'success' => false,
                'message' => 'Error uploading audio file: ' . $uploadResult['message']
            ];
        }
    } else {
        // Audio file is required for songs
        return [
            'success' => false,
            'message' => 'Audio file is required for songs'
        ];
    }
    
    // Prepare SQL statement
    $sql = "INSERT INTO songs (
                title, 
                artist, 
                album, 
                genre, 
                duration, 
                language, 
                cover_image, 
                audio_file,
                created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    try {
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $title,
            $artist,
            $album,
            $genre,
            $duration,
            $language,
            $cover_image_path,
            $audio_file_path
        ]);
        
        if ($result && $stmt->rowCount() > 0) {
            $song_id = $pdo->lastInsertId();
            
            // Log activity if user_id exists in session
            if (isset($_SESSION['user_id'])) {
                logUserActivity($pdo, $_SESSION['user_id'], 'add_song', "Added song: $title by $artist (ID: $song_id)");
            }
            
            error_log("Song added successfully: $title by $artist (ID: $song_id)");
            
            return [
                'success' => true,
                'message' => 'Song added successfully!',
                'song_id' => $song_id
            ];
        } else {
            error_log('Failed to execute song insert statement');
            return [
                'success' => false,
                'message' => 'Failed to add song to database.'
            ];
        }
    } catch (PDOException $e) {
        error_log('Database error in handleSongSubmission: ' . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Database error: Could not add song. ' . (DEBUG ? $e->getMessage() : '')
        ];
    }
}

// File upload function
function uploadFile($file, $subfolder = '', $allowedExtensions = [], $maxSize = 5242880) {
    $fileName = basename($file['name']);
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    
    // Check for upload errors
    if ($fileError !== UPLOAD_ERR_OK) {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize directive',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
        ];
        
        return [
            'success' => false,
            'message' => $errorMessages[$fileError] ?? 'Unknown upload error (Code: ' . $fileError . ')'
        ];
    }
    
    // Check if file was actually uploaded
    if (!is_uploaded_file($fileTmpName)) {
        return [
            'success' => false,
            'message' => 'File upload verification failed'
        ];
    }
    
    // Check file size
    if ($fileSize > $maxSize) {
        return [
            'success' => false,
            'message' => 'File is too large. Maximum size: ' . formatBytes($maxSize)
        ];
    }
    
    // Get file extension
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    // Check if extension is allowed
    if (!empty($allowedExtensions) && !in_array($fileExt, $allowedExtensions)) {
        return [
            'success' => false,
            'message' => 'File type not allowed. Allowed types: ' . implode(', ', $allowedExtensions)
        ];
    }
    
    // Generate unique filename
    $newFileName = uniqid('', true) . '_' . time() . '.' . $fileExt;
    
    // Create upload directory
    $uploadDir = __DIR__ . "/../uploads/$subfolder/";
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            error_log('Failed to create upload directory: ' . $uploadDir);
            return [
                'success' => false,
                'message' => 'Failed to create upload directory. Please check server permissions.'
            ];
        }
    }
    
    // Check if directory is writable
    if (!is_writable($uploadDir)) {
        error_log('Upload directory not writable: ' . $uploadDir);
        return [
            'success' => false,
            'message' => 'Upload directory is not writable. Please check server permissions.'
        ];
    }
    
    // Set full upload path
    $uploadPath = $uploadDir . $newFileName;
    
    // Move uploaded file
    if (move_uploaded_file($fileTmpName, $uploadPath)) {
        // Verify file was moved successfully
        if (file_exists($uploadPath)) {
            error_log('File uploaded successfully: ' . $uploadPath . ' (' . formatBytes(filesize($uploadPath)) . ')');
            return [
                'success' => true,
                'path' => "/uploads/$subfolder/" . $newFileName,
                'full_path' => $uploadPath,
                'file_name' => $newFileName
            ];
        } else {
            error_log('File move succeeded but file does not exist at: ' . $uploadPath);
            return [
                'success' => false,
                'message' => 'File upload verification failed after move'
            ];
        }
    } else {
        error_log('Failed to move uploaded file from ' . $fileTmpName . ' to ' . $uploadPath);
        return [
            'success' => false,
            'message' => 'Failed to move uploaded file. Please try again.'
        ];
    }
}

// Format bytes to human readable format
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    
    return round($bytes, $precision) . ' ' . $units[$pow];
}

// Log User activity
function logUserActivity($pdo, $user_id, $activity_type, $description = '') {
    try {
        $sql = "INSERT INTO user_activity (user_id, activity_type, description, ip_address, created_at) 
                VALUES (?, ?, ?, ?, NOW())";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $user_id,
            $activity_type,
            $description,
            $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
        ]);
        
        return true;
    } catch (Exception $e) {
        error_log('Failed to log activity: ' . $e->getMessage());
        return false;
    }
}
?>