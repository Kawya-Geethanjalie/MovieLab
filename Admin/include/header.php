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

// Get admin profile data (you can modify this to fetch from database)
$admin_profile_image = $_SESSION['admin_profile_image'] ?? '/images/AdminProfile.jpg';
$admin_full_name = $_SESSION['admin_full_name'] ?? $admin_username;
$admin_email = $_SESSION['admin_email'] ?? 'admin@movielab.com';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Lab Admin - Sidebar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background: linear-gradient(135deg, #1a1a1a 0%, #0c0c0c 100%);
            color: #fff;
        }

        .sidebar {
            width: 280px;
            background: rgba(15, 15, 15, 0.95);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(229, 9, 20, 0.3);
            padding: 24px 0;
            display: flex;
            flex-direction: column;
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
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

        /* Profile Section Styles */
        .profile-section {
            background: rgba(229, 9, 20, 0.05);
            border: 1px solid rgba(229, 9, 20, 0.2);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .profile-section:hover {
            background: rgba(229, 9, 20, 0.1);
            border-color: rgba(229, 9, 20, 0.4);
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .profile-image-container {
            position: relative;
            width: 50px;
            height: 50px;
        }

        .profile-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #E50914;
            box-shadow: 0 0 10px rgba(229, 9, 20, 0.3);
        }

        .profile-image-placeholder {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #E50914 0%, #B80710 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #E50914;
            box-shadow: 0 0 10px rgba(229, 9, 20, 0.3);
            color: white;
            font-size: 20px;
        }

        .profile-info {
            flex: 1;
        }

        .profile-name {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 2px;
        }

        .profile-email {
            font-size: 11px;
            color: #E50914;
            font-family: monospace;
        }

        .profile-edit-btn {
            background: none;
            border: 1px solid rgba(229, 9, 20, 0.3);
            color: #E50914;
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 11px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .profile-edit-btn:hover {
            background: rgba(229, 9, 20, 0.1);
            border-color: #E50914;
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

        .mobile-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 100;
            background: #E50914;
            color: white;
            border: none;
            border-radius: 5px;
            width: 40px;
            height: 40px;
            font-size: 20px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                height: 100vh;
                z-index: 90;
                transition: transform 0.3s ease;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .mobile-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .profile-section {
                padding: 12px;
            }

            .profile-image, .profile-image-placeholder {
                width: 40px;
                height: 40px;
            }

            .profile-name {
                font-size: 13px;
            }

            .profile-email {
                font-size: 10px;
            }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(229, 9, 20, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(229, 9, 20, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(229, 9, 20, 0);
            }
        }
    </style>
</head>
<body>
    <button class="mobile-toggle" id="mobileToggle">
        <i class="fas fa-bars"></i>
    </button>

    <div class="sidebar" id="sidebar">
        <div class="logo-container">
            <div class="logo">
                <div class="logo-icon pulse">
                    <i class="fas fa-film"></i>
                </div>
                <div class="logo-text">Movie Lab Admin</div>
            </div>
        </div>

        <div class="nav-section">
            <div class="section-title">Analytics & Reports</div>
            <ul class="nav-items">
                <li>
                    <a href="../pages/dashboard.php" class="nav-item">
                        <div class="nav-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="nav-text">Dashboard</div>
                    </a>
                </li>
                <li>
                    <a href="../pages/content_management.php" class="nav-item">
                        <div class="nav-icon">
                            <i class="fas fa-video"></i>
                        </div>
                        <div class="nav-text">Content Management</div>
                    </a>
                </li>
                <li>
                    <a href="../pages/User_management.php" class="nav-item">
                        <div class="nav-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="nav-text">User Management</div>
                    </a>
                </li>
                <li>
                    <a href="../pages/Subscriptions.php" class="nav-item">
                        <div class="nav-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="nav-text">Subscriptions & Payments</div>
                    </a>
                </li>
                <li>
                    <a href="Categories.php" class="nav-item">
                        <div class="nav-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div class="nav-text">Category Tools</div>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-section">
            <ul class="nav-items">
                <li>
                    <a href="../pages/profile.php" class="nav-item">
                        <div class="nav-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="nav-text">Profile</div>
                    </a>
                </li>
                <li>
                    <a href="Setting.php" class="nav-item">
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
            <!-- Profile Section -->
            <div class="profile-section">
                <div class="profile-header">
                    <div class="profile-image-container">
                        <?php if (!empty($admin_profile_image) && file_exists($admin_profile_image)): ?>
                            <img src="<?php echo htmlspecialchars($admin_profile_image); ?>" alt="Profile" class="profile-image">
                        <?php else: ?>
                            <div class="profile-image-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="profile-info">
                        <div class="profile-name"><?php echo htmlspecialchars($admin_full_name); ?></div>
                        <div class="profile-email"><?php echo htmlspecialchars($admin_email); ?></div>
                    </div>
                </div>
                <a href="../pages/profile.php" class="profile-edit-btn">
                    <i class="fas fa-edit"></i>
                    <span>Edit Profile</span>
                </a>
            </div>

            <a href="logout.php" class="logout-btn" onclick="return confirm('Are you sure you want to logout?');">
                <i class='bx bx-log-out'></i>
                <span class="links_name">Logout</span>
            </a>

            <div class="auth-status">Authenticated</div>
            <div class="user-id">Username: <?php echo $_SESSION['admin_username'] ?? 'ADMIN-ML-001'; ?></div>
        </div>
    </div>

    <script>
        // Mobile toggle functionality
        document.getElementById('mobileToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });

        // Navigation item selection
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function() {
                // Remove active class from all items
                document.querySelectorAll('.nav-item').forEach(nav => {
                    nav.classList.remove('active');
                });
                
                // Add active class to clicked item
                this.classList.add('active');
                
                // Close sidebar on mobile after selection
                if (window.innerWidth <= 768) {
                    document.getElementById('sidebar').classList.remove('open');
                }
            });
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('mobileToggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !toggle.contains(event.target) && 
                sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
            }
        });

        // Set initial active item based on current page
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname.split('/').pop();
            const navItems = document.querySelectorAll('.nav-item');
            
            navItems.forEach(item => {
                const href = item.getAttribute('href');
                if (href === currentPage) {
                    item.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>