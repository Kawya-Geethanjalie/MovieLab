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

$admin_username = $_SESSION['admin_username'] ?? 'Admin';

include("../include/header.php");

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

// Fetch content from database
try {
    // Get all movies
    $movies_stmt = $pdo->prepare("
        SELECT movie_id, title, description, release_year, genre, rating, duration, poster_image, 
               COALESCE(view_count, 0) as views 
        FROM movies 
        ORDER BY created_at DESC
    ");
    $movies_stmt->execute();
    $movies = $movies_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get all songs
    $songs_stmt = $pdo->prepare("
        SELECT song_id, title, artist, album, genre, duration, language, cover_image, 
               COALESCE(view_count, 0) as views 
        FROM songs 
        ORDER BY created_at DESC
    ");
    $songs_stmt->execute();
    $songs = $songs_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Combine all content
    $all_content = [];
    
    foreach ($movies as $movie) {
        $all_content[] = [
            'type' => 'movie',
            'id' => $movie['movie_id'],
            'title' => $movie['title'],
            'description' => $movie['description'],
            'genre' => $movie['genre'],
            'year' => $movie['release_year'],
            'duration' => $movie['duration'],
            'rating' => $movie['rating'],
            'views' => $movie['views'],
            'image' => $movie['poster_image']
        ];
    }
    
    foreach ($songs as $song) {
        $all_content[] = [
            'type' => 'song',
            'id' => $song['song_id'],
            'title' => $song['title'],
            'description' => $song['album'] . ' - ' . $song['artist'],
            'genre' => $song['genre'],
            'year' => null,
            'duration' => $song['duration'],
            'rating' => null,
            'views' => $song['views'],
            'image' => $song['cover_image'],
            'artist' => $song['artist']
        ];
    }

} catch (PDOException $e) {
    error_log("Content management error: " . $e->getMessage());
    $all_content = [];
    $error_message = "Error loading content from database.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Management - Movie Lab Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary-red: #E50914;
            --dark-bg: #0c0c0c;
            --card-bg: #1a1a1a;
            --text-light: #e0e0e0;
            --text-gray: #888;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--dark-bg) 0%, #1a1a1a 100%);
            color: #fff;
            overflow-x: hidden;
        }

        /* Content Management Styles */
        .content-management {
            padding: 20px;
            min-height: 100vh;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(229, 9, 20, 0.3);
            flex-wrap: wrap;
            gap: 15px;
        }

        .page-title {
            font-size: clamp(24px, 4vw, 28px);
            font-weight: 700;
            background: linear-gradient(90deg, #fff 0%, var(--primary-red) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.2;
        }

        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .btn-primary {
            background: var(--primary-red);
            color: white;
        }

        .btn-primary:hover {
            background: #b8070f;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-light);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Alerts */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.2);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #10B981;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #EF4444;
        }

        /* Filters and Search */
        .filters-section {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .search-box {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            min-width: 250px;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: var(--text-light);
            font-size: 14px;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-red);
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-label {
            font-size: 14px;
            color: var(--text-gray);
            font-weight: 500;
        }

        .filter-select {
            padding: 10px 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            color: var(--text-light);
            font-size: 14px;
            cursor: pointer;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--primary-red);
        }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .content-card {
            background: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        .content-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
            border-color: rgba(229, 9, 20, 0.3);
        }

        .content-poster {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #333 0%, #555 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-gray);
            font-size: 48px;
            position: relative;
            overflow: hidden;
        }

        .content-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .content-type {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            background: var(--primary-red);
            color: white;
        }

        .content-type.movie {
            background: var(--primary-red);
        }

        .content-type.song {
            background: #8B5CF6;
        }

        .content-info {
            padding: 20px;
        }

        .content-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-light);
            line-height: 1.3;
        }

        .content-meta {
            display: flex;
            gap: 15px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--text-gray);
        }

        .content-description {
            font-size: 14px;
            color: var(--text-light);
            line-height: 1.5;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .content-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 6px;
            flex: 1;
            text-align: center;
            min-width: 60px;
        }

        .btn-edit {
            background: rgba(59, 130, 246, 0.2);
            color: #3B82F6;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .btn-edit:hover {
            background: rgba(59, 130, 246, 0.3);
        }

        .btn-delete {
            background: rgba(239, 68, 68, 0.2);
            color: #EF4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .btn-delete:hover {
            background: rgba(239, 68, 68, 0.3);
        }

        .btn-view {
            background: rgba(16, 185, 129, 0.2);
            color: #10B981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .btn-view:hover {
            background: rgba(16, 185, 129, 0.3);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-gray);
            grid-column: 1 / -1;
        }

        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: var(--text-light);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 15px;
            }
        }

        @media (max-width: 768px) {
            .content-management {
                padding: 15px;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .header-actions {
                width: 100%;
                justify-content: space-between;
            }

            .filters-section {
                padding: 15px;
            }

            .search-box {
                flex-direction: column;
            }

            .search-input {
                min-width: 100%;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

            .content-actions {
                justify-content: center;
            }

            .btn-sm {
                flex: none;
                min-width: 80px;
            }
        }

        @media (max-width: 480px) {
            .content-management {
                padding: 10px;
            }

            .content-info {
                padding: 15px;
            }

            .content-meta {
                flex-direction: column;
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    <!-- Content Management -->
    <div class="content-management">
        <div class="page-header">
            <h1 class="page-title">Content Management</h1>
            <div class="header-actions">
                <button class="btn btn-secondary">
                    <i class="fas fa-download"></i>
                    Export
                </button>
                <a href="../library/add-content.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Add New Content
                </a>
            </div>
        </div>

        <!-- Alerts -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Filters Section -->
        <div class="filters-section">
            <div class="search-box">
                <input type="text" class="search-input" placeholder="Search movies, songs...">
                <button class="btn btn-primary">
                    <i class="fas fa-search"></i>
                    Search
                </button>
                <button class="btn btn-secondary">
                    <i class="fas fa-filter"></i>
                    Filters
                </button>
            </div>
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Content Type</label>
                    <select class="filter-select" id="contentTypeFilter">
                        <option value="">All Types</option>
                        <option value="movie">Movies</option>
                        <option value="song">Songs</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Genre</label>
                    <select class="filter-select" id="genreFilter">
                        <option value="">All Genres</option>
                        <option value="Action">Action</option>
                        <option value="Drama">Drama</option>
                        <option value="Comedy">Comedy</option>
                        <option value="Romance">Romance</option>
                        <option value="Thriller">Thriller</option>
                        <option value="Sci-Fi">Sci-Fi</option>
                        <option value="Pop">Pop</option>
                        <option value="Rock">Rock</option>
                        <option value="Reggaeton">Reggaeton</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Sort By</label>
                    <select class="filter-select" id="sortFilter">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="title">Title A-Z</option>
                        <option value="views">Most Viewed</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid" id="contentGrid">
            <?php if (!empty($all_content)): ?>
                <?php foreach ($all_content as $content): ?>
                    <div class="content-card" data-type="<?php echo $content['type']; ?>" data-genre="<?php echo $content['genre']; ?>">
                        <div class="content-poster">
                            <?php if ($content['image']): ?>
                                <img src="<?php echo htmlspecialchars($content['image']); ?>" alt="<?php echo htmlspecialchars($content['title']); ?>">
                            <?php else: ?>
                                <i class="fas fa-<?php echo $content['type'] === 'movie' ? 'film' : 'music'; ?>"></i>
                            <?php endif; ?>
                            <span class="content-type <?php echo $content['type']; ?>">
                                <?php echo ucfirst($content['type']); ?>
                            </span>
                        </div>
                        <div class="content-info">
                            <h3 class="content-title"><?php echo htmlspecialchars($content['title']); ?></h3>
                            <div class="content-meta">
                                <span class="meta-item">
                                    <i class="fas fa-<?php echo $content['type'] === 'movie' ? 'film' : 'music'; ?>"></i>
                                    <?php echo ucfirst($content['type']); ?>
                                </span>
                                <?php if ($content['duration']): ?>
                                    <span class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        <?php echo gmdate("H:i", $content['duration']); ?>
                                    </span>
                                <?php endif; ?>
                                <span class="meta-item">
                                    <i class="fas fa-eye"></i>
                                    <?php echo number_format($content['views']); ?>
                                </span>
                            </div>
                            <p class="content-description">
                                <?php 
                                if ($content['type'] === 'movie') {
                                    echo htmlspecialchars($content['description'] ?: 'No description available.');
                                } else {
                                    echo htmlspecialchars($content['description'] ?: 'No album information.');
                                }
                                ?>
                            </p>
                            <div class="content-actions">
                                <a href="edit-content.php?type=<?php echo $content['type']; ?>&id=<?php echo $content['id']; ?>" class="btn btn-sm btn-edit">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                                <button class="btn btn-sm btn-view">
                                    <i class="fas fa-eye"></i>
                                    View
                                </button>
                                <a href="?delete_id=<?php echo $content['id']; ?>&type=<?php echo $content['type']; ?>" 
                                   class="btn btn-sm btn-delete" 
                                   onclick="return confirm('Are you sure you want to delete <?php echo htmlspecialchars($content['title']); ?>?')">
                                    <i class="fas fa-trash"></i>
                                    Delete
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-film"></i>
                    <h3>No Content Found</h3>
                    <p>There is no content in the database. Start by adding some content.</p>
                    <a href="../library/add-content.php" class="btn btn-primary" style="margin-top: 15px;">
                        <i class="fas fa-plus"></i>
                        Add New Content
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Search functionality
        document.querySelector('.search-input').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const contentCards = document.querySelectorAll('.content-card');
            
            contentCards.forEach(card => {
                const title = card.querySelector('.content-title').textContent.toLowerCase();
                const description = card.querySelector('.content-description').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Filter functionality
        function applyFilters() {
            const contentType = document.getElementById('contentTypeFilter').value;
            const genre = document.getElementById('genreFilter').value;
            const contentCards = document.querySelectorAll('.content-card');
            
            contentCards.forEach(card => {
                const cardType = card.getAttribute('data-type');
                const cardGenre = card.getAttribute('data-genre');
                let showCard = true;
                
                if (contentType && cardType !== contentType) {
                    showCard = false;
                }
                
                if (genre && cardGenre !== genre) {
                    showCard = false;
                }
                
                card.style.display = showCard ? 'block' : 'none';
            });
        }

        document.getElementById('contentTypeFilter').addEventListener('change', applyFilters);
        document.getElementById('genreFilter').addEventListener('change', applyFilters);
        document.getElementById('sortFilter').addEventListener('change', applyFilters);

        // View button functionality
        document.querySelectorAll('.btn-view').forEach(btn => {
            btn.addEventListener('click', function() {
                const contentTitle = this.closest('.content-card').querySelector('.content-title').textContent;
                alert('Viewing: ' + contentTitle);
                // Add actual view logic here
            });
        });
    </script>
</body>
</html>