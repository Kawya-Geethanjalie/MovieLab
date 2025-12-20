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
    }

    body {
        min-height: 100vh;
        background: linear-gradient(135deg, var(--dark-bg) 0%, #1a1a1a 100%);
        color: #fff;
        overflow-x: hidden;
    }

    /* Subscriptions & Payments Styles */
    .subscriptions-payments {
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
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .stat-change.positive {
        color: var(--success);
    }

    .stat-change.negative {
        color: var(--danger);
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

    /* Tabs */
    .tabs-container {
        background: var(--card-bg);
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.05);
        overflow: hidden;
    }

    .tabs-header {
        display: flex;
        background: rgba(229, 9, 20, 0.1);
        border-bottom: 1px solid rgba(229, 9, 20, 0.3);
    }

    .tab-btn {
        padding: 15px 24px;
        background: none;
        border: none;
        color: var(--text-light);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        border-bottom: 3px solid transparent;
    }

    .tab-btn:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .tab-btn.active {
        color: var(--primary-red);
        border-bottom-color: var(--primary-red);
        background: rgba(229, 9, 20, 0.1);
    }

    .tab-content {
        display: none;
        padding: 0;
    }

    .tab-content.active {
        display: block;
    }

    /* Tables */
    .table-container {
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

    .data-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .data-table th {
        background: rgba(229, 9, 20, 0.1);
        color: var(--text-light);
        font-weight: 600;
        padding: 15px 12px;
        text-align: left;
        border-bottom: 2px solid rgba(229, 9, 20, 0.3);
    }

    .data-table td {
        padding: 15px 12px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        color: var(--text-light);
    }

    .data-table tr:hover {
        background: rgba(255, 255, 255, 0.02);
    }

    /* Badges */
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

    .status-expired {
        background: rgba(239, 68, 68, 0.2);
        color: var(--danger);
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.2);
        color: var(--warning);
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .plan-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .plan-premium {
        background: rgba(139, 92, 246, 0.2);
        color: var(--purple);
        border: 1px solid rgba(139, 92, 246, 0.3);
    }

    .plan-basic {
        background: rgba(59, 130, 246, 0.2);
        color: var(--info);
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .payment-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .payment-success {
        background: rgba(16, 185, 129, 0.2);
        color: var(--success);
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .payment-failed {
        background: rgba(239, 68, 68, 0.2);
        color: var(--danger);
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .payment-pending {
        background: rgba(245, 158, 11, 0.2);
        color: var(--warning);
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    /* Action Buttons */
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
        .subscriptions-payments {
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

        .table-container {
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

        .tabs-header {
            flex-direction: column;
        }

        .tab-btn {
            width: 100%;
            text-align: left;
        }
    }

    @media (max-width: 480px) {
        .subscriptions-payments {
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

<!-- Subscriptions & Payments -->
<div class="subscriptions-payments">
    <div class="page-header">
        <h1 class="page-title">Subscriptions & Payments</h1>
        <div class="header-actions">
            <button class="btn btn-secondary">
                <i class="fas fa-download"></i>
                Export Report
            </button>
            <a href="add_subscription.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Add Subscription
                </a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">
                <i class="fas fa-money-bill-wave"></i>
                Total Revenue
            </div>
            <div class="stat-value">LKR 2.45M</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                15.2% from last month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">
                <i class="fas fa-users"></i>
                Active Subscriptions
            </div>
            <div class="stat-value">4,567</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                8.3% from last month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">
                <i class="fas fa-crown"></i>
                Premium Users
            </div>
            <div class="stat-value">3,245</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                12.1% from last month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">
                <i class="fas fa-exclamation-triangle"></i>
                Failed Payments
            </div>
            <div class="stat-value">45</div>
            <div class="stat-change negative">
                <i class="fas fa-arrow-down"></i>
                5.2% from last week
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <div class="search-box">
            <input type="text" class="search-input" placeholder="Search by user name, email, or transaction ID...">
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
                <label class="filter-label">Subscription Plan</label>
                <select class="filter-select">
                    <option value="">All Plans</option>
                    <option value="premium">Premium</option>
                    <option value="basic">Basic</option>
                    <option value="free">Free</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Payment Status</label>
                <select class="filter-select">
                    <option value="">All Status</option>
                    <option value="success">Success</option>
                    <option value="failed">Failed</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Date Range</label>
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
                    <option value="amount">Amount High to Low</option>
                    <option value="user">User Name A-Z</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="tabs-container">
        <div class="tabs-header">
            <button class="tab-btn active" data-tab="subscriptions">
                <i class="fas fa-crown"></i>
                Subscriptions
            </button>
            <button class="tab-btn" data-tab="payments">
                <i class="fas fa-credit-card"></i>
                Payment History
            </button>
            <button class="tab-btn" data-tab="revenue">
                <i class="fas fa-chart-line"></i>
                Revenue Analytics
            </button>
        </div>

        <!-- Subscriptions Tab -->
        <div class="tab-content active" id="subscriptions">
            <div class="table-container">
                <div class="table-header">
                    <h3 class="table-title">Active Subscriptions</h3>
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

                <table class="data-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>Expiry Date</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Subscription 1 -->
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
                            <td><span class="plan-badge plan-premium">Premium</span></td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td>2024-01-15</td>
                            <td>2024-04-15</td>
                            <td>LKR 1,500</td>
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

                        <!-- Subscription 2 -->
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
                            <td><span class="plan-badge plan-basic">Basic</span></td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td>2024-02-20</td>
                            <td>2024-05-20</td>
                            <td>LKR 800</td>
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

                        <!-- Subscription 3 -->
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
                            <td><span class="plan-badge plan-premium">Premium</span></td>
                            <td><span class="status-badge status-expired">Expired</span></td>
                            <td>2024-01-10</td>
                            <td>2024-04-10</td>
                            <td>LKR 1,500</td>
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
        </div>

        <!-- Payments Tab -->
        <div class="tab-content" id="payments">
            <div class="table-container">
                <div class="table-header">
                    <h3 class="table-title">Payment History</h3>
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

                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Payment Method</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Payment 1 -->
                        <tr>
                            <td>#TXN-784512</td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">JD</div>
                                    <div class="user-details">
                                        <div class="user-name">John Doe</div>
                                    </div>
                                </div>
                            </td>
                            <td>LKR 1,500</td>
                            <td><span class="plan-badge plan-premium">Premium</span></td>
                            <td><span class="payment-badge payment-success">Success</span></td>
                            <td>2024-03-15</td>
                            <td>Credit Card</td>
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
                                </div>
                            </td>
                        </tr>

                        <!-- Payment 2 -->
                        <tr>
                            <td>#TXN-784513</td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">AS</div>
                                    <div class="user-details">
                                        <div class="user-name">Alice Smith</div>
                                    </div>
                                </div>
                            </td>
                            <td>LKR 800</td>
                            <td><span class="plan-badge plan-basic">Basic</span></td>
                            <td><span class="payment-badge payment-success">Success</span></td>
                            <td>2024-03-14</td>
                            <td>PayPal</td>
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
                                </div>
                            </td>
                        </tr>

                        <!-- Payment 3 -->
                        <tr>
                            <td>#TXN-784514</td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">DW</div>
                                    <div class="user-details">
                                        <div class="user-name">David Wilson</div>
                                    </div>
                                </div>
                            </td>
                            <td>LKR 1,500</td>
                            <td><span class="plan-badge plan-premium">Premium</span></td>
                            <td><span class="payment-badge payment-failed">Failed</span></td>
                            <td>2024-03-13</td>
                            <td>Credit Card</td>
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
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Revenue Analytics Tab -->
        <div class="tab-content" id="revenue">
            <div class="table-container">
                <div class="table-header">
                    <h3 class="table-title">Revenue Analytics</h3>
                    <div class="table-actions">
                        <button class="btn btn-secondary">
                            <i class="fas fa-download"></i>
                            Export Report
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-chart-bar"></i>
                            Generate Report
                        </button>
                    </div>
                </div>
                <div style="text-align: center; padding: 40px; color: var(--text-gray);">
                    <i class="fas fa-chart-line" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
                    <h3 style="margin-bottom: 10px; color: var(--text-light);">Revenue Analytics</h3>
                    <p>Detailed revenue charts and analytics will be displayed here.</p>
                    <p style="font-size: 14px; margin-top: 10px;">Features include monthly revenue trends, plan-wise revenue breakdown, and payment success rates.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <div class="pagination-info">
            Showing 1-3 of 4,567 subscriptions
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
            <button class="pagination-btn">152</button>
            <button class="pagination-btn">
                Next
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<script>
    // Tab functionality
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all tabs
            document.querySelectorAll('.tab-btn').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Add active class to clicked tab
            this.classList.add('active');
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Search functionality
    document.querySelector('.search-input').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const activeTab = document.querySelector('.tab-content.active');
        const rows = activeTab.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Filter functionality
    document.querySelectorAll('.filter-select').forEach(select => {
        select.addEventListener('change', function() {
            console.log('Filter changed:', this.value);
        });
    });

    // Delete confirmation
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const userName = this.closest('tr').querySelector('.user-name').textContent;
            if (confirm(`Are you sure you want to delete subscription for "${userName}"?`)) {
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
            }
        });
    });
</script>

</body>
</html>