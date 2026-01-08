<?php
// settings.php
session_start();
require_once '../include/connection.php';

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

// Load settings from database using PDO
function getSetting($key, $default = '') {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM admin_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        return $result ? $result['setting_value'] : $default;
    } catch (PDOException $e) {
        error_log("Get setting error: " . $e->getMessage());
        return $default;
    }
}

// Get all settings
$settings = [];
try {
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM admin_settings");
    $results = $stmt->fetchAll();
    foreach ($results as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
} catch (PDOException $e) {
    error_log("Get all settings error: " . $e->getMessage());
}

// Get current theme (from session or database)
if (isset($_SESSION['theme_mode'])) {
    $current_theme = $_SESSION['theme_mode'];
} else {
    $current_theme = $settings['theme_mode'] ?? 'dark';
}

include("../include/header.php");
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?php echo $current_theme; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Movie Lab Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Your existing CSS remains exactly the same */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: background 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        :root {
            --primary-red: #E50914;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            
            /* Default Dark Theme Variables */
            --dark-bg: #0c0c0c;
            --card-bg: #1a1a1a;
            --text-light: #e0e0e0;
            --text-gray: #888;
            --border-color: rgba(255, 255, 255, 0.1);
            --input-bg: rgba(255, 255, 255, 0.05);
            --hover-bg: rgba(255, 255, 255, 0.08);
            --select-bg: #1a1a1a;
            --select-text: #e0e0e0;
        }
/* Backup Section Styles */
.backup-section {
    background: var(--input-bg);
    border-radius: 10px;
    padding: 25px;
    margin: 30px 0;
    border: 1px solid var(--border-color);
}

.backup-section h3 {
    color: var(--text-light);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.backup-list table {
    width: 100%;
    border-collapse: collapse;
}

.backup-list th {
    padding: 12px;
    text-align: left;
    background: var(--input-bg);
    color: var(--text-light);
    border-bottom: 1px solid var(--border-color);
    font-weight: 600;
}

.backup-list td {
    padding: 12px;
    border-bottom: 1px solid var(--border-color);
    color: var(--text-light);
}

.backup-list tr:hover {
    background: var(--hover-bg);
}

.backup-list .btn {
    padding: 6px 12px;
    font-size: 13px;
}

/* Alert styles */
.alert {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 15px;
    display: none;
}

.alert-success {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: #10B981;
}

.alert-error {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #EF4444;
}

/* Responsive */
@media (max-width: 768px) {
    .backup-section {
        padding: 15px;
    }
    
    .backup-list table {
        display: block;
        overflow-x: auto;
    }
}
        /* Light Theme Variables on HTML element */
        html[data-theme="light"] {
            --dark-bg: #f4f7f6;
            --card-bg: #ffffff;
            --text-light: #1a1a1a;
            --text-gray: #666;
            --border-color: rgba(0, 0, 0, 0.1);
            --input-bg: #f9f9f9;
            --hover-bg: rgba(0, 0, 0, 0.05);
            --select-bg: #ffffff;
            --select-text: #1a1a1a;
        }

        body {
            min-height: 100vh;
            background: var(--dark-bg);
            color: var(--text-light);
            overflow-x: hidden;
        }

        .settings {
            padding: 30px 20px;
            min-height: 100vh;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 25px;
            border-bottom: 1px solid var(--border-color);
            flex-wrap: wrap;
            gap: 25px;
        }

        .page-title {
            font-size: clamp(32px, 5vw, 42px);
            font-weight: 800;
            background: linear-gradient(90deg, var(--text-light) 0%, var(--primary-red) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn-primary { 
            background: var(--primary-red); 
            color: white; 
        }
        
        .btn-secondary { 
            background: var(--input-bg); 
            color: var(--text-light); 
            border: 1px solid var(--border-color); 
        }

        .btn-primary:hover {
            background: #f40612;
        }

        .settings-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
        }

        /* Sidebar */
        .settings-sidebar {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 20px;
            border: 1px solid var(--border-color);
            height: fit-content;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .settings-nav {
            list-style: none;
        }

        .settings-nav-item {
            margin-bottom: 8px;
        }

        .settings-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            color: var(--text-light);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .settings-nav-link:hover {
            background: var(--hover-bg);
        }

        .settings-nav-link.active {
            background: rgba(229, 9, 20, 0.1);
            color: var(--primary-red);
            font-weight: 600;
        }

        .settings-nav-link i {
            width: 20px;
            text-align: center;
        }

        /* Content Area */
        .settings-content {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 35px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
        }

        .form-group { 
            margin-bottom: 25px; 
        }
        
        .form-label { 
            display: block; 
            font-size: 14px; 
            color: var(--text-light); 
            margin-bottom: 10px; 
            font-weight: 600; 
        }
        
        .form-input, 
        select.form-input { 
            width: 100%; 
            padding: 14px; 
            background: var(--input-bg); 
            border: 1px solid var(--border-color); 
            border-radius: 10px; 
            color: var(--text-light);
            font-size: 15px;
            transition: border-color 0.3s ease;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        /* SELECT BOX STYLING */
        select.form-input {
            background-color: var(--select-bg) !important;
            color: var(--select-text) !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23e0e0e0' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 16px;
            padding-right: 40px;
        }

        html[data-theme="light"] select.form-input {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%231a1a1a' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        }

        /* SELECT OPTIONS */
        select.form-input option {
            background-color: #1a1a1a !important;
            color: #e0e0e0 !important;
            padding: 12px;
        }

        html[data-theme="light"] select.form-input option {
            background-color: #ffffff !important;
            color: #1a1a1a !important;
        }

        .form-input:focus,
        select.form-input:focus {
            outline: none;
            border-color: var(--primary-red);
        }

        .toggle-group { 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            padding: 18px 0; 
            border-bottom: 1px solid var(--border-color); 
        }

        /* Toggle Switch UI */
        .toggle-switch { 
            position: relative; 
            width: 60px; 
            height: 30px; 
        }
        
        .toggle-switch input { 
            opacity: 0; 
            width: 0; 
            height: 0; 
        }
        
        .toggle-slider { 
            position: absolute; 
            cursor: pointer; 
            top: 0; 
            left: 0; 
            right: 0; 
            bottom: 0; 
            background-color: #555; 
            transition: .4s; 
            border-radius: 34px; 
        }
        
        .toggle-slider:before { 
            position: absolute; 
            content: ""; 
            height: 22px; 
            width: 22px; 
            left: 4px; 
            bottom: 4px; 
            background-color: white; 
            transition: .4s; 
            border-radius: 50%; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        input:checked + .toggle-slider { 
            background-color: var(--primary-red); 
        }
        
        input:checked + .toggle-slider:before { 
            transform: translateX(30px); 
        }

        .toggle-label {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .toggle-status {
            font-size: 12px;
            color: var(--text-gray);
            font-weight: normal;
        }

        .settings-section { 
            display: none; 
        }
        
        .settings-section.active { 
            display: block; 
            animation: fadeIn 0.4s ease; 
        }
        
        @keyframes fadeIn { 
            from { 
                opacity: 0; 
                transform: translateY(10px); 
            } 
            to { 
                opacity: 1; 
                transform: translateY(0); 
            } 
        }

        .section-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid var(--border-color);
        }

        /* Alert/Notification */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #10B981;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #EF4444;
        }

        /* Color input styling */
        input[type="color"] {
            width: 60px;
            height: 60px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            background: transparent;
        }

        input[type="color"]::-webkit-color-swatch-wrapper {
            padding: 0;
            border-radius: 8px;
        }

        input[type="color"]::-webkit-color-swatch {
            border: 2px solid var(--border-color);
            border-radius: 8px;
        }

        /* Ensure theme applies to all elements */
        html[data-theme] body,
        html[data-theme] .settings-content,
        html[data-theme] .settings-section,
        html[data-theme] .form-input {
            background-color: var(--card-bg) !important;
            color: var(--text-light) !important;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .settings-layout { 
                grid-template-columns: 1fr; 
            }
            
            .settings-sidebar {
                margin-bottom: 20px;
            }
        }

        @media (max-width: 768px) {
            .settings {
                padding: 20px 15px;
            }
            
            .settings-content {
                padding: 25px 20px;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .header-actions {
                width: 100%;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
<div class="settings">
    <div class="page-header">
        <h1 class="page-title">Settings</h1>
        <div class="header-actions">
            <button class="btn btn-primary" id="saveAllSettings">
                <i class="fas fa-save"></i> Save All Changes
            </button>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success" id="saveAlert" style="display: block;">
            <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success_message']; ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php elseif (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-error" id="saveAlert" style="display: block;">
            <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error_message']; ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php else: ?>
        <div class="alert alert-success" id="saveAlert" style="display: none;"></div>
    <?php endif; ?>

    <!-- Note: Now the form action points to setting_Backend.php -->
    <form method="POST" action="../library/setting_Backend.php" id="settingsForm">
        <input type="hidden" name="save_settings" value="1">
        
        <div class="settings-layout">
            <div class="settings-sidebar">
                <ul class="settings-nav">
                    <li class="settings-nav-item">
                        <a href="#" class="settings-nav-link active" data-tab="general">
                            <i class="fas fa-cog"></i> General
                        </a>
                    </li>
                    <li class="settings-nav-item">
                        <a href="#" class="settings-nav-link" data-tab="appearance">
                            <i class="fas fa-palette"></i> Appearance
                        </a>
                    </li>
                    <li class="settings-nav-item">
                        <a href="#" class="settings-nav-link" data-tab="security">
                            <i class="fas fa-shield-alt"></i> Security
                        </a>
                    </li>
                    <li class="settings-nav-item">
                        <a href="#" class="settings-nav-link" data-tab="notifications">
                            <i class="fas fa-bell"></i> Notifications
                        </a>
                    </li>
                    <li class="settings-nav-item">
                        <a href="#" class="settings-nav-link" data-tab="advanced">
                            <i class="fas fa-sliders-h"></i> Advanced
                        </a>
                    </li>
                </ul>
            </div>

            <div class="settings-content">
                <!-- General Settings -->
                <div class="settings-section active" id="general">
                    <h2 style="margin-bottom: 25px; color: var(--text-light);">General Settings</h2>
                    
                    <div class="form-group">
                        <label class="form-label">Platform Name</label>
                        <input type="text" class="form-input" name="settings[platform_name]" 
                               value="<?php echo htmlspecialchars($settings['platform_name'] ?? 'Movie Lab'); ?>" 
                               placeholder="Enter platform name">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Admin Email</label>
                        <input type="email" class="form-input" name="settings[admin_email]" 
                               value="<?php echo htmlspecialchars($settings['admin_email'] ?? 'admin@movielab.com'); ?>" 
                               placeholder="Enter admin email">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Timezone</label>
                        <select class="form-input" name="settings[timezone]">
                            <option value="Asia/Colombo" <?php echo ($settings['timezone'] ?? 'Asia/Colombo') == 'Asia/Colombo' ? 'selected' : ''; ?>>Asia/Colombo (Sri Lanka)</option>
                            <option value="UTC" <?php echo ($settings['timezone'] ?? '') == 'UTC' ? 'selected' : ''; ?>>UTC</option>
                            <option value="America/New_York" <?php echo ($settings['timezone'] ?? '') == 'America/New_York' ? 'selected' : ''; ?>>America/New_York</option>
                            <option value="Europe/London" <?php echo ($settings['timezone'] ?? '') == 'Europe/London' ? 'selected' : ''; ?>>Europe/London</option>
                            <option value="Asia/Tokyo" <?php echo ($settings['timezone'] ?? '') == 'Asia/Tokyo' ? 'selected' : ''; ?>>Asia/Tokyo</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Date Format</label>
                        <select class="form-input" name="settings[date_format]">
                            <option value="Y-m-d" <?php echo ($settings['date_format'] ?? 'Y-m-d') == 'Y-m-d' ? 'selected' : ''; ?>>YYYY-MM-DD</option>
                            <option value="d/m/Y" <?php echo ($settings['date_format'] ?? '') == 'd/m/Y' ? 'selected' : ''; ?>>DD/MM/YYYY</option>
                            <option value="m/d/Y" <?php echo ($settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : ''; ?>>MM/DD/YYYY</option>
                        </select>
                    </div>
                </div>

                <!-- Appearance Settings -->
                <div class="settings-section" id="appearance">
                    <h2 style="margin-bottom: 25px; color: var(--text-light);">Appearance</h2>
                    <p style="color: var(--text-gray); margin-bottom: 25px;">පද්ධතියේ පෙනුම මෙතැනින් වෙනස් කරන්න.</p>
                    
                    <div class="toggle-group">
                        <div class="toggle-label">
                            <span style="font-weight: 600; display: block; color: var(--text-light);">Light Mode</span>
                            <small class="toggle-status" id="themeStatus">
                                <?php echo ($settings['theme_mode'] ?? 'dark') == 'light' ? 'සක්‍රියයි' : 'අක්‍රියයි'; ?>
                            </small>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" id="themeToggle" name="settings[theme_mode]" 
                                   value="light" <?php echo ($settings['theme_mode'] ?? 'dark') == 'light' ? 'checked' : ''; ?>>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Primary Color</label>
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <input type="color" class="form-input" id="primaryColor" 
                                   name="settings[primary_color]" 
                                   value="<?php echo htmlspecialchars($settings['primary_color'] ?? '#E50914'); ?>" 
                                   style="height: 50px; padding: 5px;">
                            <span id="colorValue"><?php echo htmlspecialchars($settings['primary_color'] ?? '#E50914'); ?></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Font Size</label>
                        <select class="form-input" name="settings[font_size]">
                            <option value="small" <?php echo ($settings['font_size'] ?? 'medium') == 'small' ? 'selected' : ''; ?>>Small</option>
                            <option value="medium" <?php echo ($settings['font_size'] ?? 'medium') == 'medium' ? 'selected' : ''; ?>>Medium</option>
                            <option value="large" <?php echo ($settings['font_size'] ?? 'medium') == 'large' ? 'selected' : ''; ?>>Large</option>
                        </select>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="settings-section" id="security">
                    <h2 style="margin-bottom: 25px; color: var(--text-light);">Security Settings</h2>
                    
                    <div class="toggle-group">
                        <div class="toggle-label">
                            <span style="font-weight: 600; display: block; color: var(--text-light);">Two-Factor Authentication</span>
                            <small style="color: var(--text-gray);">ලොගින් වීමේදී අතිරේක ආරක්ෂාව</small>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" id="twoFactorToggle" name="settings[two_factor_auth]" 
                                   value="1" <?php echo ($settings['two_factor_auth'] ?? '0') == '1' ? 'checked' : ''; ?>>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Session Timeout (Minutes)</label>
                        <input type="number" class="form-input" name="settings[session_timeout]" 
                               value="<?php echo htmlspecialchars($settings['session_timeout'] ?? '30'); ?>" 
                               min="5" max="120">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Password Policy</label>
                        <select class="form-input" name="settings[password_policy]">
                            <option value="low" <?php echo ($settings['password_policy'] ?? 'medium') == 'low' ? 'selected' : ''; ?>>Low (Minimum 6 characters)</option>
                            <option value="medium" <?php echo ($settings['password_policy'] ?? 'medium') == 'medium' ? 'selected' : ''; ?>>Medium (Minimum 8 characters with mix)</option>
                            <option value="high" <?php echo ($settings['password_policy'] ?? 'medium') == 'high' ? 'selected' : ''; ?>>High (Minimum 12 characters with special chars)</option>
                        </select>
                    </div>
                </div>

                <!-- Notifications Settings -->
                <div class="settings-section" id="notifications">
                    <h2 style="margin-bottom: 25px; color: var(--text-light);">Notifications</h2>
                    
                    <div class="toggle-group">
                        <div class="toggle-label">
                            <span style="font-weight: 600; display: block; color: var(--text-light);">Email Notifications</span>
                            <small style="color: var(--text-gray);">විද්‍යුත් තැපෑලෙන් දැනුම්දීම්</small>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" id="emailNotifications" name="settings[email_notifications]" 
                                   value="1" <?php echo ($settings['email_notifications'] ?? '1') == '1' ? 'checked' : ''; ?>>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    
                    <div class="toggle-group">
                        <div class="toggle-label">
                            <span style="font-weight: 600; display: block; color: var(--text-light);">Push Notifications</span>
                            <small style="color: var(--text-gray);">තවත් පරිශීලකයින් සම්බන්ධ කිරීම්</small>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" id="pushNotifications" name="settings[push_notifications]" 
                                   value="1" <?php echo ($settings['push_notifications'] ?? '1') == '1' ? 'checked' : ''; ?>>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Notification Sound</label>
                        <select class="form-input" name="settings[notification_sound]">
                            <option value="default" <?php echo ($settings['notification_sound'] ?? 'default') == 'default' ? 'selected' : ''; ?>>Default</option>
                            <option value="chime" <?php echo ($settings['notification_sound'] ?? '') == 'chime' ? 'selected' : ''; ?>>Chime</option>
                            <option value="bell" <?php echo ($settings['notification_sound'] ?? '') == 'bell' ? 'selected' : ''; ?>>Bell</option>
                            <option value="none" <?php echo ($settings['notification_sound'] ?? '') == 'none' ? 'selected' : ''; ?>>None</option>
                        </select>
                    </div>
                </div>


                <!-- Advanced Settings -->
<div class="settings-section" id="advanced">
    <h2 style="margin-bottom: 25px; color: var(--text-light);">Advanced Settings</h2>
    <p style="color: var(--text-gray); margin-bottom: 25px;">Database backup and restore options.</p>
    
    <!-- Backup Section -->
    <div class="backup-section">
        <h3><i class="fas fa-database"></i> Database Backup</h3>
        
        <!-- Backup Stats -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 25px;">
            <div style="background: var(--card-bg); padding: 15px; border-radius: 8px; border: 1px solid var(--border-color);">
                <div style="font-size: 12px; color: var(--text-gray); margin-bottom: 5px;">Tables</div>
                <div style="font-size: 24px; font-weight: bold; color: var(--text-light);" id="tableCount">0</div>
            </div>
            <div style="background: var(--card-bg); padding: 15px; border-radius: 8px; border: 1px solid var(--border-color);">
                <div style="font-size: 12px; color: var(--text-gray); margin-bottom: 5px;">Total Backup Size</div>
                <div style="font-size: 24px; font-weight: bold; color: var(--text-light);" id="backupSize">0 MB</div>
            </div>
        </div>
        
        <!-- Create Backup Button -->
        <button class="btn btn-primary" id="createBackupBtn" style="margin-bottom: 25px;">
            <i class="fas fa-plus"></i> Create New Backup
        </button>
        
        <!-- Backup List -->
        <h4 style="margin-bottom: 15px; color: var(--text-light);">Existing Backups</h4>
        <div class="backup-list" id="backupList">
            <div style="text-align: center; padding: 20px; color: var(--text-gray);">
                Loading backups...
            </div>
        </div>
    </div>
    
    <!-- Additional Advanced Settings -->
    <div class="toggle-group">
        <div class="toggle-label">
            <span style="font-weight: 600; display: block; color: var(--text-light);">Debug Mode</span>
            <small style="color: var(--text-gray);">Enable detailed error logging</small>
        </div>
        <label class="toggle-switch">
            <input type="checkbox" id="debugMode" name="settings[debug_mode]" 
                   value="1" <?php echo ($settings['debug_mode'] ?? '0') == '1' ? 'checked' : ''; ?>>
            <span class="toggle-slider"></span>
        </label>
    </div>
    
    <div class="toggle-group">
        <div class="toggle-label">
            <span style="font-weight: 600; display: block; color: var(--text-light);">Maintenance Mode</span>
            <small style="color: var(--text-gray);">Temporarily disable public access</small>
        </div>
        <label class="toggle-switch">
            <input type="checkbox" id="maintenanceMode" name="settings[maintenance_mode]" 
                   value="1" <?php echo ($settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : ''; ?>>
            <span class="toggle-slider"></span>
        </label>
    </div>
    
    <div class="form-group">
        <label class="form-label">Cache Duration (Seconds)</label>
        <input type="number" class="form-input" name="settings[cache_duration]" 
               value="<?php echo htmlspecialchars($settings['cache_duration'] ?? '3600'); ?>" 
               min="0" max="86400">
    </div>
</div>
<?php
// include/functions.php

/**
 * Database Backup Function
 */
function createDatabaseBackup($pdo, $backupDir = '../backups/') {
    // Create backup directory if not exists
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d_H-i-s');
    $backupFile = $backDir . 'movielab_backup_' . $timestamp . '.sql';
    
    // Get all tables
    $tables = [];
    $result = $pdo->query("SHOW TABLES");
    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }
    
    $backupContent = "-- Movie Lab Database Backup\n";
    $backupContent .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
    $backupContent .= "-- Database: movielab\n\n";
    
    foreach ($tables as $table) {
        // Table structure
        $backupContent .= "--\n-- Table structure for table `$table`\n--\n";
        $createTable = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_NUM);
        $backupContent .= $createTable[1] . ";\n\n";
        
        // Table data
        $backupContent .= "--\n-- Dumping data for table `$table`\n--\n";
        
        $rows = $pdo->query("SELECT * FROM `$table`");
        $rowCount = 0;
        
        while ($row = $rows->fetch(PDO::FETCH_ASSOC)) {
            if ($rowCount == 0) {
                $backupContent .= "INSERT INTO `$table` VALUES \n";
            } else {
                $backupContent .= ",\n";
            }
            
            $values = array_map(function($value) use ($pdo) {
                if ($value === null) {
                    return 'NULL';
                }
                return $pdo->quote($value);
            }, array_values($row));
            
            $backupContent .= "(" . implode(", ", $values) . ")";
            $rowCount++;
        }
        
        if ($rowCount > 0) {
            $backupContent .= ";\n\n";
        }
    }
    
    // Save to file
    if (file_put_contents($backupFile, $backupContent)) {
        return [
            'success' => true,
            'file' => $backupFile,
            'size' => filesize($backupFile),
            'timestamp' => $timestamp
        ];
    }
    
    return ['success' => false, 'error' => 'Failed to write backup file'];
}

/**
 * Get all backup files
 */
function getBackupFiles($backupDir = '../backups/') {
    if (!is_dir($backupDir)) {
        return [];
    }
    
    $files = [];
    $backupFiles = glob($backupDir . 'movielab_backup_*.sql');
    
    foreach ($backupFiles as $file) {
        $files[] = [
            'name' => basename($file),
            'path' => $file,
            'size' => filesize($file),
            'modified' => filemtime($file),
            'date' => date('Y-m-d H:i:s', filemtime($file))
        ];
    }
    
    // Sort by modified date (newest first)
    usort($files, function($a, $b) {
        return $b['modified'] - $a['modified'];
    });
    
    return $files;
}

/**
 * Restore database from backup
 */
function restoreDatabase($pdo, $backupFile) {
    if (!file_exists($backupFile)) {
        return ['success' => false, 'error' => 'Backup file not found'];
    }
    
    $sql = file_get_contents($backupFile);
    
    try {
        $pdo->beginTransaction();
        
        // Disable foreign key checks
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        
        // Execute SQL queries
        $queries = explode(";\n", $sql);
        foreach ($queries as $query) {
            $query = trim($query);
            if (!empty($query)) {
                $pdo->exec($query);
            }
        }
        
        // Enable foreign key checks
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
        
        $pdo->commit();
        
        return ['success' => true, 'message' => 'Database restored successfully'];
        
    } catch (Exception $e) {
        $pdo->rollBack();
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
?>

                <!-- Save button -->
                <div class="section-actions">
                    <button type="submit" class="btn btn-primary" id="saveChanges">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // SIMPLE AND EFFECTIVE THEME MANAGEMENT
    const themeToggle = document.getElementById('themeToggle');
    const themeStatus = document.getElementById('themeStatus');
    const htmlElement = document.documentElement;
    
    // Initialize theme on page load
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = "<?php echo $current_theme; ?>";
        
        // Apply saved theme
        if (savedTheme === 'light') {
            htmlElement.setAttribute('data-theme', 'light');
            if (themeToggle) themeToggle.checked = true;
            if (themeStatus) themeStatus.textContent = 'සක්‍රියයි';
        } else {
            htmlElement.setAttribute('data-theme', 'dark');
            if (themeToggle) themeToggle.checked = false;
            if (themeStatus) themeStatus.textContent = 'අක්‍රියයි';
        }
        
        // Color picker value display
        const colorPicker = document.getElementById('primaryColor');
        const colorValue = document.getElementById('colorValue');
        
        if (colorPicker && colorValue) {
            colorPicker.addEventListener('input', function() {
                colorValue.textContent = this.value;
            });
        }
    });
    
    // Theme toggle event
    themeToggle.addEventListener('change', function() {
        if (this.checked) {
            htmlElement.setAttribute('data-theme', 'light');
            themeStatus.textContent = 'සක්‍රියයි';
            this.value = 'light';
        } else {
            htmlElement.setAttribute('data-theme', 'dark');
            themeStatus.textContent = 'අක්‍රියයි';
            this.value = 'dark';
        }
    });
    
    // Tab Navigation - Theme will persist automatically because it's on HTML element
    document.querySelectorAll('.settings-nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Update active tab in nav
            document.querySelectorAll('.settings-nav-link').forEach(n => n.classList.remove('active'));
            this.classList.add('active');
            
            // Show corresponding section
            const tabId = this.getAttribute('data-tab');
            document.querySelectorAll('.settings-section').forEach(s => s.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            
            // Theme is already on HTML element, so it persists automatically
            // No need to reapply
        });
    });
    
    // Save All Settings button
    document.getElementById('saveAllSettings').addEventListener('click', function() {
        document.getElementById('settingsForm').submit();
    });
    
    // Checkbox value handling
    document.querySelectorAll('.toggle-switch input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                this.value = '1';
            } else {
                this.value = '0';
            }
        });
        
        // Initialize checkbox values
        if (checkbox.checked) {
            checkbox.value = '1';
        } else {
            checkbox.value = '0';
        }
    });
    
    // Simple theme persistence check
    setInterval(() => {
        const currentTheme = htmlElement.getAttribute('data-theme');
        if (themeToggle) {
            // Keep checkbox in sync
            themeToggle.checked = (currentTheme === 'light');
        }
    }, 500);



    // Database Backup Functions
document.addEventListener('DOMContentLoaded', function() {
    // Load backup stats
    loadBackupStats();
    loadBackupList();
    
    // Create backup button
    document.getElementById('createBackupBtn').addEventListener('click', function() {
        createBackup();
    });
});

function loadBackupStats() {
    fetch('../library/backup_Backend.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=get_stats'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('tableCount').textContent = data.table_count;
            document.getElementById('backupSize').textContent = data.total_size_mb + ' MB';
        }
    })
    .catch(error => {
        console.error('Error loading stats:', error);
    });
}

function loadBackupList() {
    const backupList = document.getElementById('backupList');
    
    fetch('../library/backup_Backend.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=get_backups'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.backups.length > 0) {
            let html = '<table style="width: 100%; border-collapse: collapse;">';
            html += '<thead>';
            html += '<tr style="background: var(--input-bg);">';
            html += '<th style="padding: 10px; text-align: left; border-bottom: 1px solid var(--border-color); color: var(--text-light);">File Name</th>';
            html += '<th style="padding: 10px; text-align: left; border-bottom: 1px solid var(--border-color); color: var(--text-light);">Date</th>';
            html += '<th style="padding: 10px; text-align: left; border-bottom: 1px solid var(--border-color); color: var(--text-light);">Size</th>';
            html += '<th style="padding: 10px; text-align: left; border-bottom: 1px solid var(--border-color); color: var(--text-light);">Actions</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';
            
            data.backups.forEach(backup => {
                const fileName = backup.name;
                const fileDate = backup.date;
                const fileSize = (backup.size / 1024 / 1024).toFixed(2) + ' MB';
                const filePath = backup.path;
                
                html += '<tr style="border-bottom: 1px solid var(--border-color);">';
                html += `<td style="padding: 10px; color: var(--text-light);">${fileName}</td>`;
                html += `<td style="padding: 10px; color: var(--text-gray);">${fileDate}</td>`;
                html += `<td style="padding: 10px; color: var(--text-gray);">${fileSize}</td>`;
                html += '<td style="padding: 10px;">';
                html += `<button class="btn btn-secondary" style="padding: 5px 10px; margin-right: 5px;" onclick="downloadBackup('${fileName}')">
                            <i class="fas fa-download"></i>
                         </button>`;
                html += `<button class="btn btn-secondary" style="padding: 5px 10px; margin-right: 5px;" onclick="restoreBackup('${filePath}')">
                            <i class="fas fa-undo"></i>
                         </button>`;
                html += `<button class="btn btn-secondary" style="padding: 5px 10px;" onclick="deleteBackup('${filePath}')">
                            <i class="fas fa-trash"></i>
                         </button>`;
                html += '</td>';
                html += '</tr>';
            });
            
            html += '</tbody>';
            html += '</table>';
            
            backupList.innerHTML = html;
        } else {
            backupList.innerHTML = '<div style="text-align: center; padding: 20px; color: var(--text-gray);">No backups found</div>';
        }
    })
    .catch(error => {
        console.error('Error loading backups:', error);
        backupList.innerHTML = '<div style="text-align: center; padding: 20px; color: #EF4444;">Error loading backups</div>';
    });
}

