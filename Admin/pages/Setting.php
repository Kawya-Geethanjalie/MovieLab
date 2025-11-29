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

    /* Settings Styles */
    .settings {
        padding: 20px;
        min-height: 100vh;
        max-width: 1200px;
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

    /* Settings Layout */
    .settings-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 25px;
    }

    @media (max-width: 1024px) {
        .settings-layout {
            grid-template-columns: 1fr;
        }
    }

    /* Settings Sidebar */
    .settings-sidebar {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.05);
        height: fit-content;
        position: sticky;
        top: 20px;
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
        padding: 12px 16px;
        color: var(--text-light);
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }

    .settings-nav-link:hover {
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-light);
    }

    .settings-nav-link.active {
        background: rgba(229, 9, 20, 0.1);
        color: var(--primary-red);
        border-left-color: var(--primary-red);
    }

    .settings-nav-icon {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: inherit;
    }

    /* Settings Content */
    .settings-content {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .settings-section {
        margin-bottom: 40px;
    }

    .settings-section:last-child {
        margin-bottom: 0;
    }

    .section-header {
        margin-bottom: 25px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 600;
        color: var(--text-light);
        margin-bottom: 8px;
    }

    .section-description {
        font-size: 14px;
        color: var(--text-gray);
        line-height: 1.5;
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 20px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
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
        transition: all 0.3s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary-red);
        background: rgba(255, 255, 255, 0.08);
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23888'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
        padding-right: 40px;
    }

    /* Toggle Switch */
    .toggle-group {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .toggle-label {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .toggle-title {
        font-size: 14px;
        color: var(--text-light);
        font-weight: 500;
    }

    .toggle-description {
        font-size: 12px;
        color: var(--text-gray);
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
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
        background-color: rgba(255, 255, 255, 0.1);
        transition: .4s;
        border-radius: 24px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .toggle-slider {
        background-color: var(--primary-red);
    }

    input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }

    /* Checkbox Group */
    .checkbox-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: rgba(255, 255, 255, 0.02);
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        transition: all 0.3s ease;
    }

    .checkbox-item:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .checkbox-input {
        width: 18px;
        height: 18px;
        border-radius: 4px;
        border: 2px solid rgba(255, 255, 255, 0.2);
        background: transparent;
        cursor: pointer;
    }

    .checkbox-input:checked {
        background: var(--primary-red);
        border-color: var(--primary-red);
    }

    .checkbox-label {
        flex: 1;
    }

    .checkbox-title {
        font-size: 14px;
        color: var(--text-light);
        margin-bottom: 2px;
    }

    .checkbox-description {
        font-size: 12px;
        color: var(--text-gray);
    }

    /* Danger Zone */
    .danger-zone {
        border: 1px solid rgba(239, 68, 68, 0.3);
        background: rgba(239, 68, 68, 0.05);
        border-radius: 12px;
        padding: 25px;
    }

    .danger-title {
        color: var(--danger);
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .danger-description {
        color: var(--text-gray);
        font-size: 14px;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .btn-danger {
        background: rgba(239, 68, 68, 0.2);
        color: var(--danger);
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .btn-danger:hover {
        background: rgba(239, 68, 68, 0.3);
    }

    /* Save Actions */
    .save-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 25px;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        margin-top: 30px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .settings {
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

        .settings-content {
            padding: 20px;
        }

        .save-actions {
            flex-direction: column;
        }

        .save-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .settings {
            padding: 10px;
        }

        .settings-content {
            padding: 15px;
        }
    }
</style>

<!-- Settings Page -->
<div class="settings">
    <div class="page-header">
        <h1 class="page-title">Settings</h1>
        <div class="header-actions">
            <button class="btn btn-secondary">
                <i class="fas fa-download"></i>
                Export Settings
            </button>
            <button class="btn btn-primary" id="saveAllSettings">
                <i class="fas fa-save"></i>
                Save All Changes
            </button>
        </div>
    </div>

    <div class="settings-layout">
        <!-- Settings Sidebar -->
        <div class="settings-sidebar">
            <ul class="settings-nav">
                <li class="settings-nav-item">
                    <a href="#general" class="settings-nav-link active" data-tab="general">
                        <div class="settings-nav-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        General Settings
                    </a>
                </li>
                <li class="settings-nav-item">
                    <a href="#appearance" class="settings-nav-link" data-tab="appearance">
                        <div class="settings-nav-icon">
                            <i class="fas fa-palette"></i>
                        </div>
                        Appearance
                    </a>
                </li>
                <li class="settings-nav-item">
                    <a href="#notifications" class="settings-nav-link" data-tab="notifications">
                        <div class="settings-nav-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        Notifications
                    </a>
                </li>
                <li class="settings-nav-item">
                    <a href="#security" class="settings-nav-link" data-tab="security">
                        <div class="settings-nav-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        Security
                    </a>
                </li>
                <li class="settings-nav-item">
                    <a href="#advanced" class="settings-nav-link" data-tab="advanced">
                        <div class="settings-nav-icon">
                            <i class="fas fa-sliders-h"></i>
                        </div>
                        Advanced
                    </a>
                </li>
                <li class="settings-nav-item">
                    <a href="#backup" class="settings-nav-link" data-tab="backup">
                        <div class="settings-nav-icon">
                            <i class="fas fa-database"></i>
                        </div>
                        Backup & Restore
                    </a>
                </li>
            </ul>
        </div>

        <!-- Settings Content -->
        <div class="settings-content">
            <!-- General Settings -->
            <div class="settings-section active" id="general">
                <div class="section-header">
                    <h2 class="section-title">General Settings</h2>
                    <p class="section-description">Manage your Movie Lab platform's basic configuration and preferences.</p>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Platform Name</label>
                        <input type="text" class="form-input" value="Movie Lab" placeholder="Enter platform name">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Admin Email</label>
                        <input type="email" class="form-input" value="admin@movielab.com" placeholder="Enter admin email">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Platform Description</label>
                    <textarea class="form-input form-textarea" placeholder="Enter platform description">Your ultimate movie streaming platform with the latest content and exclusive features.</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Default Language</label>
                        <select class="form-input form-select">
                            <option value="en" selected>English</option>
                            <option value="si">Sinhala</option>
                            <option value="ta">Tamil</option>
                            <option value="hi">Hindi</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Time Zone</label>
                        <select class="form-input form-select">
                            <option value="asia/colombo" selected>Asia/Colombo (GMT+5:30)</option>
                            <option value="utc">UTC</option>
                            <option value="est">Eastern Time</option>
                            <option value="pst">Pacific Time</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Currency</label>
                    <select class="form-input form-select">
                        <option value="lkr" selected>Sri Lankan Rupee (LKR)</option>
                        <option value="usd">US Dollar (USD)</option>
                        <option value="eur">Euro (EUR)</option>
                        <option value="gbp">British Pound (GBP)</option>
                    </select>
                </div>
            </div>

            <!-- Appearance Settings -->
            <div class="settings-section" id="appearance">
                <div class="section-header">
                    <h2 class="section-title">Appearance</h2>
                    <p class="section-description">Customize the look and feel of your Movie Lab admin panel.</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Theme</label>
                    <select class="form-input form-select">
                        <option value="dark" selected>Dark Theme</option>
                        <option value="light">Light Theme</option>
                        <option value="auto">Auto (System Preference)</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Primary Color</label>
                        <input type="color" class="form-input" value="#E50914" style="height: 45px; padding: 5px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Accent Color</label>
                        <input type="color" class="form-input" value="#B80710" style="height: 45px; padding: 5px;">
                    </div>
                </div>

                <div class="toggle-group">
                    <div class="toggle-label">
                        <span class="toggle-title">Compact Mode</span>
                        <span class="toggle-description">Use compact layout for better space utilization</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="toggle-group">
                    <div class="toggle-label">
                        <span class="toggle-title">Show Avatars</span>
                        <span class="toggle-description">Display user avatars in lists and tables</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="toggle-group">
                    <div class="toggle-label">
                        <span class="toggle-title">Animations</span>
                        <span class="toggle-description">Enable smooth animations and transitions</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="settings-section" id="notifications">
                <div class="section-header">
                    <h2 class="section-title">Notifications</h2>
                    <p class="section-description">Configure how and when you receive notifications.</p>
                </div>

                <div class="checkbox-group">
                    <div class="checkbox-item">
                        <input type="checkbox" class="checkbox-input" checked>
                        <div class="checkbox-label">
                            <div class="checkbox-title">Email Notifications</div>
                            <div class="checkbox-description">Receive important updates via email</div>
                        </div>
                    </div>

                    <div class="checkbox-item">
                        <input type="checkbox" class="checkbox-input" checked>
                        <div class="checkbox-label">
                            <div class="checkbox-title">Push Notifications</div>
                            <div class="checkbox-description">Get instant browser notifications</div>
                        </div>
                    </div>

                    <div class="checkbox-item">
                        <input type="checkbox" class="checkbox-input">
                        <div class="checkbox-label">
                            <div class="checkbox-title">SMS Alerts</div>
                            <div class="checkbox-description">Receive critical alerts via SMS</div>
                        </div>
                    </div>

                    <div class="checkbox-item">
                        <input type="checkbox" class="checkbox-input" checked>
                        <div class="checkbox-label">
                            <div class="checkbox-title">New User Registrations</div>
                            <div class="checkbox-description">Get notified when new users sign up</div>
                        </div>
                    </div>

                    <div class="checkbox-item">
                        <input type="checkbox" class="checkbox-input" checked>
                        <div class="checkbox-label">
                            <div class="checkbox-title">Payment Notifications</div>
                            <div class="checkbox-description">Receive alerts for successful and failed payments</div>
                        </div>
                    </div>

                    <div class="checkbox-item">
                        <input type="checkbox" class="checkbox-input">
                        <div class="checkbox-label">
                            <div class="checkbox-title">System Updates</div>
                            <div class="checkbox-description">Notifications about system maintenance and updates</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="settings-section" id="security">
                <div class="section-header">
                    <h2 class="section-title">Security</h2>
                    <p class="section-description">Manage security preferences and access controls.</p>
                </div>

                <div class="toggle-group">
                    <div class="toggle-label">
                        <span class="toggle-title">Two-Factor Authentication</span>
                        <span class="toggle-description">Add an extra layer of security to your account</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="toggle-group">
                    <div class="toggle-label">
                        <span class="toggle-title">Session Timeout</span>
                        <span class="toggle-description">Automatically log out after 30 minutes of inactivity</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="toggle-group">
                    <div class="toggle-label">
                        <span class="toggle-title">IP Whitelisting</span>
                        <span class="toggle-description">Restrict access to specific IP addresses</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-label">Password Policy</label>
                    <select class="form-input form-select">
                        <option value="standard" selected>Standard (8+ characters)</option>
                        <option value="strong">Strong (12+ characters with symbols)</option>
                        <option value="very-strong">Very Strong (16+ characters with complexity)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Login Attempts Limit</label>
                    <select class="form-input form-select">
                        <option value="3">3 attempts</option>
                        <option value="5" selected>5 attempts</option>
                        <option value="10">10 attempts</option>
                    </select>
                </div>
            </div>

            <!-- Advanced Settings -->
            <div class="settings-section" id="advanced">
                <div class="section-header">
                    <h2 class="section-title">Advanced Settings</h2>
                    <p class="section-description">Advanced configuration options for experienced administrators.</p>
                </div>

                <div class="form-group">
                    <label class="form-label">API Access Key</label>
                    <input type="text" class="form-input" value="ml_sk_***************" readonly>
                    <button class="btn btn-secondary btn-sm" style="margin-top: 8px;">
                        <i class="fas fa-redo"></i>
                        Regenerate Key
                    </button>
                </div>

                <div class="form-group">
                    <label class="form-label">Cache Duration</label>
                    <select class="form-input form-select">
                        <option value="5">5 minutes</option>
                        <option value="15">15 minutes</option>
                        <option value="30" selected>30 minutes</option>
                        <option value="60">1 hour</option>
                    </select>
                </div>

                <div class="toggle-group">
                    <div class="toggle-label">
                        <span class="toggle-title">Debug Mode</span>
                        <span class="toggle-description">Enable detailed error logging and debugging information</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="toggle-group">
                    <div class="toggle-label">
                        <span class="toggle-title">Maintenance Mode</span>
                        <span class="toggle-description">Put the system in maintenance mode</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>

            <!-- Backup & Restore -->
            <div class="settings-section" id="backup">
                <div class="section-header">
                    <h2 class="section-title">Backup & Restore</h2>
                    <p class="section-description">Manage your data backups and restoration processes.</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Auto Backup Frequency</label>
                    <select class="form-input form-select">
                        <option value="daily">Daily</option>
                        <option value="weekly" selected>Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="never">Never</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Backup Retention</label>
                    <select class="form-input form-select">
                        <option value="7">7 days</option>
                        <option value="30" selected>30 days</option>
                        <option value="90">90 days</option>
                        <option value="365">1 year</option>
                    </select>
                </div>

                <div class="form-row">
                    <button class="btn btn-secondary">
                        <i class="fas fa-download"></i>
                        Create Backup Now
                    </button>
                    <button class="btn btn-secondary">
                        <i class="fas fa-upload"></i>
                        Restore from Backup
                    </button>
                </div>

                <!-- Danger Zone -->
                <div class="danger-zone" style="margin-top: 30px;">
                    <h3 class="danger-title">Danger Zone</h3>
                    <p class="danger-description">
                        These actions are irreversible. Please proceed with caution. 
                        Deleting all data will remove all users, content, and settings permanently.
                    </p>
                    <button class="btn btn-danger">
                        <i class="fas fa-trash"></i>
                        Delete All Data
                    </button>
                </div>
            </div>

            <!-- Save Actions -->
            <div class="save-actions">
                <button class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Discard Changes
                </button>
                <button class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Tab navigation
    document.querySelectorAll('.settings-nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all links and sections
            document.querySelectorAll('.settings-nav-link').forEach(nav => {
                nav.classList.remove('active');
            });
            document.querySelectorAll('.settings-section').forEach(section => {
                section.classList.remove('active');
            });
            
            // Add active class to clicked link and corresponding section
            this.classList.add('active');
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Save all settings
    document.getElementById('saveAllSettings').addEventListener('click', function() {
        const saveBtn = this;
        const originalText = saveBtn.innerHTML;
        
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        saveBtn.disabled = true;
        
        // Simulate saving process
        setTimeout(() => {
            saveBtn.innerHTML = '<i class="fas fa-check"></i> Saved Successfully!';
            saveBtn.style.background = 'var(--success)';
            
            setTimeout(() => {
                saveBtn.innerHTML = originalText;
                saveBtn.style.background = '';
                saveBtn.disabled = false;
            }, 2000);
        }, 1500);
    });

    // Form submission
    document.querySelectorAll('.settings-section form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Settings saved successfully!');
        });
    });

    // Danger zone confirmation
    document.querySelector('.btn-danger').addEventListener('click', function() {
        if (confirm('⚠️ DANGER ZONE ⚠️\n\nAre you absolutely sure you want to delete ALL data? This action cannot be undone and will permanently delete all users, content, and settings.')) {
            if (confirm('This is your final warning. This will destroy all data irreversibly. Type "DELETE ALL" to confirm.')) {
                const confirmation = prompt('Please type "DELETE ALL" to confirm:');
                if (confirmation === 'DELETE ALL') {
                    alert('Data deletion initiated. This process may take several minutes.');
                    // In real application, this would trigger an API call
                } else {
                    alert('Deletion cancelled. Data is safe.');
                }
            }
        }
    });

    // Toggle switch functionality
    document.querySelectorAll('.toggle-switch input').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const label = this.closest('.toggle-group').querySelector('.toggle-title').textContent;
            const status = this.checked ? 'enabled' : 'disabled';
            console.log(`${label}: ${status}`);
        });
    });

    // Checkbox functionality
    document.querySelectorAll('.checkbox-input').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const label = this.closest('.checkbox-item').querySelector('.checkbox-title').textContent;
            const status = this.checked ? 'enabled' : 'disabled';
            console.log(`${label}: ${status}`);
        });
    });
</script>

</body>
</html>