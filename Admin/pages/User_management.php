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

// --- 1. DATABASE CONNECTION & DATA FETCHING ---
$host = 'localhost';
$dbname = 'movielab';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    
    $sql = "SELECT * FROM users WHERE username LIKE :search OR email LIKE :search ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['search' => "%$search%"]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}


// සංඛ්‍යාලේඛන ලබා ගැනීම සඳහා SQL Queries
try {
    // 1. Total Users
    $total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

    // 2. Premium Users
    $premium_users = $pdo->query("SELECT COUNT(*) FROM users WHERE user_type = 'Premium'")->fetchColumn();

    // 3. Active Today (අද දිනයේ Login වූ පරිශීලකයින්)
    $active_today = $pdo->query("SELECT COUNT(*) FROM users WHERE DATE(last_login) = CURDATE()")->fetchColumn();

    // 4. Suspended Users
   // Suspended Users ගණන ලබා ගැනීම
    $suspended_users = $pdo->query("SELECT COUNT(*) FROM users WHERE status = 'Suspend'")->fetchColumn() ?: 0;

    // --- ප්‍රතිශත (Growth Percentages) ගණනය කිරීම ---
    // පසුගිය දින 30 තුළ ලියාපදිංචි වූ පිරිස (උදාහරණයක් ලෙස)
    $last_month_users = $pdo->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();
    $growth_rate = ($total_users > 0) ? round(($last_month_users / $total_users) * 100, 1) : 0;

} catch (PDOException $e) {
    error_log($e->getMessage());
}


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

    /* Profile Image & Avatar Styling */
.user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.profile-img, .user-avatar {
    width: 40px;      /* ඔබට අවශ්‍ය ප්‍රමාණය මෙතැනින් වෙනස් කළ හැක */
    height: 40px;
    border-radius: 50%; /* රවුමක් කිරීමට */
    object-fit: cover;  /* රූපය ඇද නොවී පෙනීමට */
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: white;
    flex-shrink: 0;    /* Table එක කුඩා වූවත් රූපය හැකිලීම වැලැක්වීමට */
    border: 2px solid rgba(229, 9, 20, 0.5); /* රතු පැහැති සිහින් බෝඩරයක් */
}