function createBackup() {
    const btn = document.getElementById('createBackupBtn');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Backup...';
    btn.disabled = true;
    
    fetch('../library/backup_Backend.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=create_backup'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Backup created successfully!', 'success');
            loadBackupStats();
            loadBackupList();
        } else {
            showAlert('Error creating backup: ' + data.error, 'error');
        }
        
        btn.innerHTML = originalText;
        btn.disabled = false;
    })
    .catch(error => {
        showAlert('Error creating backup: ' + error.message, 'error');
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

function downloadBackup(fileName) {
    window.open('../backups/' + fileName, '_blank');
}

function restoreBackup(filePath) {
    if (!confirm('Are you sure you want to restore this backup? This will overwrite your current database!')) {
        return;
    }
    
    fetch('../library/backup_Backend.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=restore_backup&backup_file=${encodeURIComponent(filePath)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Database restored successfully!', 'success');
        } else {
            showAlert('Error restoring backup: ' + data.error, 'error');
        }
    })
    .catch(error => {
        showAlert('Error restoring backup: ' + error.message, 'error');
    });
}

function deleteBackup(filePath) {
    if (!confirm('Are you sure you want to delete this backup?')) {
        return;
    }
    
    fetch('../library/backup_Backend.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=delete_backup&backup_file=${encodeURIComponent(filePath)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Backup deleted successfully!', 'success');
            loadBackupStats();
            loadBackupList();
        } else {
            showAlert('Error deleting backup: ' + data.error, 'error');
        }
    })
    .catch(error => {
        showAlert('Error deleting backup: ' + error.message, 'error');
    });
}

function showAlert(message, type) {
    const alertDiv = document.getElementById('saveAlert');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
    alertDiv.style.display = 'block';
    
    setTimeout(() => {
        alertDiv.style.display = 'none';
    }, 5000);
}
</script>

</body>
</html>