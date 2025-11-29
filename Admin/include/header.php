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

        .content {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .content h1 {
            font-size: 36px;
            margin-bottom: 16px;
            background: linear-gradient(90deg, #fff 0%, #E50914 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .content p {
            font-size: 18px;
            color: #aaa;
            max-width: 600px;
            line-height: 1.6;
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
            
            .content {
                padding: 80px 20px 40px;
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
                    <a href="" class="nav-item">
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
                    <a href="subscriptions-payments.html" class="nav-item active">
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

    <div class="content">
        <h1>Movie Lab Admin Dashboard</h1>
        <p>Select a menu item from the sidebar to manage different aspects of your Movie Lab platform.</p>
        <p style="margin-top: 20px; color: #E50914; font-size: 16px;">Current Section: <strong>Subscriptions & Payments</strong></p>
        <div style="margin-top: 30px; padding: 20px; background: rgba(229, 9, 20, 0.1); border-radius: 10px; max-width: 500px;">
            <h3 style="margin-bottom: 15px; color: #E50914;">Navigation Links Added</h3>
            <p style="font-size: 14px; color: #ccc;">Each category now has a proper href link pointing to its respective page:</p>
            <ul style="text-align: left; margin-top: 15px; font-size: 14px; color: #ccc;">
                <li>Dashboard → dashboard.html</li>
                <li>Content Management → content-management.html</li>
                <li>User Management → user-management.html</li>
                <li>Subscriptions & Payments → subscriptions-payments.html</li>
                <li>Genre/Category Tools → genre-tools.html</li>
                <li>Profile → profile.html</li>
                <li>Settings → settings.html</li>
                <li>Logout → logout.html</li>
            </ul>
        </div>
    </div>

    <script>
        // Mobile toggle functionality
        document.getElementById('mobileToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });

        // Navigation item selection
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                // Remove active class from all items
                document.querySelectorAll('.nav-item').forEach(nav => {
                    nav.classList.remove('active');
                });
                
                // Add active class to clicked item
                this.classList.add('active');
                
                // Update content based on selection
                const sectionName = this.querySelector('.nav-text').textContent;
                document.querySelector('.content p:last-child').innerHTML = `Current Section: <strong>${sectionName}</strong>`;
                
                // Close sidebar on mobile after selection
                if (window.innerWidth <= 768) {
                    document.getElementById('sidebar').classList.remove('open');
                }
                
                // In a real application, the page would navigate to the href
                // For this demo, we'll prevent default to stay on the same page
                e.preventDefault();
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
    </script>
</body>
</html>