<?php
// Check if session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

// Include header
include("../include/header.php");
?>

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
        --info: #3B82F6;
        --purple: #8B5CF6;
        --pink: #EC4899;
    }

    body {
        min-height: 100vh;
        background: linear-gradient(135deg, var(--dark-bg) 0%, #1a1a1a 100%);
        color: #fff;
        overflow-x: hidden;
    }

    /* Category Tools Styles */
    .category-tools {
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

    /* Stats Overview */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .stat-card {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.05);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
    }

    .stat-title {
        font-size: 14px;
        color: var(--text-gray);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .stat-change {
        font-size: 12px;
        color: var(--success);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Main Content Grid */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 25px;
    }

    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Category Management */
    .category-management {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-light);
    }

    .section-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* Category List */
    .category-list {
        max-height: 400px;
        overflow-y: auto;
    }

    .category-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        transition: background 0.3s ease;
    }

    .category-item:hover {
        background: rgba(255, 255, 255, 0.02);
    }

    .category-item:last-child {
        border-bottom: none;
    }

    .category-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .category-color {
        width: 16px;
        height: 16px;
        border-radius: 4px;
    }

    .category-details {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .category-name {
        font-weight: 500;
        color: var(--text-light);
    }

    .category-count {
        font-size: 12px;
        color: var(--text-gray);
    }

    .category-actions {
        display: flex;
        gap: 8px;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
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

    /* Add Category Form */
    .add-category-form {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        color: var(--text-light);
        margin-bottom: 8px;
        font-weight: 500;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        color: var(--text-light);
        font-size: 14px;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary-red);
    }

    .form-textarea {
        resize: vertical;
        min-height: 80px;
    }

    .color-picker {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .color-option {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .color-option:hover {
        transform: scale(1.1);
    }

    .color-option.selected {
        border-color: var(--text-light);
        transform: scale(1.1);
    }

    /* Genre Distribution */
    .genre-distribution {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.05);
        grid-column: 1 / -1;
    }

    .distribution-chart {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .distribution-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: rgba(255, 255, 255, 0.02);
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .distribution-color {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    .distribution-info {
        flex: 1;
    }

    .distribution-name {
        font-size: 14px;
        color: var(--text-light);
        margin-bottom: 4px;
    }

    .distribution-bar {
        height: 6px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
        overflow: hidden;
    }

    .distribution-fill {
        height: 100%;
        border-radius: 3px;
        transition: width 0.3s ease;
    }

    .distribution-count {
        font-size: 12px;
        color: var(--text-gray);
        min-width: 40px;
        text-align: right;
    }

    /* Bulk Actions */
    .bulk-actions {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.05);
        grid-column: 1 / -1;
    }

    .bulk-actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .bulk-action-card {
        background: rgba(255, 255, 255, 0.02);
        border-radius: 8px;
        padding: 20px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .bulk-action-card:hover {
        background: rgba(255, 255, 255, 0.05);
        transform: translateY(-2px);
    }

    .bulk-action-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        font-size: 18px;
    }

    .bulk-action-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-light);
        margin-bottom: 8px;
    }

    .bulk-action-desc {
        font-size: 12px;
        color: var(--text-gray);
        line-height: 1.4;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .category-tools {
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

        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .section-actions {
            width: 100%;
            justify-content: space-between;
        }

        .category-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .category-actions {
            width: 100%;
            justify-content: flex-end;
        }

        .distribution-chart {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .category-tools {
            padding: 10px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .category-actions {
            flex-direction: column;
            gap: 5px;
        }

        .btn-sm {
            justify-content: center;
        }
    }
</style>

<!-- Category Tools -->
<div class="category-tools">
    <div class="page-header">
        <h1 class="page-title">Category Tools</h1>
        <div class="header-actions">
            <button class="btn btn-secondary">
                <i class="fas fa-download"></i>
                Export Data
            </button>
            <button class="btn btn-primary">
                <i class="fas fa-sync"></i>
                Refresh All
            </button>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">
                <i class="fas fa-tags"></i>
                Total Categories
            </div>
            <div class="stat-value">24</div>
            <div class="stat-change">
                <i class="fas fa-arrow-up"></i>
                3 new this month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">
                <i class="fas fa-film"></i>
                Total Movies
            </div>
            <div class="stat-value">2,847</div>
            <div class="stat-change">
                <i class="fas fa-arrow-up"></i>
                5.7% from last month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">
                <i class="fas fa-tv"></i>
                TV Shows
            </div>
            <div class="stat-value">456</div>
            <div class="stat-change">
                <i class="fas fa-arrow-up"></i>
                12.3% from last month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">
                <i class="fas fa-video"></i>
                Documentaries
            </div>
            <div class="stat-value">189</div>
            <div class="stat-change">
                <i class="fas fa-arrow-up"></i>
                8.1% from last month
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Category Management -->
        <div class="category-management">
            <div class="section-header">
                <h3 class="section-title">Manage Categories</h3>
                <div class="section-actions">
                    <button class="btn btn-secondary btn-sm">
                        <i class="fas fa-sort"></i>
                        Sort
                    </button>
                    <button class="btn btn-secondary btn-sm">
                        <i class="fas fa-filter"></i>
                        Filter
                    </button>
                </div>
            </div>

            <div class="category-list">
                <!-- Category 1 -->
                <div class="category-item">
                    <div class="category-info">
                        <div class="category-color" style="background: #E50914;"></div>
                        <div class="category-details">
                            <div class="category-name">Action</div>
                            <div class="category-count">324 movies</div>
                        </div>
                    </div>
                    <div class="category-actions">
                        <button class="btn-sm btn-edit">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn-sm btn-delete">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>

                <!-- Category 2 -->
                <div class="category-item">
                    <div class="category-info">
                        <div class="category-color" style="background: #3B82F6;"></div>
                        <div class="category-details">
                            <div class="category-name">Drama</div>
                            <div class="category-count">287 movies</div>
                        </div>
                    </div>
                    <div class="category-actions">
                        <button class="btn-sm btn-edit">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn-sm btn-delete">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>

                <!-- Category 3 -->
                <div class="category-item">
                    <div class="category-info">
                        <div class="category-color" style="background: #10B981;"></div>
                        <div class="category-details">
                            <div class="category-name">Comedy</div>
                            <div class="category-count">412 movies</div>
                        </div>
                    </div>
                    <div class="category-actions">
                        <button class="btn-sm btn-edit">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn-sm btn-delete">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>

                <!-- Category 4 -->
                <div class="category-item">
                    <div class="category-info">
                        <div class="category-color" style="background: #F59E0B;"></div>
                        <div class="category-details">
                            <div class="category-name">Romance</div>
                            <div class="category-count">198 movies</div>
                        </div>
                    </div>
                    <div class="category-actions">
                        <button class="btn-sm btn-edit">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn-sm btn-delete">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>

                <!-- Category 5 -->
                <div class="category-item">
                    <div class="category-info">
                        <div class="category-color" style="background: #8B5CF6;"></div>
                        <div class="category-details">
                            <div class="category-name">Thriller</div>
                            <div class="category-count">156 movies</div>
                        </div>
                    </div>
                    <div class="category-actions">
                        <button class="btn-sm btn-edit">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn-sm btn-delete">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Category Form -->
        <div class="add-category-form">
            <div class="section-header">
                <h3 class="section-title">Add New Category</h3>
            </div>

            <form id="addCategoryForm">
                <div class="form-group">
                    <label class="form-label">Category Name</label>
                    <input type="text" class="form-input" placeholder="Enter category name" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-input form-textarea" placeholder="Enter category description"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Color</label>
                    <div class="color-picker">
                        <div class="color-option selected" style="background: #E50914;" data-color="#E50914"></div>
                        <div class="color-option" style="background: #3B82F6;" data-color="#3B82F6"></div>
                        <div class="color-option" style="background: #10B981;" data-color="#10B981"></div>
                        <div class="color-option" style="background: #F59E0B;" data-color="#F59E0B"></div>
                        <div class="color-option" style="background: #8B5CF6;" data-color="#8B5CF6"></div>
                        <div class="color-option" style="background: #EC4899;" data-color="#EC4899"></div>
                        <div class="color-option" style="background: #6366F1;" data-color="#6366F1"></div>
                        <div class="color-option" style="background: #14B8A6;" data-color="#14B8A6"></div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 20px;">
                    <i class="fas fa-plus"></i>
                    Add Category
                </button>
            </form>
        </div>
    </div>

    <!-- Genre Distribution -->
    <div class="genre-distribution">
        <div class="section-header">
            <h3 class="section-title">Genre Distribution</h3>
            <div class="section-actions">
                <button class="btn btn-secondary btn-sm">
                    <i class="fas fa-chart-pie"></i>
                    View Chart
                </button>
            </div>
        </div>

        <div class="distribution-chart">
            <!-- Distribution Item 1 -->
            <div class="distribution-item">
                <div class="distribution-color" style="background: #E50914;"></div>
                <div class="distribution-info">
                    <div class="distribution-name">Action</div>
                    <div class="distribution-bar">
                        <div class="distribution-fill" style="background: #E50914; width: 85%;"></div>
                    </div>
                </div>
                <div class="distribution-count">85%</div>
            </div>

            <!-- Distribution Item 2 -->
            <div class="distribution-item">
                <div class="distribution-color" style="background: #3B82F6;"></div>
                <div class="distribution-info">
                    <div class="distribution-name">Drama</div>
                    <div class="distribution-bar">
                        <div class="distribution-fill" style="background: #3B82F6; width: 72%;"></div>
                    </div>
                </div>
                <div class="distribution-count">72%</div>
            </div>

            <!-- Distribution Item 3 -->
            <div class="distribution-item">
                <div class="distribution-color" style="background: #10B981;"></div>
                <div class="distribution-info">
                    <div class="distribution-name">Comedy</div>
                    <div class="distribution-bar">
                        <div class="distribution-fill" style="background: #10B981; width: 68%;"></div>
                    </div>
                </div>
                <div class="distribution-count">68%</div>
            </div>

            <!-- Distribution Item 4 -->
            <div class="distribution-item">
                <div class="distribution-color" style="background: #F59E0B;"></div>
                <div class="distribution-info">
                    <div class="distribution-name">Romance</div>
                    <div class="distribution-bar">
                        <div class="distribution-fill" style="background: #F59E0B; width: 55%;"></div>
                    </div>
                </div>
                <div class="distribution-count">55%</div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-actions">
        <div class="section-header">
            <h3 class="section-title">Bulk Category Actions</h3>
            <div class="section-actions">
                <button class="btn btn-secondary">
                    <i class="fas fa-cog"></i>
                    Settings
                </button>
            </div>
        </div>

        <div class="bulk-actions-grid">
            <!-- Bulk Action 1 -->
            <div class="bulk-action-card">
                <div class="bulk-action-icon" style="background: rgba(59, 130, 246, 0.2); color: #3B82F6;">
                    <i class="fas fa-random"></i>
                </div>
                <div class="bulk-action-title">Merge Categories</div>
                <div class="bulk-action-desc">Combine multiple categories into one and reassign all content</div>
            </div>

            <!-- Bulk Action 2 -->
            <div class="bulk-action-card">
                <div class="bulk-action-icon" style="background: rgba(16, 185, 129, 0.2); color: #10B981;">
                    <i class="fas fa-copy"></i>
                </div>
                <div class="bulk-action-title">Duplicate Categories</div>
                <div class="bulk-action-desc">Create copies of existing categories with new names</div>
            </div>

            <!-- Bulk Action 3 -->
            <div class="bulk-action-card">
                <div class="bulk-action-icon" style="background: rgba(245, 158, 11, 0.2); color: #F59E0B;">
                    <i class="fas fa-file-export"></i>
                </div>
                <div class="bulk-action-title">Export Categories</div>
                <div class="bulk-action-desc">Export all category data to CSV or JSON format</div>
            </div>

            <!-- Bulk Action 4 -->
            <div class="bulk-action-card">
                <div class="bulk-action-icon" style="background: rgba(139, 92, 246, 0.2); color: #8B5CF6;">
                    <i class="fas fa-file-import"></i>
                </div>
                <div class="bulk-action-title">Import Categories</div>
                <div class="bulk-action-desc">Import Categories from external files or systems</div>
            </div>
        </div>
    </div>
</div>

<script>
    // Color picker functionality
    document.querySelectorAll('.color-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.color-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            this.classList.add('selected');
        });
    });

    // Add category form submission
    document.getElementById('addCategoryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const categoryName = this.querySelector('input[type="text"]').value;
        const selectedColor = document.querySelector('.color-option.selected').getAttribute('data-color');
        
        if (categoryName.trim()) {
            // Simulate adding category
            alert(`Category "${categoryName}" added successfully with color ${selectedColor}`);
            this.reset();
            document.querySelector('.color-option').classList.add('selected');
        }
    });

    // Delete category Confirmation
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const categoryName = this.closest('.category-item').querySelector('.category-name').textContent;
            if (confirm(`Are you sure you want to delete category "${categoryName}"? This action cannot be undone.`)) {
                this.closest('.category-item').style.opacity = '0.5';
                setTimeout(() => {
                    this.closest('.category-item').remove();
                }, 500);
            }
        });
    });

    // Bulk action cards
    document.querySelectorAll('.bulk-action-card').forEach(card => {
        card.addEventListener('click', function() {
            const actionTitle = this.querySelector('.bulk-action-title').textContent;
            alert(`Initiating: ${actionTitle}\nThis feature would open a detailed modal in the full application.`);
        });
    });
</script>

</body>
</html>