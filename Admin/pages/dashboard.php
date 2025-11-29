<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Overview - Movie Lab Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--dark-bg) 0%, #1a1a1a 100%);
            color: #fff;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: -280px;
            top: 0;
            width: 280px;
            height: 100vh;
            background: rgba(15, 15, 15, 0.98);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(229, 9, 20, 0.3);
            padding: 24px 0;
            display: flex;
            flex-direction: column;
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.5);
            transition: left 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, 
                #E50914 0%, #E50914 20%, 
                transparent 20%, transparent 40%,
                #E50914 40%, #E50914 60%,
                transparent 60%, transparent 80%,
                #E50914 80%, #E50914 100%);
            z-index: 10;
        }

        .sidebar::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, 
                #E50914 0%, #E50914 20%, 
                transparent 20%, transparent 40%,
                #E50914 40%, #E50914 60%,
                transparent 60%, transparent 80%,
                #E50914 80%, #E50914 100%);
            z-index: 10;
        }

        .sidebar:hover {
            left: 0;
        }

        .hover-trigger {
            position: fixed;
            left: 0;
            top: 0;
            width: 20px;
            height: 100vh;
            z-index: 999;
            cursor: pointer;
        }

        .logo-container {
            padding: 0 24px 24px;
            border-bottom: 1px solid rgba(229, 9, 20, 0.3);
            margin-bottom: 24px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #E50914 0%, #B80710 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(229, 9, 20, 0.4);
        }

        .logo-text {
            font-size: 22px;
            font-weight: 700;
            background: linear-gradient(90deg, #fff 0%, #E50914 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 10px rgba(229, 9, 20, 0.5);
        }

        .nav-section {
            margin-bottom: 32px;
        }

        .section-title {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #E50914;
            padding: 0 24px 12px;
            font-weight: 600;
        }

        .nav-items {
            list-style: none;
        }

        .nav-item {
            padding: 14px 24px;
            display: flex;
            align-items: center;
            gap: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            border-left: 3px solid transparent;
            text-decoration: none;
            color: inherit;
        }

        .nav-item:hover {
            background: rgba(229, 9, 20, 0.1);
            border-left-color: #E50914;
        }

        .nav-item.active {
            background: rgba(229, 9, 20, 0.15);
            border-left-color: #E50914;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 20px;
            background: #E50914;
            border-radius: 2px 0 0 2px;
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #E50914;
        }

        .nav-text {
            font-size: 15px;
            font-weight: 500;
            color: #e0e0e0;
        }

        .nav-item.active .nav-text {
            color: #fff;
            font-weight: 600;
        }

        .divider {
            height: 1px;
            background: rgba(229, 9, 20, 0.3);
            margin: 16px 24px;
        }

        .auth-section {
            margin-top: auto;
            padding: 0 24px;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px;
            background: rgba(229, 9, 20, 0.1);
            border: 1px solid rgba(229, 9, 20, 0.3);
            border-radius: 8px;
            color: #E50914;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-bottom: 20px;
            text-decoration: none;
            justify-content: center;
        }

        .logout-btn:hover {
            background: rgba(229, 9, 20, 0.2);
            box-shadow: 0 0 10px rgba(229, 9, 20, 0.3);
        }

        .auth-status {
            font-size: 12px;
            color: #888;
            margin-bottom: 4px;
        }

        .user-id {
            font-size: 12px;
            color: #E50914;
            font-family: monospace;
        }

        /* Dashboard Styles */
        .dashboard {
            padding: 30px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .dashboard.with-sidebar {
            margin-left: 280px;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(229, 9, 20, 0.3);
        }

        .dashboard-title {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(90deg, #fff 0%, var(--primary-red) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .date-display {
            color: var(--text-gray);
            font-size: 14px;
        }

        .notification-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-light);
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .notification-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--primary-red);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        }

        .stat-card.red-highlight {
            border-left: 4px solid var(--primary-red);
        }

        .stat-card.green-highlight {
            border-left: 4px solid #10B981;
        }

        .stat-card.blue-highlight {
            border-left: 4px solid #3B82F6;
        }

        .stat-card.purple-highlight {
            border-left: 4px solid #8B5CF6;
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
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-change {
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .stat-change.positive {
            color: #10B981;
        }

        .stat-change.negative {
            color: var(--primary-red);
        }

        .charts-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .chart-title {
            font-size: 18px;
            font-weight: 600;
        }

        .chart-actions {
            display: flex;
            gap: 10px;
        }

        .chart-action-btn {
            background: rgba(255, 255, 255, 0.05);
            border: none;
            color: var(--text-light);
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            transition: background 0.3s ease;
        }

        .chart-action-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .chart-action-btn.active {
            background: var(--primary-red);
            color: white;
        }

        .chart-container {
            height: 300px;
            position: relative;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .recent-activity, .top-content {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .activity-header, .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .activity-title, .content-title {
            font-size: 18px;
            font-weight: 600;
        }

        .activity-list, .content-list {
            list-style: none;
        }

        .activity-item, .content-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .activity-item:last-child, .content-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(229, 9, 20, 0.1);
            color: var(--primary-red);
        }

        .content-poster {
            width: 50px;
            height: 70px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-gray);
            font-size: 24px;
        }

        .activity-content, .content-details {
            flex: 1;
        }

        .activity-text, .content-name {
            font-size: 14px;
            margin-bottom: 4px;
        }

        .activity-time, .content-views {
            font-size: 12px;
            color: var(--text-gray);
        }

        .content-views {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 30px;
        }

        .action-btn {
            background: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--text-light);
            text-decoration: none;
        }

        .action-btn:hover {
            background: rgba(229, 9, 20, 0.1);
            border-color: var(--primary-red);
            transform: translateY(-3px);
        }

        .action-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: rgba(229, 9, 20, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-red);
            font-size: 20px;
        }

        .action-text {
            font-size: 14px;
            font-weight: 500;
            text-align: center;
        }

        @media (max-width: 1024px) {
            .dashboard {
                padding: 20px;
            }
            
            .charts-grid, .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .header-actions {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>
    <!-- Hover Trigger Area -->
    <div class="hover-trigger"></div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo-container">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-film"></i>
                </div>
                <div class="logo-text">Movie Lab Admin</div>
            </div>
        </div>

        <div class="nav-section">
            <div class="section-title">Analytics & Reports</div>
            <ul class="nav-items">
                <li>
                    <a href="../pages/dashboard.php" class="nav-item active">
                        <div class="nav-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="nav-text">Dashboard</div>
                    </a>
                </li>
                <li>
                    <a href="content-management.html" class="nav-item">
                        <div class="nav-icon">
                            <i class="fas fa-video"></i>
                        </div>
                        <div class="nav-text">Content Management</div>
                    </a>
                </li>
                <li>
                    <a href="user-management.html" class="nav-item">
                        <div class="nav-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="nav-text">User Management</div>
                    </a>
                </li>
                <li>
                    <a href="subscriptions-payments.html" class="nav-item">
                        <div class="nav-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="nav-text">Subscriptions & Payments</div>
                    </a>
                </li>
                <li>
                    <a href="genre-tools.html" class="nav-item">
                        <div class="nav-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div class="nav-text">Genre/Category Tools</div>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-section">
            <ul class="nav-items">
                <li>
                    <a href="profile.html" class="nav-item">
                        <div class="nav-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="nav-text">Profile</div>
                    </a>
                </li>
                <li>
                    <a href="settings.html" class="nav-item">
                        <div class="nav-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="nav-text">Settings</div>
                    </a>
                </li>
            </ul>
        </div>

        <div class="divider"></div>

        <div class="auth-section">
            <a href="logout.html" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
            <div class="auth-status">Authenticated</div>
            <div class="user-id">User ID: ADMIN-ML-001</div>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="dashboard">
        <div class="dashboard-header">
            <h1 class="dashboard-title">Dashboard Overview</h1>
            <div class="header-actions">
                <div class="date-display" id="currentDate"></div>
                <div class="notification-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card red-highlight">
                <div class="stat-title">
                    <i class="fas fa-users"></i>
                    Total Users
                </div>
                <div class="stat-value">15,892</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    12.5% from last month
                </div>
            </div>

            <div class="stat-card green-highlight">
                <div class="stat-title">
                    <i class="fas fa-play-circle"></i>
                    Content Views
                </div>
                <div class="stat-value">245,678</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    8.3% from last month
                </div>
            </div>

            <div class="stat-card blue-highlight">
                <div class="stat-title">
                    <i class="fas fa-money-bill-wave"></i>
                    Revenue (YTD)
                </div>
                <div class="stat-value">LKR 45.6M</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    15.2% from last year
                </div>
            </div>

            <div class="stat-card purple-highlight">
                <div class="stat-title">
                    <i class="fas fa-film"></i>
                    Active Content
                </div>
                <div class="stat-value">2,847</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    5.7% from last month
                </div>
            </div>
        </div>

        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Monthly Revenue Trend</h3>
                    <div class="chart-actions">
                        <button class="chart-action-btn active">1M</button>
                        <button class="chart-action-btn">6M</button>
                        <button class="chart-action-btn">1Y</button>
                        <button class="chart-action-btn">All</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Content by Genre</h3>
                    <div class="chart-actions">
                        <button class="chart-action-btn">View All</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="genreChart"></canvas>
                </div>
            </div>
        </div>

        <div class="content-grid">
            <div class="recent-activity">
                <div class="activity-header">
                    <h3 class="activity-title">Recent Activity</h3>
                    <button class="chart-action-btn">View All</button>
                </div>
                <ul class="activity-list">
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-text">New movie "Kusa Paba" added to library</div>
                            <div class="activity-time">2 hours ago</div>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-text">125 new users registered today</div>
                            <div class="activity-time">5 hours ago</div>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-text">Subscription plan updated for user group</div>
                            <div class="activity-time">Yesterday</div>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-video"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-text">Content "Ran Kevita 2" reached 10,000 views</div>
                            <div class="activity-time">2 days ago</div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="top-content">
                <div class="content-header">
                    <h3 class="content-title">Trending Content</h3>
                    <button class="chart-action-btn">View All</button>
                </div>
                <ul class="content-list">
                    <li class="content-item">
                        <div class="content-poster">
                            <i class="fas fa-film"></i>
                        </div>
                        <div class="content-details">
                            <div class="content-name">Kusa Paba</div>
                            <div class="content-views">
                                <i class="fas fa-eye"></i>
                                45,678 views
                            </div>
                        </div>
                    </li>
                    <li class="content-item">
                        <div class="content-poster">
                            <i class="fas fa-film"></i>
                        </div>
                        <div class="content-details">
                            <div class="content-name">Ran Kevita 2</div>
                            <div class="content-views">
                                <i class="fas fa-eye"></i>
                                38,921 views
                            </div>
                        </div>
                    </li>
                    <li class="content-item">
                        <div class="content-poster">
                            <i class="fas fa-film"></i>
                        </div>
                        <div class="content-details">
                            <div class="content-name">Sinhala Comedy Special</div>
                            <div class="content-views">
                                <i class="fas fa-eye"></i>
                                32,456 views
                            </div>
                        </div>
                    </li>
                    <li class="content-item">
                        <div class="content-poster">
                            <i class="fas fa-film"></i>
                        </div>
                        <div class="content-details">
                            <div class="content-name">Action Thriller 2024</div>
                            <div class="content-views">
                                <i class="fas fa-eye"></i>
                                28,734 views
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="quick-actions">
            <a href="content-management.html" class="action-btn">
                <div class="action-icon">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="action-text">Add New Content</div>
            </a>
            <a href="user-management.html" class="action-btn">
                <div class="action-icon">
                    <i class="fas fa-user-cog"></i>
                </div>
                <div class="action-text">Manage Users</div>
            </a>
            <a href="subscriptions-payments.html" class="action-btn">
                <div class="action-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="action-text">View Reports</div>
            </a>
            <a href="settings.html" class="action-btn">
                <div class="action-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <div class="action-text">System Settings</div>
            </a>
        </div>
    </div>

    <script>
        // Set current date
        const now = new Date();
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', options);

        // Initialize Charts
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Revenue (LKR Millions)',
                        data: [5.2, 5.5, 6.1, 7.0, 6.8, 7.5, 8.2, 8.5, 9.0, 9.5, 10.1, 11.0],
                        borderColor: '#E50914',
                        backgroundColor: 'rgba(229, 9, 20, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#E50914',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: '#888',
                                callback: function(value) {
                                    return 'LKR ' + value + 'M';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#888'
                            }
                        }
                    }
                }
            });

            // Genre Chart
            const genreCtx = document.getElementById('genreChart').getContext('2d');
            const genreChart = new Chart(genreCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Action', 'Drama', 'Comedy', 'Romance', 'Thriller', 'Other'],
                    datasets: [{
                        data: [25, 20, 18, 15, 12, 10],
                        backgroundColor: [
                            '#E50914',
                            '#8B5CF6',
                            '#3B82F6',
                            '#10B981',
                            '#F59E0B',
                            '#6B7280'
                        ],
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#e0e0e0',
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        }
                    }
                }
            });

            // Chart period buttons
            document.querySelectorAll('.chart-action-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (this.parentElement.classList.contains('chart-actions')) {
                        this.parentElement.querySelectorAll('.chart-action-btn').forEach(b => {
                            b.classList.remove('active');
                        });
                        this.classList.add('active');
                    }
                });
            });

            // Sidebar functionality
            const sidebar = document.querySelector('.sidebar');
            const hoverTrigger = document.querySelector('.hover-trigger');
            const dashboard = document.querySelector('.dashboard');

            hoverTrigger.addEventListener('mouseenter', function() {
                sidebar.style.left = '0';
                dashboard.classList.add('with-sidebar');
            });

            sidebar.addEventListener('mouseleave', function() {
                sidebar.style.left = '-280px';
                dashboard.classList.remove('with-sidebar');
            });

            // Navigation item selection
            document.querySelectorAll('.nav-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    document.querySelectorAll('.nav-item').forEach(nav => {
                        nav.classList.remove('active');
                    });
                    this.classList.add('active');
                    e.preventDefault();
                });
            });
        });
    </script>
</body>
</html>