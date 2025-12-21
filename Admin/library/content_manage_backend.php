<?php 
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Session timeout (30 minutes)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header('Location: login.php?error=session_expired');
    exit();
}
$_SESSION['last_activity'] = time();

// Database connection
require_once '../include/connection.php';

// Handle delete action
if (isset($_GET['delete_id']) && isset($_GET['type'])) {
    $delete_id = $_GET['delete_id'];
    $type = $_GET['type'];
    
    try {
        if ($type === 'movie') {
            $stmt = $pdo->prepare("DELETE FROM movies WHERE movie_id = ?");
        } elseif ($type === 'song') {
            $stmt = $pdo->prepare("DELETE FROM songs WHERE song_id = ?");
        }
        
        $stmt->execute([$delete_id]);
        $success_message = "Content deleted successfully!";
    } catch (PDOException $e) {
        $error_message = "Error deleting content: " . $e->getMessage();
    }
}

// Handle search functionality
$searchTerm = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = trim($_GET['search']);
}

try {
    // Base queries
    $moviesQuery = "
        SELECT movie_id, title, description, release_year, genre, rating, duration, poster_image, 
               COALESCE(view_count, 0) as views, 'movie' as type
        FROM movies 
        WHERE 1=1
    ";
    
    $songsQuery = "
        SELECT song_id as content_id, title, CONCAT(artist, ' - ', album) as description, 
               NULL as release_year, genre, NULL as rating, duration, cover_image as image, 
               0 as views, 'song' as type, artist
        FROM songs 
        WHERE 1=1
    ";
    
    $params = [];
    
    // Add search condition if search term exists
    if (!empty($searchTerm)) {
        $searchParam = "%" . $searchTerm . "%";
        $moviesQuery .= " AND (title LIKE ? OR description LIKE ? OR genre LIKE ?)";
        $songsQuery .= " AND (title LIKE ? OR artist LIKE ? OR album LIKE ? OR genre LIKE ?)";
        
        // For movies: 3 parameters
        $movieParams = [$searchParam, $searchParam, $searchParam];
        // For songs: 4 parameters
        $songParams = [$searchParam, $searchParam, $searchParam, $searchParam];
    }
    
    // Add ORDER BY
    $moviesQuery .= " ORDER BY created_at DESC";
    $songsQuery .= " ORDER BY created_at DESC";
    
    // Execute movies query
    $movies_stmt = $pdo->prepare($moviesQuery);
    if (!empty($searchTerm)) {
        $movies_stmt->execute($movieParams);
    } else {
        $movies_stmt->execute();
    }
    $movies = $movies_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Execute songs query
    $songs_stmt = $pdo->prepare($songsQuery);
    if (!empty($searchTerm)) {
        $songs_stmt->execute($songParams);
    } else {
        $songs_stmt->execute();
    }
    $songs = $songs_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Process songs to match movies structure
    $processedSongs = [];
    foreach ($songs as $song) {
        $processedSongs[] = [
            'movie_id' => $song['content_id'],
            'title' => $song['title'],
            'description' => $song['description'],
            'release_year' => $song['release_year'],
            'genre' => $song['genre'],
            'rating' => $song['rating'],
            'duration' => $song['duration'],
            'poster_image' => $song['image'],
            'views' => $song['views'],
            'type' => 'song',
            'artist' => $song['artist']
        ];
    }
    
    // Get unique genres from both movies and songs
    $genre_stmt = $pdo->query("
        SELECT DISTINCT genre FROM movies 
        WHERE genre IS NOT NULL AND genre != ''
        UNION
        SELECT DISTINCT genre FROM songs 
        WHERE genre IS NOT NULL AND genre != ''
        ORDER BY genre
    ");
    $genres = $genre_stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Get content types (always movie and song)
    $contentTypes = ['movie', 'song'];
    
    // Get sort options
    $sortOptions = [
        ['value' => 'newest', 'label' => 'Newest First'],
        ['value' => 'oldest', 'label' => 'Oldest First'],
        ['value' => 'title_asc', 'label' => 'Title A-Z'],
        ['value' => 'title_desc', 'label' => 'Title Z-A'],
        ['value' => 'views', 'label' => 'Most Viewed'],
        ['value' => 'rating', 'label' => 'Highest Rating']
    ];

    // Combine all content
    $all_content = array_merge($movies, $processedSongs);
    
    // Apply sorting if specified
    if (isset($_GET['sort'])) {
        $sort = $_GET['sort'];
        usort($all_content, function($a, $b) use ($sort) {
            switch ($sort) {
                case 'title_asc':
                    return strcmp($a['title'], $b['title']);
                case 'title_desc':
                    return strcmp($b['title'], $a['title']);
                case 'views':
                    return $b['views'] - $a['views'];
                case 'rating':
                    if ($a['type'] === 'movie' && $b['type'] === 'movie') {
                        return ($b['rating'] ?? 0) <=> ($a['rating'] ?? 0);
                    }
                    return 0;
                case 'oldest':
                    // Assuming newer items are added later
                    return strtotime($a['created_at'] ?? '') <=> strtotime($b['created_at'] ?? '');
                case 'newest':
                default:
                    // Default: newest first (already sorted by created_at DESC in SQL)
                    return 0;
            }
        });
    }
    
    // Filter by content type if specified
    if (isset($_GET['content_type']) && !empty($_GET['content_type'])) {
        $contentType = $_GET['content_type'];
        $all_content = array_filter($all_content, function($item) use ($contentType) {
            return $item['type'] === $contentType;
        });
        $all_content = array_values($all_content); // Reindex array
    }
    
    // Filter by genre if specified
    if (isset($_GET['genre']) && !empty($_GET['genre'])) {
        $genre = $_GET['genre'];
        $all_content = array_filter($all_content, function($item) use ($genre) {
            return $item['genre'] === $genre;
        });
        $all_content = array_values($all_content); // Reindex array
    }

    // Prepare data for frontend
    $data = [
        'success' => true,
        'content' => $all_content,
        'genres' => $genres,
        'contentTypes' => $contentTypes,
        'sortOptions' => $sortOptions,
        'searchTerm' => $searchTerm,
        'success_message' => $success_message ?? null,
        'error_message' => $error_message ?? null
    ];

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();

} catch (PDOException $e) {
    error_log("Content management error: " . $e->getMessage());
    
    $data = [
        'success' => false,
        'error_message' => "Error loading content from database: " . $e->getMessage(),
        'content' => [],
        'genres' => [],
        'contentTypes' => ['movie', 'song'],
        'sortOptions' => [],
        'searchTerm' => ''
    ];
    
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}
?>
