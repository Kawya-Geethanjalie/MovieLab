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

// Initialize variables
$success_message = '';
$error_message = '';
$form_data = [
    'content_type' => '',
    'title' => '',
    'description' => '',
    'genre' => '',
    'release_year' => '',
    'duration' => '',
    'rating' => '',
    'artist' => '',
    'album' => '',
    'language' => ''
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $content_type = $_POST['content_type'] ?? '';
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $genre = $_POST['genre'] ?? '';
    $release_year = $_POST['release_year'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $rating = $_POST['rating'] ?? '';
    $artist = trim($_POST['artist'] ?? '');
    $album = trim($_POST['album'] ?? '');
    $language = $_POST['language'] ?? '';
    
    // Validate required fields
    if (empty($title)) {
        $error_message = 'Title is required!';
    } elseif (empty($content_type)) {
        $error_message = 'Content type is required!';
    } else {
        try {
            if ($content_type === 'movie') {
                // Validate movie specific fields
                if (!empty($release_year) && ($release_year < 1900 || $release_year > date('Y'))) {
                    $error_message = 'Please enter a valid release year!';
                } elseif (!empty($rating) && ($rating < 0 || $rating > 10)) {
                    $error_message = 'Rating must be between 0 and 10!';
                } else {
                    // Insert movie
                    $stmt = $pdo->prepare("
                        INSERT INTO movies (title, description, release_year, genre, rating, duration, poster_image) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)
                    ");
                    
                    $result = $stmt->execute([
                        $title,
                        $description ?: null,
                        $release_year ?: null,
                        $genre ?: null,
                        $rating ?: null,
                        $duration ?: null,
                        null // poster_image - you can add file upload later
                    ]);
                    
                    if ($result) {
                        $success_message = 'Movie "' . htmlspecialchars($title) . '" added successfully!';
                        
                        // Clear form data after successful submission
                        $form_data = [
                            'content_type' => '',
                            'title' => '',
                            'description' => '',
                            'genre' => '',
                            'release_year' => '',
                            'duration' => '',
                            'rating' => '',
                            'artist' => '',
                            'album' => '',
                            'language' => ''
                        ];
                    } else {
                        $error_message = 'Failed to add movie to database!';
                    }
                }
                
            } elseif ($content_type === 'song') {
                // Validate song specific fields
                if (empty($artist)) {
                    $error_message = 'Artist name is required for songs!';
                } else {
                    // Insert song
                    $stmt = $pdo->prepare("
                        INSERT INTO songs (title, artist, album, genre, duration, language, cover_image) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)
                    ");
                    
                    $result = $stmt->execute([
                        $title,
                        $artist,
                        $album ?: null,
                        $genre ?: null,
                        $duration ?: null,
                        $language ?: null,
                        null // cover_image - you can add file upload later
                    ]);
                    
                    if ($result) {
                        $success_message = 'Song "' . htmlspecialchars($title) . '" by ' . htmlspecialchars($artist) . ' added successfully!';
                        
                        // Clear form data after successful submission
                        $form_data = [
                            'content_type' => '',
                            'title' => '',
                            'description' => '',
                            'genre' => '',
                            'release_year' => '',
                            'duration' => '',
                            'rating' => '',
                            'artist' => '',
                            'album' => '',
                            'language' => ''
                        ];
                    } else {
                        $error_message = 'Failed to add song to database!';
                    }
                }
            }
            
        } catch (PDOException $e) {
            error_log("Add content error: " . $e->getMessage());
            
            // Check for duplicate entry error
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $error_message = 'This content already exists in the database!';
            } else {
                $error_message = 'Error adding content to database: ' . $e->getMessage();
            }
            
            // Keep form data for re-population
            $form_data = [
                'content_type' => $content_type,
                'title' => $title,
                'description' => $description,
                'genre' => $genre,
                'release_year' => $release_year,
                'duration' => $duration,
                'rating' => $rating,
                'artist' => $artist,
                'album' => $album,
                'language' => $language
            ];
        }
    }
    
    // If there's an error, keep the form data
    if (!empty($error_message)) {
        $form_data = [
            'content_type' => $content_type,
            'title' => $title,
            'description' => $description,
            'genre' => $genre,
            'release_year' => $release_year,
            'duration' => $duration,
            'rating' => $rating,
            'artist' => $artist,
            'album' => $album,
            'language' => $language
        ];
    }
}