.user-avatar {
    background: var(--primary-red);
    font-size: 16px;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
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

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.8);
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content {
        background: var(--card-bg);
        margin: 5% auto;
        padding: 0;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        padding: 20px 25px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 22px;
        color: var(--text-light);
    }

    .close {
        color: var(--text-gray);
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.3s;
    }

    .close:hover,
    .close:focus {
        color: var(--primary-red);
    }

    .modal-body {
        padding: 25px;
    }

    .user-profile-section {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .profile-img-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--primary-red);
    }

    .user-avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--primary-red);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: bold;
        color: white;
        border: 3px solid var(--primary-red);
    }

    .profile-info h3 {
        margin: 0 0 5px 0;
        font-size: 20px;
        color: var(--text-light);
    }

    .profile-info p {
        margin: 0;
        color: var(--text-gray);
        font-size: 14px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
    }

    .detail-item {
        padding: 12px;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .detail-label {
        font-size: 12px;
        color: var(--text-gray);
        margin-bottom: 5px;
        text-transform: uppercase;
        font-weight: 500;
    }

    .detail-value {
        font-size: 14px;
        color: var(--text-light);
        font-weight: 500;
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

        .modal-content {
            width: 95%;
            margin: 10% auto;
        }

        .detail-grid {
            grid-template-columns: 1fr;
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

    .status-select {
    padding: 6px 10px;
    border-radius: 20px;
    border: 1px solid #444;
    background: #222;
    color: #fff;
    font-size: 13px;
    cursor: pointer;
    outline: none;
}

/* Status අනුව පාට වෙනස් කිරීම */
.status-active { color: #10B981; border-color: #10B981; }
.status-inactive { color: #888; border-color: #888; }
.status-suspend { color: #EF4444; border-color: #EF4444; }
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
        <div class="stat-value"><?php echo number_format($total_users); ?></div>
       <div class="stat-change positive">
                    <i class="fas fa-database"></i>
                    Active Users
                </div>
    </div>

    <div class="stat-card">
        <div class="stat-title">
            <i class="fas fa-crown"></i>
            Premium Users
        </div>
        <div class="stat-value"><?php echo number_format($premium_users); ?></div>
       <div class="stat-change positive">
                    <i class="fas fa-database"></i>
                    Active Premium Users
                </div>
    </div>

    <div class="stat-card">
        <div class="stat-title">
            <i class="fas fa-user-clock"></i>
            Active Today
        </div>
        <div class="stat-value"><?php echo number_format($active_today); ?></div>
        <div class="stat-change positive">
                    <i class="fas fa-database"></i>
                    Today Active Users
                </div>
    </div>

  <div class="stat-card">
    <div class="stat-title">
        <i class="fas fa-ban"></i>
        Suspended Users
    </div>
    <div class="stat-value" id="suspended-count"><?php echo number_format($suspended_users ?? 0); ?></div>
    <div class="stat-change positive">
                    <i class="fas fa-database"></i>
                    Suspend Users
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
            <a href="User_management.php" class="btn btn-secondary" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-filter"></i>
            Clear
        </a>
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
    <?php if (count($users) > 0): ?>
        <?php foreach ($users as $row): ?>
            <tr>
                <td>
                    <div class="user-info">
                        <?php if (!empty($row['profile_image'])): ?>
                            <img src="../../uploads/profile_images/<?php echo $row['profile_image']; ?>" alt="Profile" class="profile-img">
                        <?php else: ?>
                            <div class="user-avatar"><?php echo strtoupper(substr($row['username'], 0, 1)); ?></div>
                        <?php endif; ?>
                        
                        <div class="user-details">
                            <span class="user-name"><?php echo htmlspecialchars($row['username']); ?></span>
                            <span class="user-email"><?php echo htmlspecialchars($row['email']); ?></span>
                        </div>
                    </div>
                </td>

                <td>
                  
    <?php $currentStatus = $row['status'] ?? 'Active'; ?>
    
    <select class="status-select status-<?php echo strtolower($currentStatus); ?>" 
            onchange="updateUserStatus(<?php echo $row['user_id']; ?>, this.value)">
        <option value="Active" <?php echo ($currentStatus == 'Active') ? 'selected' : ''; ?>>Active</option>
        <option value="Inactive" <?php echo ($currentStatus == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
        <option value="Suspend" <?php echo ($currentStatus == 'Suspend') ? 'selected' : ''; ?>>Suspend</option>
    </select>
</td>
                

                <td>
                    <span class="subscription-badge">
                        <?php echo ucfirst($row['user_type']); ?>
                    </span>
                </td>

                <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>

                <td><?php echo $row['last_login'] ? date('M d, H:i', strtotime($row['last_login'])) : 'Never'; ?></td>

                <td>
                    <div class="action-buttons">
                        <button class="btn-sm btn-view" onclick="viewUserDetails(<?php echo $row['user_id']; ?>)">
                            <i class="fas fa-eye"></i>
                        </button>
                        <a href="../library/user-management-backend.php?action=delete&id=<?php echo $row['user_id']; ?>" 
                           class="btn-sm btn-delete" 
                           onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                           <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6" style="text-align: center; padding: 20px;">No users found.</td>
        </tr>
    <?php endif; ?>
</tbody>
        </table>
    </div>
</div>

<!-- User Details Modal -->
<div id="userModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>User Profile Details</h2>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        <div class="modal-body" id="modalBody">
            <div style="text-align: center; padding: 40px;">
                <i class="fas fa-spinner fa-spin" style="font-size: 32px; color: var(--primary-red);"></i>
                <p style="margin-top: 15px; color: var(--text-gray);">Loading user details...</p>
            </div>
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

    // Update user status
    function updateUserStatus(userId, newStatus) {
        if (confirm('Are you sure you want to change this user status?')) {
            fetch('../library/user-management-backend.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=update_status&user_id=${userId}&status=${newStatus}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Status updated successfully!');
                    
                    // Update suspended count if available
                    if (data.suspended_count !== undefined) {
                        document.getElementById('suspended-count').textContent = data.suspended_count;
                    }
                    
                    // Reload page to reflect changes
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating status');
                location.reload();
            });
        } else {
            // Reset select to original value if cancelled
            location.reload();
        }
    }

    // View user details in modal
    function viewUserDetails(userId) {
        const modal = document.getElementById('userModal');
        const modalBody = document.getElementById('modalBody');
        
        // Show modal
        modal.style.display = 'block';
        
        // Show loading state
        modalBody.innerHTML = `
            <div style="text-align: center; padding: 40px;">
                <i class="fas fa-spinner fa-spin" style="font-size: 32px; color: var(--primary-red);"></i>
                <p style="margin-top: 15px; color: var(--text-gray);">Loading user details...</p>
            </div>
        `;
        
        // Fetch user details
        fetch(`../library/user-management-backend.php?action=get_user_details&user_id=${userId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const user = data.user;
                    
                    // Build profile image or avatar
                    let profileImageHtml = '';
                    if (user.profile_image) {
                        profileImageHtml = `<img src="../../uploads/profile_images/${user.profile_image}" alt="Profile" class="profile-img-large">`;
                    } else {
                        const initial = user.username.charAt(0).toUpperCase();
                        profileImageHtml = `<div class="user-avatar-large">${initial}</div>`;
                    }
                    
                    // Build modal content
                    modalBody.innerHTML = `
                        <div class="user-profile-section">
                            ${profileImageHtml}
                            <div class="profile-info">
                                <h3>${user.first_name} ${user.last_name}</h3>
                                <p>@${user.username}</p>
                            </div>
                        </div>
                        
                        <div class="detail-grid">
                            <div class="detail-item">
                                <div class="detail-label">Email</div>
                                <div class="detail-value">${user.email}</div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">User Type</div>
                                <div class="detail-value">${user.user_type_display}</div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">Status</div>
                                <div class="detail-value">${user.status_display}</div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">Country</div>
                                <div class="detail-value">${user.country || 'Not specified'}</div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">Birthday</div>
                                <div class="detail-value">${user.birthday_formatted || 'Not specified'}</div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">Email Verified</div>
                                <div class="detail-value">${user.email_verified ? 'Yes' : 'No'}</div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">Registration Date</div>
                                <div class="detail-value">${user.created_at_formatted}</div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">Last Login</div>
                                <div class="detail-value">${user.last_login_formatted}</div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">Last Updated</div>
                                <div class="detail-value">${user.updated_at_formatted}</div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">User ID</div>
                                <div class="detail-value">#${user.user_id}</div>
                            </div>
                        </div>
                    `;
                } else {
                    modalBody.innerHTML = `
                        <div style="text-align: center; padding: 40px;">
                            <i class="fas fa-exclamation-circle" style="font-size: 48px; color: var(--danger);"></i>
                            <p style="margin-top: 15px; color: var(--text-light);">Failed to load user details</p>
                            <p style="color: var(--text-gray); font-size: 14px;">${data.message}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                modalBody.innerHTML = `
                    <div style="text-align: center; padding: 40px;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: var(--warning);"></i>
                        <p style="margin-top: 15px; color: var(--text-light);">An error occurred</p>
                        <p style="color: var(--text-gray); font-size: 14px;">Please try again later</p>
                    </div>
                `;
            });
    }

    // Close modal
    function closeModal() {
        document.getElementById('userModal').style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('userModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });
</script>

</body>
</html>