<?php
// header.php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
$host = '127.0.0.1';
$dbname = 'movielab';
$username = 'root';
$password = '';

try {
    $conn = new mysqli($host, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }
} catch(Exception $e) {
    die("Database connection error: " . $e->getMessage());
}

// Check if user is logged in and fetch user data
$currentUser = null;
$isLoggedIn = false;

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("SELECT user_id, username, email, first_name, last_name, profile_image, user_type, is_active FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $currentUser = $result->fetch_assoc();
        $isLoggedIn = true;
        
        // Check if user is active
        if ($currentUser['is_active'] == 0) {
            // User is inactive, log them out
            session_unset();
            session_destroy();
            $currentUser = null;
            $isLoggedIn = false;
        }
    }
    $stmt->close();
}

// Check if user has active PRO subscription
$hasProSubscription = false;
$currentSubscription = null;
if ($isLoggedIn) {
    // First check if subscription_plans table exists
    $tableCheck = $conn->query("SHOW TABLES LIKE 'subscription_plans'");
    $tableCheck2 = $conn->query("SHOW TABLES LIKE 'user_subscriptions'");
    
    if ($tableCheck->num_rows > 0 && $tableCheck2->num_rows > 0) {
        // Check subscription status
        $stmt = $conn->prepare("
            SELECT us.*, sp.name as plan_name, sp.price, sp.billing_cycle, sp.features
            FROM user_subscriptions us
            JOIN subscription_plans sp ON us.plan_id = sp.plan_id
            WHERE us.user_id = ? AND us.status = 'active'
            ORDER BY us.created_at DESC LIMIT 1
        ");
        
        if ($stmt) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $hasProSubscription = true;
                $currentSubscription = $result->fetch_assoc();
            }
            $stmt->close();
        }
    } else {
        // Tables don't exist, create them
        createSubscriptionTables($conn);
    }
}

// Close connection
$conn->close();

