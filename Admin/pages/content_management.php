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

        .content-status {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-active {
            background: var(--success);
            color: white;
        }

        .status-inactive {
            background: var(--text-gray);
            color: white;
        }

        .status-featured {
            background: var(--warning);
            color: black;
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

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .pagination-btn {
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            color: var(--text-light);
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .pagination-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .pagination-btn.active {
            background: var(--primary-red);
            color: white;
            border-color: var(--primary-red);
        }

        .pagination-info {
            color: var(--text-gray);
            font-size: 14px;
            margin: 0 15px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-gray);
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

            .pagination {
                flex-direction: column;
                gap: 8px;
            }

            .pagination-info {
                margin: 10px 0;
            }
        }

        /* Loading Animation */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid var(--primary-red);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
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
                <a href="add-content.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Add New Content
                </a>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="filters-section">
            <div class="search-box">
                <input type="text" class="search-input" placeholder="Search movies, TV shows...">
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
                    <label class="filter-label">Genre</label>
                    <select class="filter-select">
                        <option value="">All Genres</option>
                        <option value="action">Action</option>
                        <option value="drama">Drama</option>
                        <option value="comedy">Comedy</option>
                        <option value="romance">Romance</option>
                        <option value="thriller">Thriller</option>
                        <option value="horror">Horror</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Category</label>
                    <select class="filter-select">
                        <option value="">All Categories</option>
                        <option value="movie">Movies</option>
                        <option value="tv">TV Shows</option>
                        <option value="documentary">Documentaries</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select class="filter-select">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="featured">Featured</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Sort By</label>
                    <select class="filter-select">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="title">Title A-Z</option>
                        <option value="views">Most Viewed</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Content Card 1 -->
            <div class="content-card">
                <div class="content-poster">
                    <i class="fas fa-film"></i>
                    <span class="content-status status-active">Active</span>
                </div>
                <div class="content-info">
                    <h3 class="content-title">Kusa Paba</h3>
                    <div class="content-meta">
                        <span class="meta-item">
                            <i class="fas fa-film"></i>
                            Movie
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-clock"></i>
                            2h 15m
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-eye"></i>
                            45.6K
                        </span>
                    </div>
                    <p class="content-description">
                        A romantic drama set in the beautiful landscapes of Sri Lanka, exploring love and tradition.
                    </p>
                    <div class="content-actions">
                        <button class="btn btn-sm btn-edit">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn btn-sm btn-view">
                            <i class="fas fa-eye"></i>
                            View
                        </button>
                        <button class="btn btn-sm btn-delete">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content Card 2 -->
            <div class="content-card">
                <div class="content-poster">
                    <i class="fas fa-film"></i>
                    <span class="content-status status-featured">Featured</span>
                </div>
                <div class="content-info">
                    <h3 class="content-title">Ran Kevita 2</h3>
                    <div class="content-meta">
                        <span class="meta-item">
                            <i class="fas fa-film"></i>
                            Movie
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-clock"></i>
                            2h 30m
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-eye"></i>
                            38.9K
                        </span>
                    </div>
                    <p class="content-description">
                        The sequel to the blockbuster action thriller with intense sequences and stunning visuals.
                    </p>
                    <div class="content-actions">
                        <button class="btn btn-sm btn-edit">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn btn-sm btn-view">
                            <i class="fas fa-eye"></i>
                            View
                        </button>
                        <button class="btn btn-sm btn-delete">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content Card 3 -->
            <div class="content-card">
                <div class="content-poster">
                    <i class="fas fa-tv"></i>
                    <span class="content-status status-active">Active</span>
                </div>
                <div class="content-info">
                    <h3 class="content-title">Sinhala Comedy Special</h3>
                    <div class="content-meta">
                        <span class="meta-item">
                            <i class="fas fa-tv"></i>
                            TV Show
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-list"></i>
                            12 Episodes
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-eye"></i>
                            32.4K
                        </span>
                    </div>
                    <p class="content-description">
                        Hilarious comedy series featuring top Sri Lankan comedians in various funny situations.
                    </p>
                    <div class="content-actions">
                        <button class="btn btn-sm btn-edit">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn btn-sm btn-view">
                            <i class="fas fa-eye"></i>
                            View
                        </button>
                        <button class="btn btn-sm btn-delete">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content Card 4 -->
            <div class="content-card">
                <div class="content-poster">
                    <i class="fas fa-film"></i>
                    <span class="content-status status-inactive">Inactive</span>
                </div>
                <div class="content-info">
                    <h3 class="content-title">Action Thriller 2024</h3>
                    <div class="content-meta">
                        <span class="meta-item">
                            <i class="fas fa-film"></i>
                            Movie
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-clock"></i>
                            1h 55m
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-eye"></i>
                            28.7K
                        </span>
                    </div>
                    <p class="content-description">
                        High-octane action movie with breathtaking stunts and a gripping storyline.
                    </p>
                    <div class="content-actions">
                        <button class="btn btn-sm btn-edit">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn btn-sm btn-view">
                            <i class="fas fa-eye"></i>
                            View
                        </button>
                        <button class="btn btn-sm btn-delete">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content Card 5 -->
            <div class="content-card">
                <div class="content-poster">
                    <i class="fas fa-video"></i>
                    <span class="content-status status-active">Active</span>
                </div>
                <div class="content-info">
                    <h3 class="content-title">Wildlife Documentary</h3>
                    <div class="content-meta">
                        <span class="meta-item">
                            <i class="fas fa-video"></i>
                            Documentary
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-clock"></i>
                            1h 30m
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-eye"></i>
                            15.2K
                        </span>
                    </div>
                    <p class="content-description">
                        Explore the beautiful wildlife of Sri Lanka through this stunning documentary.
                    </p>
                    <div class="content-actions">
                        <button class="btn btn-sm btn-edit">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn btn-sm btn-view">
                            <i class="fas fa-eye"></i>
                            View
                        </button>
                        <button class="btn btn-sm btn-delete">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content Card 6 -->
            <div class="content-card">
                <div class="content-poster">
                    <i class="fas fa-tv"></i>
                    <span class="content-status status-featured">Featured</span>
                </div>
                <div class="content-info">
                    <h3 class="content-title">Historical Drama Series</h3>
                    <div class="content-meta">
                        <span class="meta-item">
                            <i class="fas fa-tv"></i>
                            TV Series
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-list"></i>
                            8 Episodes
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-eye"></i>
                            22.1K
                        </span>
                    </div>
                    <p class="content-description">
                        A captivating historical drama based on Sri Lankan history and culture.
                    </p>
                    <div class="content-actions">
                        <button class="btn btn-sm btn-edit">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn btn-sm btn-view">
                            <i class="fas fa-eye"></i>
                            View
                        </button>
                        <button class="btn btn-sm btn-delete">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <button class="pagination-btn">
                <i class="fas fa-chevron-left"></i>
                Previous
            </button>
            <button class="pagination-btn active">1</button>
            <button class="pagination-btn">2</button>
            <button class="pagination-btn">3</button>
            <span class="pagination-info">...</span>
            <button class="pagination-btn">8</button>
            <button class="pagination-btn">
                Next
                <i class="fas fa-chevron-right"></i>
            </button>
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
        document.querySelectorAll('.filter-select').forEach(select => {
            select.addEventListener('change', function() {
                // Add filter logic here
                console.log('Filter changed:', this.value);
            });
        });

        // Delete confirmation
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const contentTitle = this.closest('.content-card').querySelector('.content-title').textContent;
                if (confirm(`Are you sure you want to delete "${contentTitle}"?`)) {
                    // Add delete logic here
                    this.closest('.content-card').style.opacity = '0.5';
                    setTimeout(() => {
                        this.closest('.content-card').remove();
                    }, 500);
                }
            });
        });

        // Pagination
        document.querySelectorAll('.pagination-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (!this.classList.contains('active')) {
                    document.querySelectorAll('.pagination-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    // Add pagination logic here
                }
            });
        });
    </script>
</body>
</html>