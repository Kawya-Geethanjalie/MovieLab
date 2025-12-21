
<?php 
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

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
            color: #ffffff; /* White text */
            font-size: 14px;
            cursor: pointer;
        }

        .filter-select option {
            background-color: var(--card-bg);
            color: #ffffff; /* White text for options */
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

        /* Loading State */
        .loading-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-gray);
            grid-column: 1 / -1;
        }

        .loading-spinner {
            border: 4px solid rgba(255, 255, 255, 0.1);
            border-left-color: var(--primary-red);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
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
                <button class="btn btn-secondary" id="exportBtn">
                    <i class="fas fa-download"></i>
                    Export
                </button>
                <a href="add-content.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Add New Content
                </a>
            </div>
        </div>

        <!-- Alerts Container -->
        <div id="alertsContainer"></div>

        <!-- Filters Section -->
        <div class="filters-section">
            <div class="search-box">
                <input type="text" class="search-input" id="searchInput" placeholder="Search movies, songs..." >
                <button class="btn btn-primary" id="searchBtn">
                    <i class="fas fa-search"></i>
                    Search
                </button>
                <button class="btn btn-secondary" id="resetFiltersBtn">
                    <i class="fas fa-redo"></i>
                    Reset
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
                        <!-- Genres will be loaded dynamically -->
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
            <div class="loading-state">
                <div class="loading-spinner"></div>
                <p>Loading content...</p>
            </div>
        </div>
    </div>

<script>
    // Global variables
    let allContent = [];
    let genres = [];
    let filteredContent = [];

    // පිටුව Load වන විට දත්ත ලබා ගැනීම
    async function loadContent() {
        try {
            const response = await fetch('../library/content_manage_backend.php');
            const data = await response.json();
            
            if (data.success) {
                allContent = data.content;
                genres = data.genres || [];
                
                displayAlerts(data);
                populateGenreFilter();
                applyFilters();
            } else {
                showError(data.error_message || 'Failed to load content');
                displayContent([]);
            }
        } catch (error) {
            console.error('Error loading content:', error);
            showError('Failed to load content. Please try again.');
            displayContent([]);
        }
    }

    // Alerts පෙන්වීම
    function displayAlerts(data) {
        const alertsContainer = document.getElementById('alertsContainer');
        if(!alertsContainer) return;
        alertsContainer.innerHTML = '';
        
        if (data.success_message || data.error_message) {
            const isError = !!data.error_message;
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert ${isError ? 'alert-error' : 'alert-success'}`;
            alertDiv.innerHTML = `<i class="fas ${isError ? 'fa-exclamation-circle' : 'fa-check-circle'}"></i> ${data.success_message || data.error_message}`;
            alertsContainer.appendChild(alertDiv);
            
            setTimeout(() => { alertDiv.remove(); }, 5000);
        }
    }

    // Genre filter එකට දත්ත ඇතුළත් කිරීම
    function populateGenreFilter() {
        const genreFilter = document.getElementById('genreFilter');
        if(!genreFilter) return;
        
        while (genreFilter.options.length > 1) { genreFilter.remove(1); }
        
        const uniqueGenres = [...new Set(allContent.map(item => item.genre))].filter(g => g);
        
        uniqueGenres.forEach(genre => {
            const option = document.createElement('option');
            option.value = genre;
            option.textContent = genre;
            genreFilter.appendChild(option);
        });
    }

    // දත්ත CARDS ආකාරයෙන් පෙන්වීම
    function displayContent(contentArray) {
        const contentGrid = document.getElementById('contentGrid');
        if(!contentGrid) return;
        
        contentGrid.innerHTML = '';

        if (!contentArray || contentArray.length === 0) {
            contentGrid.innerHTML = `<div class="empty-state" style="grid-column: 1/-1; text-align: center; padding: 50px; color: #888;">...No Content Found...</div>`;
            return;
        }

        contentArray.forEach(content => {
            const rawPath = content.poster_image;
            let imageSrc = (rawPath && rawPath !== null && rawPath !== "") 
                ? '../' + rawPath.replace(/^\//, '')
                : (content.type === 'movie' ? '../assets/movie.jpg' : '../assets/song.jpg');
            
            const badgeColor = content.type === 'movie' ? '#E50914' : '#8B5CF6';
            
            contentGrid.innerHTML += `
                <div class="content-card">
                    <div class="content-poster">
                        <img src="${imageSrc}" 
                             onerror="this.onerror=null; this.src='../assets/${content.type}.jpg';" 
                             alt="${escapeHtml(content.title)}">
                        <span class="content-type ${content.type}" style="background: ${badgeColor}">
                            ${content.type.toUpperCase()}
                        </span>
                    </div>
                    <div class="content-info">
                        <h3 class="content-title">${escapeHtml(content.title)}</h3>
                        <div class="content-meta">
                            <div class="meta-item">
                                <i class="fas fa-tag"></i>
                                <span>${content.genre || 'N/A'}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                <span>${content.duration || 0} min</span>
                            </div>
                            ${content.type === 'movie' && content.rating ? `
                            <div class="meta-item">
                                <i class="fas fa-star"></i>
                                <span>${content.rating}/10</span>
                            </div>` : ''}
                        </div>
                        <p class="content-description">${escapeHtml(content.description || 'No description available')}</p>
                        <div class="content-actions">
                            <a href="edit-content.php?type=${content.type}&id=${content.movie_id || content.content_id}" class="btn btn-sm btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button onclick="deleteContent(${content.movie_id || content.content_id}, '${content.type}')" class="btn btn-sm btn-delete">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>`;
        });
    }

    // Filters ක්‍රියාත්මක කිරීම
    function applyFilters() {
        const contentType = document.getElementById('contentTypeFilter').value;
        const genre = document.getElementById('genreFilter').value;
        const sortBy = document.getElementById('sortFilter').value;
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        
        filteredContent = allContent.filter(content => {
            const matchesType = !contentType || content.type === contentType;
            const matchesGenre = !genre || content.genre === genre;
            const matchesSearch = !searchTerm || 
                                 content.title.toLowerCase().includes(searchTerm) || 
                                 (content.genre && content.genre.toLowerCase().includes(searchTerm)) ||
                                 (content.description && content.description.toLowerCase().includes(searchTerm));
            
            return matchesType && matchesGenre && matchesSearch;
        });
        
        sortContent(filteredContent, sortBy);
        displayContent(filteredContent);
    }

    function sortContent(contentArray, sortBy) {
        if (sortBy === 'title') {
            contentArray.sort((a, b) => a.title.localeCompare(b.title));
        } else if (sortBy === 'views') {
            contentArray.sort((a, b) => (b.views || 0) - (a.views || 0));
        } else if (sortBy === 'oldest') {
            contentArray.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
        } else {
            contentArray.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Delete content function - Updated with confirmation and proper redirect
    async function deleteContent(id, type) {
        if (!confirm(`Are you sure you want to delete this ${type}? This action cannot be undone.`)) {
            return;
        }

        try {
            // Send delete request to backend
            const response = await fetch(`../library/content_manage_backend.php?delete_id=${id}&type=${type}`, {
                method: 'GET'
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Show success message
                showAlert('Content deleted successfully!', 'success');
                
                // Reload content after deletion
                setTimeout(() => {
                    loadContent();
                }, 1500);
            } else {
                showAlert(data.error_message || 'Failed to delete content', 'error');
            }
        } catch (error) {
            console.error('Error deleting content:', error);
            showAlert('Failed to delete content. Please try again.', 'error');
        }
    }

    // Show alert message
    function showAlert(message, type) {
        const alertsContainer = document.getElementById('alertsContainer');
        if (!alertsContainer) return;
        
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.innerHTML = `<i class="fas ${type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle'}"></i> ${message}`;
        alertsContainer.appendChild(alertDiv);
        
        setTimeout(() => { 
            alertDiv.style.opacity = '0';
            setTimeout(() => { alertDiv.remove(); }, 300);
        }, 5000);
    }

    function showError(message) {
        showAlert(message, 'error');
    }

    // Event Listeners
    document.addEventListener('DOMContentLoaded', () => {
        loadContent();
        
        document.getElementById('contentTypeFilter').addEventListener('change', applyFilters);
        document.getElementById('genreFilter').addEventListener('change', applyFilters);
        document.getElementById('sortFilter').addEventListener('change', applyFilters);
        document.getElementById('searchInput').addEventListener('input', applyFilters);
        document.getElementById('searchBtn').addEventListener('click', applyFilters);

        document.getElementById('resetFiltersBtn').addEventListener('click', () => {
            document.getElementById('contentTypeFilter').value = '';
            document.getElementById('genreFilter').value = '';
            document.getElementById('sortFilter').value = 'newest';
            document.getElementById('searchInput').value = '';
            applyFilters();
        });
    });
</script>
</body>
</html>
[file content end]