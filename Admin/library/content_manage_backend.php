<?php 
session_start();

// Database connection
require_once '../include/connection.php'; // Path එක නිවැරදිද බලන්න

header('Content-Type: application/json');

// Check login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$action = $_GET['action'] ?? 'fetch';

try {
    // ---------------------------------------------------------
    // 1. DELETE ACTION
    // ---------------------------------------------------------
    if ($action === 'delete') {
        if (!isset($_GET['id']) || !isset($_GET['type'])) {
            throw new Exception("Missing ID or Type");
        }

        $id = $_GET['id'];
        $type = $_GET['type'];

        if ($type === 'movie') {
            $stmt = $pdo->prepare("DELETE FROM movies WHERE movie_id = ?");
        } else {
            $stmt = $pdo->prepare("DELETE FROM songs WHERE song_id = ?");
        }
        
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'message' => 'Deleted successfully']);
        exit();
    }

    // ---------------------------------------------------------
    // 2. UPDATE ACTION (මෙන්න මේ කොටස ඔයාගේ file එකේ තිබුනේ නෑ)
    // ---------------------------------------------------------
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

            // Handle Image Upload
            $imagePath = null;
            if (isset($_FILES['poster_image']) && $_FILES['poster_image']['error'] === 0) {
                // සරලව ගත්තොත් upload logic එක (ඔයාගේ project folder structure එක අනුව වෙනස් කරගන්න)
                $targetDir = "../uploads/movies/";
                if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
                $fileName = time() . "_" . basename($_FILES['poster_image']['name']);
                move_uploaded_file($_FILES['poster_image']['tmp_name'], $targetDir . $fileName);
                $imagePath = "uploads/movies/" . $fileName;
            }

            // Update Query
            $sql = "UPDATE movies SET title=?, genre=?, duration=?, rating=?, release_year=?, description=?";
            $params = [$title, $genre, $duration, $rating, $year, $desc];
            
            if ($imagePath) {
                $sql .= ", poster_image=?";
                $params[] = $imagePath;
            }
            $sql .= " WHERE movie_id=?";
            $params[] = $id;
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

        } else { // Song
            $artist = $_POST['artist'];
            $album = $_POST['album'];
            $duration = $_POST['duration_sec'];
            $language = $_POST['language'];

            // Handle Image Upload
            $imagePath = null;
            if (isset($_FILES['poster_image']) && $_FILES['poster_image']['error'] === 0) {
                $targetDir = "../uploads/songs/covers/";
                if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
                $fileName = time() . "_" . basename($_FILES['poster_image']['name']);
                move_uploaded_file($_FILES['poster_image']['tmp_name'], $targetDir . $fileName);
                $imagePath = "uploads/songs/covers/" . $fileName;
            }

            // Handle Audio Upload
            $audioPath = null;
            if (isset($_FILES['audio_file']) && $_FILES['audio_file']['error'] === 0) {
                $targetDir = "../uploads/songs/audio/";
                if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
                $fileName = time() . "_" . basename($_FILES['audio_file']['name']);
                move_uploaded_file($_FILES['audio_file']['tmp_name'], $targetDir . $fileName);
                $audioPath = "uploads/songs/audio/" . $fileName;
            }

            // Update Query
            $sql = "UPDATE songs SET title=?, artist=?, album=?, genre=?, duration=?, language=?";
            $params = [$title, $artist, $album, $genre, $duration, $language];

            if ($imagePath) {
                $sql .= ", cover_image=?";
                $params[] = $imagePath;
            }
            if ($audioPath) {
                $sql .= ", audio_file=?";
                $params[] = $audioPath;
            }
            $sql .= " WHERE song_id=?";
            $params[] = $id;

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
        }

        echo json_encode(['success' => true, 'message' => 'Updated successfully']);
        exit();
    }

    // ---------------------------------------------------------
    // 3. FETCH DATA (Search & Filter)
    // ---------------------------------------------------------
    
    // Movies Query (Select ALL necessary columns)
    $moviesQuery = "SELECT *, 'movie' as type FROM movies";
    $moviesStmt = $pdo->prepare($moviesQuery);
    $moviesStmt->execute();
    $movies = $moviesStmt->fetchAll(PDO::FETCH_ASSOC);

    // Songs Query (Select ALL necessary columns - album, language, audio_file ඇතුලුව)
    $songsQuery = "SELECT *, 'song' as type FROM songs";
    $songsStmt = $pdo->prepare($songsQuery);
    $songsStmt->execute();
    $songs = $songsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Combine
    $allContent = array_merge($movies, $songs);

    // Note: Search filter එක මෙතන PHP array එක ඇතුලේ කරන එක ලේසියි query එක සංකීර්ණ නොකර.
    // නමුත් ඔයාගේ කලින් code එකේ තිබ්බ වගේ SQL වලින් කරනවා නම් වඩා හොඳයි.
    // දැනට Data පෙන්නන ප්‍රශ්නේ විසඳන්න සරලව යවමු.

    echo json_encode([
        'success' => true,
        'content' => $allContent
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'DB Error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>