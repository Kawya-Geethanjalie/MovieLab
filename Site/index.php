[file name]: index.php
[file content begin]
<?php 
include("../include/header.php");

// Database connection
$host = '127.0.0.1';
$dbname = 'movielab';
$username = 'root';
$password = '';

try {
    $conn = new mysqli($host, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }
} catch(Exception $e) {
    die("Database connection error: " . $e->getMessage());
}

// Helper function to fix image paths
function fixImagePath($path) {
    if (empty($path)) {
        return null;
    }
    
    // If path already has ../, return as is
    if (strpos($path, '../') === 0) {
        return $path;
    }
    
    // If path starts with /uploads/, prepend ..
    if (strpos($path, '/uploads/') === 0) {
        return '..' . $path;
    }
    
    // If path is a full URL, return as is
    if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
        return $path;
    }
    
    // If path is relative without ../, prepend ../
    if (strpos($path, './') === 0 || strpos($path, 'images/') === 0) {
        return '../' . ltrim($path, './');
    }
    
    // Default: return as is
    return '../Admin/'.$path;
}

// Get filter parameters from URL
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$content_type = isset($_GET['type']) ? $_GET['type'] : 'all'; // 'all', 'movies', 'songs'
$genre = isset($_GET['genre']) ? $_GET['genre'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';
$language = isset($_GET['language']) ? $_GET['language'] : '';

// Function to get movies
function getMovies($conn, $filter, $genre, $year, $language) {
    $sql = "SELECT * FROM movies WHERE 1=1";
    $params = [];
    $types = "";

    if (!empty($genre)) {
        $sql .= " AND genre LIKE ?";
        $params[] = "%$genre%";
        $types .= "s";
    }

    if (!empty($year) && $year !== 'older') {
        $sql .= " AND release_year = ?";
        $params[] = $year;
        $types .= "s";
    } elseif ($year === 'older') {
        $sql .= " AND release_year < 2018";
    }

    // Add ordering based on filter
    switch($filter) {
        case 'now_playing':
            $sql .= " ORDER BY release_year DESC LIMIT 20";
            break;
        case 'top_rated':
            $sql .= " ORDER BY rating DESC LIMIT 20";
            break;
        case 'new':
            $sql .= " ORDER BY created_at DESC LIMIT 20";
            break;
        case 'popular':
            $sql .= " ORDER BY view_count DESC, rating DESC LIMIT 20";
            break;
        default:
            $sql .= " ORDER BY created_at DESC LIMIT 20";
            break;
    }

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $movies = [];
    while ($row = $result->fetch_assoc()) {
        // Fix image paths
        if (!empty($row['poster_image'])) {
            $row['poster_image'] = fixImagePath($row['poster_image']);
        }
        if (!empty($row['trailer_url'])) {
            $row['trailer_url'] = htmlspecialchars($row['trailer_url']);
        }
        $movies[] = $row;
    }
    $stmt->close();
    
    return $movies;
}

// Function to get songs
function getSongs($conn, $filter, $genre, $language) {
    $sql = "SELECT * FROM songs WHERE 1=1";
    $params = [];
    $types = "";

    if (!empty($genre)) {
        $sql .= " AND genre LIKE ?";
        $params[] = "%$genre%";
        $types .= "s";
    }

    if (!empty($language)) {
        $sql .= " AND language LIKE ?";
        $params[] = "%$language%";
        $types .= "s";
    }

    // Add ordering based on filter
    switch($filter) {
        case 'top':
            $sql .= " ORDER BY RAND() LIMIT 20";
            break;
        case 'new':
            $sql .= " ORDER BY created_at DESC LIMIT 20";
            break;
        case 'playlists':
            $sql .= " ORDER BY artist ASC LIMIT 20";
            break;
        default:
            $sql .= " ORDER BY created_at DESC LIMIT 20";
            break;
    }

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $songs = [];
    while ($row = $result->fetch_assoc()) {
        // Fix image and audio paths
        if (!empty($row['cover_image'])) {
            $row['cover_image'] = fixImagePath($row['cover_image']);
        }
        if (!empty($row['audio_file'])) {
            $row['audio_file'] = fixImagePath($row['audio_file']);
        }
        $songs[] = $row;
    }
    $stmt->close();
    
    return $songs;
}

// Get content based on type
$movies = [];
$songs = [];

if ($content_type === 'all' || $content_type === 'movies') {
    $movies = getMovies($conn, $filter, $genre, $year, $language);
}

if ($content_type === 'all' || $content_type === 'songs') {
    $songs = getSongs($conn, $filter, $genre, $language);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoviLab - Your Ultimate Movie & Music Destination</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <script>
        // Custom Tailwind Configuration for Carousel Fonts and Animations
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'], 
                        'bebas': ['"Bebas Neue"', 'sans-serif'],
                        'poppins': ['"Poppins"', 'sans-serif'],
                    },
                    colors: {
                        'neon-red': '#FF4500', 
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                        'zoom-in': 'zoomIn 10s linear infinite',
                        'pop-in': 'popIn 0.5s ease-out forwards',
                        'slide-up': 'slideUp 0.5s ease-out forwards',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(40px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        zoomIn: {
                            '0%': { transform: 'scale(1)' },
                            '100%': { transform: 'scale(1.1)' },
                        },
                        popIn: {
                            '0%': { opacity: '0', transform: 'scale(0.8)' },
                            '100%': { opacity: '1', transform: 'scale(1)' },
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .card-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.7);
        }
        
        .hover-shadow {
            box-shadow: 0 20px 25px -5px rgba(220, 38, 38, 0.3);
        }
        
        .content-card-container {
            max-width: 100%;
            width: 100%;
        }
        
        .content-card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .content-poster, .content-player {
            height: 250px;
            position: relative;
        }
        
        .song-cover {
            height: 250px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .movie-poster {
            height: 250px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .content-info {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .content-details {
            flex-grow: 1;
        }
        
        .action-buttons-container {
            margin-top: auto;
            padding-top: 12px;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Player styles */
        .player-container {
            position: relative;
            width: 100%;
            height: 100%;
        }
        
        .player-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .content-poster:hover .player-overlay {
            opacity: 1;
        }
        
        .play-button {
            background: rgba(229, 9, 20, 0.9);
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .play-button:hover {
            background: #E50914;
            transform: scale(1.1);
            box-shadow: 0 0 20px rgba(229, 9, 20, 0.5);
        }
        
        .play-button i {
            color: white;
            font-size: 24px;
            margin-left: 4px;
        }
        
        /* Image fallback styles */
        .img-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .img-fallback i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.7;
        }
        
        .img-fallback h3 {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
        }
        
        .img-fallback p {
            font-size: 14px;
            text-align: center;
            opacity: 0.8;
        }
        
        /* Thumbnails Styles */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .thumbnail.active {
            border-color: #FF4500;
            box-shadow: 0 0 15px rgba(255, 69, 0, 0.5);
            transform: scale(1.05);
        }
        
        /* Content type indicator */
        .content-type-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            z-index: 10;
        }
        
        .movie-badge {
            background: rgba(229, 9, 20, 0.9);
            color: white;
        }
        
        .song-badge {
            background: rgba(37, 99, 235, 0.9);
            color: white;
        }
        
        /* Audio player custom styles */
        .audio-player-container {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .audio-player-content {
            text-align: center;
            width: 100%;
        }
        
        .audio-controls {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .audio-control-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .audio-control-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }
        
        .audio-play-btn {
            background: #10B981;
            border-color: #10B981;
        }
        
        .audio-play-btn:hover {
            background: #059669;
        }
    </style>
</head>
<body class="bg-black text-white font-sans">
 
    <!-- New Hero Carousel Section (from caro.html) -->
    <section class="relative overflow-hidden h-[70vh] md:h-[85vh] w-full">
        <div id="bg-layer" class="absolute inset-0 w-full h-full overflow-hidden">
            <!-- Active Background Image -->
            <div id="active-bg" class="w-full h-full bg-cover bg-center transition-all duration-800 ease-in-out animate-zoom-in brightness-100"></div>
            <!-- Gradient Overlays for readability -->
            <div class="absolute inset-0 bg-gradient-to-r from-black via-black/60 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-black/40"></div>
        </div>

        <div class="relative z-10 container mx-auto px-6 h-full flex flex-col justify-center md:justify-start pt-12 pb-24 md:pt-40 md:pb-16">
            
            <!-- Main Text Content (Animated) -->
            <div id="text-content" class="max-w-2xl  pl-4 border-l-4 border-red-500">
                <div class="flex items-center gap-3 mb-4">
                    <span id="movie-rating" class="bg-yellow-500 text-black font-bold px-2 py-1 rounded text-xs"></span>
                    <span id="movie-year" class="text-gray-300 text-sm"></span>
                    <span id="movie-genre" class="text-red-500 text-sm uppercase tracking-wider font-semibold"></span>
                </div>
                
                <h1 id="movie-title" class="font-bebas text-xl md:text-4xl leading-none mb-4 text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400 drop-shadow-lg">
                </h1>
                
                <p id="movie-desc" class="text-gray-300 text-lg mb-6 line-clamp-3 md:line-clamp-none max-w-lg">
                </p>

                <div class="flex gap-4">
                    <button class="bg-red-600 hover:bg-red-500 text-white font-bold py-3 px-8 rounded-full transition transform hover:scale-105 shadow-[0_0_20px_rgba(255,69,0,0.4)] flex items-center gap-2">
                        <i class="fas fa-play"></i> Watch Now
                    </button>
                    <button class="bg-black/40 backdrop-blur-md border border-white/10 hover:bg-white/10 text-white font-bold py-3 px-8 rounded-full transition flex items-center gap-2">
                        <i class="fas fa-plus"></i> My List
                    </button>
                </div>
            </div>

        </div>

        <!-- Carousel Controls and Thumbnails -->
        <div class="absolute bottom-0 left-0 w-full z-20">
            <div class="container mx-auto px-6 py-6 relative">
                
                <div class="flex justify-between items-end w-full">

                    <!-- Desktop Navigation Buttons -->
                    <div class="hidden md:flex gap-4 flex-shrink-0">
                        <button id="prev-btn" class="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-red-600 hover:border-red-600 transition duration-300 z-30">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button id="next-btn" class="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-red-600 hover:border-red-600 transition duration-300 z-30">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    
                    <div class="flex items-end gap-6 ml-auto w-full md:w-auto">
                        <!-- Thumbnail Container Wrapper -->
                        <div id="thumbnails-container-wrapper" class="max-w-full md:max-w-[800px] w-full flex-grow overflow-hidden relative">
                            
                            <!-- Mobile Overlay Navigation Buttons -->
                            <div class="absolute inset-0 flex justify-between items-center z-30 md:hidden pointer-events-none px-2">
                                <button id="mobile-prev-btn" class="w-10 h-10 rounded-full bg-black/70 border border-white/20 flex items-center justify-center text-white hover:bg-red-600 hover:border-red-600 transition duration-300 pointer-events-auto">
                                    <i class="fas fa-chevron-left text-sm"></i>
                                </button>
                                <button id="mobile-next-btn" class="w-10 h-10 rounded-full bg-black/70 border border-white/20 flex items-center justify-center text-white hover:bg-red-600 hover:border-red-600 transition duration-300 pointer-events-auto">
                                    <i class="fas fa-chevron-right text-sm"></i>
                                </button>
                            </div>

                            <!-- Thumbnails Scroll Area -->
                            <div class="bg-black/40 backdrop-blur-xl border border-white/10 p-4 rounded-2xl flex overflow-x-auto hide-scrollbar">
                                <div id="thumbnails-container" class="flex flex-row gap-4 pr-4">
                                    <!-- Thumbnails are generated here by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Content Type Filter Buttons -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div class="flex flex-wrap gap-2">
                <a href="index.php?type=all" class="px-3 py-1.5 md:px-4 md:py-2 <?php echo ($content_type === 'all' || empty($_GET['type'])) ? 'bg-red-600 text-white' : 'bg-gray-900 text-gray-300 hover:bg-gray-800'; ?> rounded-lg transition-colors text-sm">
                    <i class="fas fa-layer-group mr-1"></i> All Content
                </a>
                <a href="index.php?type=movies" class="px-3 py-1.5 md:px-4 md:py-2 <?php echo $content_type === 'movies' ? 'bg-red-600 text-white' : 'bg-gray-900 text-gray-300 hover:bg-gray-800'; ?> rounded-lg transition-colors text-sm">
                    <i class="fas fa-film mr-1"></i> Movies
                </a>
                <a href="index.php?type=songs" class="px-3 py-1.5 md:px-4 md:py-2 <?php echo $content_type === 'songs' ? 'bg-red-600 text-white' : 'bg-gray-900 text-gray-300 hover:bg-gray-800'; ?> rounded-lg transition-colors text-sm">
                    <i class="fas fa-music mr-1"></i> Songs
                </a>
            </div>
            
            <div class="flex flex-wrap gap-2">
                <a href="index.php?type=<?php echo $content_type; ?>&filter=popular" class="px-3 py-1.5 md:px-4 md:py-2 <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'popular') ? 'bg-red-600 text-white' : 'bg-gray-900 text-gray-300 hover:bg-gray-800'; ?> rounded-lg transition-colors text-sm">
                    Popular
                </a>
                <a href="index.php?type=<?php echo $content_type; ?>&filter=new" class="px-3 py-1.5 md:px-4 md:py-2 <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'new') ? 'bg-red-600 text-white' : 'bg-gray-900 text-gray-300 hover:bg-gray-800'; ?> rounded-lg transition-colors text-sm">
                    New
                </a>
                <a href="index.php?type=<?php echo $content_type; ?>&filter=top_rated" class="px-3 py-1.5 md:px-4 md:py-2 <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'top_rated') ? 'bg-red-600 text-white' : 'bg-gray-900 text-gray-300 hover:bg-gray-800'; ?> rounded-lg transition-colors text-sm">
                    Top Rated
                </a>
            </div>
        </div>

        <!-- Active Filter Info -->
        <?php if (!empty($genre) || !empty($year) || !empty($language)): ?>
            <div class="mb-6 p-4 bg-gray-900 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">Active Filters:</h3>
                <div class="flex flex-wrap gap-2">
                    <?php if (!empty($genre)): ?>
                        <span class="px-3 py-1 bg-red-600 rounded-full text-sm">
                            Genre: <?php echo htmlspecialchars($genre); ?>
                            <a href="<?php echo remove_filter('genre'); ?>" class="ml-1 hover:text-gray-300">×</a>
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($year)): ?>
                        <span class="px-3 py-1 bg-red-600 rounded-full text-sm">
                            Year: <?php echo htmlspecialchars($year); ?>
                            <a href="<?php echo remove_filter('year'); ?>" class="ml-1 hover:text-gray-300">×</a>
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($language)): ?>
                        <span class="px-3 py-1 bg-red-600 rounded-full text-sm">
                            Language: <?php echo htmlspecialchars($language); ?>
                            <a href="<?php echo remove_filter('language'); ?>" class="ml-1 hover:text-gray-300">×</a>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Content Grid -->
        <section class="mb-10">
            <h2 class="text-xl md:text-2xl font-bold mb-5">
                <?php 
                    if ($content_type === 'movies') {
                        if (!empty($genre)) echo "Movies in " . htmlspecialchars($genre);
                        elseif (!empty($year)) echo "Movies from " . htmlspecialchars($year);
                        elseif (isset($_GET['filter'])) echo ucfirst(str_replace('_', ' ', $_GET['filter'])) . " Movies";
                        else echo "All Movies";
                    } elseif ($content_type === 'songs') {
                        if (!empty($genre)) echo "Songs in " . htmlspecialchars($genre);
                        elseif (isset($_GET['filter'])) echo ucfirst(str_replace('_', ' ', $_GET['filter'])) . " Songs";
                        else echo "All Songs";
                    } else {
                        echo "All Content";
                    }
                ?>
            </h2>
            
            <?php 
                $totalContent = count($movies) + count($songs);
                if ($totalContent > 0): 
            ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5" id="content-container">
                    <!-- Display Movies -->
                    <?php foreach ($movies as $movie): ?>
                        <div class="content-card-container">
                            <div class="bg-gray-800 rounded-xl overflow-hidden card-shadow transition-all duration-300 group relative hover:-translate-y-2 hover:hover-shadow content-card">
                                <!-- Content Poster/Player Area -->
                                <div class="content-poster relative overflow-hidden">
                                    <!-- Content Type Badge -->
                                    <div class="content-type-badge movie-badge">
                                        <i class="fas fa-film mr-1"></i> Movie
                                    </div>
                                    
                                    <!-- Movie Poster or Trailer Player -->
                                    <div id="poster-<?php echo $movie['movie_id']; ?>" class="w-full h-full">
                                        <?php 
                                        $movieImage = fixImagePath($movie['poster_image'] ?? '');
                                        if (!empty($movieImage)): ?>
                                            <img 
                                                src="<?php echo htmlspecialchars($movieImage); ?>" 
                                                alt="<?php echo htmlspecialchars($movie['title']); ?>" 
                                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                                onerror="this.onerror=null; this.parentElement.innerHTML = `
                                                    <div class='img-fallback movie-poster'>
                                                        <i class='fas fa-film'></i>
                                                        <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
                                                        <p><?php echo htmlspecialchars($movie['genre'] ?? 'Movie'); ?></p>
                                                    </div>
                                                `"
                                            >
                                        <?php else: ?>
                                            <!-- Default movie image with gradient and film icon -->
                                            <div class="img-fallback movie-poster">
                                                <i class="fas fa-film"></i>
                                                <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
                                                <p><?php echo htmlspecialchars($movie['genre'] ?? 'Movie'); ?></p>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Play Overlay for Trailer -->
                                        <?php if (!empty($movie['trailer_url'])): ?>
                                            <div class="player-overlay">
                                                <button 
                                                    class="play-button"
                                                    onclick="playTrailer(<?php echo $movie['movie_id']; ?>, '<?php echo htmlspecialchars($movie['trailer_url']); ?>')"
                                                >
                                                    <i class="fas fa-play"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Trailer Player (Initially Hidden) -->
                                    <div id="player-<?php echo $movie['movie_id']; ?>" class="player-container hidden">
                                        <div class="relative h-full">
                                            <iframe 
                                                id="frame-<?php echo $movie['movie_id']; ?>"
                                                class="w-full h-full" 
                                                frameborder="0" 
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                allowfullscreen
                                            ></iframe>
                                            <button 
                                                onclick="closePlayer(<?php echo $movie['movie_id']; ?>)"
                                                class="absolute top-2 right-2 bg-black/70 text-white rounded-full p-2 hover:bg-red-600 transition-colors z-20"
                                            >
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Content Info -->
                                <div class="content-info p-4">
                                    <div class="content-details">
                                        <div class="flex justify-between items-start mb-2">
                                            <h2 class="text-lg font-bold text-white"><?php echo htmlspecialchars($movie['title']); ?></h2>
                                            <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">
                                                <?php echo htmlspecialchars($movie['rating'] ?? 'N/A'); ?>
                                            </span>
                                        </div>
                                        <p class="text-gray-400 text-sm mb-3 line-clamp-2">
                                            <?php echo htmlspecialchars($movie['description'] ?? 'No description available'); ?>
                                        </p>
                                        
                                        <div class="flex justify-between text-sm text-gray-400">
                                            <div class="flex items-center">
                                                <i class="fas fa-clock mr-1"></i>
                                                <span><?php echo htmlspecialchars($movie['duration'] ?? 'N/A'); ?>m</span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-calendar mr-1"></i>
                                                <span><?php echo htmlspecialchars($movie['release_year'] ?? 'N/A'); ?></span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-tag mr-1"></i>
                                                <span><?php echo htmlspecialchars($movie['genre'] ?? 'N/A'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="action-buttons-container flex gap-2">
                                        <button 
                                            class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-3 rounded-lg transition-colors duration-300 flex items-center justify-center text-sm"
                                            onclick="addToFavorites(<?php echo $movie['movie_id']; ?>, 'movie')"
                                        >
                                            <i class="fas fa-heart mr-2"></i>
                                            Favorite
                                        </button>
                                        <?php if (!empty($movie['trailer_url'])): ?>
                                            <button 
                                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-3 rounded-lg transition-colors duration-300 flex items-center justify-center text-sm"
                                                onclick="playTrailer(<?php echo $movie['movie_id']; ?>, '<?php echo htmlspecialchars($movie['trailer_url']); ?>')"
                                            >
                                                <i class="fas fa-play mr-2"></i>
                                                Trailer
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <!-- Display Songs -->
                    <?php foreach ($songs as $song): ?>
                        <div class="content-card-container">
                            <div class="bg-gray-800 rounded-xl overflow-hidden card-shadow transition-all duration-300 group relative hover:-translate-y-2 hover:hover-shadow content-card">
                                <!-- Content Poster/Player Area -->
                                <div class="content-poster relative overflow-hidden">
                                    <!-- Content Type Badge -->
                                    <div class="content-type-badge song-badge">
                                        <i class="fas fa-music mr-1"></i> Song
                                    </div>
                                    
                                    <!-- Song Cover or Audio Player -->
                                    <div id="song-poster-<?php echo $song['song_id']; ?>" class="w-full h-full">
                                        <?php 
                                        $songImage = fixImagePath($song['cover_image'] ?? '');
                                        if (!empty($songImage)): ?>
                                            <img 
                                                src="<?php echo htmlspecialchars($songImage); ?>" 
                                                alt="<?php echo htmlspecialchars($song['title']); ?>" 
                                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                                onerror="this.onerror=null; this.parentElement.innerHTML = `
                                                    <div class='img-fallback song-cover'>
                                                        <i class='fas fa-music'></i>
                                                        <h3><?php echo htmlspecialchars($song['title']); ?></h3>
                                                        <p><?php echo htmlspecialchars($song['artist']); ?></p>
                                                    </div>
                                                `"
                                            >
                                        <?php else: ?>
                                            <!-- Default song image with gradient and music icon -->
                                            <div class="img-fallback song-cover">
                                                <i class="fas fa-music"></i>
                                                <h3><?php echo htmlspecialchars($song['title']); ?></h3>
                                                <p><?php echo htmlspecialchars($song['artist']); ?></p>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Play Overlay for Audio -->
                                        <?php if (!empty($song['audio_file'])): ?>
                                            <div class="player-overlay">
                                                <button 
                                                    class="play-button"
                                                    onclick="playAudio(<?php echo $song['song_id']; ?>, '<?php echo htmlspecialchars(fixImagePath($song['audio_file'])); ?>')"
                                                >
                                                    <i class="fas fa-play"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Audio Player (Initially Hidden) - UPDATED STRUCTURE -->
                                    <div id="audio-player-<?php echo $song['song_id']; ?>" class="player-container hidden">
                                        <div class="audio-player-container bg-gradient-to-r from-blue-900 to-purple-900">
                                            <div class="audio-player-content">
                                                <i class="fas fa-music text-4xl text-white mb-4"></i>
                                                <h4 class="text-white font-bold mb-2"><?php echo htmlspecialchars($song['title']); ?></h4>
                                                <p class="text-gray-300 text-sm mb-6"><?php echo htmlspecialchars($song['artist']); ?></p>
                                                
                                                <!-- Audio element with controls -->
                                                <audio 
                                                    id="audio-<?php echo $song['song_id']; ?>" 
                                                    controls 
                                                    class="w-full mb-4"
                                                >
                                                    <source src="<?php echo htmlspecialchars(fixImagePath($song['audio_file'] ?? '')); ?>" type="audio/mpeg">
                                                    Your browser does not support the audio element.
                                                </audio>
                                                
                                                <!-- Manual play button for browsers that block autoplay -->
                                                
                                            </div>
                                            <button 
                                                onclick="closeAudioPlayer(<?php echo $song['song_id']; ?>)"
                                                class="absolute top-2 right-2 bg-black/70 text-white rounded-full p-2 hover:bg-red-600 transition-colors"
                                            >
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Content Info -->
                                <div class="content-info p-4">
                                    <div class="content-details">
                                        <div class="flex justify-between items-start mb-2">
                                            <h2 class="text-lg font-bold text-white"><?php echo htmlspecialchars($song['title']); ?></h2>
                                            <span class="bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded">
                                                <?php echo htmlspecialchars($song['genre'] ?? 'Music'); ?>
                                            </span>
                                        </div>
                                        <p class="text-gray-400 text-sm mb-3">
                                            By <?php echo htmlspecialchars($song['artist']); ?>
                                            <?php if (!empty($song['album'])): ?>
                                                <br>Album: <?php echo htmlspecialchars($song['album']); ?>
                                            <?php endif; ?>
                                        </p>
                                        
                                        <div class="flex justify-between text-sm text-gray-400">
                                            <div class="flex items-center">
                                                <i class="fas fa-clock mr-1"></i>
                                                <span><?php echo htmlspecialchars(floor($song['duration'] / 60) . ':' . sprintf('%02d', $song['duration'] % 60)); ?></span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-globe mr-1"></i>
                                                <span><?php echo htmlspecialchars($song['language'] ?? 'N/A'); ?></span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-compact-disc mr-1"></i>
                                                <span><?php echo htmlspecialchars($song['genre'] ?? 'N/A'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="action-buttons-container flex gap-2">
                                        <button 
                                            class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-3 rounded-lg transition-colors duration-300 flex items-center justify-center text-sm"
                                            onclick="addToFavorites(<?php echo $song['song_id']; ?>, 'song')"
                                        >
                                            <i class="fas fa-heart mr-2"></i>
                                            Favorite
                                        </button>
                                        <?php if (!empty($song['audio_file'])): ?>
                                            <button 
                                                class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-3 rounded-lg transition-colors duration-300 flex items-center justify-center text-sm"
                                                onclick="playAudio(<?php echo $song['song_id']; ?>, '<?php echo htmlspecialchars(fixImagePath($song['audio_file'])); ?>')"
                                            >
                                                <i class="fas fa-play mr-2"></i>
                                                Play
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-12 bg-gray-900 rounded-xl">
                    <i class="fas fa-search text-6xl text-gray-600 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-300 mb-2">No content found</h3>
                    <p class="text-gray-400">Try selecting a different filter or category.</p>
                    <a href="index.php" class="inline-block mt-4 px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                        Clear Filters
                    </a>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <!-- Success Message -->
    <div id="success-message" class="fixed bottom-4 right-4 z-50 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg hidden">
        <div class="flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span id="success-text" class="text-sm">Operation completed successfully!</span>
        </div>
    </div>

    <script>
        // --- Carousel Movie Data (5 Slides) ---
        const movies = [
            {
                title: "AVATAR: WAY OF WATER",
                year: "2022",
                rating: "IMDb 7.8",
                genre: "Sci-Fi • Adventure",
                desc: "Jake Sully lives with his newfound family formed on the extrasolar moon Pandora. Once a familiar threat returns to finish what was previously started, Jake must work with Neytiri to protect their home.",
                image: "https://www.yashrajfilms.com/images/default-source/movies/hrithik-vs-tiger/hrithik-v-s-tiger47bda6a026f56f7f9f64ff0b00090313.jpg?sfvrsn=9e48c9cc_17",
                cast: "Sam Worthington, Zoe Saldana, Sigourney Weaver",
                director: "James Cameron"
            },
            {
                title: "OPPENHEIMER",
                year: "2023",
                rating: "IMDb 8.6",
                genre: "Biography • Drama",
                desc: "The story of American scientist J. Robert Oppenheimer and his role in the development of the atomic bomb. A cinematic masterpiece that explores the paradox of saving the world to destroy it.",
                image: "https://mir-s3-cdn-cf.behance.net/project_modules/hd/62804b18669443.562cd567cbcd8.jpg",
                cast: "Cillian Murphy, Emily Blunt, Matt Damon",
                director: "Christopher Nolan"
            },
            {
                title: "SPIDER-MAN: ACROSS THE SPIDER-VERSE",
                year: "2023",
                rating: "IMDb 8.9",
                genre: "Animation • Action",
                desc: "Miles Morales catapults across the Multiverse, where he encounters a team of Spider-People charged with protecting its very existence. A visual spectacle of color and emotion.",
                image: "https://i.ytimg.com/vi/s7njeTw9lSU/maxresdefault.jpg",
                cast: "Shameik Moore, Hailee Steinfeld, Oscar Isaac",
                director: "Joaquim Dos Santos, Kemp Powers, Justin K. Thompson"
            },
            {
                title: "DUNE: PART TWO",
                year: "2024",
                rating: "IMDb 8.8",
                genre: "Sci-Fi • Epic",
                desc: "Paul Atreides unites with Chani and the Fremen while on a warpath of revenge against the conspirators who destroyed his family. The sand worms await in this epic conclusion.",
                image: "https://wallpapercave.com/wp/wp8807385.jpg",
                cast: "Timothée Chalamet, Zendaya, Rebecca Ferguson",
                director: "Denis Villeneuve"
            },
            {
                title: "JOHN WICK: CHAPTER 4",
                year: "2023",
                rating: "IMDb 7.9",
                genre: "Action • Thriller",
                desc: "John Wick uncovers a path to defeating The High Table. But before he can earn his freedom, Wick must face off against a new enemy with powerful alliances across the globe.",
                image: "https://wallpapercave.com/wp/wp1945939.jpg",
                cast: "Keanu Reeves, Donnie Yen, Bill Skarsgård",
                director: "Chad Stahelski"
            }
        ];

        // --- Carousel State and Constants ---
        let currentIndex = 0;
        let autoPlayInterval;
        const slideDuration = 6000;
        
        // --- Carousel DOM Elements ---
        const bgImage = document.getElementById('active-bg');
        const titleEl = document.getElementById('movie-title');
        const descEl = document.getElementById('movie-desc');
        const yearEl = document.getElementById('movie-year');
        const ratingEl = document.getElementById('movie-rating');
        const genreEl = document.getElementById('movie-genre');
        const textContainer = document.getElementById('text-content');
        const thumbnailsContainer = document.getElementById('thumbnails-container');
        
        // Success Message Elements
        const successMessage = document.getElementById('success-message');
        const successText = document.getElementById('success-text');

        // --- Carousel Functions ---

        /**
         * Creates and initializes the clickable thumbnails.
         */
        function initThumbnails() {
            thumbnailsContainer.innerHTML = '';
            movies.forEach((movie, index) => {
                const thumb = document.createElement('div');
                thumb.className = `thumbnail relative w-32 h-24 flex-shrink-0 rounded-lg overflow-hidden cursor-pointer border-2 border-transparent transition-all duration-300 group hover:scale-105`;
                
                // Add event listener to change the slide
                thumb.onclick = () => {
                    if (currentIndex !== index) {
                        currentIndex = index;
                        updateSlide();
                        resetTimer(); 
                    }
                };
                
                thumb.innerHTML = `
                    <img src="${movie.image}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-black/40 group-hover:bg-transparent transition"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black to-transparent">
                        <p class="text-white text-xs font-semibold truncate">${movie.title}</p>
                    </div>
                `;
                thumbnailsContainer.appendChild(thumb);
            });
        }

        /**
         * Scroll thumbnails to show active thumbnail in the center
         */
        function scrollThumbnailsToActive() {
            const container = thumbnailsContainer.parentElement;
            const activeThumb = thumbnailsContainer.querySelector('.thumbnail.active');
            
            if (activeThumb && container) {
                const containerWidth = container.offsetWidth;
                const thumbWidth = activeThumb.offsetWidth;
                const thumbLeft = activeThumb.offsetLeft;
                const thumbCenter = thumbLeft - (containerWidth / 2) + (thumbWidth / 2);
                
                container.scrollTo({
                    left: thumbCenter,
                    behavior: 'smooth'
                });
            }
        }

        /**
         * Updates the main hero slide content and background.
         */
        function updateSlide() {
            const movie = movies[currentIndex];

            // 1. Update background image
            bgImage.style.backgroundImage = `url('${movie.image}')`;
            
            // 2. Update the text content
            titleEl.textContent = movie.title;
            descEl.textContent = movie.desc;
            yearEl.textContent = movie.year;
            ratingEl.textContent = movie.rating;
            genreEl.textContent = movie.genre;

            // 3. Apply entrance animation for text
            textContainer.classList.remove('animate-fade-in-up');
            void textContainer.offsetWidth; // Force reflow to restart animation
            textContainer.classList.add('animate-fade-in-up');

            // 4. Update Thumbnails Active State
            const thumbs = document.querySelectorAll('.thumbnail');
            
            thumbs.forEach((t, i) => {
                if (i === currentIndex) {
                    t.classList.add('active');
                } else {
                    t.classList.remove('active');
                }
            });
            
            // 5. Auto scroll to active thumbnail
            scrollThumbnailsToActive();
        }

        /**
         * Moves to the next slide, wrapping around.
         */
        function nextSlide() {
            currentIndex = (currentIndex + 1) % movies.length;
            updateSlide();
        }

        /**
         * Moves to the previous slide, wrapping around.
         */
        function prevSlide() {
            currentIndex = (currentIndex - 1 + movies.length) % movies.length;
            updateSlide();
        }

        // --- Carousel Auto Play Logic ---
        function startTimer() {
            clearInterval(autoPlayInterval);
            autoPlayInterval = setInterval(() => {
                nextSlide();
            }, slideDuration);
        }

        function resetTimer() {
            startTimer();
        }

        // --- Content Player Functions ---

        /**
         * Plays a movie trailer inside the card
         */
        function playTrailer(contentId, trailerUrl) {
            const poster = document.getElementById(`poster-${contentId}`);
            const player = document.getElementById(`player-${contentId}`);
            const frame = document.getElementById(`frame-${contentId}`);
            
            if (poster && player && frame) {
                // Hide poster, show player
                poster.classList.add('hidden');
                player.classList.remove('hidden');
                
                // Set iframe source with autoplay
                const videoId = extractYouTubeId(trailerUrl);
                if (videoId) {
                    frame.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&modestbranding=1`;
                } else {
                    frame.src = trailerUrl;
                }
            }
        }

        /**
         * Closes the trailer player
         */
        function closePlayer(contentId) {
            const poster = document.getElementById(`poster-${contentId}`);
            const player = document.getElementById(`player-${contentId}`);
            const frame = document.getElementById(`frame-${contentId}`);
            
            if (poster && player && frame) {
                // Hide player, show poster
                player.classList.add('hidden');
                poster.classList.remove('hidden');
                
                // Stop video
                frame.src = '';
            }
        }

        /**
         * Plays audio inside the card - FIXED VERSION
         */
        function playAudio(contentId, audioUrl) {
            const poster = document.getElementById(`song-poster-${contentId}`);
            const player = document.getElementById(`audio-player-${contentId}`);
            
            if (poster && player) {
                // Hide poster, show player
                poster.classList.add('hidden');
                player.classList.remove('hidden');
                
                // Get or create audio element
                let audioElement = document.getElementById(`audio-${contentId}`);
                
                if (!audioElement) {
                    // Create audio element if it doesn't exist
                    audioElement = document.createElement('audio');
                    audioElement.id = `audio-${contentId}`;
                    audioElement.controls = true;
                    audioElement.className = 'w-full mb-4';
                    
                    const source = document.createElement('source');
                    source.src = audioUrl;
                    source.type = 'audio/mpeg';
                    
                    audioElement.appendChild(source);
                    audioElement.innerHTML += 'Your browser does not support the audio element.';
                    
                    // Add to player
                    const playerContent = player.querySelector('.audio-player-content');
                    if (playerContent) {
                        playerContent.insertBefore(audioElement, playerContent.querySelector('.audio-control-btn'));
                    }
                } else {
                    // Update existing audio source if needed
                    const source = audioElement.querySelector('source');
                    if (source && source.src !== audioUrl) {
                        source.src = audioUrl;
                        audioElement.load();
                    }
                }
                
                // Try to play automatically (may be blocked by browser)
                setTimeout(() => {
                    const playPromise = audioElement.play();
                    
                    if (playPromise !== undefined) {
                        playPromise.then(() => {
                            console.log("Audio playing successfully");
                            // Hide manual play button if autoplay succeeds
                            const manualBtn = document.getElementById(`manual-play-${contentId}`);
                            if (manualBtn) {
                                manualBtn.classList.add('hidden');
                            }
                        }).catch(error => {
                            console.log("Auto-play blocked:", error);
                            // Show manual play button
                            const manualBtn = document.getElementById(`manual-play-${contentId}`);
                            if (manualBtn) {
                                manualBtn.classList.remove('hidden');
                            }
                            showSuccess("Click the play button to start audio");
                        });
                    }
                }, 100);
            }
        }

        /**
         * Manual play function for audio (when autoplay is blocked)
         */
        function manualPlayAudio(contentId) {
            const audioElement = document.getElementById(`audio-${contentId}`);
            if (audioElement) {
                audioElement.play().then(() => {
                    // Hide manual button on success
                    const manualBtn = document.getElementById(`manual-play-${contentId}`);
                    if (manualBtn) {
                        manualBtn.classList.add('hidden');
                    }
                }).catch(error => {
                    console.log("Manual play failed:", error);
                    showSuccess("Please use the audio player controls");
                });
            }
        }

        /**
         * Closes the audio player
         */
        function closeAudioPlayer(contentId) {
            const poster = document.getElementById(`song-poster-${contentId}`);
            const player = document.getElementById(`audio-player-${contentId}`);
            const audioElement = document.getElementById(`audio-${contentId}`);
            
            if (poster && player && audioElement) {
                // Hide player, show poster
                player.classList.add('hidden');
                poster.classList.remove('hidden');
                
                // Pause audio and reset
                audioElement.pause();
                audioElement.currentTime = 0;
                
                // Hide manual play button
                const manualBtn = document.getElementById(`manual-play-${contentId}`);
                if (manualBtn) {
                    manualBtn.classList.add('hidden');
                }
            }
        }

        /**
         * Extracts YouTube video ID from URL
         */
        function extractYouTubeId(url) {
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
            const match = url.match(regExp);
            return (match && match[2].length === 11) ? match[2] : null;
        }

        // --- Utility functions ---
        function showSuccess(message) {
            successText.textContent = message;
            successMessage.classList.remove('hidden');
            setTimeout(() => {
                successMessage.classList.add('hidden');
            }, 3000);
        }

        function addToFavorites(contentId, contentType) {
            // Check if user is logged in
            const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
            
            if (!isLoggedIn) {
                showSuccess("Please login to add to favorites");
                // Redirect to login page or show login modal
                setTimeout(() => {
                    window.location.href = "login.php";
                }, 1500);
                return;
            }
            
            fetch('../library/add_favorite.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    content_id: contentId,
                    content_type: contentType
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showSuccess('Added to favorites!');
                } else {
                    showSuccess('Already in favorites!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showSuccess('Error adding to favorites');
            });
        }

        // --- Event Listeners ---
        document.getElementById('next-btn').addEventListener('click', () => {
            nextSlide();
            resetTimer();
        });

        document.getElementById('prev-btn').addEventListener('click', () => {
            prevSlide();
            resetTimer();
        });
        
        document.getElementById('mobile-next-btn').addEventListener('click', () => {
            nextSlide();
            resetTimer();
        });

        document.getElementById('mobile-prev-btn').addEventListener('click', () => {
            prevSlide();
            resetTimer();
        });

        // --- Initialization ---
        window.onload = function() {
            // Initialize carousel
            initThumbnails();
            updateSlide();
            startTimer();
        }

    </script>
</body>
</html>

<?php 
// Helper function to remove a filter from URL
function remove_filter($filter_name) {
    $params = $_GET;
    unset($params[$filter_name]);
    return 'index.php?' . http_build_query($params);
}
include("../include/footer.php");
?>
[file content end]