// Get existing content for validation (optional - to show existing titles)
try {
    $existing_movies = $pdo->query("SELECT title FROM movies LIMIT 10")->fetchAll(PDO::FETCH_COLUMN);
    $existing_songs = $pdo->query("SELECT title, artist FROM songs LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Fetch existing content error: " . $e->getMessage());
    $existing_movies = [];
    $existing_songs = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Content - Movie Lab Admin</title>
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

        /* Add Content Styles */
        .add-content {
            padding: 20px;
            min-height: 100vh;
            max-width: 1000px;
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

        /* Form Styles */
        .form-container {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Alerts */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 25px;
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

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            font-size: 14px;
            color: var(--text-light);
            font-weight: 500;
        }

        .form-label .required {
            color: var(--primary-red);
        }

        .form-input, .form-select, .form-textarea {
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: var(--text-light);
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary-red);
            box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-help {
            font-size: 12px;
            color: var(--text-gray);
            margin-top: 4px;
        }

        .existing-content {
            font-size: 12px;
            color: var(--text-gray);
            margin-top: 5px;
            max-height: 60px;
            overflow-y: auto;
            padding: 5px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 4px;
        }

        .existing-content ul {
            list-style: none;
            padding-left: 0;
        }

        .existing-content li {
            padding: 2px 0;
        }

        /* Content Type Tabs */
        .content-type-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            background: rgba(255, 255, 255, 0.05);
            padding: 8px;
            border-radius: 10px;
        }

        .content-type-tab {
            flex: 1;
            padding: 12px 20px;
            background: transparent;
            border: none;
            border-radius: 6px;
            color: var(--text-light);
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            font-weight: 500;
        }

        .content-type-tab.active {
            background: var(--primary-red);
            color: white;
        }

        .content-type-tab:hover:not(.active) {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Content Type Sections */
        .content-type-section {
            display: none;
        }

        .content-type-section.active {
            display: block;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Preview Section */
        .preview-section {
            background: rgba(255, 255, 255, 0.02);
            border-radius: 8px;
            padding: 20px;
            margin-top: 25px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .preview-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--text-light);
        }

        .preview-content {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 15px;
            align-items: start;
        }

        .preview-poster {
            width: 120px;
            height: 160px;
            background: linear-gradient(135deg, #333 0%, #555 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-gray);
            font-size: 32px;
        }

        .preview-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .preview-title-text {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-light);
        }

        .preview-meta {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .preview-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--text-gray);
        }

        .preview-description {
            font-size: 14px;
            color: var(--text-light);
            line-height: 1.5;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .add-content {
                padding: 15px;
            }

            .form-container {
                padding: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 15px;
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

            .content-type-tabs {
                flex-direction: column;
            }

            .form-actions {
                flex-direction: column;
            }

            .preview-content {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .preview-poster {
                width: 100px;
                height: 140px;
                margin: 0 auto;
            }
        }

        @media (max-width: 480px) {
            .add-content {
                padding: 10px;
            }

            .form-container {
                padding: 15px;
            }

            .preview-meta {
                flex-direction: column;
                gap: 8px;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.3s ease;
        }
    </style>
</head>
<body>
    <!-- Add Content -->
    <div class="add-content">
        <div class="page-header">
            <h1 class="page-title">Add New Content</h1>
            <div class="header-actions">
                <a href="../pages/content_management.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Content
                </a>
            </div>
        </div>

        <!-- Alerts -->
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success fade-in">
                <i class="fas fa-check-circle"></i>
                <?php echo $success_message; ?>
                <a href="../pages/content_management.php" class="btn btn-sm" style="margin-left: auto; padding: 4px 8px; font-size: 12px;">
                    View Content
                </a>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-error fade-in">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <!-- Content Type Selection -->
            <div class="content-type-tabs">
                <button type="button" class="content-type-tab <?php echo $form_data['content_type'] === 'movie' ? 'active' : ''; ?>" data-type="movie">
                    <i class="fas fa-film"></i>
                    Movie
                </button>
                <button type="button" class="content-type-tab <?php echo $form_data['content_type'] === 'song' ? 'active' : ''; ?>" data-type="song">
                    <i class="fas fa-music"></i>
                    Song
                </button>
            </div>

            <form method="POST" id="contentForm">
                <!-- Hidden field for content type -->
                <input type="hidden" name="content_type" id="contentType" value="<?php echo htmlspecialchars($form_data['content_type']); ?>">

                <!-- Common Fields -->
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label class="form-label" for="title">Title <span class="required">*</span></label>
                        <input type="text" class="form-input" id="title" name="title" 
                               value="<?php echo htmlspecialchars($form_data['title']); ?>" 
                               placeholder="Enter content title" required>
                        <div class="form-help">Enter a unique title for the content</div>
                        <?php if (!empty($existing_movies) || !empty($existing_songs)): ?>
                            <div class="existing-content">
                                <strong>Existing content:</strong>
                                <ul>
                                    <?php foreach ($existing_movies as $movie): ?>
                                        <li>🎬 <?php echo htmlspecialchars($movie); ?></li>
                                    <?php endforeach; ?>
                                    <?php foreach ($existing_songs as $song): ?>
                                        <li>🎵 <?php echo htmlspecialchars($song['title']); ?> - <?php echo htmlspecialchars($song['artist']); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-textarea" id="description" name="description" 
                                  placeholder="Enter content description"><?php echo htmlspecialchars($form_data['description']); ?></textarea>
                        <div class="form-help">Brief description of the content (optional)</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="genre">Genre</label>
                        <select class="form-select" id="genre" name="genre">
                            <option value="">Select Genre</option>
                            <option value="Action" <?php echo $form_data['genre'] === 'Action' ? 'selected' : ''; ?>>Action</option>
                            <option value="Drama" <?php echo $form_data['genre'] === 'Drama' ? 'selected' : ''; ?>>Drama</option>
                            <option value="Comedy" <?php echo $form_data['genre'] === 'Comedy' ? 'selected' : ''; ?>>Comedy</option>
                            <option value="Romance" <?php echo $form_data['genre'] === 'Romance' ? 'selected' : ''; ?>>Romance</option>
                            <option value="Thriller" <?php echo $form_data['genre'] === 'Thriller' ? 'selected' : ''; ?>>Thriller</option>
                            <option value="Horror" <?php echo $form_data['genre'] === 'Horror' ? 'selected' : ''; ?>>Horror</option>
                            <option value="Sci-Fi" <?php echo $form_data['genre'] === 'Sci-Fi' ? 'selected' : ''; ?>>Sci-Fi</option>
                            <option value="Fantasy" <?php echo $form_data['genre'] === 'Fantasy' ? 'selected' : ''; ?>>Fantasy</option>
                            <option value="Pop" <?php echo $form_data['genre'] === 'Pop' ? 'selected' : ''; ?>>Pop</option>
                            <option value="Rock" <?php echo $form_data['genre'] === 'Rock' ? 'selected' : ''; ?>>Rock</option>
                            <option value="Hip Hop" <?php echo $form_data['genre'] === 'Hip Hop' ? 'selected' : ''; ?>>Hip Hop</option>
                            <option value="Electronic" <?php echo $form_data['genre'] === 'Electronic' ? 'selected' : ''; ?>>Electronic</option>
                            <option value="Classical" <?php echo $form_data['genre'] === 'Classical' ? 'selected' : ''; ?>>Classical</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="duration">Duration (minutes)</label>
                        <input type="number" class="form-input" id="duration" name="duration" 
                               value="<?php echo htmlspecialchars($form_data['duration']); ?>" 
                               placeholder="e.g., 120" min="1">
                        <div class="form-help">For songs, enter duration in seconds</div>
                    </div>
                </div>

                <!-- Movie Specific Fields -->
                <div class="content-type-section <?php echo $form_data['content_type'] === 'movie' ? 'active' : ''; ?>" id="movieSection">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label" for="release_year">Release Year</label>
                            <input type="number" class="form-input" id="release_year" name="release_year" 
                                   value="<?php echo htmlspecialchars($form_data['release_year']); ?>" 
                                   placeholder="e.g., 2024" min="1900" max="<?php echo date('Y'); ?>">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="rating">Rating (0-10)</label>
                            <input type="number" class="form-input" id="rating" name="rating" 
                                   value="<?php echo htmlspecialchars($form_data['rating']); ?>" 
                                   placeholder="e.g., 7.5" min="0" max="10" step="0.1">
                        </div>
                    </div>
                </div>

                <!-- Song Specific Fields -->
                <div class="content-type-section <?php echo $form_data['content_type'] === 'song' ? 'active' : ''; ?>" id="songSection">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label" for="artist">Artist <span class="required">*</span></label>
                            <input type="text" class="form-input" id="artist" name="artist" 
                                   value="<?php echo htmlspecialchars($form_data['artist']); ?>" 
                                   placeholder="Enter artist name">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="album">Album</label>
                            <input type="text" class="form-input" id="album" name="album" 
                                   value="<?php echo htmlspecialchars($form_data['album']); ?>" 
                                   placeholder="Enter album name">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="language">Language</label>
                            <select class="form-select" id="language" name="language">
                                <option value="">Select Language</option>
                                <option value="English" <?php echo $form_data['language'] === 'English' ? 'selected' : ''; ?>>English</option>
                                <option value="Sinhala" <?php echo $form_data['language'] === 'Sinhala' ? 'selected' : ''; ?>>Sinhala</option>
                                <option value="Tamil" <?php echo $form_data['language'] === 'Tamil' ? 'selected' : ''; ?>>Tamil</option>
                                <option value="Hindi" <?php echo $form_data['language'] === 'Hindi' ? 'selected' : ''; ?>>Hindi</option>
                                <option value="Spanish" <?php echo $form_data['language'] === 'Spanish' ? 'selected' : ''; ?>>Spanish</option>
                                <option value="Other" <?php echo $form_data['language'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Preview Section -->
                <div class="preview-section">
                    <h3 class="preview-title">Preview</h3>
                    <div class="preview-content">
                        <div class="preview-poster">
                            <i class="fas fa-question"></i>
                        </div>
                        <div class="preview-info">
                            <div class="preview-title-text" id="previewTitle">Content Title</div>
                            <div class="preview-meta">
                                <span class="preview-meta-item" id="previewType">Type: Not selected</span>
                                <span class="preview-meta-item" id="previewGenre">Genre: Not selected</span>
                                <span class="preview-meta-item" id="previewDuration">Duration: Not set</span>
                            </div>
                            <div class="preview-description" id="previewDescription">No description provided.</div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="reset" class="btn btn-secondary" onclick="return confirm('Are you sure you want to reset the form?')">
                        <i class="fas fa-undo"></i>
                        Reset Form
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Add Content
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Content Type Tabs
        document.querySelectorAll('.content-type-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                const type = this.getAttribute('data-type');
                
                // Update active tab
                document.querySelectorAll('.content-type-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Update hidden field
                document.getElementById('contentType').value = type;
                
                // Show/hide sections
                document.querySelectorAll('.content-type-section').forEach(section => {
                    section.classList.remove('active');
                });
                document.getElementById(type + 'Section').classList.add('active');
                
                // Update duration placeholder and help text
                const durationInput = document.getElementById('duration');
                const durationHelp = document.querySelector('.form-help');
                if (type === 'song') {
                    durationInput.placeholder = 'e.g., 240 (seconds)';
                    if (durationHelp) {
                        durationHelp.textContent = 'For songs, enter duration in seconds';
                    }
                } else {
                    durationInput.placeholder = 'e.g., 120 (minutes)';
                    if (durationHelp) {
                        durationHelp.textContent = 'For movies, enter duration in minutes';
                    }
                }
                
                // Update preview
                updatePreview();
            });
        });

        // Real-time preview update
        function updatePreview() {
            const type = document.getElementById('contentType').value;
            const title = document.getElementById('title').value || 'Content Title';
            const description = document.getElementById('description').value || 'No description provided.';
            const genre = document.getElementById('genre').value || 'Not selected';
            const duration = document.getElementById('duration').value || 'Not set';
            const releaseYear = document.getElementById('release_year').value;
            const rating = document.getElementById('rating').value;
            const artist = document.getElementById('artist').value;
            const album = document.getElementById('album').value;
            const language = document.getElementById('language').value;
            
            // Update preview elements
            document.getElementById('previewTitle').textContent = title;
            
            let typeText = type ? type.charAt(0).toUpperCase() + type.slice(1) : 'Not selected';
            if (type === 'song' && artist) {
                typeText += ` by ${artist}`;
            }
            document.getElementById('previewType').textContent = `Type: ${typeText}`;
            
            document.getElementById('previewGenre').textContent = `Genre: ${genre}`;
            
            let durationText = 'Not set';
            if (duration) {
                durationText = type === 'song' ? `${duration} seconds` : `${duration} minutes`;
            }
            document.getElementById('previewDuration').textContent = `Duration: ${durationText}`;
            
            let descriptionText = description;
            if (type === 'movie' && releaseYear) {
                descriptionText = `(${releaseYear}) ${descriptionText}`;
            }
            if (type === 'song' && album) {
                descriptionText = `Album: ${album}\n${descriptionText}`;
            }
            document.getElementById('previewDescription').textContent = descriptionText;
            
            // Update poster icon
            const posterIcon = document.querySelector('.preview-poster i');
            if (type === 'movie') {
                posterIcon.className = 'fas fa-film';
            } else if (type === 'song') {
                posterIcon.className = 'fas fa-music';
            } else {
                posterIcon.className = 'fas fa-question';
            }
        }

        // Add event listeners for real-time updates
        const inputsToWatch = [
            'title', 'description', 'genre', 'duration', 
            'release_year', 'rating', 'artist', 'album', 'language'
        ];
        
        inputsToWatch.forEach(inputId => {
            const element = document.getElementById(inputId);
            if (element) {
                element.addEventListener('input', updatePreview);
                if (element.tagName === 'SELECT') {
                    element.addEventListener('change', updatePreview);
                }
            }
        });

        // Form validation
        document.getElementById('contentForm').addEventListener('submit', function(e) {
            const type = document.getElementById('contentType').value;
            const title = document.getElementById('title').value.trim();
            
            if (!type) {
                e.preventDefault();
                alert('Please select a content type (Movie or Song)');
                return;
            }
            
            if (!title) {
                e.preventDefault();
                alert('Please enter a title');
                return;
            }
            
            if (type === 'song') {
                const artist = document.getElementById('artist').value.trim();
                if (!artist) {
                    e.preventDefault();
                    alert('Please enter artist name for songs');
                    return;
                }
            }
            
            // Additional validation
            const releaseYear = document.getElementById('release_year').value;
            if (releaseYear && (releaseYear < 1900 || releaseYear > new Date().getFullYear())) {
                e.preventDefault();
                alert('Please enter a valid release year');
                return;
            }
            
            const rating = document.getElementById('rating').value;
            if (rating && (rating < 0 || rating > 10)) {
                e.preventDefault();
                alert('Rating must be between 0 and 10');
                return;
            }
        });

        // Initialize preview on page load
        document.addEventListener('DOMContentLoaded', function() {
            updatePreview();
            
            // Set initial duration placeholder based on default content type
            const defaultType = document.getElementById('contentType').value;
            const durationInput = document.getElementById('duration');
            if (defaultType === 'song') {
                durationInput.placeholder = 'e.g., 240 (seconds)';
            }
        });
    </script>
</body>
</html>