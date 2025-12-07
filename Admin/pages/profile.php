<?php
include '../include/header.php';
include '../include/connection.php';

// Initialize success/error messages
$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'update_profile':
                    $first_name = trim($_POST['first_name']);
                    $last_name = trim($_POST['last_name']);
                    $email = trim($_POST['email']);
                    
                    // Validate inputs
                    if (empty($first_name) || empty($last_name) || empty($email)) {
                        throw new Exception('First name, last name and email are required.');
                    }
                    
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        throw new Exception('Please enter a valid email address.');
                    }
                    
                    // Check if email already exists for another user
                    $admin_id = $_SESSION['admin_id'];
                    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
                    $stmt->execute([$email, $admin_id]);
                    if ($stmt->rowCount() > 0) {
                        throw new Exception('Email already exists for another user.');
                    }
                    
                    // Update database
                    $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, updated_at = NOW() WHERE user_id = ?");
                    $success = $stmt->execute([$first_name, $last_name, $email, $admin_id]);
                    
                    if ($success) {
                        // Update session data immediately
                        $_SESSION['admin_first_name'] = $first_name;
                        $_SESSION['admin_last_name'] = $last_name;
                        $_SESSION['admin_email'] = $email;
                        $_SESSION['admin_full_name'] = $first_name . ' ' . $last_name;
                        
                        $success_message = 'Profile updated successfully!';
                    } else {
                        throw new Exception('Failed to update profile.');
                    }
                    break;
                    
                case 'update_password':
                    $current_password = $_POST['current_password'];
                    $new_password = $_POST['new_password'];
                    $confirm_password = $_POST['confirm_password'];
                    
                    // Validate inputs
                    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                        throw new Exception('All password fields are required.');
                    }
                    
                    if ($new_password !== $confirm_password) {
                        throw new Exception('New passwords do not match.');
                    }
                    
                    if (strlen($new_password) < 6) {
                        throw new Exception('New password must be at least 6 characters long.');
                    }
                    
                    // Verify current password
                    $admin_id = $_SESSION['admin_id'];
                    $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE user_id = ?");
                    $stmt->execute([$admin_id]);
                    $user = $stmt->fetch();
                    
                    if (!$user || !password_verify($current_password, $user['password_hash'])) {
                        throw new Exception('Current password is incorrect.');
                    }
                    
                    // Update password
                    $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);
                    $stmt = $pdo->prepare("UPDATE users SET password_hash = ?, updated_at = NOW() WHERE user_id = ?");
                    $success = $stmt->execute([$new_password_hash, $admin_id]);
                    
                    if ($success) {
                        $success_message = 'Password updated successfully!';
                    } else {
                        throw new Exception('Failed to update password.');
                    }
                    break;
                    
                case 'upload_image':
                    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
                        $upload_dir = '../../uploads/profile_images/';
                        
                        // Create directory if it doesn't exist
                        if (!is_dir($upload_dir)) {
                            mkdir($upload_dir, 0755, true);
                        }
                        
                        $file_info = pathinfo($_FILES['profile_image']['name']);
                        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                        
                        if (!in_array(strtolower($file_info['extension']), $allowed_extensions)) {
                            throw new Exception('Only JPG, JPEG, PNG, and GIF files are allowed.');
                        }
                        
                        // Check file size (max 1MB)
                        if ($_FILES['profile_image']['size'] > 1 * 1024 * 1024) {
                            throw new Exception('File size must be less than 1MB.');
                        }
                        
                        $new_filename = 'profile_' . $_SESSION['admin_username'] . '_' . time() . '.' . $file_info['extension'];
                        $upload_path = $upload_dir . $new_filename;
                        
                        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                            // Remove old profile image if exists
                            $admin_id = $_SESSION['admin_id'];
                            $stmt = $pdo->prepare("SELECT profile_image FROM users WHERE user_id = ?");
                            $stmt->execute([$admin_id]);
                            $old_user = $stmt->fetch();
                            
                            if ($old_user && !empty($old_user['profile_image']) && file_exists('../../uploads/profile_images/' . $old_user['profile_image'])) {
                                unlink('../../uploads/profile_images/' . $old_user['profile_image']);
                            }
                            
                            // Update database
                            $stmt = $pdo->prepare("UPDATE users SET profile_image = ?, updated_at = NOW() WHERE user_id = ?");
                            $success = $stmt->execute([$new_filename, $admin_id]);
                            
                            if ($success) {
                                // Update session data immediately
                                $_SESSION['admin_profile_image'] = $new_filename;
                                $success_message = 'Profile image updated successfully!';
                            } else {
                                throw new Exception('Failed to update profile image in database.');
                            }
                        } else {
                            throw new Exception('Failed to upload image.');
                        }
                    } else {
                        throw new Exception('Please select an image file.');
                    }
                    break;
            }
        }
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

