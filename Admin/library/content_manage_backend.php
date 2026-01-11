<?php
session_start();
require_once '../include/connection.php';
header('Content-Type: application/json');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Validate CSRF token for POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
        exit();
    }
}

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$action = $_GET['action'] ?? 'fetch';

try {
    if ($action === 'update') {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new Exception("Invalid request method");
        }

        // Validate required fields
        $required = ['id', 'type', 'title', 'genre'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $genre = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_STRING);

        if (!$id || $id < 1) {
            throw new Exception("Invalid ID");
        }

        if ($type === 'movie') {
            // Validate movie specific fields
            $duration = filter_input(INPUT_POST, 'duration', FILTER_SANITIZE_STRING);
            $rating = filter_input(INPUT_POST, 'rating', FILTER_SANITIZE_STRING);
            $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
            $desc = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
            $trailer_url = filter_input(INPUT_POST, 'trailer_url', FILTER_VALIDATE_URL);
           $languagem = filter_input(INPUT_POST, 'languagem', FILTER_SANITIZE_STRING);

            // NEW: Get play_url and download_url - allow empty values
            $play_url = filter_input(INPUT_POST, 'play_url', FILTER_SANITIZE_STRING);
            $download_url = filter_input(INPUT_POST, 'download_url', FILTER_SANITIZE_STRING);

            if (!$duration || !$rating || !$year || !$desc) {
                throw new Exception("Missing required movie fields");
            }

            // Handle image upload
            $imagePath = null;
            if (isset($_FILES['poster_image']) && $_FILES['poster_image']['error'] === UPLOAD_ERR_OK) {
                // Validate file
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $fileType = mime_content_type($_FILES['poster_image']['tmp_name']);
                
                if (!in_array($fileType, $allowedTypes)) {
                    throw new Exception("Invalid image type. Only JPG, PNG, GIF, and WEBP are allowed.");
                }
                
                // Check file size (max 5MB)
                if ($_FILES['poster_image']['size'] > 5 * 1024 * 1024) {
                    throw new Exception("Image file is too large. Maximum size is 5MB.");
                }
                
                $targetDir = "../uploads/movies/";
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                
                $extension = pathinfo($_FILES['poster_image']['name'], PATHINFO_EXTENSION);
                $fileName = time() . "_" . uniqid() . "." . $extension;
                $targetFile = $targetDir . $fileName;
                
                if (move_uploaded_file($_FILES['poster_image']['tmp_name'], $targetFile)) {
                    $imagePath = "uploads/movies/" . $fileName;
                    
                    // Delete old image if exists
                    $stmt = $pdo->prepare("SELECT poster_image FROM movies WHERE movie_id = ?");
                    $stmt->execute([$id]);
                    $oldImage = $stmt->fetchColumn();
                    if ($oldImage && file_exists("../" . $oldImage)) {
                        unlink("../" . $oldImage);
                    }
                } else {
                    throw new Exception("Failed to upload image");
                }
            }

            // Update movie in database - NOW INCLUDES play_url and download_url
            $sql = "UPDATE movies SET title = ?, genre = ?, duration = ?, rating = ?, release_year = ?, 
                    description = ?, trailer_url = ?, play_url = ?, download_url = ?,language = ? ";
            $params = [$title, $genre, $duration, $rating, $year, $desc, $trailer_url, $play_url, $download_url,$languagem,];
            
            if ($imagePath) {
                $sql .= ", poster_image = ?";
                $params[] = $imagePath;
            }
            
            $sql .= " WHERE movie_id = ?";
            $params[] = $id;
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            // if ($stmt->rowCount() === 0) {
            //     throw new Exception("No movie found with the specified ID");
            // }

        } elseif ($type === 'song') {
            // Validate song specific fields
            $artist = filter_input(INPUT_POST, 'artist', FILTER_SANITIZE_STRING);
            $album = filter_input(INPUT_POST, 'album', FILTER_SANITIZE_STRING);
            $duration = filter_input(INPUT_POST, 'duration_sec', FILTER_VALIDATE_INT);
            $language = filter_input(INPUT_POST, 'language', FILTER_SANITIZE_STRING);

            if (!$artist || !$duration || !$language) {
                throw new Exception("Missing required song fields");
            }

            // Handle cover image upload
            $imagePath = null;
            if (isset($_FILES['poster_image']) && $_FILES['poster_image']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $fileType = mime_content_type($_FILES['poster_image']['tmp_name']);
                
                if (!in_array($fileType, $allowedTypes)) {
                    throw new Exception("Invalid image type. Only JPG, PNG, GIF, and WEBP are allowed.");
                }
                
                if ($_FILES['poster_image']['size'] > 5 * 1024 * 1024) {
                    throw new Exception("Image file is too large. Maximum size is 5MB.");
                }
                
                $targetDir = "../uploads/songs/covers/";
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                
                $extension = pathinfo($_FILES['poster_image']['name'], PATHINFO_EXTENSION);
                $fileName = time() . "_" . uniqid() . "." . $extension;
                $targetFile = $targetDir . $fileName;
                
                if (move_uploaded_file($_FILES['poster_image']['tmp_name'], $targetFile)) {
                    $imagePath = "uploads/songs/covers/" . $fileName;
                    
                    // Delete old image
                    $stmt = $pdo->prepare("SELECT cover_image FROM songs WHERE song_id = ?");
                    $stmt->execute([$id]);
                    $oldImage = $stmt->fetchColumn();
                    if ($oldImage && file_exists("../" . $oldImage)) {
                        unlink("../" . $oldImage);
                    }
                } else {
                    throw new Exception("Failed to upload cover image");
                }
            }

            // Handle audio file upload
            $audioPath = null;
            if (isset($_FILES['audio_file']) && $_FILES['audio_file']['error'] === UPLOAD_ERR_OK) {
                $allowedAudioTypes = ['audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/ogg'];
                $audioType = mime_content_type($_FILES['audio_file']['tmp_name']);
                
                if (!in_array($audioType, $allowedAudioTypes)) {
                    throw new Exception("Invalid audio type. Only MP3, WAV, and OGG are allowed.");
                }
                
                if ($_FILES['audio_file']['size'] > 20 * 1024 * 1024) {
                    throw new Exception("Audio file is too large. Maximum size is 20MB.");
                }
                
                $targetDir = "../uploads/songs/audio/";
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                
                $extension = pathinfo($_FILES['audio_file']['name'], PATHINFO_EXTENSION);
                $fileName = time() . "_" . uniqid() . "." . $extension;
                $targetFile = $targetDir . $fileName;
                
                if (move_uploaded_file($_FILES['audio_file']['tmp_name'], $targetFile)) {
                    $audioPath = "uploads/songs/audio/" . $fileName;
                    
                    // Delete old audio file
                    $stmt = $pdo->prepare("SELECT audio_file FROM songs WHERE song_id = ?");
                    $stmt->execute([$id]);
                    $oldAudio = $stmt->fetchColumn();
                    if ($oldAudio && file_exists("../" . $oldAudio)) {
                        unlink("../" . $oldAudio);
                    }
                } else {
                    throw new Exception("Failed to upload audio file");
                }
            }

            // Update song in database
            $sql = "UPDATE songs SET title = ?, artist = ?, album = ?, genre = ?, duration = ?, language = ?";
            $params = [$title, $artist, $album, $genre, $duration, $language];

            if ($imagePath) { 
                $sql .= ", cover_image = ?"; 
                $params[] = $imagePath; 
            }
            
            if ($audioPath) { 
                $sql .= ", audio_file = ?"; 
                $params[] = $audioPath; 
            }
            
            $sql .= " WHERE song_id = ?";
            $params[] = $id;

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            if ($stmt->rowCount() === 0) {
                throw new Exception("No song found with the specified ID");
            }
        } else {
            throw new Exception("Invalid content type");
        }

        echo json_encode([
            'success' => true, 
            'message' => ucfirst($type) . ' updated successfully',
            'csrf_token' => $_SESSION['csrf_token']
        ]);
        exit();

    } elseif ($action === 'delete') {
        // Delete content
        if (!isset($_GET['id']) || !isset($_GET['type'])) {
            throw new Exception("Missing ID or type for deletion");
        }
        
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_STRING);
        
        if ($type === 'movie') {
            // Get movie info to delete files
            $stmt = $pdo->prepare("SELECT poster_image FROM movies WHERE movie_id = ?");
            $stmt->execute([$id]);
            $movie = $stmt->fetch();
            
            if ($movie && $movie['poster_image'] && file_exists("../" . $movie['poster_image'])) {
                unlink("../" . $movie['poster_image']);
            }
            
            $stmt = $pdo->prepare("DELETE FROM movies WHERE movie_id = ?");
            $stmt->execute([$id]);
        } elseif ($type === 'song') {
            // Get song info to delete files
            $stmt = $pdo->prepare("SELECT cover_image, audio_file FROM songs WHERE song_id = ?");
            $stmt->execute([$id]);
            $song = $stmt->fetch();
            
            if ($song) {
                if ($song['cover_image'] && file_exists("../" . $song['cover_image'])) {
                    unlink("../" . $song['cover_image']);
                }
                if ($song['audio_file'] && file_exists("../" . $song['audio_file'])) {
                    unlink("../" . $song['audio_file']);
                }
            }
            
            $stmt = $pdo->prepare("DELETE FROM songs WHERE song_id = ?");
            $stmt->execute([$id]);
        } else {
            throw new Exception("Invalid content type for deletion");
        }
        
        echo json_encode([
            'success' => true, 
            'message' => ucfirst($type) . ' deleted successfully',
            'csrf_token' => $_SESSION['csrf_token']
        ]);
        exit();
    }

    // Fetch all content - NOW INCLUDES play_url and download_url for movies
// content_manage_backend.php හි පහළම ඇති කොටස
$moviesQuery = "SELECT movie_id as id, title, genre, duration, language, rating, release_year as year, 
               description, poster_image, trailer_url, play_url, download_url, 'movie' as type 
               FROM movies ORDER BY title";
    $moviesStmt = $pdo->prepare($moviesQuery);
    $moviesStmt->execute();
    $movies = $moviesStmt->fetchAll(PDO::FETCH_ASSOC);

    $songsQuery = "SELECT song_id as id, title, artist, album, genre, duration, language, 
                  cover_image as poster_image, audio_file, 'song' as type FROM songs ORDER BY title";
    $songsStmt = $pdo->prepare($songsQuery);
    $songsStmt->execute();
    $songs = $songsStmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true, 
        'content' => array_merge($movies, $songs),
        'csrf_token' => $_SESSION['csrf_token']
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage(),
        'csrf_token' => $_SESSION['csrf_token'] ?? ''
    ]);
}
?>