// Function to create subscription tables if they don't exist
function createSubscriptionTables($conn) {
    // Create subscription_plans table
    $sql = "CREATE TABLE IF NOT EXISTS subscription_plans (
        plan_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        billing_cycle ENUM('week', 'month', 'year') NOT NULL,
        features TEXT,
        is_active BOOLEAN DEFAULT true,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql) === TRUE) {
        // Insert default plans if table is empty
        $check = $conn->query("SELECT COUNT(*) as count FROM subscription_plans");
        $row = $check->fetch_assoc();
        
        if ($row['count'] == 0) {
            $defaultPlans = [
                "('Weekly Pass', 'Perfect for a short binge-watching session', 1.99, 'week', 'HD Streaming,1 simultaneous screen', true)",
                "('Monthly Pro', 'Flexible, no long-term contract', 5.99, 'month', 'HD Streaming,2 simultaneous screens,Offline Downloads', true)",
                "('Yearly Pro', 'Limited time offer for long-term commitment', 49.99, 'year', '4K Ultra HD Streaming,5 simultaneous screens,Offline Downloads,Priority Support', true)"
            ];
            
            foreach ($defaultPlans as $plan) {
                $conn->query("INSERT INTO subscription_plans (name, description, price, billing_cycle, features, is_active) VALUES $plan");
            }
        }
    }
    
    // Create user_subscriptions table
    $sql2 = "CREATE TABLE IF NOT EXISTS user_subscriptions (
        subscription_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        plan_id INT NOT NULL,
        status ENUM('active', 'canceled', 'expired', 'pending') DEFAULT 'pending',
        current_period_start DATETIME,
        current_period_end DATETIME,
        stripe_subscription_id VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
        FOREIGN KEY (plan_id) REFERENCES subscription_plans(plan_id) ON DELETE CASCADE
    )";
    
    $conn->query($sql2);
    
    // Create index for better performance
    $conn->query("CREATE INDEX IF NOT EXISTS idx_user_status ON user_subscriptions(user_id, status)");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Lab</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* 'Inter' font for modern web apps */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0d0d0d;
            overflow-x: hidden;
        }
        
        /* Custom glow effect for red text/elements */
        .text-glow-red {
            text-shadow: 0 0 10px rgba(229, 9, 20, 0.9),
                         0 0 25px rgba(229, 9, 20, 0.7),
                         0 0 40px rgba(229, 9, 20, 0.4);
        }

        /* Navigation link underline effect */
        .nav-link-underline {
            position: relative;
            color: #FFFFFF;
            transition: color 0.3s ease;
        }

        .nav-link-underline::after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #E50914;
            transition: width 0.3s ease, box-shadow 0.3s ease;
            border-radius: 9999px;
            box-shadow: none;
        }

        .nav-link-underline:hover::after,
        .nav-link-underline:focus::after {
            width: 100%;
            box-shadow: 0 0 10px rgba(229, 9, 20, 0.8), 0 0 20px rgba(229, 9, 20, 0.5);
        }

        .nav-dropdown-btn:hover .nav-link-underline::after,
        .nav-dropdown-btn:focus .nav-link-underline::after {
            width: 100%;
            box-shadow: 0 0 10px rgba(229, 9, 20, 0.8), 0 0 20px rgba(229, 9, 20, 0.5);
        }
        
        /* PRO Button Gradient */
        .pro-button-gradient {
            background-image: linear-gradient(to right, #c60505ff, #d40404ff);
            transition: all 0.3s ease;
        }
        
        .pro-button-gradient:hover {
            box-shadow: 0 0 15px rgba(217, 30, 5, 0.8), 0 0 30px rgba(250, 71, 27, 0.5);
            transform: scale(1.05);
        }
        
        /* Input fields */
        .input-field {
            width: 100%;
            margin-top: 6px;
            margin-bottom: 14px;
            background-color: #0d0d0d;
            color: white;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #444;
            outline: none;
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            border-color: #E50914;
            box-shadow: 0 0 6px #E50914;
            transform: translateY(-1px);
        }

        .input-field.error {
            border-color: #ef4444;
            box-shadow: 0 0 6px rgba(239, 68, 68, 0.5);
            animation: shake 0.5s ease-in-out;
        }

        .input-field.success {
            border-color: #10b981;
            box-shadow: 0 0 6px rgba(16, 185, 129, 0.5);
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        /* reCAPTCHA box */
        .recaptcha-box {
            background: #f9f9f9;
            border: 1px solid #d3d3d3;
            padding: 14px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* Loading animation */
        .btn-loading {
            position: relative;
            pointer-events: none;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            margin: auto;
            border: 2px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Modal animations */
        .modal-enter {
            animation: modalEnter 0.3s ease-out;
        }

        @keyframes modalEnter {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        /* Validation messages */
        .validation-message {
            font-size: 0.875rem;
            margin-top: 0.25rem;
            padding: 0.5rem;
            border-radius: 0.375rem;
            display: none;
        }

        .validation-message.show {
            display: block;
            animation: slideDown 0.3s ease-out;
        }

        .validation-message.error {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .validation-message.success {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Profile dropdown */
        .profile-dropdown {
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: 0.5rem;
            width: 12rem;
            background-color: #1f2937;
            border: 1px solid #E50914;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            z-index: 50;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .profile-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        /* Profile image */
        .profile-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #E50914;
        }

        /* Image preview */
        .image-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #E50914;
            margin: 10px auto;
            display: none;
        }

        .image-preview.show {
            display: block;
        }

        /* PRO Badge */
        .pro-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* Subscription card */
        .subscription-card {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            border: 1px solid #E50914;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 16px;
        }

        /* Payment form styling */
        .card-input {
            background: #1a1a1a;
            border: 1px solid #444;
            color: white;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
        }

        .card-input:focus {
            border-color: #E50914;
            box-shadow: 0 0 0 2px rgba(229, 9, 20, 0.2);
        }

        @media (max-width: 640px) {
            .profile-image {
                width: 32px;
                height: 32px;
            }
            .pro-badge {
                font-size: 0.6rem;
                padding: 1px 6px;
            }
        }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-red': '#E50914',
                        'dark-bg': '#141414',
                        'dark-card': '#222222',
                        'theme-orange': '#FA471B',
                        'pro-purple': '#764ba2',
                        'pro-gradient-start': '#667eea',
                        'pro-gradient-end': '#764ba2',
                    }
                }
            }
        }

        // PHP variables passed to JavaScript
        const currentUser = <?php echo json_encode($currentUser); ?>;
        const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
        const hasProSubscription = <?php echo $hasProSubscription ? 'true' : 'false'; ?>;
        const currentSubscription = <?php echo json_encode($currentSubscription); ?>;

        // Mobile menu toggle
        function toggleMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // Dropdown functions
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            document.querySelectorAll('div.absolute[id$="-dropdown"]').forEach(d => {
                if (d.id !== id) d.classList.add('hidden');
            });
            dropdown.classList.toggle('hidden');
        }

        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profile-dropdown');
            const isVisible = dropdown.classList.contains('show');
            
            // Close all other dropdowns
            document.querySelectorAll('div.absolute[id$="-dropdown"]').forEach(d => {
                d.classList.add('hidden');
            });
            
            // Toggle profile dropdown
            if (isVisible) {
                dropdown.classList.remove('show');
            } else {
                dropdown.classList.add('show');
            }
        }

        // Close dropdowns on outside click
        window.onclick = function(event) {
            const profileButton = document.querySelector('button[onclick="toggleProfileDropdown()"]');
            const profileDropdown = document.getElementById('profile-dropdown');
            
            // Close profile dropdown if clicking outside
            if (profileDropdown && profileButton && !profileButton.contains(event.target) && !profileDropdown.contains(event.target)) {
                profileDropdown.classList.remove('show');
            }
            
            // Close other dropdowns
            if (!event.target.closest('.nav-dropdown-btn')) {
                document.querySelectorAll('div.absolute[id$="-dropdown"]').forEach(d => {
                    d.classList.add('hidden');
                });
            }
        }
        
        // PRO Modal
        function openProModal() {
            document.getElementById('pro-modal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            
            // Update subscription status in modal
            updateProModalStatus();
        }

        function closeProModal(event) {
            const modal = document.getElementById('pro-modal');
            if (!event || event.target.id === 'pro-modal') {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        // Search bar
        function toggleSearchBar() {
            const searchBar = document.getElementById('search-bar');
            const searchInput = document.getElementById('search-input');
            
            searchBar.classList.toggle('hidden');
            searchBar.classList.toggle('opacity-0');
            searchBar.classList.toggle('opacity-100');
            searchBar.classList.toggle('scale-95');
            searchBar.classList.toggle('scale-100');
            
            if (!searchBar.classList.contains('hidden')) {
                setTimeout(() => searchInput.focus(), 100);
            }
        }

        // Handle search
        function handleSearch(event) {
            event.preventDefault();
            const searchTerm = document.getElementById('search-input').value.trim();
            if (searchTerm) {
                window.location.href = `search.php?q=${encodeURIComponent(searchTerm)}`;
            }
        }

        // Close search bar on outside click
        document.addEventListener('click', function(event) {
            const searchBar = document.getElementById('search-bar');
            const searchButton = document.querySelector('button[onclick="toggleSearchBar()"]');
            
            if (searchBar && !searchBar.classList.contains('hidden') && 
                !searchBar.contains(event.target) && 
                !searchButton.contains(event.target)) {
                searchBar.classList.add('hidden');
                searchBar.classList.remove('opacity-100', 'scale-100');
                searchBar.classList.add('opacity-0', 'scale-95');
            }
        });

        // Logout function
        async function logout() {
            try {
                const response = await fetch('library/logoutBackend.php', {
                    method: 'POST'
                });
                const data = await response.json();
                
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Logged Out',
                        text: 'You have been successfully logged out!',
                        confirmButtonColor: '#E50914'
                    }).then(() => {
                        window.location.reload();
                    });
                }
            } catch (error) {
                console.error('Logout error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Logout Failed',
                    text: 'Unable to logout. Please try again.',
                    confirmButtonColor: '#E50914'
                });
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Update navbar based on login status
            updateNavbar();
            
            // Add event listener for search input
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        handleSearch(e);
                    }
                });
            }
            
            // Initialize PRO subscription features
            initProSubscription();
            
            // Close dropdowns when clicking escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const profileDropdown = document.getElementById('profile-dropdown');
                    if (profileDropdown) {
                        profileDropdown.classList.remove('show');
                    }
                    document.querySelectorAll('div.absolute[id$="-dropdown"]').forEach(d => {
                        d.classList.add('hidden');
                    });
                }
            });
        });

        // Update navbar based on login status
        function updateNavbar() {
            const signInBtn = document.getElementById('sign-in-btn');
            const mobileSignInBtn = document.getElementById('mobile-sign-in-btn');
            const userProfileSection = document.getElementById('user-profile-section');
            const adminLink = document.getElementById('admin-link');
            
            if (isLoggedIn && currentUser) {
                // User is logged in
                if (signInBtn) signInBtn.style.display = 'none';
                if (mobileSignInBtn) mobileSignInBtn.style.display = 'none';
                if (userProfileSection) {
                    userProfileSection.style.display = 'flex';
                    updateUserProfileDisplay();
                }
                
                // Show admin link if user is admin
                if (currentUser.user_type === 'admin' && adminLink) {
                    adminLink.style.display = 'inline-flex';
                }
                
                // Update PRO button text if user has subscription
                updateProButton();
            } else {
                // User is not logged in
                if (signInBtn) signInBtn.style.display = 'inline-flex';
                if (mobileSignInBtn) mobileSignInBtn.style.display = 'block';
                if (userProfileSection) userProfileSection.style.display = 'none';
                if (adminLink) adminLink.style.display = 'none';
            }
        }

        // Update user profile display
        function updateUserProfileDisplay() {
            const profileImg = document.getElementById('user-profile-img');
            const userName = document.getElementById('user-name-display');
            const proBadge = document.getElementById('pro-badge');
            
            if (currentUser) {
                if (profileImg) {
                    if (currentUser.profile_image) {
                        profileImg.src = 'uploads/profile_images/' + currentUser.profile_image;
                    } else {
                        // Generate avatar with initials
                        const initials = (currentUser.first_name?.charAt(0) || '') + (currentUser.last_name?.charAt(0) || '');
                        profileImg.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(initials)}&background=E50914&color=fff&size=40`;
                    }
                }
                if (userName) {
                    userName.textContent = currentUser.first_name || currentUser.username;
                }
                
                // Add PRO badge if user has subscription
                if (hasProSubscription && proBadge) {
                    proBadge.style.display = 'inline-flex';
                }
            }
        }

        // Update PRO button based on subscription status
        function updateProButton() {
            const proButtons = document.querySelectorAll('.pro-button');
            
            proButtons.forEach(button => {
                if (hasProSubscription) {
                    button.innerHTML = '<i class="fas fa-crown mr-2"></i>PRO ACTIVE';
                    button.classList.remove('pro-button-gradient');
                    button.classList.add('bg-green-600', 'hover:bg-green-700');
                    button.onclick = function() {
                        showSubscriptionDetails();
                    };
                } else {
                    button.innerHTML = '<i class="fas fa-crown mr-2"></i>GO PRO';
                    button.classList.add('pro-button-gradient');
                    button.classList.remove('bg-green-600', 'hover:bg-green-700');
                    button.onclick = function() {
                        openProModal();
                    };
                }
            });
        }

        // Initialize PRO subscription features
        function initProSubscription() {
            // Format card number input
            const cardNumberInput = document.getElementById('card_number');
            if (cardNumberInput) {
                cardNumberInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
                    let formatted = value.replace(/(\d{4})/g, '$1 ').trim();
                    e.target.value = formatted.substring(0, 19);
                });
            }

            // Format expiry date input
            const expiryInput = document.getElementById('expiry_date');
            if (expiryInput) {
                expiryInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/[^0-9]/g, '');
                    if (value.length >= 2) {
                        value = value.substring(0, 2) + '/' + value.substring(2, 4);
                    }
                    e.target.value = value.substring(0, 5);
                });
            }
        }

        // Update PRO modal with subscription status
        function updateProModalStatus() {
            const subscriptionStatus = document.getElementById('subscription-status');
            const planDetails = document.getElementById('plan-details');
            const paymentForm = document.getElementById('payment-form-section');
            const currentPlanSection = document.getElementById('current-plan-section');
            
            if (hasProSubscription && currentSubscription) {
                // User has active subscription
                if (subscriptionStatus) {
                    subscriptionStatus.innerHTML = `
                        <div class="subscription-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-white">${currentSubscription.plan_name}</h3>
                                    <p class="text-green-400 text-sm">Active Subscription</p>
                                </div>
                                <span class="pro-badge"><i class="fas fa-crown"></i> PRO</span>
                            </div>
                            <div class="mt-4 text-sm text-gray-300">
                                <p>Next Billing: ${new Date(currentSubscription.current_period_end).toLocaleDateString()}</p>
                                <p>Price: $${currentSubscription.price} per ${currentSubscription.billing_cycle}</p>
                            </div>
                            <button onclick="cancelSubscription()" class="mt-4 w-full bg-gray-700 text-white py-2 rounded-lg hover:bg-gray-600">
                                Cancel Subscription
                            </button>
                        </div>
                    `;
                }
                
                if (currentPlanSection) currentPlanSection.classList.remove('hidden');
                if (paymentForm) paymentForm.classList.add('hidden');
            } else {
                // User doesn't have subscription
                if (subscriptionStatus) {
                    subscriptionStatus.innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-crown text-4xl text-gray-500 mb-4"></i>
                            <h3 class="text-xl font-bold text-white mb-2">No Active Subscription</h3>
                            <p class="text-gray-400">Upgrade to PRO to unlock premium features</p>
                        </div>
                    `;
                }
                
                if (currentPlanSection) currentPlanSection.classList.add('hidden');
                if (paymentForm) paymentForm.classList.remove('hidden');
            }
        }

        // Select subscription plan
        function selectPlan(planId, planName, planPrice, billingCycle) {
            const selectedPlanElement = document.getElementById('selected-plan');
            const planSummaryElement = document.getElementById('plan-summary');
            const cardForm = document.getElementById('card-payment-form');
            
            // Update selected plan display
            if (selectedPlanElement) {
                selectedPlanElement.textContent = `${planName} - $${planPrice} per ${billingCycle}`;
            }
            
            // Update plan summary
            if (planSummaryElement) {
                planSummaryElement.innerHTML = `
                    <div class="flex justify-between mb-1">
                        <span>Plan:</span>
                        <span>${planName}</span>
                    </div>
                    <div class="flex justify-between mb-1">
                        <span>Billing Cycle:</span>
                        <span>${billingCycle}</span>
                    </div>
                    <div class="flex justify-between font-bold mt-2 pt-2 border-t border-gray-700">
                        <span>Total:</span>
                        <span>$${planPrice}</span>
                    </div>
                `;
            }
            
            // Store selected plan in form
            document.getElementById('plan_id').value = planId;
            
            // Show payment form
            if (cardForm) {
                cardForm.classList.remove('hidden');
            }
            
            // Scroll to payment form
            document.getElementById('payment-form-section').scrollIntoView({ behavior: 'smooth' });
        }

        // Process payment
        async function processPayment(event) {
            event.preventDefault();
            
            const submitBtn = document.getElementById('payment-submit-btn');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Processing...';
            
            try {
                const formData = new FormData(document.getElementById('payment-form'));
                
                // Basic validation
                const cardNumber = formData.get('card_number').replace(/\s+/g, '');
                const expiry = formData.get('expiry_date');
                const cvc = formData.get('cvc');
                
                if (cardNumber.length !== 16 || !/^\d+$/.test(cardNumber)) {
                    throw new Error('Invalid card number');
                }
                
                if (!expiry.match(/^\d{2}\/\d{2}$/)) {
                    throw new Error('Invalid expiry date (MM/YY)');
                }
                
                if (!cvc.match(/^\d{3,4}$/)) {
                    throw new Error('Invalid CVC');
                }
                
                const response = await fetch('subscription_api.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Subscription Activated!',
                        html: `
                            <div class="text-center">
                                <i class="fas fa-crown text-4xl text-yellow-400 mb-4"></i>
                                <h3 class="text-xl font-bold text-white mb-2">Welcome to PRO!</h3>
                                <p class="text-gray-300">Your subscription has been activated successfully.</p>
                                <div class="mt-4 p-3 bg-dark-card rounded-lg">
                                    <p class="text-sm text-gray-400">You can now enjoy:</p>
                                    <ul class="text-sm text-gray-300 mt-2 space-y-1">
                                        <li><i class="fas fa-check text-green-400 mr-2"></i>4K Ultra HD Streaming</li>
                                        <li><i class="fas fa-check text-green-400 mr-2"></i>Multiple Screens</li>
                                        <li><i class="fas fa-check text-green-400 mr-2"></i>Offline Downloads</li>
                                        <li><i class="fas fa-check text-green-400 mr-2"></i>Priority Support</li>
                                    </ul>
                                </div>
                            </div>
                        `,
                        confirmButtonColor: '#E50914',
                        confirmButtonText: 'Start Watching',
                        showClass: {
                            popup: 'animate__animated animate__fadeInUp'
                        }
                    }).then(() => {
                        closeProModal();
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.error || 'Payment failed');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Payment Failed',
                    text: error.message,
                    confirmButtonColor: '#E50914'
                });
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        }

        // Cancel subscription
        async function cancelSubscription() {
            const { value: confirm } = await Swal.fire({
                title: 'Cancel Subscription?',
                html: `
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle text-3xl text-yellow-400 mb-4"></i>
                        <p class="text-gray-300">Are you sure you want to cancel your PRO subscription?</p>
                        <div class="mt-4 p-3 bg-dark-card rounded-lg">
                            <p class="text-sm text-red-400">You will lose access to PRO features immediately.</p>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonColor: '#E50914',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Cancel',
                cancelButtonText: 'Keep Subscription'
            });
            
            if (!confirm) return;
            
            try {
                const response = await fetch('subscription_api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=cancel_subscription'
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Subscription Cancelled',
                        text: 'Your PRO subscription has been cancelled.',
                        confirmButtonColor: '#E50914'
                    }).then(() => {
                        closeProModal();
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.error || 'Failed to cancel subscription');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Cancellation Failed',
                    text: error.message,
                    confirmButtonColor: '#E50914'
                });
            }
        }

        // Show subscription details
        function showSubscriptionDetails() {
            if (hasProSubscription) {
                openProModal();
            } else {
                openProModal();
            }
        }

        // Mock social login
        function mockSocialLogin(provider) {
            Swal.fire({
                icon: 'info',
                title: `${provider} Login`,
                text: `This would normally redirect to ${provider} for authentication.`,
                confirmButtonColor: '#E50914'
            });
        }
    </script>
</head>
<body class="min-h-screen">

    <!-- NAVIGATION BAR START -->
    <nav class="bg-dark-bg shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">

                <!-- Logo and Main Links (Left Side) -->
                <div class="flex-shrink-0 flex items-center">
                    <div class="top-8 left-8 items-center gap-2 ms-8">
                        <a href="index.php" class="text-glow-red">
                            <i class="fas fa-film text-red-600 text-3xl"></i>
                        </a>
                        <a href="index.php" class="text-3xl font-extrabold text-primary-red tracking-wider cursor-pointer text-glow-red mr-6">
                            Movie Lab 
                        </a>
                    </div>
                    
                    <!-- Primary Desktop Links -->
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-4 lg:space-x-6 items-center">
                        <!-- Movies Dropdown -->
                        <div class="relative">
                            <button onclick="toggleDropdown('movies-dropdown')" class="nav-dropdown-btn inline-flex items-center px-1 pt-1 text-sm font-medium text-white transition duration-300 focus:outline-none">
                                <span class="nav-link-underline">Movies</span>
                                <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div id="movies-dropdown" class="absolute hidden mt-3 w-48 rounded-lg shadow-2xl bg-dark-card ring-1 ring-primary-red ring-opacity-20 z-20">
                                <div class="py-1" role="menu" aria-orientation="vertical">
                                    <a href="movies.php?filter=now_playing" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">Now Playing</a>
                                    <a href="movies.php?filter=popular" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">Popular</a>
                                    <a href="movies.php?filter=top_rated" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">Top Rated</a>
                                </div>
                            </div>
                        </div>

                        <!-- Songs Dropdown -->
                        <div class="relative">
                            <button onclick="toggleDropdown('songs-dropdown')" class="nav-dropdown-btn inline-flex items-center px-1 pt-1 text-sm font-medium text-white transition duration-300 focus:outline-none">
                                <span class="nav-link-underline">Songs</span>
                                <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div id="songs-dropdown" class="absolute hidden mt-3 w-48 rounded-lg shadow-2xl bg-dark-card ring-1 ring-primary-red ring-opacity-20 z-20">
                                <div class="py-1" role="menu" aria-orientation="vertical">
                                    <a href="songs.php?filter=new" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">New Releases</a>
                                    <a href="songs.php?filter=top" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">Top Charts</a>
                                    <a href="songs.php?filter=playlists" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">Playlists</a>
                                </div>
                            </div>
                        </div>

                        <!-- TV Series Dropdown -->
                        <div class="relative">
                            <button onclick="toggleDropdown('tv-series-dropdown')" class="nav-dropdown-btn inline-flex items-center px-1 pt-1 text-sm font-medium text-white transition duration-300 focus:outline-none">
                                <span class="nav-link-underline">TV Series</span>
                                <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div id="tv-series-dropdown" class="absolute hidden mt-3 w-48 rounded-lg shadow-2xl bg-dark-card ring-1 ring-primary-red ring-opacity-20 z-20">
                                <div class="py-1" role="menu" aria-orientation="vertical">
                                    <a href="tv.php?filter=trending" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">Trending</a>
                                    <a href="tv.php?filter=on_air" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">On Air</a>
                                    <a href="tv.php?filter=originals" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">Originals</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Genres, Years, Languages, Search, Notifications, PRO, Sign In -->
                <div class="flex items-center">
                    <!-- Genres, Years, and Languages -->
                    <div class="hidden sm:flex items-center space-x-0 lg:space-x-0">
                        <!-- Genres Dropdown -->
                        <div class="relative">
                            <button onclick="toggleDropdown('genres-dropdown')" class="nav-dropdown-btn inline-flex items-center px-3 pt-1 text-sm font-medium text-white transition duration-300 focus:outline-none">
                                <span class="nav-link-underline">Genres</span>
                                <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div id="genres-dropdown" class="absolute hidden mt-3 w-72 rounded-lg shadow-2xl bg-dark-card ring-1 ring-primary-red ring-opacity-20 z-20 -right-24">
                                <div class="p-2 grid grid-cols-2 gap-x-4 gap-y-1">
                                    <a href="movies.php?genre=action" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md">Action</a>
                                    <a href="movies.php?genre=horror" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md">Horror</a>
                                    <a href="movies.php?genre=comedy" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md">Comedy</a>
                                    <a href="movies.php?genre=sci-fi" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md">Sci-Fi</a>
                                    <a href="movies.php?genre=drama" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md">Drama</a>
                                    <a href="movies.php?genre=romance" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md">Romance</a>
                                    <a href="movies.php?genre=thriller" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md">Thriller</a>
                                    <a href="movies.php?genre=documentary" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md">Documentary</a>
                                    <a href="movies.php?genre=animation" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md">Animation</a>
                                    <a href="movies.php?genre=fantasy" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md">Fantasy</a>
                                    <a href="movies.php?genre=crime" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md">Crime</a>
                                    <a href="movies.php?genre=mystery" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md">Mystery</a>
                                </div>
                            </div>
                        </div>

                        <!-- Years Dropdown -->
                        <div class="relative">
                            <button onclick="toggleDropdown('years-dropdown')" class="nav-dropdown-btn inline-flex items-center px-3 pt-1 text-sm font-medium text-white transition duration-300 focus:outline-none">
                                <span class="nav-link-underline">Years</span>
                                <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div id="years-dropdown" class="absolute hidden mt-3 w-32 rounded-lg shadow-2xl bg-dark-card ring-1 ring-primary-red ring-opacity-20 z-20">
                                <div class="py-1 grid grid-cols-2 gap-x-1 gap-y-1">
                                    <a href="movies.php?year=2025" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">2025</a>
                                    <a href="movies.php?year=2024" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">2024</a>
                                    <a href="movies.php?year=2023" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">2023</a>
                                    <a href="movies.php?year=2022" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">2022</a>
                                    <a href="movies.php?year=2021" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">2021</a>
                                    <a href="movies.php?year=2020" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">2020</a>
                                    <a href="movies.php?year=2019" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">2019</a>
                                    <a href="movies.php?year=2018" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">2018</a>
                                    <a href="movies.php?year=older" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">Older</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Languages Dropdown -->
                        <div class="relative">
                            <button onclick="toggleDropdown('languages-dropdown')" class="nav-dropdown-btn inline-flex items-center px-3 pt-1 text-sm font-medium text-white transition duration-300 focus:outline-none">
                                <span class="nav-link-underline">Languages</span>
                                <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div id="languages-dropdown" class="absolute hidden mt-3 w-72 rounded-lg shadow-2xl bg-dark-card ring-1 ring-primary-red ring-opacity-20 z-20 -right-16">
                                <div class="p-2 grid grid-cols-2 gap-x-4 gap-y-1">
                                    <a href="movies.php?language=all" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md">All</a>
                                    <a href="movies.php?language=english" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md">English</a>
                                    <a href="movies.php?language=hindi" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md">Hindi</a>
                                    <a href="movies.php?language=korean" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md">Korean</a>
                                    <a href="movies.php?language=french" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md">French</a>
                                    <a href="movies.php?language=sinhala" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md">Sinhala</a>
                                    <a href="movies.php?language=tamil" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md">Tamil</a>
                                    <a href="movies.php?language=malayalam" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md">Malayalam</a>
                                    <a href="movies.php?language=kannada" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md">Kannada</a>
                                    <a href="movies.php?language=italian" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md">Italian</a>
                                    <a href="movies.php?language=telugu" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md">Telugu</a>
                                    <a href="movies.php?language=russian" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md">Russian</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Search, Notifications, PRO, and Sign In -->
                    <div class="flex items-center space-x-4 lg:space-x-6 ml-4">
                        <!-- Admin Link (only for admin users) -->
                        <?php if ($isLoggedIn && $currentUser && $currentUser['user_type'] === 'admin'): ?>
                            <a id="admin-link" href="admin/user_management.php" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white rounded-md transition duration-300 hover:bg-primary-red hover:shadow-lg hover:shadow-primary-red/50 border-2 border-primary-red mr-2">
                                <i class="fas fa-cog mr-1"></i> Admin
                            </a>
                        <?php endif; ?>
                        
                        <!-- Search Button -->
                        <button type="button" onclick="toggleSearchBar()" class="p-2 text-gray-400 hover:text-white transition duration-300 focus:outline-none rounded-full hover:bg-dark-card hidden sm:block">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                        
                        <!-- Bell/Notifications Button -->
                        <button type="button" class="p-2 text-gray-400 hover:text-primary-red transition duration-300 focus:outline-none rounded-full hover:bg-dark-card hidden sm:block">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </button>
                        
                        <!-- Sign In Link (for guests) -->
                        <?php if (!$isLoggedIn): ?>
                            <button id="sign-in-btn" onclick="openLoginModal()" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white rounded-md transition duration-300 hover:bg-red-600 hover:shadow-lg hover:shadow-primary-red/50 border-2 border-red-600">
                                Sign In
                            </button>
                        <?php endif; ?>

                        <!-- User Profile Section (for logged in users) -->
                        <div id="user-profile-section" class="relative hidden items-center space-x-3">
                            <?php if ($hasProSubscription): ?>
                                <span id="pro-badge" class="pro-badge">
                                    <i class="fas fa-crown"></i> PRO
                                </span>
                            <?php endif; ?>
                            <button onclick="toggleProfileDropdown()" class="flex items-center space-x-2 text-white hover:text-primary-red transition duration-300 focus:outline-none relative">
                                <?php if ($isLoggedIn && $currentUser): ?>
                                    <?php if ($currentUser['profile_image']): ?>
                                        <img id="user-profile-img" src="uploads/profile_images/<?php echo htmlspecialchars($currentUser['profile_image']); ?>" alt="Profile" class="profile-image">
                                    <?php else: ?>
                                        <img id="user-profile-img" src="https://ui-avatars.com/api/?name=<?php echo urlencode(($currentUser['first_name'] ?? '') . ' ' . ($currentUser['last_name'] ?? '')); ?>&background=E50914&color=fff&size=40" alt="Profile" class="profile-image">
                                    <?php endif; ?>
                                    <span id="user-name-display" class="hidden sm:inline text-sm font-medium"><?php echo htmlspecialchars($currentUser['first_name'] ?? $currentUser['username']); ?></span>
                                <?php endif; ?>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <!-- Profile Dropdown -->
                            <div id="profile-dropdown" class="profile-dropdown">
                                <a href="../library/profile.php" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">
                                    <i class="fas fa-user mr-2"></i>My Profile
                                </a>
                                <a href="favorites.php" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">
                                    <i class="fas fa-heart mr-2"></i>Favorites
                                </a>
                                <?php if ($hasProSubscription): ?>
                                    <a href="pro_subscription.php" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">
                                        <i class="fas fa-crown mr-2 text-yellow-400"></i>PRO Subscription
                                    </a>
                                <?php endif; ?>
                                <a href="settings.php" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">
                                    <i class="fas fa-cog mr-2"></i>Settings
                                </a>
                                <hr class="border-gray-600 my-1">
                                <button onclick="logout()" class="w-full text-left px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Log Out
                                </button>
                            </div>
                        </div>

                        <!-- PRO BUTTON -->
                        <button onclick="<?php echo $isLoggedIn ? 'openProModal()' : 'openLoginModal()'; ?>" class="pro-button pro-button-gradient px-4 py-2 text-sm font-bold text-white rounded-md transition duration-300 shadow-md shadow-theme-orange/50 uppercase tracking-widest hidden sm:inline-flex items-center">
                            <?php if ($hasProSubscription): ?>
                                <i class="fas fa-crown mr-2"></i>PRO ACTIVE
                            <?php else: ?>
                                <i class="fas fa-crown mr-2"></i>GO PRO
                            <?php endif; ?>
                        </button>
                    </div>
                    
                    <!-- Mobile Menu Button -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button onclick="toggleMenu()" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-dark-card focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-red transition duration-300" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEARCH BAR -->
        <div id="search-bar" class="hidden opacity-0 scale-95 absolute top-16 left-0 right-0 bg-dark-card p-4 shadow-lg z-40 border-t border-primary-red/20">
            <div class="max-w-7xl mx-auto">
                <form onsubmit="handleSearch(event)" class="flex items-center">
                    <div class="relative flex-grow">
                        <input 
                            id="search-input"
                            type="text" 
                            placeholder="Search for movies, TV series, songs..." 
                            class="w-full bg-dark-bg text-white placeholder-gray-500 rounded-full py-3 pl-12 pr-4 focus:outline-none focus:ring-2 focus:ring-primary-red border border-gray-700"
                        >
                        <svg class="absolute left-4 top-3 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <button type="submit" class="ml-4 bg-primary-red text-white font-medium py-3 px-6 rounded-full hover:bg-red-600 transition duration-200">
                        Search
                    </button>
                </form>
            </div>
        </div>

        <!-- MOBILE MENU -->
        <div class="sm:hidden hidden" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1 bg-dark-bg border-t border-primary-red/10 max-h-[calc(100vh-4rem)] overflow-y-auto">
                <?php if ($isLoggedIn && $currentUser): ?>
                    <!-- User info in mobile menu -->
                    <div class="px-3 py-2 bg-dark-card mb-2">
                        <div class="flex items-center space-x-3">
                            <?php if ($currentUser['profile_image']): ?>
                                <img src="uploads/profile_images/<?php echo htmlspecialchars($currentUser['profile_image']); ?>" alt="Profile" class="w-10 h-10 rounded-full border-2 border-primary-red">
                            <?php else: ?>
                                <div class="w-10 h-10 rounded-full bg-primary-red flex items-center justify-center text-white font-bold">
                                    <?php echo strtoupper(substr($currentUser['first_name'] ?? 'U', 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                            <div>
                                <p class="text-white font-medium"><?php echo htmlspecialchars($currentUser['first_name'] . ' ' . $currentUser['last_name']); ?></p>
                                <p class="text-gray-400 text-sm">@<?php echo htmlspecialchars($currentUser['username']); ?></p>
                                <?php if ($hasProSubscription): ?>
                                    <span class="pro-badge mt-1"><i class="fas fa-crown"></i> PRO</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($currentUser['user_type'] === 'admin'): ?>
                        <a href="admin/user_management.php" class="block px-3 py-2 bg-primary-red text-white rounded-md mx-2 mb-2 text-center">
                            <i class="fas fa-cog mr-2"></i>Admin Panel
                        </a>
                    <?php endif; ?>
                    
                    <a href="../library/profile.php" class="block px-3 py-2 text-gray-300 hover:bg-dark-card hover:text-white">My Profile</a>
                    <a href="favorites.php" class="block px-3 py-2 text-gray-300 hover:bg-dark-card hover:text-white">Favorites</a>
                    <?php if ($hasProSubscription): ?>
                        <a href="pro_subscription.php" class="block px-3 py-2 text-gray-300 hover:bg-dark-card hover:text-white">
                            <i class="fas fa-crown text-yellow-400 mr-2"></i>PRO Subscription
                        </a>
                    <?php endif; ?>
                    <a href="settings.php" class="block px-3 py-2 text-gray-300 hover:bg-dark-card hover:text-white">Settings</a>
                    <button onclick="logout()" class="w-full text-left px-3 py-2 text-gray-300 hover:bg-dark-card hover:text-white">
                        Log Out
                    </button>
                    <hr class="border-gray-700 my-2">
                <?php else: ?>
                    <!-- Sign In Mobile Link -->
                    <button onclick="openLoginModal()" class="w-full text-left px-3 py-2 text-gray-300 hover:bg-dark-card hover:text-white">
                        Sign In
                    </button>
                <?php endif; ?>

                <!-- PRO Button for Mobile -->
                <button onclick="<?php echo $isLoggedIn ? 'openProModal()' : 'openLoginModal()'; ?>" class="w-full px-3 py-2 bg-primary-red text-white font-bold rounded-md mx-2 mb-2 hover:bg-red-600 flex items-center justify-center">
                    <i class="fas fa-crown mr-2"></i>
                    <?php echo $hasProSubscription ? 'PRO ACTIVE' : 'GET PRO ACCESS'; ?>
                </button>
                
                <!-- Mobile Categories -->
                <div class="px-3">
                    <h4 class="text-sm text-gray-500 font-semibold uppercase mb-2">Quick Links</h4>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="movies.php" class="px-3 py-2 bg-dark-card text-gray-300 rounded-md text-center hover:bg-primary-red hover:text-white">Movies</a>
                        <a href="songs.php" class="px-3 py-2 bg-dark-card text-gray-300 rounded-md text-center hover:bg-primary-red hover:text-white">Songs</a>
                        <a href="tv.php" class="px-3 py-2 bg-dark-card text-gray-300 rounded-md text-center hover:bg-primary-red hover:text-white">TV Series</a>
                        <a href="genres.php" class="px-3 py-2 bg-dark-card text-gray-300 rounded-md text-center hover:bg-primary-red hover:text-white">Genres</a>
                    </div>
                </div>

                <!-- Mobile Search -->
                <div class="mt-4 px-3">
                    <form onsubmit="handleSearch(event)" class="relative">
                        <input type="text" placeholder="Search..." class="w-full bg-dark-card text-white placeholder-gray-500 rounded-md py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-primary-red">
                        <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    <!-- NAVIGATION BAR END -->

    <!-- PRO SUBSCRIPTION MODAL -->
    <div id="pro-modal" class="fixed inset-0 bg-black bg-opacity-80 z-[100] hidden flex items-center justify-center p-4" onclick="closeProModal(event)">
        <div class="bg-dark-bg rounded-xl shadow-2xl p-6 md:p-8 w-full max-w-4xl transform transition-all duration-300 scale-100 border border-primary-red/50 max-h-[90vh] flex flex-col" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center border-b border-gray-700 pb-4 mb-4 shrink-0">
                <h2 class="text-3xl font-bold text-white text-glow-red">
                    <i class="fas fa-crown text-theme-orange mr-2"></i> PRO Subscription
                </h2>
                <button onclick="closeProModal()" class="text-gray-400 hover:text-primary-red transition duration-200 p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="overflow-y-auto flex-grow space-y-6">
                <!-- Current Subscription Status -->
                <div id="subscription-status">
                    <!-- Dynamic content will be inserted here -->
                </div>

                <!-- Subscription Plans -->
                <?php if (!$hasProSubscription): ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Yearly Plan -->
                    <div class="bg-dark-card p-6 rounded-xl border-2 border-theme-orange/80 shadow-lg relative overflow-hidden flex flex-col">
                        <div class="absolute top-0 right-0 bg-primary-red text-white text-xs font-bold py-1 px-4 rounded-bl-lg">BEST VALUE</div>
                        <h3 class="text-2xl font-bold text-theme-orange mb-2">Yearly Pro</h3>
                        <p class="text-gray-400 mb-4 h-12">Limited time offer for long-term commitment.</p>
                        <div class="text-4xl font-extrabold text-white mb-6">$49.99 <span class="text-base font-normal text-gray-500">/ Year</span></div>
                        <ul class="text-gray-300 space-y-2 mb-8 flex-grow">
                            <li class="flex items-center"><i class="fas fa-check text-theme-orange mr-2"></i> 4K Ultra HD Streaming</li>
                            <li class="flex items-center"><i class="fas fa-check text-theme-orange mr-2"></i> 5 simultaneous screens</li>
                            <li class="flex items-center"><i class="fas fa-check text-theme-orange mr-2"></i> Offline Downloads</li>
                            <li class="flex items-center"><i class="fas fa-check text-theme-orange mr-2"></i> Priority Support</li>
                        </ul>
                        <button onclick="selectPlan(3, 'Yearly Pro', '49.99', 'year')" class="pro-button-gradient mt-auto w-full text-white font-bold py-3 rounded-full shadow-lg shadow-theme-orange/40 hover:shadow-theme-orange/80 transform hover:scale-[1.02]">
                            Get Yearly Plan
                        </button>
                    </div>

                    <!-- Monthly Plan -->
                    <div class="bg-dark-card p-6 rounded-xl border border-gray-600 shadow-md flex flex-col">
                        <h3 class="text-2xl font-bold text-white mb-2">Monthly Pro</h3>
                        <p class="text-gray-400 mb-4 h-12">Flexible, no long-term contract.</p>
                        <div class="text-4xl font-extrabold text-white mb-6">$5.99 <span class="text-base font-normal text-gray-500">/ Month</span></div>
                        <ul class="text-gray-300 space-y-2 mb-8 flex-grow">
                            <li class="flex items-center"><i class="fas fa-check text-primary-red mr-2"></i> HD Streaming</li>
                            <li class="flex items-center"><i class="fas fa-check text-primary-red mr-2"></i> 2 simultaneous screens</li>
                            <li class="flex items-center"><i class="fas fa-check text-primary-red mr-2"></i> Offline Downloads</li>
                            <li class="flex items-center text-gray-500"><i class="fas fa-times text-gray-700 mr-2"></i> Standard Support</li>
                        </ul>
                        <button onclick="selectPlan(2, 'Monthly Pro', '5.99', 'month')" class="mt-auto w-full bg-primary-red text-white font-bold py-3 rounded-full hover:bg-red-600 transition duration-200">
                            Get Monthly Plan
                        </button>
                    </div>

                    <!-- Weekly Plan -->
                    <div class="bg-dark-card p-6 rounded-xl border border-gray-600 shadow-md flex flex-col">
                        <h3 class="text-2xl font-bold text-white mb-2">Weekly Pass</h3>
                        <p class="text-gray-400 mb-4 h-12">Perfect for a short binge-watching session.</p>
                        <div class="text-4xl font-extrabold text-white mb-6">$1.99 <span class="text-base font-normal text-gray-500">/ Week</span></div>
                        <ul class="text-gray-300 space-y-2 mb-8 flex-grow">
                            <li class="flex items-center"><i class="fas fa-check text-primary-red mr-2"></i> HD Streaming</li>
                            <li class="flex items-center"><i class="fas fa-check text-primary-red mr-2"></i> 1 simultaneous screen</li>
                            <li class="flex items-center text-gray-500"><i class="fas fa-times text-gray-700 mr-2"></i> No Downloads</li>
                            <li class="flex items-center text-gray-500"><i class="fas fa-times text-gray-700 mr-2"></i> Standard Support</li>
                        </ul>
                        <button onclick="selectPlan(1, 'Weekly Pass', '1.99', 'week')" class="mt-auto w-full bg-primary-red text-white font-bold py-3 rounded-full hover:bg-red-600 transition duration-200">
                            Get Weekly Pass
                        </button>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Selected Plan Display -->
                <div id="current-plan-section" class="hidden">
                    <h3 class="text-xl font-bold text-white mb-4">Selected Plan</h3>
                    <div class="bg-dark-card p-4 rounded-lg border border-primary-red">
                        <p id="selected-plan" class="text-lg font-semibold text-white"></p>
                    </div>
                </div>

                <!-- Payment Form -->
                <div id="payment-form-section" class="<?php echo $hasProSubscription ? 'hidden' : ''; ?>">
                    <form id="payment-form" onsubmit="processPayment(event)" class="space-y-4">
                        <input type="hidden" id="plan_id" name="plan_id" value="">
                        <input type="hidden" name="action" value="create_subscription">
                        
                        <h3 class="text-xl font-bold text-white mb-4">Payment Details</h3>
                        
                        <!-- Cardholder Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Cardholder Name</label>
                            <input type="text" name="cardholder_name" required
                                class="card-input"
                                placeholder="John Doe">
                        </div>
                        
                        <!-- Card Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Card Number</label>
                            <div class="relative">
                                <input type="text" id="card_number" name="card_number" required
                                    class="card-input pr-12"
                                    placeholder="1234 5678 9012 3456"
                                    maxlength="19">
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 flex gap-2">
                                    <i class="fab fa-cc-visa text-blue-500"></i>
                                    <i class="fab fa-cc-mastercard text-red-500"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Expiry and CVC -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Expiry Date (MM/YY)</label>
                                <input type="text" id="expiry_date" name="expiry_date" required
                                    class="card-input"
                                    placeholder="MM/YY"
                                    maxlength="5">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">CVC</label>
                                <input type="text" id="cvc" name="cvc" required
                                    class="card-input"
                                    placeholder="123"
                                    maxlength="4">
                            </div>
                        </div>
                        
                        <!-- Billing Summary -->
                        <div class="bg-dark-card rounded-lg p-4 mt-4">
                            <h4 class="font-bold text-white mb-3">Order Summary</h4>
                            <div id="plan-summary" class="text-sm text-gray-300">
                                <!-- Dynamic content will be inserted here -->
                            </div>
                        </div>
                        
                        <!-- Terms -->
                        <div class="flex items-start mt-4">
                            <input id="payment-terms" name="terms" type="checkbox" required
                                class="h-4 w-4 text-primary-red bg-gray-700 border-gray-600 rounded focus:ring-primary-red mt-1">
                            <label for="payment-terms" class="ml-2 text-gray-400 text-sm">
                                I agree to the <a href="#" class="text-primary-red hover:text-red-400">Terms of Service</a> 
                                and authorize recurring payments.
                            </label>
                        </div>
                        
                        <!-- Submit Button -->
                        <button id="payment-submit-btn" type="submit" class="w-full bg-primary-red text-white font-bold py-3 rounded-full hover:bg-red-600 transition duration-200 mt-4">
                            Complete Purchase
                        </button>
                    </form>
                </div>
                
                <!-- Card Payment Form (Hidden by default) -->
                <div id="card-payment-form" class="hidden">
                    <!-- This will be shown when a plan is selected -->
                </div>
            </div>
        </div>
    </div>

    <!-- LOGIN MODAL -->
    <div id="login-modal" class="fixed inset-0 bg-black bg-opacity-80 hidden z-[200] flex items-center justify-center p-4" onclick="closeLoginModal(event)">
        <div onclick="event.stopPropagation()" class="bg-dark-card w-full max-w-md p-6 rounded-xl shadow-xl border border-primary-red/40 max-h-[90vh] overflow-y-auto modal-enter">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-white">Sign In</h2>
                <button onclick="closeLoginModal()" class="text-gray-400 hover:text-primary-red">✖</button>
            </div>

            <form id="login-form">
                <label for="login-identifier" class="block text-sm font-medium text-gray-300 mb-1">Username or Email</label>
                <input id="login-identifier" type="text" placeholder="Enter Username or Email" class="input-field" required>
                <label for="login-password" class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                <input id="login-password" type="password" placeholder="Enter Password" class="input-field" required>
            </form>
           
            <p class="text-gray-400 mt-2 text-center">
                Forgot Password?
                <button onclick="openForgotModal()" class="text-primary-red">Reset</button>
            </p>

            <div class="recaptcha-box mt-3 mb-4">
                <input type="checkbox" class="w-3 h-3">
                <span class="text-gray-900 text-sm">I'm not a robot</span>
                <img src="https://www.gstatic.com/recaptcha/api2/logo_48.png" class="ml-auto w-5">
            </div>

            <button id="login-btn" class="w-full bg-primary-red text-white py-3 rounded-lg font-bold hover:bg-red-600">
                Log In
            </button>
            
            <div class="flex items-center my-6">
                <div class="flex-grow border-t border-gray-700"></div>
                <span class="flex-shrink mx-4 text-gray-500 text-sm">Or continue with</span>
                <div class="flex-grow border-t border-gray-700"></div>
            </div>

            <div class="space-y-3">
                <button onclick="mockSocialLogin('Google')" class="w-full bg-gray-700 text-white py-3 rounded-lg flex items-center justify-center hover:bg-gray-600 transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.0003 4.75C14.0315 4.75 15.6888 5.41875 16.9453 6.64937L19.5378 4.0975C17.5028 2.1975 14.9082 1 12.0003 1C7.81708 1 4.2325 3.4475 2.55344 6.9425L5.75354 9.4975C6.55938 7.07812 9.00698 4.75 12.0003 4.75Z" fill="#EA4335"/><path d="M23.6382 12.0001C23.6382 11.3283 23.5852 10.6783 23.4756 10.0461H12.0003V14.6296H18.4239C18.1565 16.0967 17.3484 17.2917 16.2084 18.0641V21.1077H20.0898C22.2537 19.0601 23.6382 16.1437 23.6382 12.0001Z" fill="#4285F4"/><path d="M5.75344 14.5026C5.58984 14.0049 5.50036 13.5025 5.50036 12.9999C5.50036 12.4973 5.58984 11.9949 5.75344 11.4971V8.4534L2.55344 5.90156C1.94052 7.18562 1.59973 8.56781 1.59973 9.9999C1.59973 11.432 1.94052 12.8142 2.55344 14.0983L5.75344 14.5026Z" fill="#FBBC05"/><path d="M12.0003 23.0002C15.0005 23.0002 17.6975 21.9213 19.7431 20.1585L16.2084 18.0641C15.1793 18.7308 13.7845 19.1668 12.0003 19.1668C9.00698 19.1668 6.55938 17.0211 5.75354 14.5027L2.55354 17.0578C4.2325 20.5528 7.81708 23.0002 12.0003 23.0002Z" fill="#34A853"/></svg>
                    Continue with Google
                </button>
                <button onclick="mockSocialLogin('Facebook')" class="w-full bg-blue-700 text-white py-3 rounded-lg flex items-center justify-center hover:bg-blue-600 transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33V22C18.343 21.128 22 16.991 22 12z"/></svg>
                    Continue with Facebook
                </button>
            </div>

            <p class="text-gray-300 text-center mt-4">
                Don't have an account?
                <button onclick="openRegisterModal()" class="text-primary-red">Register</button>
            </p>
        </div>
    </div>

    <!-- REGISTER MODAL -->
    <div id="register-modal" class="fixed inset-0 bg-black bg-opacity-80 hidden z-[210] flex items-center justify-center p-4" onclick="closeRegisterModal(event)">
        <div onclick="event.stopPropagation()" class="bg-dark-card w-full max-w-2xl p-4 rounded-xl shadow-xl border border-primary-red/40 max-h-[90vh] overflow-y-auto modal-enter">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-xl font-bold text-white mb-2">Create Account</h2>
                <button onclick="closeRegisterModal()" class="text-gray-400 hover:text-primary-red">✖</button>
            </div>

            <form id="register-form" enctype="multipart/form-data">
                <!-- Profile Image Upload -->
                <div class="text-center mb-3">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Profile Image</label>
                    <div class="relative">
                        <input type="file" id="profile_image" name="profile_image" accept="image/*" class="hidden" onchange="previewImage(this)">
                        <label for="profile_image" class="cursor-pointer block">
                            <div class="w-20 h-20 mx-auto rounded-full border-2 border-dashed border-gray-600 flex items-center justify-center hover:border-primary-red transition duration-300">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-400 mt-2">Click to upload</p>
                        </label>
                        <img id="image-preview" class="image-preview" alt="Preview">
                    </div>
                </div>

                <!-- Form Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <!-- First Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">First Name</label>
                        <input id="first_name" name="first_name" type="text" placeholder="Enter First Name" class="input-field" onblur="validateField(this, 'first_name')" required>
                        <div id="first_name_message" class="validation-message"></div>
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Last Name</label>
                        <input id="last_name" name="last_name" type="text" placeholder="Enter Last Name" class="input-field" onblur="validateField(this, 'last_name')" required>
                        <div id="last_name_message" class="validation-message"></div>
                    </div>

                    <!-- Email -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                        <input id="email" name="email" type="email" placeholder="Enter Email" class="input-field" onblur="validateField(this, 'email')" required>
                        <div id="email_message" class="validation-message"></div>
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Username</label>
                        <input id="username" name="username" type="text" placeholder="Enter Username" class="input-field" onblur="validateField(this, 'username')" required>
                        <div id="username_message" class="validation-message"></div>
                    </div>

                    <!-- Birthday -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Birthday</label>
                        <input id="birthday" name="birthday" type="date" class="input-field" onblur="validateField(this, 'birthday')" required>
                        <div id="birthday_message" class="validation-message"></div>
                    </div>

                    <!-- Country -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-300 mb-1">Country</label>
                        <select id="countrySelect" name="country" class="w-full mt-2 mb-2 bg-[#0d0d0d] text-white p-3 rounded-lg border border-[#444]" onblur="validateField(this, 'country')" required></select>
                        <div id="country_message" class="validation-message"></div>
                    </div>

                    <!-- Password -->
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                        <input id="password" name="password" type="password" placeholder="Enter Password" class="input-field pr-10" onblur="validateField(this, 'password')" required>
                        <button type="button" onclick="togglePassword('password')" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400">
                            👁
                        </button>
                        <div id="password_message" class="validation-message"></div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-300 mb-1">Confirm Password</label>
                        <input id="confirm_password" name="confirm_password" type="password" placeholder="Confirm Password" class="input-field pr-10" onblur="validateField(this, 'confirm_password')" required>
                        <button type="button" onclick="togglePassword('confirm_password')" class="absolute right-2 top-1/2 w-5 transform -translate-y-1/2 text-gray-400">
                            👁
                        </button>
                        <div id="confirm_password_message" class="validation-message"></div>
                    </div>
                </div>

                <!-- Terms -->
                <div class="flex items-start mt-4">
                    <input id="terms-check" name="agree" type="checkbox" class="h-4 w-4 text-primary-red bg-gray-700 border-gray-600 rounded focus:ring-primary-red mt-1" required>
                    <label for="terms-check" class="ml-2 text-gray-400 text-sm">
                        I agree to the <a href="#" class="text-primary-red hover:text-red-400">Terms of Service</a> 
                        and Privacy Policy.
                    </label>
                </div>

                <button type="submit" id="registerBtn" class="w-full bg-primary-red mt-4 py-3 rounded-lg font-bold text-white hover:bg-red-600 transition duration-200">
                    Register
                </button>
            </form>

            <p class="text-center text-gray-300 mt-4">
                Already have an account?
                <button onclick="openLoginModal()" class="text-primary-red">Sign In</button>
            </p>
        </div>
    </div>

    <!-- FORGOT PASSWORD MODAL -->
    <div id="forgot-modal" class="fixed inset-0 bg-black bg-opacity-80 hidden z-[220] flex items-center justify-center p-4" onclick="closeForgotModal(event)">
        <div onclick="event.stopPropagation()" class="bg-dark-card w-full max-w-md p-6 rounded-xl border border-primary-red/40 modal-enter">
            <h2 class="text-xl font-bold text-white mb-4">Reset Password</h2>
            <input type="email" class="input-field" placeholder="Enter Email">
            <button class="w-full bg-primary-red py-3 rounded-lg font-bold text-white">Send Reset Link</button>
        </div>
    </div>

    <!-- MODAL JAVASCRIPT -->
    <script>
        // Modal functions
        function openLoginModal(){ 
            const modal = document.getElementById("login-modal");
            modal.classList.remove("hidden"); 
            if (modal.querySelector('.modal-enter')) {
                modal.querySelector('.modal-enter').classList.add('modal-enter');
            }
        }

        function closeLoginModal(e){ 
            if(!e || e.target.id==="login-modal") {
                const modal = document.getElementById("login-modal");
                modal.classList.add("hidden"); 
            }
        }

        function openRegisterModal(){ 
            closeLoginModal(); 
            const modal = document.getElementById("register-modal");
            modal.classList.remove("hidden");
            if (modal.querySelector('.modal-enter')) {
                modal.querySelector('.modal-enter').classList.add('modal-enter');
            }
        }

        function closeRegisterModal(e){ 
            if(!e || e.target.id==="register-modal") {
                const modal = document.getElementById("register-modal");
                modal.classList.add("hidden");
            }
        }

        function openForgotModal(){ 
            closeLoginModal(); 
            const modal = document.getElementById("forgot-modal");
            modal.classList.remove("hidden");
            if (modal.querySelector('.modal-enter')) {
                modal.querySelector('.modal-enter').classList.add('modal-enter');
            }
        }

        function closeForgotModal(e){ 
            if(!e || e.target.id==="forgot-modal") {
                const modal = document.getElementById("forgot-modal");
                modal.classList.add("hidden");
            }
        }

        // Image preview
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const file = input.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.add('show');
                }
                reader.readAsDataURL(file);
            } else {
                preview.classList.remove('show');
            }
        }

        // Password toggle
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            if (field.type === "password") {
                field.type = "text";
            } else {
                field.type = "password";
            }
        }

        // Validation function
        function validateField(input, fieldType) {
            const value = input.value.trim();
            const messageEl = document.getElementById(fieldType + '_message');
            let isValid = true;
            let message = '';

            input.classList.remove('error', 'success');
            messageEl.classList.remove('show', 'error', 'success');

            switch(fieldType) {
                case 'first_name':
                case 'last_name':
                    if (!value) {
                        message = 'This field is required';
                        isValid = false;
                    } else if (!/^[a-zA-Z\s]{2,30}$/.test(value)) {
                        message = 'Name must contain only letters (2-30 characters)';
                        isValid = false;
                    } else {
                        message = 'Looks good!';
                    }
                    break;

                case 'email':
                    if (!value) {
                        message = 'Email is required';
                        isValid = false;
                    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                        message = 'Please enter a valid email address';
                        isValid = false;
                    } else {
                        message = 'Valid email format!';
                    }
                    break;

                case 'username':
                    if (!value) {
                        message = 'Username is required';
                        isValid = false;
                    } else if (value.length < 3) {
                        message = 'Username must be at least 3 characters long';
                        isValid = false;
                    } else if (!/^[a-zA-Z0-9_]+$/.test(value)) {
                        message = 'Username can only contain letters, numbers, and underscores';
                        isValid = false;
                    } else {
                        message = 'Username looks good!';
                    }
                    break;

                case 'birthday':
                    if (!value) {
                        message = 'Birthday is required';
                        isValid = false;
                    } else {
                        const dob = new Date(value);
                        const now = new Date();
                        const age = now.getFullYear() - dob.getFullYear();
                        if (age < 13) {
                            message = 'You must be at least 13 years old';
                            isValid = false;
                        } else {
                            message = 'Age verified!';
                        }
                    }
                    break;

                case 'country':
                    if (!value || value === 'Select Country') {
                        message = 'Please select your country';
                        isValid = false;
                    } else {
                        message = 'Country selected!';
                    }
                    break;

                case 'password':
                    if (!value) {
                        message = 'Password is required';
                        isValid = false;
                    } else if (value.length < 8) {
                        message = 'Password must be at least 8 characters long';
                        isValid = false;
                    } else if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(value)) {
                        message = 'Password needs uppercase, lowercase, and number';
                        isValid = false;
                    } else {
                        message = 'Strong password!';
                    }
                    break;

                case 'confirm_password':
                    const password = document.getElementById('password').value;
                    if (!value) {
                        message = 'Please confirm your password';
                        isValid = false;
                    } else if (value !== password) {
                        message = 'Passwords do not match';
                        isValid = false;
                    } else {
                        message = 'Passwords match!';
                    }
                    break;
            }

            if (value) {
                input.classList.add(isValid ? 'success' : 'error');
                messageEl.textContent = message;
                messageEl.classList.add('show', isValid ? 'success' : 'error');
            }

            return isValid;
        }

        // Load countries
        const countries = [
            "Select Country","Afghanistan","Albania","Algeria","Andorra","Angola","Argentina","Armenia","Australia",
            "Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium",
            "Belize","Benin","Bhutan","Bolivia","Bosnia & Herzegovina","Botswana","Brazil","Brunei",
            "Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cape Verde",
            "Central African Republic","Chad","Chile","China","Colombia","Comoros","Congo",
            "Costa Rica","Croatia","Cuba","Cyprus","Czech Republic","Denmark","Djibouti","Dominica",
            "Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea",
            "Estonia","Eswatini","Ethiopia","Fiji","Finland","France","Gabon","Gambia","Georgia",
            "Germany","Ghana","Greece","Grenada","Guatemala","Guinea","Guinea-Bissau","Guyana",
            "Haiti","Honduras","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland",
            "Israel","Italy","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati","Kuwait",
            "Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein",
            "Lithuania","Luxembourg","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta",
            "Marshall Islands","Mauritania","Mauritius","Mexico","Micronesia","Moldova","Monaco",
            "Mongolia","Montenegro","Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal",
            "Netherlands","New Zealand","Nicaragua","Niger","Nigeria","North Korea","North Macedonia",
            "Norway","Oman","Pakistan","Palau","Panama","Papua New Guinea","Paraguay","Peru",
            "Philippines","Poland","Portugal","Qatar","Romania","Russia","Rwanda","Saint Lucia",
            "Samoa","San Marino","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone",
            "Singapore","Slovakia","Slovenia","Somalia","South Africa","South Korea","South Sudan",
            "Spain","Sri Lanka","Sudan","Suriname","Sweden","Switzerland","Syria","Taiwan",
            "Tajikistan","Tanzania","Thailand","Timor-Leste","Togo","Tonga","Trinidad & Tobago",
            "Tunisia","Turkey","Turkmenistan","Tuvalu","Uganda","Ukraine","United Arab Emirates",
            "United Kingdom","United States","Uruguay","Uzbekistan","Vanuatu","Vatican City",
            "Venezuela","Vietnam","Yemen","Zambia","Zimbabwe"
        ];

        // Populate country select on DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            const countrySelect = document.getElementById("countrySelect");
            if (countrySelect) {
                countries.forEach(country => {
                    let option = document.createElement("option");
                    option.value = country;
                    option.textContent = country;
                    countrySelect.appendChild(option);
                });
            }

            // Register form submission
            const registerForm = document.getElementById("register-form");
            if (registerForm) {
                registerForm.addEventListener("submit", function (e) {
                    e.preventDefault();

                    const btn = document.getElementById("registerBtn");
                    btn.classList.add('btn-loading');
                    btn.textContent = '';

                    // Validate all fields
                    let allValid = true;
                    const fieldsToValidate = ['first_name', 'last_name', 'email', 'username', 'birthday', 'password', 'confirm_password'];
                    
                    fieldsToValidate.forEach(fieldId => {
                        const input = document.getElementById(fieldId);
                        if (!validateField(input, fieldId)) {
                            allValid = false;
                        }
                    });

                    const countrySelect = document.getElementById("countrySelect");
                    if (!validateField(countrySelect, 'country')) {
                        allValid = false;
                    }

                    const termsCheck = document.getElementById("terms-check");
                    if (!termsCheck.checked) {
                        allValid = false;
                        Swal.fire({
                            icon: 'error',
                            title: 'Terms Required',
                            text: 'You must agree to the Terms of Service and Privacy Policy',
                            confirmButtonColor: "#E50914"
                        });
                        btn.classList.remove('btn-loading');
                        btn.textContent = 'Register';
                        return;
                    }

                    if (!allValid) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please fix the errors in the form before submitting',
                            confirmButtonColor: "#E50914"
                        });
                        btn.classList.remove('btn-loading');
                        btn.textContent = 'Register';
                        return;
                    }

                    let data = new FormData(this);

                    fetch("../library/registerBackend.php", {
                        method: "POST",
                        body: data
                    })
                    .then(res => res.text())
                    .then(text => {
                        let data;
                        try { data = JSON.parse(text); }
                        catch (e) {
                            Swal.fire({
                                icon: "error",
                                title: "Server Error",
                                text: "Invalid server response. Please try again.",
                                confirmButtonColor: "#E50914"
                            });
                            return;
                        }

                        if (data.status === "success") {
                            Swal.fire({
                                icon: "success",
                                title: "🎉 Congratulations!",
                                text: data.message,
                                confirmButtonColor: "#E50914",
                                confirmButtonText: "Continue to Login",
                                showClass: {
                                    popup: 'animate__animated animate__fadeInUp'
                                }
                            }).then(() => {
                                registerForm.reset();
                                countrySelect.value = "Select Country";
                                document.getElementById("image-preview").classList.remove('show');
                                
                                document.querySelectorAll('.validation-message').forEach(msg => {
                                    msg.classList.remove('show', 'error', 'success');
                                });
                                document.querySelectorAll('.input-field').forEach(input => {
                                    input.classList.remove('error', 'success');
                                });

                                closeRegisterModal();
                                
                                setTimeout(() => {
                                    openLoginModal();
                                }, 300);
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Registration Failed",
                                text: data.message,
                                confirmButtonColor: "#E50914"
                            });
                        }
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: "error",
                            title: "Network Error",
                            text: "Could not reach server. Please check your connection and try again.",
                            confirmButtonColor: "#E50914"
                        });
                        console.error(err);
                    })
                    .finally(() => {
                        btn.classList.remove('btn-loading');
                        btn.textContent = 'Register';
                    });
                });
            }

            // Login form submission
            const loginBtn = document.getElementById("login-btn");
            if (loginBtn) {
                loginBtn.addEventListener("click", function (e) {
                    e.preventDefault();

                    const btn = this;
                    btn.classList.add('btn-loading');
                    btn.textContent = '';

                    const identifier = document.getElementById("login-identifier").value.trim();
                    const password = document.getElementById("login-password").value.trim();

                    if (!identifier || !password) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Missing Information',
                            text: 'Please enter both username/email and password',
                            confirmButtonColor: "#E50914"
                        });
                        btn.classList.remove('btn-loading');
                        btn.textContent = 'Log In';
                        return;
                    }

                    let data = new FormData();
                    data.append("identifier", identifier);
                    data.append("password", password);

                    fetch("../library/logingBackend.php", {
                        method: "POST",
                        body: data
                    })
                    .then(res => res.text())
                    .then(text => {
                        let data;
                        try { data = JSON.parse(text); }
                        catch (e) {
                            Swal.fire({
                                icon: "error",
                                title: "Server Error",
                                text: "Invalid server response. Please try again.",
                                confirmButtonColor: "#E50914"
                            });
                            return;
                        }

                        if (data.status === "success") {
                            Swal.fire({
                                icon: "success",
                                title: "🎬 Welcome to MovieLab!",
                                text: data.message,
                                confirmButtonColor: "#E50914",
                                confirmButtonText: "Let's Go!",
                                showClass: {
                                    popup: 'animate__animated animate__fadeInUp'
                                }
                            }).then(() => {
                                document.getElementById("login-identifier").value = "";
                                document.getElementById("login-password").value = "";
                                
                                closeLoginModal();
                                
                                // Reload the page to update the navbar
                                setTimeout(() => {
                                    window.location.reload();
                                }, 500);
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Login Failed",
                                text: data.message,
                                confirmButtonColor: "#E50914"
                            });
                        }
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: "error",
                            title: "Network Error",
                            text: "Could not reach server. Please check your connection and try again.",
                            confirmButtonColor: "#E50914"
                        });
                        console.error(err);
                    })
                    .finally(() => {
                        btn.classList.remove('btn-loading');
                        btn.textContent = 'Log In';
                    });
                });
            }
        });
    </script>
</body>
</html>