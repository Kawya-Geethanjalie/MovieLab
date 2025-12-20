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
    /* ඔබගේ existing CSS styles එකම තිබේ */
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
    }

    body {
        min-height: 100vh;
        background: linear-gradient(135deg, var(--dark-bg) 0%, #1a1a1a 100%);
        color: #fff;
        overflow-x: hidden;
    }

    /* User Management Styles */
    .user-management {
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

    /* Users Table */
    .users-table-container {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.05);
        overflow-x: auto;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .table-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-light);
    }

    .table-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .users-table th {
        background: rgba(229, 9, 20, 0.1);
        color: var(--text-light);
        font-weight: 600;
        padding: 15px 12px;
        text-align: left;
        border-bottom: 2px solid rgba(229, 9, 20, 0.3);
    }

    .users-table td {
        padding: 15px 12px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        color: var(--text-light);
    }

    .users-table tr:hover {
        background: rgba(255, 255, 255, 0.02);
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #E50914 0%, #B80710 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 14px;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-details {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .user-name {
        font-weight: 500;
        color: var(--text-light);
    }

    .user-email {
        font-size: 12px;
        color: var(--text-gray);
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
    }

    .status-active {
        background: rgba(16, 185, 129, 0.2);
        color: var(--success);
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .status-inactive {
        background: rgba(107, 114, 128, 0.2);
        color: var(--text-gray);
        border: 1px solid rgba(107, 114, 128, 0.3);
    }

    .status-suspended {
        background: rgba(239, 68, 68, 0.2);
        color: var(--danger);
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .subscription-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .subscription-premium {
        background: rgba(245, 158, 11, 0.2);
        color: var(--warning);
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .subscription-basic {
        background: rgba(59, 130, 246, 0.2);
        color: var(--info);
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .subscription-free {
        background: rgba(107, 114, 128, 0.2);
        color: var(--text-gray);
        border: 1px solid rgba(107, 114, 128, 0.3);
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
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

    .btn-view {
        background: rgba(16, 185, 129, 0.2);
        color: #10B981;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .btn-view:hover {
        background: rgba(16, 185, 129, 0.3);
    }

    /* Stats Cards */
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

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 30px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .pagination-info {
        color: var(--text-gray);
        font-size: 14px;
    }

    .pagination-controls {
        display: flex;
        gap: 8px;
        align-items: center;
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
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .pagination-btn:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .pagination-btn.active {
        background: var(--primary-red);
        color: white;
        border-color: var(--primary-red);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .user-management {
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

        .users-table-container {
            padding: 15px;
        }

        .table-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .table-actions {
            width: 100%;
            justify-content: space-between;
        }

        .action-buttons {
            justify-content: flex-start;
        }

        .pagination {
            flex-direction: column;
            align-items: center;
        }
    }

    @media (max-width: 480px) {
        .user-management {
            padding: 10px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
            gap: 5px;
        }

        .btn-sm {
            justify-content: center;
        }
    }
</style>

<!-- User Management -->
<div class="user-management">
    <div class="page-header">
        <h1 class="page-title">User Management</h1>
        <div class="header-actions">
            <button class="btn btn-secondary">
                <i class="fas fa-download"></i>
                Export Users
            </button>
            <a href="add_user.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Add New User
                </a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">
                <i class="fas fa-users"></i>
                Total Users
            </div>
            <div class="stat-value">15,892</div>
            <div class="stat-change">
                <i class="fas fa-arrow-up"></i>
                12.5% from last month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">
                <i class="fas fa-crown"></i>
                Premium Users
            </div>
            <div class="stat-value">4,567</div>
            <div class="stat-change">
                <i class="fas fa-arrow-up"></i>
                8.3% from last month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">
                <i class="fas fa-user-clock"></i>
                Active Today
            </div>
            <div class="stat-value">2,345</div>
            <div class="stat-change">
                <i class="fas fa-arrow-up"></i>
                5.2% from yesterday
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">
                <i class="fas fa-ban"></i>
                Suspended Users
            </div>
            <div class="stat-value">127</div>
            <div class="stat-change">
                <i class="fas fa-arrow-down"></i>
                3.1% from last week
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <div class="search-box">
            <input type="text" class="search-input" placeholder="Search users by name or email...">
            <button class="btn btn-primary">
                <i class="fas fa-search"></i>
                Search
            </button>
            <button class="btn btn-secondary">
                <i class="fas fa-filter"></i>
                Advanced Filters
            </button>
        </div>
        <div class="filter-grid">
            <div class="filter-group">
                <label class="filter-label">Status</label>
                <select class="filter-select">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="suspended">Suspended</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Subscription</label>
                <select class="filter-select">
                    <option value="">All Subscriptions</option>
                    <option value="premium">Premium</option>
                    <option value="basic">Basic</option>
                    <option value="free">Free</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Registration Date</label>
                <select class="filter-select">
                    <option value="">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Sort By</label>
                <select class="filter-select">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="name">Name A-Z</option>
                    <option value="activity">Last Active</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="users-table-container">
        <div class="table-header">
            <h3 class="table-title">All Users</h3>
            <div class="table-actions">
                <button class="btn btn-secondary btn-sm">
                    <i class="fas fa-sync"></i>
                    Refresh
                </button>
                <button class="btn btn-secondary btn-sm">
                    <i class="fas fa-columns"></i>
                    Columns
                </button>
            </div>
        </div>

        <table class="users-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Status</th>
                    <th>Subscription</th>
                    <th>Registration Date</th>
                    <th>Last Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- User 1 -->
                <tr>
                    <td>
                        <div class="user-info">
                            <div class="user-avatar">JD</div>
                            <div class="user-details">
                                <div class="user-name">John Doe</div>
                                <div class="user-email">john.doe@email.com</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="status-badge status-active">Active</span></td>
                    <td><span class="subscription-badge subscription-premium">Premium</span></td>
                    <td>2024-01-15</td>
                    <td>2 hours ago</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-sm btn-view">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                            <button class="btn-sm btn-edit">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- User 2 -->
                <tr>
                    <td>
                        <div class="user-info">
                            <div class="user-avatar">AS</div>
                            <div class="user-details">
                                <div class="user-name">Alice Smith</div>
                                <div class="user-email">alice.smith@email.com</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="status-badge status-active">Active</span></td>
                    <td><span class="subscription-badge subscription-basic">Basic</span></td>
                    <td>2024-02-20</td>
                    <td>1 day ago</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-sm btn-view">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                            <button class="btn-sm btn-edit">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- User 3 -->
                <tr>
                    <td>
                        <div class="user-info">
                            <div class="user-avatar">BJ</div>
                            <div class="user-details">
                                <div class="user-name">Bob Johnson</div>
                                <div class="user-email">bob.johnson@email.com</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="status-badge status-inactive">Inactive</span></td>
                    <td><span class="subscription-badge subscription-free">Free</span></td>
                    <td>2024-03-10</td>
                    <td>2 weeks ago</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-sm btn-view">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                            <button class="btn-sm btn-edit">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- User 4 -->
                <tr>
                    <td>
                        <div class="user-info">
                            <div class="user-avatar">CE</div>
                            <div class="user-details">
                                <div class="user-name">Carol Evans</div>
                                <div class="user-email">carol.evans@email.com</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="status-badge status-suspended">Suspended</span></td>
                    <td><span class="subscription-badge subscription-premium">Premium</span></td>
                    <td>2024-01-05</td>
                    <td>1 month ago</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-sm btn-view">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                            <button class="btn-sm btn-edit">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- User 5 -->
                <tr>
                    <td>
                        <div class="user-info">
                            <div class="user-avatar">DW</div>
                            <div class="user-details">
                                <div class="user-name">David Wilson</div>
                                <div class="user-email">david.wilson@email.com</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="status-badge status-active">Active</span></td>
                    <td><span class="subscription-badge subscription-premium">Premium</span></td>
                    <td>2024-02-28</td>
                    <td>5 hours ago</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-sm btn-view">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                            <button class="btn-sm btn-edit">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <div class="pagination-info">
            Showing 1-5 of 15,892 users
        </div>
        <div class="pagination-controls">
            <button class="pagination-btn">
                <i class="fas fa-chevron-left"></i>
                Previous
            </button>
            <button class="pagination-btn active">1</button>
            <button class="pagination-btn">2</button>
            <button class="pagination-btn">3</button>
            <span style="color: var(--text-gray); padding: 0 10px;">...</span>
            <button class="pagination-btn">25</button>
            <button class="pagination-btn">
                Next
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<script>
    // Search functionality
    document.querySelector('.search-input').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const userRows = document.querySelectorAll('.users-table tbody tr');
        
        userRows.forEach(row => {
            const userName = row.querySelector('.user-name').textContent.toLowerCase();
            const userEmail = row.querySelector('.user-email').textContent.toLowerCase();
            
            if (userName.includes(searchTerm) || userEmail.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
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
            const userName = this.closest('tr').querySelector('.user-name').textContent;
            if (confirm(`Are you sure you want to delete user "${userName}"?`)) {
                // Add delete logic here
                this.closest('tr').style.opacity = '0.5';
                setTimeout(() => {
                    this.closest('tr').remove();
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