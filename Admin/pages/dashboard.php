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

        /* Dashboard Styles */
        .dashboard {
            padding: 20px;
            min-height: 100vh;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(229, 9, 20, 0.3);
            flex-wrap: wrap;
            gap: 15px;
        }

        .dashboard-title {
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

        .date-display {
            color: var(--text-gray);
            font-size: clamp(12px, 2vw, 14px);
            white-space: nowrap;
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
            flex-shrink: 0;
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
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            min-width: 0;
        }

        .stat-card:hover {
            transform: translateY(-3px);
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
            font-size: clamp(13px, 2vw, 14px);
            color: var(--text-gray);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .stat-value {
            font-size: clamp(24px, 4vw, 32px);
            font-weight: 700;
            margin-bottom: 5px;
            line-height: 1.2;
            word-break: break-word;
        }

        .stat-change {
            font-size: clamp(12px, 2vw, 14px);
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap;
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
            gap: 15px;
            margin-bottom: 25px;
        }

        .chart-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.05);
            min-width: 0;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .chart-title {
            font-size: clamp(16px, 3vw, 18px);
            font-weight: 600;
            line-height: 1.3;
        }

        .chart-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .chart-action-btn {
            background: rgba(255, 255, 255, 0.05);
            border: none;
            color: var(--text-light);
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 11px;
            transition: background 0.3s ease;
            white-space: nowrap;
        }

        .chart-action-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .chart-action-btn.active {
            background: var(--primary-red);
            color: white;
        }

        .chart-container {
            height: 250px;
            position: relative;
            min-height: 200px;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 25px;
        }

        .recent-activity, .top-content {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.05);
            min-width: 0;
        }

        .activity-header, .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .activity-title, .content-title {
            font-size: clamp(16px, 3vw, 18px);
            font-weight: 600;
            line-height: 1.3;
        }

        .activity-list, .content-list {
            list-style: none;
        }

        .activity-item, .content-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .activity-item:last-child, .content-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(229, 9, 20, 0.1);
            color: var(--primary-red);
            flex-shrink: 0;
        }

        .content-poster {
            width: 45px;
            height: 60px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-gray);
            font-size: 20px;
            flex-shrink: 0;
        }

        .activity-content, .content-details {
            flex: 1;
            min-width: 0;
        }

        .activity-text, .content-name {
            font-size: clamp(13px, 2vw, 14px);
            margin-bottom: 4px;
            line-height: 1.4;
            word-break: break-word;
        }

        .activity-time, .content-views {
            font-size: clamp(11px, 2vw, 12px);
            color: var(--text-gray);
            line-height: 1.3;
        }

        .content-views {
            display: flex;
            align-items: center;
            gap: 5px;
            flex-wrap: wrap;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 12px;
            margin-top: 25px;
        }

        .action-btn {
            background: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--text-light);
            text-decoration: none;
            text-align: center;
            min-width: 0;
        }

        .action-btn:hover {
            background: rgba(229, 9, 20, 0.1);
            border-color: var(--primary-red);
            transform: translateY(-2px);
        }

        .action-icon {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            background: rgba(229, 9, 20, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-red);
            font-size: 18px;
            flex-shrink: 0;
        }

        .action-text {
            font-size: clamp(12px, 2vw, 14px);
            font-weight: 500;
            line-height: 1.3;
            word-break: break-word;
        }

        /* Mobile-First Responsive Design */
        @media (max-width: 1200px) {
            .dashboard {
                padding: 18px;
            }
            
            .charts-grid {
                grid-template-columns: 1.5fr 1fr;
            }
        }

        @media (max-width: 1024px) {
            .dashboard {
                padding: 16px;
            }
            
            .charts-grid, .content-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .chart-container {
                height: 220px;
            }
        }

        @media (max-width: 768px) {
            .dashboard {
                padding: 14px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 12px;
            }
            
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            
            .header-actions {
                width: 100%;
                justify-content: space-between;
            }
            
            .stat-card {
                padding: 16px;
            }
            
            .chart-card, .recent-activity, .top-content {
                padding: 16px;
            }
            
            .activity-item, .content-item {
                gap: 10px;
                padding: 10px 0;
            }
            
            .activity-icon {
                width: 32px;
                height: 32px;
                font-size: 14px;
            }
            
            .content-poster {
                width: 40px;
                height: 55px;
                font-size: 18px;
            }
            
            .quick-actions {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
                gap: 10px;
            }
            
            .action-btn {
                padding: 12px 8px;
            }
            
            .action-icon {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .dashboard {
                padding: 12px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .stat-card {
                padding: 14px;
            }
            
            .chart-card, .recent-activity, .top-content {
                padding: 14px;
            }
            
            .chart-container {
                height: 200px;
            }
            
            .chart-actions {
                gap: 6px;
            }
            
            .chart-action-btn {
                padding: 5px 8px;
                font-size: 10px;
            }
            
            .activity-item, .content-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
                text-align: left;
            }
            
            .activity-content, .content-details {
                width: 100%;
            }
            
            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
            }
            
            .action-btn {
                padding: 10px 6px;
            }
            
            .action-icon {
                width: 35px;
                height: 35px;
                font-size: 14px;
            }
        }

        @media (max-width: 360px) {
            .dashboard {
                padding: 10px;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
            
            .header-actions {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .date-display {
                font-size: 12px;
            }
        }

        /* Touch device improvements */
        @media (hover: none) and (pointer: coarse) {
            .stat-card:hover {
                transform: none;
            }
            
            .action-btn:hover {
                transform: none;
            }
            
            .chart-action-btn, .notification-btn {
                min-height: 44px;
                min-width: 44px;
            }
        }

        /* High contrast support */
        @media (prefers-contrast: high) {
            .stat-card {
                border: 2px solid var(--text-light);
            }
            
            .chart-action-btn.active {
                border: 1px solid white;
            }
        }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            .stat-card,
            .action-btn,
            .notification-btn {
                transition: none;
            }
            
            .stat-card:hover,
            .action-btn:hover {
                transform: none;
            }
        }
    </style>
</head>
<body>
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
            <a href="content-management.php" class="action-btn">
                <div class="action-icon">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="action-text">Add New Content</div>
            </a>
            <a href="user-management.php" class="action-btn">
                <div class="action-icon">
                    <i class="fas fa-user-cog"></i>
                </div>
                <div class="action-text">Manage Users</div>
            </a>
            <a href="subscriptions-payments.php" class="action-btn">
                <div class="action-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="action-text">View Reports</div>
            </a>
            <a href="settings.php" class="action-btn">
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

        // Initialize Charts with responsive settings
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
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#E50914',
                        pointRadius: 3,
                        pointHoverRadius: 5,
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
                                },
                                font: {
                                    size: window.innerWidth < 768 ? 10 : 12
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#888',
                                font: {
                                    size: window.innerWidth < 768 ? 10 : 12
                                }
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
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#e0e0e0',
                                padding: 15,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: {
                                    size: window.innerWidth < 768 ? 10 : 12
                                }
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

            // Make charts responsive on window resize
            window.addEventListener('resize', function() {
                revenueChart.resize();
                genreChart.resize();
            });
        });
    </script>
</body>
</html>