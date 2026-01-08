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

// Fetch data from database
try {
    // Total Users
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM users WHERE is_active = 1");
    $stmt->execute();
    $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Total Movies
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM movies");
    $stmt->execute();
    $totalMovies = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Total Songs
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM songs");
    $stmt->execute();
    $totalSongs = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Total Content
    $totalContent = $totalMovies + $totalSongs;

    // Recent User Activities
    $stmt = $pdo->prepare("
        SELECT ua.*, u.username 
        FROM user_activity ua 
        JOIN users u ON ua.user_id = u.user_id 
        ORDER BY ua.created_at DESC 
        LIMIT 4
    ");
    $stmt->execute();
    $recentActivities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Top Movies by views
    $stmt = $pdo->prepare("
        SELECT movie_id, title, 
               COALESCE(view_count, 0) as views 
        FROM movies 
        ORDER BY views DESC 
        LIMIT 4
    ");
    $stmt->execute();
    $trendingMovies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Movies by Genre for chart
    $stmt = $pdo->prepare("
        SELECT genre, COUNT(*) as count 
        FROM movies 
        GROUP BY genre 
        ORDER BY count DESC
    ");
    $stmt->execute();
    $moviesByGenre = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Monthly data for revenue chart (demo data based on content)
    $monthlyData = [];
    for ($i = 0; $i < 12; $i++) {
        $monthlyData[] = rand(3, 12); // Random data for demo
    }

} catch (PDOException $e) {
    error_log("Dashboard data error: " . $e->getMessage());
    // Set default values if database error
    $totalUsers = 0;
    $totalMovies = 0;
    $totalSongs = 0;
    $totalContent = 0;
    $recentActivities = [];
    $trendingMovies = [];
    $moviesByGenre = [];
    $monthlyData = array_fill(0, 12, 0);
}

// Helper function for time ago
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $now = time();
    $diff = $now - $time;
    
    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return floor($diff / 60) . ' minutes ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
    if ($diff < 2592000) return floor($diff / 86400) . ' days ago';
    return date('M j, Y', $time);
}

// Helper function for activity icons
function getActivityIcon($activityType) {
    $icons = [
        'login' => 'sign-in-alt',
        'logout' => 'sign-out-alt',
        'register' => 'user-plus',
        'view' => 'eye',
        'default' => 'bell'
    ];
    return $icons[$activityType] ?? $icons['default'];
}
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
                    <span class="notification-badge"><?php echo count($recentActivities); ?></span>
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card red-highlight">
                <div class="stat-title">
                    <i class="fas fa-users"></i>
                    Total Users
                </div>
                <div class="stat-value"><?php echo number_format($totalUsers); ?></div>
                <div class="stat-change positive">
                    <i class="fas fa-database"></i>
                    Active Users
                </div>
            </div>

            <div class="stat-card green-highlight">
                <div class="stat-title">
                    <i class="fas fa-film"></i>
                    Total Movies
                </div>
                <div class="stat-value"><?php echo number_format($totalMovies); ?></div>
                <div class="stat-change positive">
                    <i class="fas fa-database"></i>
                    From Database
                </div>
            </div>

            <div class="stat-card blue-highlight">
                <div class="stat-title">
                    <i class="fas fa-music"></i>
                    Total Songs
                </div>
                <div class="stat-value"><?php echo number_format($totalSongs); ?></div>
                <div class="stat-change positive">
                    <i class="fas fa-database"></i>
                    From Database
                </div>
            </div>

            <div class="stat-card purple-highlight">
                <div class="stat-title">
                    <i class="fas fa-play-circle"></i>
                    Total Content
                </div>
                <div class="stat-value"><?php echo number_format($totalContent); ?></div>
                <div class="stat-change positive">
                    <i class="fas fa-database"></i>
                    Movies + Songs
                </div>
            </div>
        </div>

        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Content Overview</h3>
                    <div class="chart-actions">
                        <button class="chart-action-btn active">Overview</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="contentChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Movies by Genre</h3>
                    <div class="chart-actions">
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
                    <?php if (!empty($recentActivities)): ?>
                        <?php foreach ($recentActivities as $activity): ?>
                            <li class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-<?php echo getActivityIcon($activity['activity_type']); ?>"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-text">
                                        <strong><?php echo htmlspecialchars($activity['username']); ?></strong> - 
                                        <?php echo htmlspecialchars($activity['description']); ?>
                                    </div>
                                    <div class="activity-time"><?php echo timeAgo($activity['created_at']); ?></div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="activity-item">
                            <div class="activity-content">
                                <div class="activity-text">No recent activities found</div>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="top-content">
                <div class="content-header">
                    <h3 class="content-title">Popular Movies</h3>
                    <button class="chart-action-btn">View All</button>
                </div>
                <ul class="content-list">
                    <?php if (!empty($trendingMovies)): ?>
                        <?php foreach ($trendingMovies as $movie): ?>
                            <li class="content-item">
                                <div class="content-poster">
                                    <i class="fas fa-film"></i>
                                </div>
                                <div class="content-details">
                                    <div class="content-name"><?php echo htmlspecialchars($movie['title']); ?></div>
                                    <div class="content-views">
                                        <i class="fas fa-eye"></i>
                                        <?php echo number_format($movie['views']); ?> views
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="content-item">
                            <div class="content-details">
                                <div class="content-name">No movies available</div>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <div class="quick-actions">
            <a href="add-content.php" class="action-btn">
                <div class="action-icon">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="action-text">Add New Content</div>
            </a>
            <a href="User_management.php" class="action-btn">
                <div class="action-icon">
                    <i class="fas fa-user-cog"></i>
                </div>
                <div class="action-text">Manage Users</div>
            </a>
            <a href="movies.php" class="action-btn">
                <div class="action-icon">
                    <i class="fas fa-film"></i>
                </div>
                <div class="action-text">Manage Movies</div>
            </a>
            <a href="songs.php" class="action-btn">
                <div class="action-icon">
                    <i class="fas fa-music"></i>
                </div>
                <div class="action-text">Manage Songs</div>
            </a>
        </div>
    </div>

    <script>
        // Set current date
        const now = new Date();
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', options);

        // Initialize Charts with database data
        document.addEventListener('DOMContentLoaded', function() {
            // Content Overview Chart
            const contentCtx = document.getElementById('contentChart').getContext('2d');
            const contentChart = new Chart(contentCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Content Added',
                        data: <?php echo json_encode($monthlyData); ?>,
                        backgroundColor: '#E50914',
                        borderColor: '#E50914',
                        borderWidth: 1
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
                    labels: <?php echo json_encode(array_column($moviesByGenre, 'genre')); ?>,
                    datasets: [{
                        data: <?php echo json_encode(array_column($moviesByGenre, 'count')); ?>,
                        backgroundColor: [
                            '#E50914', '#8B5CF6', '#3B82F6', '#10B981', 
                            '#F59E0B', '#6B7280', '#EF4444', '#84CC16'
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
                                font: {
                                    size: window.innerWidth < 768 ? 10 : 12
                                }
                            }
                        }
                    }
                }
            });

            // Make charts responsive on window resize
            window.addEventListener('resize', function() {
                contentChart.resize();
                genreChart.resize();
            });
        });
    </script>
</body>
</html> 