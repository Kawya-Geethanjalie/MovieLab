<?php
// Output buffering ආරම්භ කිරීම (JSON response එක පිරිසිදුව යැවීමට)
ob_start();
include("../library/connection.php");
session_start();

// Debug mode - සජීවීව පවතින විට false කරන්න
define('DEBUG', true);
if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
}

// Upload සීමාවන් (ඔබේ add-content.php සැකසුම් අනුව)
ini_set('upload_max_filesize', '20M');
ini_set('post_max_size', '25M');
ini_set('max_execution_time', '300');

// JSON පිළිතුරු ලබාදෙන ශ්‍රිතය
function sendJsonResponse($success, $message, $data = []) {
    if (ob_get_length()) ob_clean();
    http_response_code($success ? 200 : 400);
    header('Content-Type: application/json');
    $response = ['success' => $success, 'message' => $message];
    if (!empty($data)) { $response['data'] = $data; }
    echo json_encode($response);
    exit();
}

// Admin පරීක්ෂාව
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    sendJsonResponse(false, 'Unauthorized access.');
}

// Database Connection
$connectionPath = dirname(__DIR__) . '/include/connection.php';
require_once $connectionPath;

if (!isset($pdo)) {
    sendJsonResponse(false, 'Database connection failed');
}

// POST Request එක පරීක්ෂා කිරීම
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. SEARCH LOGIC (මෙය ඔබගේ Search එක සඳහාය)
    if (isset($_POST['action']) && $_POST['action'] === 'fetch_or_search') {
        try {
            $search = isset($_POST['search_query']) ? trim($_POST['search_query']) : '';
            $searchTerm = "%$search%";

            // Genre ලැයිස්තුව ලබා ගැනීම
            $genreQuery = "SELECT DISTINCT genre FROM (SELECT genre FROM movies UNION SELECT genre FROM songs) as g WHERE genre IS NOT NULL AND genre != ''";
            $genres = $pdo->query($genreQuery)->fetchAll(PDO::FETCH_COLUMN);

            // Movies සහ Songs දෙකම UNION කර සෙවීම
            $sql = "SELECT id, title, genre, duration, view_count as views, description, poster_image as image, 'movie' as type, created_at FROM movies
                    WHERE title LIKE :search OR genre LIKE :search
                    UNION ALL
                    SELECT id, title, genre, duration, 0 as views, artist as description, cover_image as image, 'song' as type, created_at FROM songs
                    WHERE title LIKE :search OR genre LIKE :search OR artist LIKE :search
                    ORDER BY created_at DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['search' => $searchTerm]);
            $content = $stmt->fetchAll(PDO::FETCH_ASSOC);

            sendJsonResponse(true, 'Data fetched successfully', [
                'content' => $content,
                'genres' => $genres
            ]);
        } catch (PDOException $e) {
            sendJsonResponse(false, 'Fetch error: ' . $e->getMessage());
        }
    }

    // 2. ADD CONTENT LOGIC (ඔබේ පැරණි කේතය)
    $contentType = $_POST['content_type'] ?? '';
    if (!empty($contentType)) {
        if ($contentType === 'movie') {
            $res = handleMovieSubmission($pdo);
            sendJsonResponse($res['success'], $res['message']);
        } elseif ($contentType === 'song') {
            $res = handleSongSubmission($pdo);
            sendJsonResponse($res['success'], $res['message']);
        }
    }
}

// --- Helper Functions (ඔබේ add-content-backend.php ගොනුවේ තිබූ ඒවා) ---

function handleMovieSubmission($pdo) {
    if (empty($_POST['title'])) return ['success' => false, 'message' => 'Title is required'];
    
    $poster_path = null;
    if (isset($_FILES['poster_image']) && $_FILES['poster_image']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['poster_image'], 'movies', ['jpg', 'jpeg', 'png', 'webp']);
        if ($upload['success']) $poster_path = $upload['path'];
    }

    // අලුතින් එක් කළ Language අගය ලබා ගැනීම
    $language = isset($_POST['language']) ? trim($_POST['language']) : '';
    $play_url = isset($_POST['play_url']) ? trim($_POST['play_url']) : '';
    $download_url = isset($_POST['download_url']) ? trim($_POST['download_url']) : '';

    // SQL එකට 'language' තීරුව සහ අගය ඇතුළත් කර ඇත
    $sql = "INSERT INTO movies (title, description, release_year, genre, rating, duration, language, poster_image, trailer_url, play_url, download_url, view_count, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, NOW())";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['title'],
        $_POST['description'] ?? '',
        $_POST['release_year'] ?? 0,
        $_POST['genre'],
        $_POST['rating'] ?? null,
        $_POST['duration'] ?? 0,
        $language,           // නව language අගය මෙතැනට
        $poster_path,
        $_POST['trailer_url'] ?? '',
        $play_url,           // play_url
        $download_url        // download_url
    ]);
    
    return ['success' => true, 'message' => 'Movie added successfully with Language!'];
}

function handleSongSubmission($pdo) {
    if (empty($_POST['title'])) return ['success' => false, 'message' => 'Title is required'];

    $cover_path = null;
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['cover_image'], 'songs', ['jpg', 'jpeg', 'png', 'webp']);
        if ($upload['success']) $cover_path = $upload['path'];
    }

    $audio_path = null;
    if (isset($_FILES['audio_file']) && $_FILES['audio_file']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['audio_file'], 'songs/audio', ['mp3', 'wav', 'm4a'], 20 * 1024 * 1024);
        if ($upload['success']) $audio_path = $upload['path'];
    }

    // Get play_url and download_url from POST data
    

    // FIXED SQL: Added play_url and download_url fields
    $sql = "INSERT INTO songs (title, artist, album, genre, duration, language, cover_image, audio_file, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['title'], 
        $_POST['artist'], 
        $_POST['album'] ?? '', 
        $_POST['genre'],
        $_POST['duration'] ?? 0, 
        $_POST['language'] ?? '', 
        $cover_path, 
        $audio_path,
           // download_url added
    ]);
    
    return ['success' => true, 'message' => 'Song added successfully with URLs!'];
}

function uploadFile($file, $subfolder, $allowedExts, $maxSize = 5242880) {
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExt, $allowedExts)) return ['success' => false, 'message' => 'Invalid type'];
    
    $newFileName = uniqid('', true) . '.' . $fileExt;
    $uploadDir = __DIR__ . "/../uploads/$subfolder/";
    if (!file_exists($uploadDir)) mkdir($uploadDir, 0755, true);
    
    if (move_uploaded_file($file['tmp_name'], $uploadDir . $newFileName)) {
        return ['success' => true, 'path' => "uploads/$subfolder/$newFileName"];
    }
    return ['success' => false, 'message' => 'Upload failed'];
}
?>