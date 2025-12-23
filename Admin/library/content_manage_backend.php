<?php 
session_start();
require_once '../include/connection.php';
header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$action = $_GET['action'] ?? 'fetch';

try {
    if ($action === 'update') {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new Exception("Invalid request method");
        }

        $id = $_POST['id'];
        $type = $_POST['type'];
        $title = $_POST['title'];
        $genre = $_POST['genre'];

        if ($type === 'movie') {
            $duration = $_POST['duration'];
            $rating = $_POST['rating'];
            $year = $_POST['year'];
            $desc = $_POST['description'];
            $trailer_url = $_POST['trailer_url']; // Trailer URL එක ලබා ගැනීම

            $imagePath = null;
            if (isset($_FILES['poster_image']) && $_FILES['poster_image']['error'] === 0) {
                $targetDir = "../uploads/movies/";
                if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
                $fileName = time() . "_" . basename($_FILES['poster_image']['name']);
                move_uploaded_file($_FILES['poster_image']['tmp_name'], $targetDir . $fileName);
                $imagePath = "uploads/movies/" . $fileName;
            }

            // SQL Query එකට trailer_url එක එකතු කිරීම
            $sql = "UPDATE movies SET title=?, genre=?, duration=?, rating=?, release_year=?, description=?, trailer_url=?";
            $params = [$title, $genre, $duration, $rating, $year, $desc, $trailer_url];
            
            if ($imagePath) {
                $sql .= ", poster_image=?";
                $params[] = $imagePath;
            }
            $sql .= " WHERE movie_id=?";
            $params[] = $id;
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

        } else { 
            // Songs update logic (පැරණි පරිදිම)
            $artist = $_POST['artist'];
            $album = $_POST['album'];
            $duration = $_POST['duration_sec'];
            $language = $_POST['language'];

            $imagePath = null;
            if (isset($_FILES['poster_image']) && $_FILES['poster_image']['error'] === 0) {
                $targetDir = "../uploads/songs/covers/";
                if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
                $fileName = time() . "_" . basename($_FILES['poster_image']['name']);
                move_uploaded_file($_FILES['poster_image']['tmp_name'], $targetDir . $fileName);
                $imagePath = "uploads/songs/covers/" . $fileName;
            }

            $audioPath = null;
            if (isset($_FILES['audio_file']) && $_FILES['audio_file']['error'] === 0) {
                $targetDir = "../uploads/songs/audio/";
                if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
                $fileName = time() . "_" . basename($_FILES['audio_file']['name']);
                move_uploaded_file($_FILES['audio_file']['tmp_name'], $targetDir . $fileName);
                $audioPath = "uploads/songs/audio/" . $fileName;
            }

            $sql = "UPDATE songs SET title=?, artist=?, album=?, genre=?, duration=?, language=?";
            $params = [$title, $artist, $album, $genre, $duration, $language];

            if ($imagePath) { $sql .= ", cover_image=?"; $params[] = $imagePath; }
            if ($audioPath) { $sql .= ", audio_file=?"; $params[] = $audioPath; }
            $sql .= " WHERE song_id=?";
            $params[] = $id;

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
        }

        echo json_encode(['success' => true, 'message' => 'Updated successfully']);
        exit();
    }

    // Fetch logic (පැරණි පරිදිම)
    $moviesQuery = "SELECT *, 'movie' as type FROM movies";
    $moviesStmt = $pdo->prepare($moviesQuery);
    $moviesStmt->execute();
    $movies = $moviesStmt->fetchAll(PDO::FETCH_ASSOC);

    $songsQuery = "SELECT *, 'song' as type FROM songs";
    $songsStmt = $pdo->prepare($songsQuery);
    $songsStmt->execute();
    $songs = $songsStmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'content' => array_merge($movies, $songs)]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>