// Get current admin data from database using user_id
$admin_data = [
    'user_id' => '',
    'username' => $_SESSION['admin_username'] ?? 'Admin',
    'first_name' => 'Admin',
    'last_name' => 'User',
    'email' => 'admin@movielab.com',
    'profile_image' => ''
];

// First, try to get admin_id from session, if not available, get it from username
$admin_id = null;
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
} elseif (isset($_SESSION['admin_username'])) {
    // Get user_id from username
    try {
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE username = ? AND user_type = 'admin'");
        $stmt->execute([$_SESSION['admin_username']]);
        $user = $stmt->fetch();
        if ($user) {
            $admin_id = $user['user_id'];
            $_SESSION['admin_id'] = $admin_id; // Store for future use
        }
    } catch (PDOException $e) {
        error_log("Error getting admin_id: " . $e->getMessage());
    }
}

if ($admin_id) {
    try {
        $stmt = $pdo->prepare("SELECT user_id, username, first_name, last_name, email, profile_image FROM users WHERE user_id = ?");
        $stmt->execute([$admin_id]);
        $user = $stmt->fetch();
        
        if ($user) {
            $admin_data = [
                'user_id' => $user['user_id'],
                'username' => $user['username'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'email' => $user['email'],
                'profile_image' => $user['profile_image']
            ];
            
            // Update session data
            $_SESSION['admin_id'] = $user['user_id'];
            $_SESSION['admin_first_name'] = $user['first_name'];
            $_SESSION['admin_last_name'] = $user['last_name'];
            $_SESSION['admin_email'] = $user['email'];
            $_SESSION['admin_profile_image'] = $user['profile_image'];
            $_SESSION['admin_full_name'] = $user['first_name'] . ' ' . $user['last_name'];
        }
    } catch (PDOException $e) {
        error_log("Profile data fetch error: " . $e->getMessage());
    }
}
?>

<style>
    .main-content {
        flex: 1;
        padding: 30px;
        background: linear-gradient(135deg, #1a1a1a 0%, #0c0c0c 100%);
        min-height: 100vh;
        overflow-y: auto;
    }

    .profile-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .page-header {
        margin-bottom: 30px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 8px;
    }

    .page-subtitle {
        color: #888;
        font-size: 16px;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 30px;
        margin-bottom: 30px;
    }

    .profile-card {
        background: rgba(15, 15, 15, 0.95);
        border: 1px solid rgba(229, 9, 20, 0.3);
        border-radius: 12px;
        padding: 24px;
        backdrop-filter: blur(10px);
    }

    .card-title {
        font-size: 20px;
        font-weight: 600;
        color: #fff;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-title i {
        color: #E50914;
    }

    /* Profile Image Section */
    .profile-image-section {
        text-align: center;
    }

    .current-image-container {
        margin-bottom: 20px;
    }

    .current-profile-image {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #E50914;
        box-shadow: 0 0 20px rgba(229, 9, 20, 0.3);
        margin: 0 auto;
        display: block;
    }

    .profile-image-placeholder-large {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: linear-gradient(135deg, #E50914 0%, #B80710 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 4px solid #E50914;
        box-shadow: 0 0 20px rgba(229, 9, 20, 0.3);
        color: white;
        font-size: 60px;
        margin: 0 auto;
    }

    .upload-section {
        margin-top: 20px;
    }

    .file-input-wrapper {
        position: relative;
        display: inline-block;
        margin-bottom: 15px;
    }

    .file-input {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .file-input-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: rgba(229, 9, 20, 0.1);
        border: 1px solid rgba(229, 9, 20, 0.3);
        border-radius: 8px;
        color: #E50914;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .file-input-label:hover {
        background: rgba(229, 9, 20, 0.2);
        border-color: #E50914;
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #E50914;
        margin-bottom: 8px;
    }

    .form-input, .form-textarea {
        width: 100%;
        padding: 12px 16px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(229, 9, 20, 0.3);
        border-radius: 8px;
        color: #fff;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-input:focus, .form-textarea:focus {
        outline: none;
        border-color: #E50914;
        box-shadow: 0 0 10px rgba(229, 9, 20, 0.2);
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #E50914 0%, #B80710 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(229, 9, 20, 0.4);
    }

    .btn-secondary {
        background: rgba(229, 9, 20, 0.1);
        border: 1px solid rgba(229, 9, 20, 0.3);
        color: #E50914;
    }

    .btn-secondary:hover {
        background: rgba(229, 9, 20, 0.2);
        border-color: #E50914;
    }

    /* Message Styles */
    .message {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        font-weight: 500;
    }

    .message-success {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #22c55e;
    }

    .message-error {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .main-content {
            padding: 20px;
        }

        .profile-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .current-profile-image, .profile-image-placeholder-large {
            width: 120px;
            height: 120px;
        }

        .profile-image-placeholder-large {
            font-size: 48px;
        }
    }
</style>

<div class="main-content">
    <div class="profile-container">
        <div class="page-header">
            <h1 class="page-title">Admin Profile</h1>
            <p class="page-subtitle">Manage your profile information and settings</p>
        </div>

        <!-- Success/Error Messages -->
        <?php if (!empty($success_message)): ?>
            <div class="message message-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($success_message) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="message message-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <div class="profile-grid">
            <!-- Profile Image Section -->
            <div class="profile-card">
                <h2 class="card-title">
                    <i class="fas fa-camera"></i>
                    Profile Image
                </h2>
                <div class="profile-image-section">
                    <div class="current-image-container">
                        <?php if (!empty($admin_data['profile_image']) && file_exists('../../uploads/profile_images/' . $admin_data['profile_image'])): ?>
                            <img src="../../uploads/profile_images/<?= htmlspecialchars($admin_data['profile_image']) ?>" alt="Profile Image" class="current-profile-image" id="currentImage">
                        <?php else: ?>
                            <div class="profile-image-placeholder-large" id="imagePlaceholder">
                                <i class="fas fa-user"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="upload-section">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="upload_image">
                            <div class="file-input-wrapper">
                                <input type="file" name="profile_image" id="profileImageInput" class="file-input" accept="image/*" required>
                                <label for="profileImageInput" class="file-input-label">
                                    <i class="fas fa-upload"></i>
                                    Choose Image
                                </label>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Update Image
                            </button>
                        </form>
                        <p style="font-size: 12px; color: #888; margin-top: 10px;">
                            Supported formats: JPG, JPEG, PNG, GIF (Max 1MB)
                        </p>
                    </div>
                </div>
            </div>

            <!-- Profile Information -->
            <div class="profile-card">
                <h2 class="card-title">
                    <i class="fas fa-user-edit"></i>
                    Profile Information
                </h2>
                <form method="POST">
                    <input type="hidden" name="action" value="update_profile">
                    
                    <div class="form-group">
                        <label class="form-label">Username (Read Only)</label>
                        <input type="text" name="username" class="form-input" 
                        value="<?php echo htmlspecialchars($admin_data['username']); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label class="form-label">First Name *</label>
                        <input type="text" name="first_name" class="form-input" value="<?php echo htmlspecialchars($admin_data['first_name']); ?>" required>
                    </div>

                     <div class="form-group">
                        <label class="form-label">Last Name *</label>
                        <input type="text" name="last_name" class="form-input" value="<?php echo htmlspecialchars($admin_data['last_name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" class="form-input" value="<?php echo htmlspecialchars($admin_data['email']); ?>" required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Update Profile
                    </button>
                </form>
            </div>
        </div>

        <!-- Password Change Section -->
        <div class="profile-card">
            <h2 class="card-title">
                <i class="fas fa-lock"></i>
                Change Password
            </h2>
            <form method="POST">
                <input type="hidden" name="action" value="update_password">
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <div class="form-group">
                        <label class="form-label">Current Password *</label>
                        <input type="password" name="current_password" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">New Password *</label>
                        <input type="password" name="new_password" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm New Password *</label>
                        <input type="password" name="confirm_password" class="form-input" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-key"></i>
                    Change Password
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Preview image before upload
document.getElementById('profileImageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const currentImage = document.getElementById('currentImage');
            const placeholder = document.getElementById('imagePlaceholder');
            
            if (currentImage) {
                currentImage.src = e.target.result;
            } else if (placeholder) {
                // Replace placeholder with image
                placeholder.outerHTML = `<img src="${e.target.result}" alt="Profile" class="current-profile-image" id="currentImage">`;
            }
        };
        reader.readAsDataURL(file);
    }
});
</script>