<header class="sticky top-0 z-50">

<!DOCTYPE TML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* 'Inter' font for modern web apps */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0d0d0d; overflow-x: hidden;/* Slightly off-black background */
        }
        /* Custom glow effect for red text/elements (applied to the logo) */
        .text-glow-red {
            /* Enhanced glow effect for a more vivid neon look */
            text-shadow: 0 0 10px rgba(229, 9, 20, 0.9),
                         0 0 25px rgba(229, 9, 20, 0.7),
                         0 0 40px rgba(229, 9, 20, 0.4);
        }

        /* --- Custom CSS for Underline Hover Effect (For Navigation Links) --- */
        .nav-link-underline {
            position: relative;
            /* Ensures the text remains white */
            color: #FFFFFF; 
            transition: color 0.3s ease;
        }

        /* Create the pseudo-element for the underline */
        .nav-link-underline::after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px; /* Thickness of the underline */
            bottom: -8px; /* Position the underline slightly below the text */
            left: 50%;
            transform: translateX(-50%);
            background-color: #E50914; /* Primary Red Color */
            /* Smooth transition for the width property and shadow */
            transition: width 0.3s ease, box-shadow 0.3s ease;
            border-radius: 9999px; /* Fully rounded ends */
            box-shadow: none; /* Default no shadow */
        }

        /* Expand the underline and apply RED GLOW on hover/focus */
        .nav-link-underline:hover::after,
        .nav-link-underline:focus::after {
            width: 100%;
            /* Lassana red color glow eka meken enawa */
            box-shadow: 0 0 10px rgba(229, 9, 20, 0.8), 0 0 20px rgba(229, 9, 20, 0.5);
        }

        /* Apply the same underline effect to the dropdown buttons */
        .nav-dropdown-btn:hover .nav-link-underline::after,
        .nav-dropdown-btn:focus .nav-link-underline::after {
            width: 100%;
            /* Lassana red color glow eka meken enawa */
            box-shadow: 0 0 10px rgba(229, 9, 20, 0.8), 0 0 20px rgba(229, 9, 20, 0.5);
        }
        
        /* Custom PRO Button Gradient and Shadow */
        .pro-button-gradient {
            background-image: linear-gradient(to right, #c60505ff, #d40404ff);
            transition: all 0.3s ease;
        }
        
        .pro-button-gradient:hover {
            /* Shadow eka poddak loku karanawa hover karaddi */
            box-shadow: 0 0 15px rgba(217, 30, 5, 0.8), 0 0 30px rgba(250, 71, 27, 0.5);
            transform: scale(1.05); /* Poddak loku wenawa */
        }
        
        /* PRO button for mobile menu */
        .pro-button-mobile {
            background-color: #fa1b1bff; /* Darker red/orange for consistency */
            transition: background-color 0.3s ease;
        }
        .pro-button-mobile:hover {
            background-color: #C72600;
        }

        /* Search bar animation */
        .search-bar {
            transition: all 0.3s ease-in-out;
        }
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

        /* Custom reCAPTCHA UI box */
        .recaptcha-box {
            background: #f9f9f9;
            border: 1px solid #d3d3d3;
            padding: 14px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* Loading animation for buttons */
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

        .modal-exit {
            animation: modalExit 0.3s ease-in;
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

        @keyframes modalExit {
            from {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
            to {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }
        }

        /* Validation message styles */
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

        /* Profile dropdown styles */
        .profile-dropdown {
            transform: translateY(-10px);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .profile-dropdown.show {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }

        /* Profile image styles */
        .profile-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #E50914;
        }

        /* Image preview styles */
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

        /* Responsive styles */
        @media (max-width: 640px) {
            .profile-image {
                width: 32px;
                height: 32px;
            }
        }
    </style>

    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-red': '#E50914', // Netflix-style bold red
                        'dark-bg': '#141414',
                        'dark-card': '#222222',
                        'theme-orange': '#FA471B', // New accent color
                    }
                }
            }
        }

        // Global variables for user state
        let currentUser = null;
        let isLoggedIn = false;

        // JavaScript for mobile menu toggle and dropdowns
        function toggleMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // Function to toggle a specific dropdown and close others
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            // Close all other dropdowns
            document.querySelectorAll('div.absolute[id$="-dropdown"]').forEach(d => {
                if (d.id !== id) {
                    d.classList.add('hidden');
                }
            });
            // Toggle the clicked dropdown
            dropdown.classList.toggle('hidden');
        }

        // Toggle profile dropdown
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profile-dropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside (on the window)
        window.onclick = function(event) {
            // Close all dropdowns if click is not on a dropdown button
            if (!event.target.closest('button')) {
                document.querySelectorAll('div.absolute[id$="-dropdown"]').forEach(d => {
                    d.classList.add('hidden');
                });
                // Close profile dropdown
                const profileDropdown = document.getElementById('profile-dropdown');
                if (profileDropdown) {
                    profileDropdown.classList.remove('show');
                }
            }
        }
        
        // JS FOR PRO MODAL
        function openProModal() {
            document.getElementById('pro-modal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden'); // Modal eka open wela thiyeddi background eka scroll wen eka nawaththana wa
        }

        function closeProModal(event) {
            const modal = document.getElementById('pro-modal');
            
            // Modal eka close karanna click karanne overlay eke nam witharak (button ekak nowei nam)
            if (!event || event.target.id === 'pro-modal') {
                 modal.classList.add('hidden');
                 document.body.classList.remove('overflow-hidden');
            }
        }

        // JS FOR SEARCH BAR
        function toggleSearchBar() {
            const searchBar = document.getElementById('search-bar');
            const searchInput = document.getElementById('search-input');
            
            searchBar.classList.toggle('hidden');
            searchBar.classList.toggle('opacity-0');
            searchBar.classList.toggle('opacity-100');
            searchBar.classList.toggle('scale-95');
            searchBar.classList.toggle('scale-100');
            
            // Focus on the input when the search bar is shown
            if (!searchBar.classList.contains('hidden')) {
                setTimeout(() => {
                    searchInput.focus();
                }, 100);
            }
        }

        // Close search bar when clicking outside
        document.addEventListener('click', function(event) {
            const searchBar = document.getElementById('search-bar');
            const searchButton = document.querySelector('button[onclick="toggleSearchBar()"]');
            
            if (!searchBar.classList.contains('hidden') && 
                !searchBar.contains(event.target) && 
                !searchButton.contains(event.target)) {
                searchBar.classList.add('hidden');
                searchBar.classList.remove('opacity-100', 'scale-100');
                searchBar.classList.add('opacity-0', 'scale-95');
            }
        });

        // Handle search form submission
        function handleSearch(event) {
            event.preventDefault();
            const searchInput = document.getElementById('search-input');
            const searchTerm = searchInput.value.trim();
            
            if (searchTerm) {
                alert(`Searching for: ${searchTerm}`);
                // Here you would typically redirect to a search results page or perform an API call
                // For example: window.location.href = `/search?q=${encodeURIComponent(searchTerm)}`;
            }
        }

        // Check user session on page load
        function checkUserSession() {
            fetch('../library/checkSession.php')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'logged_in') {
                        currentUser = data.user;
                        isLoggedIn = true;
                        updateNavbarForLoggedInUser();
                    } else {
                        isLoggedIn = false;
                        updateNavbarForGuest();
                    }
                })
                .catch(error => {
                    console.error('Session check error:', error);
                    isLoggedIn = false;
                    updateNavbarForGuest();
                });
        }

        // Update navbar for logged in user
        function updateNavbarForLoggedInUser() {
            const signInBtn = document.getElementById('sign-in-btn');
            const mobileSignInBtn = document.getElementById('mobile-sign-in-btn');
            const userProfileSection = document.getElementById('user-profile-section');
            
            if (signInBtn) signInBtn.style.display = 'none';
            if (mobileSignInBtn) mobileSignInBtn.style.display = 'none';
            if (userProfileSection) {
                userProfileSection.style.display = 'flex';
                updateUserProfileDisplay();
            }
        }

        // Update navbar for guest user
        function updateNavbarForGuest() {
            const signInBtn = document.getElementById('sign-in-btn');
            const mobileSignInBtn = document.getElementById('mobile-sign-in-btn');
            const userProfileSection = document.getElementById('user-profile-section');
            
            if (signInBtn) signInBtn.style.display = 'inline-flex';
            if (mobileSignInBtn) mobileSignInBtn.style.display = 'block';
            if (userProfileSection) userProfileSection.style.display = 'none';
        }

        // Update user profile display
        function updateUserProfileDisplay() {
            const profileImg = document.getElementById('user-profile-img');
            const userName = document.getElementById('user-name-display');
            
            if (currentUser) {
                if (profileImg) {
                    if (currentUser.profile_image) {
                        profileImg.src = '../uploads/profile_images/' + currentUser.profile_image;
                    } else {
                        profileImg.src = 'https://via.placeholder.com/40x40/E50914/FFFFFF?text=' + (currentUser.first_name ? currentUser.first_name.charAt(0) : 'U');
                    }
                }
                if (userName) {
                    userName.textContent = currentUser.first_name;
                }
            }
        }

        // Logout Function
        function logout() {
            fetch('../library/logoutBackend.php', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    currentUser = null;
                    isLoggedIn = false;
                    updateNavbarForGuest();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Logged Out',
                        text: 'You have been successfully logged out!',
                        confirmButtonColor: '#E50914'
                    }).then(() => {
                        window.location.reload();
                    });
                }
            })
            .catch(error => {
                console.error('Logout error:', error);
            });
        }

        // JS FOR UPDATE PROFILE MODAL
        function openUpdateProfileModal() {
            const modal = document.getElementById('update-profile-modal');
            modal.classList.remove('hidden');
            
            // Apply modal-enter class to the content box for animation
            const modalContent = modal.querySelector('.modal-content');
            if (modalContent) {
                modalContent.classList.add('modal-enter');
            }
            document.body.classList.add('overflow-hidden'); 
            
            // Populate form with current user data
            if (currentUser) {
                document.getElementById('up_first_name').value = currentUser.first_name || '';
                document.getElementById('up_last_name').value = currentUser.last_name || '';
                document.getElementById('up_username').value = currentUser.username || '';
                document.getElementById('up_email').value = currentUser.email || ''; // *** Email field pre-fill එක තහවුරු කරයි ***
                
                // Set profile image preview
                const profileImgUrl = currentUser.profile_image 
                    ? '../uploads/profile_images/' + currentUser.profile_image 
                    : 'https://via.placeholder.com/100x100/222222/FFFFFF?text=' + (currentUser.first_name ? currentUser.first_name.charAt(0) : 'U');
                
                const preview = document.getElementById('up_image_preview');
                preview.src = profileImgUrl;
                preview.classList.add('show');
            }
        }

        function closeUpdateProfileModal(event) {
            const modal = document.getElementById('update-profile-modal');
            const modalContent = modal.querySelector('.modal-content');
            
            if (!event || event.target.id === 'update-profile-modal' || event.target.closest('#update-profile-modal .close-btn')) {
                 if (modalContent) {
                    modalContent.classList.remove('modal-enter');
                    // Add modal-exit for visual feedback
                    modalContent.classList.add('modal-exit'); 
                 }
                 
                 // Wait for the exit animation to finish before hiding
                 setTimeout(() => {
                    modal.classList.add('hidden');
                    if (modalContent) {
                        modalContent.classList.remove('modal-exit');
                    }
                    document.body.classList.remove('overflow-hidden');
                    
                    // Reset password fields
                    document.getElementById('up_current_password').value = ''; // *** Current Password ක්ෂේත්‍රය Reset කිරීම ***
                    document.getElementById('up_password').value = '';
                    document.getElementById('up_confirm_password').value = '';
                 }, 300); // 300ms matches the transition duration in CSS
            }
        }

        // Reuse the image preview function for the update profile form
        function previewUpdateImage(input) {
            const preview = document.getElementById('up_image_preview');
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.add('show');
                }
                reader.readAsDataURL(file);
            } else {
                // If the user clears the file input, we keep the existing image if available
                if (currentUser && currentUser.profile_image) {
                     preview.src = '../uploads/profile_images/' + currentUser.profile_image;
                } else {
                    preview.src = 'https://via.placeholder.com/100x100/222222/FFFFFF?text=No+Image';
                }
            }
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            checkUserSession();
            
            // Handle Update Profile Form Submission
            const form = document.getElementById('update-profile-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const newPassword = document.getElementById('up_password').value;
                    const confirmPassword = document.getElementById('up_confirm_password').value;
                    const currentPassword = document.getElementById('up_current_password').value; // *** Current Password එක ලබා ගැනීම ***

                    // 1. New Password / Confirm Password Validation
                    if (newPassword && newPassword !== confirmPassword) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Passwords Do Not Match',
                            text: 'The new password and confirm password fields must match.',
                            confirmButtonColor: '#E50914'
                        });
                        return; // Stop submission
                    }
                    
                    // 2. *** Current Password Required Validation (Backend ඉල්ලීම පරිදි) ***
                    if (newPassword && !currentPassword) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Current Password Required',
                            text: 'You must enter your current password to set a new one.',
                            confirmButtonColor: '#E50914'
                        });
                        // Visual feedback (shaking the field)
                        document.getElementById('up_current_password').classList.add('error');
                        setTimeout(() => document.getElementById('up_current_password').classList.remove('error'), 1500);
                        return; // Stop submission
                    }


                    const btn = document.getElementById('update-profile-btn');
                    btn.classList.add('btn-loading');
                    btn.textContent = ''; 

                    const formData = new FormData(this);
                    
                    fetch('../library/updateProfileBackend.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        btn.classList.remove('btn-loading');
                        btn.textContent = 'Update Profile';
                        
                        if (data.status === 'success') {
                            // Update global currentUser object and UI display
                            currentUser = data.user;
                            updateUserProfileDisplay(); 

                            Swal.fire({
                                icon: 'success',
                                title: 'Profile Updated!',
                                text: data.message,
                                confirmButtonColor: '#E50914'
                            }).then(() => {
                                closeUpdateProfileModal();
                            });
                            
                            // Clear password fields on success
                            document.getElementById('up_current_password').value = '';
                            document.getElementById('up_password').value = '';
                            document.getElementById('up_confirm_password').value = '';
                            
                        } else {
                             Swal.fire({
                                icon: 'error',
                                title: 'Update Failed',
                                text: data.message,
                                confirmButtonColor: '#E50914'
                            });
                        }
                    })
                    .catch(error => {
                        btn.classList.remove('btn-loading');
                        btn.textContent = 'Update Profile';
                        Swal.fire({
                            icon: 'error',
                            title: 'Network Error',
                            text: 'Could not reach server. Please check your connection and try again.',
                            confirmButtonColor: '#E50914'
                        });
                        console.error('Update profile error:', error);
                    });
                });
            }
        });
    </script>
</head>
<body class="min-h-screen">

    <nav class="bg-dark-bg shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">

                <div class="flex-shrink-0 flex items-center">
                         <div class=" top-8 left-8  items-center gap-2 ms-8">
                            <a href="../Site/index.php" class="text-glow-red">
                         <i class="fas fa-film text-red-600 text-3xl" > </i>
                         </a>
            
                    <a  href="../Site/index.php"class="text-3xl font-extrabold text-primary-red tracking-wider cursor-pointer text-glow-red mr-6">
                        Movie Lab 
                      </a></div>
                      
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-4 lg:space-x-6 items-center">
                        <div class="relative">
                            <button onclick="toggleDropdown('movies-dropdown')" class="nav-dropdown-btn inline-flex items-center px-1 pt-1 text-sm font-medium text-white transition duration-300 focus:outline-none">
                                <span class="nav-link-underline">Movies</span>
                                <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div id="movies-dropdown" class="absolute hidden mt-3 w-48 rounded-lg shadow-2xl bg-dark-card ring-1 ring-primary-red ring-opacity-20 z-20">
                                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="movies-menu-button">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">Now Playing</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">Popular</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">Top Rated</a>
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <button onclick="toggleDropdown('songs-dropdown')" class="nav-dropdown-btn inline-flex items-center px-1 pt-1 text-sm font-medium text-white transition duration-300 focus:outline-none">
                                <span class="nav-link-underline">Songs</span>
                                <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div id="songs-dropdown" class="absolute hidden mt-3 w-48 rounded-lg shadow-2xl bg-dark-card ring-1 ring-primary-red ring-opacity-20 z-20">
                                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="songs-menu-button">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">New Releases</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">Top Charts</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">Playlists</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">Artists</a>
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <button onclick="toggleDropdown('tv-series-dropdown')" class="nav-dropdown-btn inline-flex items-center px-1 pt-1 text-sm font-medium text-white transition duration-300 focus:outline-none">
                                <span class="nav-link-underline">TV Series</span>
                                <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div id="tv-series-dropdown" class="absolute hidden mt-3 w-48 rounded-lg shadow-2xl bg-dark-card ring-1 ring-primary-red ring-opacity-20 z-20">
                                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="tv-series-menu-button">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">Trending</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">On Air</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">Netflix Originals</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center">
                    <div class="hidden sm:flex items-center space-x-0 lg:space-x-0">
                        <div class="relative">
                            <button onclick="toggleDropdown('genres-dropdown')" class="nav-dropdown-btn inline-flex items-center px-3 pt-1 text-sm font-medium text-white transition duration-300 focus:outline-none">
                                <span class="nav-link-underline">Genres</span>
                                <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div id="genres-dropdown" class="absolute hidden mt-3 w-72 rounded-lg shadow-2xl bg-dark-card ring-1 ring-primary-red ring-opacity-20 z-20 -right-24">
                                <div class="p-2 grid grid-cols-2 gap-x-4 gap-y-1" role="menu" aria-orientation="vertical" aria-labelledby="genres-menu-button">
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Action</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Horror</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Comedy</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Sci-Fi</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Drama</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Romance</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Thriller</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Documentary</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Animation</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Fantasy</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Crime</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Mystery</a>
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <button onclick="toggleDropdown('years-dropdown')" class="nav-dropdown-btn inline-flex items-center px-3 pt-1 text-sm font-medium text-white transition duration-300 focus:outline-none">
                                <span class="nav-link-underline">Years</span>
                                <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div id="years-dropdown" class="absolute hidden mt-3 w-32 rounded-lg shadow-2xl bg-dark-card ring-1 ring-primary-red ring-opacity-20 z-20">
                                <div class="py-1 grid grid-cols-2 gap-x-1 gap-y-1" role="menu" aria-orientation="vertical" aria-labelledby="years-menu-button">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2025</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2024</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2023</a>
                                      <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2023</a>
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2022</a>
                                          <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2021</a>
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2020</a>
                                              <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2019</a>
                                                <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2018</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">Older</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="relative">
                            <button onclick="toggleDropdown('languages-dropdown')" class="nav-dropdown-btn inline-flex items-center px-3 pt-1 text-sm font-medium text-white transition duration-300 focus:outline-none">
                                <span class="nav-link-underline">Languages</span>
                                <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div id="languages-dropdown" class="absolute hidden mt-3 w-72 rounded-lg shadow-2xl bg-dark-card ring-1 ring-primary-red ring-opacity-20 z-20 -right-16">
                                <div class="p-2 grid grid-cols-2 gap-x-4 gap-y-1" role="menu" aria-orientation="vertical" aria-labelledby="languages-menu-button">
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">English</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Japanese</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Mandarin</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Spanish</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">German</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Hindi</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Korean</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">French</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Sinhala</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Tamil</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Malayalam</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Kannada</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Italian</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Telugu</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Russian</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Arabic</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3 ml-4">
                        <div class="relative">
                            <button onclick="toggleSearchBar()" class="text-gray-400 hover:text-white p-2 rounded-full hover:bg-dark-card transition duration-200 focus:outline-none focus:ring-2 focus:ring-primary-red">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                            <form id="search-bar" onsubmit="handleSearch(event)" class="search-bar absolute right-0 mt-3 w-64 md:w-80 hidden opacity-0 scale-95 origin-top-right bg-dark-card p-3 rounded-lg shadow-2xl ring-1 ring-primary-red ring-opacity-20 z-20" style="min-width: 250px;">
                                <div class="flex items-center">
                                    <input id="search-input" type="text" placeholder="Search movies, series..." class="w-full bg-dark-bg text-white border border-gray-600 rounded-md py-2 px-3 focus:outline-none focus:border-primary-red" autocomplete="off">
                                    <button type="submit" class="ml-2 text-primary-red hover:text-white transition duration-200">
                                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <button class="text-gray-400 hover:text-white p-2 rounded-full hover:bg-dark-card transition duration-200 focus:outline-none focus:ring-2 focus:ring-primary-red hidden sm:block">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 3 01-6 0v-1m6 0H9" />
                            </svg>
                        </button>
                        
                        <div id="user-profile-section" class="relative hidden sm:ml-4 sm:flex items-center" style="display: none;">
                            <button id="user-menu-button" onclick="toggleProfileDropdown()" class="flex items-center space-x-2 text-white p-1 rounded-full hover:ring-2 hover:ring-primary-red focus:outline-none focus:ring-2 focus:ring-primary-red transition duration-200" aria-expanded="false" aria-haspopup="true">
                                <img id="user-profile-img" class="profile-image" src="https://via.placeholder.com/40x40/E50914/FFFFFF?text=U" alt="User Profile">
                                <span id="user-name-display" class="hidden lg:inline text-sm font-medium">User</span>
                            </button>
                            
                            <div id="profile-dropdown" class="profile-dropdown absolute right-0 top-12 w-48 bg-dark-card rounded-lg shadow-xl border border-primary-red/20 py-2">
                                
                                <button onclick="openUpdateProfileModal()" class="w-full text-left block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">
                                    <i class="fas fa-user mr-2"></i>Update Profile
                                </button>
                                
                                <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">
                                    <i class="fas fa-cog mr-2"></i>Settings
                                </a>
                                <hr class="border-gray-600 my-1">
                                <button onclick="logout()" class="w-full text-left px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Log Out
                                </button>
                            </div>
                        </div>
                        
                        <button id="sign-in-btn" onclick="openLoginModal()" class="px-4 py-1.5 text-sm font-semibold text-white bg-primary-red hover:bg-red-600 rounded-md transition duration-200 shadow-md hidden sm:inline-flex">
                            Sign In
                        </button>

                        <button onclick="openProModal()" class="pro-button-gradient px-4 py-2 text-sm font-bold text-white rounded-md transition duration-300 shadow-md shadow-theme-orange/50 uppercase tracking-widest hidden sm:inline-flex"> PRO </button>
                        </div>
                </div>

                <div class="-mr-2 flex items-center sm:hidden">
                    <button onclick="toggleMenu()" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-dark-card focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-red transition duration-300" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="sm:hidden hidden" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1 px-2">
                
                <button onclick="openProModal()" class="pro-button-mobile w-full text-center px-3 py-2 rounded-md text-base font-bold text-white shadow-md shadow-theme-orange/50 uppercase tracking-widest mb-3">
                    GET PRO ACCESS
                </button>
                
                <a href="#" class="bg-dark-card text-white block px-3 py-2 rounded-md text-base font-medium">Home</a>

                <div id="mobile-user-actions" class="border-t border-gray-700 pt-3">
                    <button id="mobile-sign-in-btn" onclick="openLoginModal()" class="px-4 py-1.5 text-sm text-white hover:bg-red-600 rounded-md block w-full text-center"> Sign In </button>
                    
                    <div id="mobile-logged-in-actions" class="space-y-1" style="display: none;">
                        <div class="flex items-center px-3 py-2">
                            <img id="mobile-user-profile-img" class="profile-image w-8 h-8 mr-3" src="https://via.placeholder.com/40x40/E50914/FFFFFF?text=U" alt="User Profile">
                            <span class="text-white text-base font-medium" id="mobile-user-name-display">User</span>
                        </div>
                        
                        <button onclick="openUpdateProfileModal()" class="text-gray-300 hover:bg-dark-card hover:text-primary-red flex items-center px-3 py-2 rounded-md text-base font-medium w-full text-left">
                            <i class="fas fa-user mr-2 w-6"></i>Update Profile
                        </button>

                        <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-primary-red flex items-center px-3 py-2 rounded-md text-base font-medium">
                            <i class="fas fa-cog mr-2 w-6"></i>Settings
                        </a>

                        <button onclick="logout()" class="text-gray-300 hover:bg-dark-card hover:text-primary-red flex items-center px-3 py-2 rounded-md text-base font-medium w-full text-left">
                            <i class="fas fa-sign-out-alt mr-2 w-6"></i>Log Out
                        </button>
                    </div>
                </div>

                <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-primary-red flex items-center px-3 py-2 rounded-md text-base font-medium">
                    <svg class="h-6 w-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg> Notifications 
                </a>

                <h4 class="text-sm px-3 pt-3 text-gray-500 font-semibold uppercase">Genres (12)</h4>
                <div class="grid grid-cols-2 gap-y-1">
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Action</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Horror</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Comedy</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Sci-Fi</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Drama</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Romance</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Thriller</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Documentary</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Animation</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Fantasy</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Crime</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Mystery</a>
                </div>

                <h4 class="text-sm px-3 pt-3 text-gray-500 font-semibold uppercase">Years</h4>
                <div class="grid grid-cols-2 gap-y-1">
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">2025</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">2024</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">2023</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">2022</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">2021</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">2020</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">2019</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">2018</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Older</a>
                </div>

                <h4 class="text-sm px-3 pt-3 text-gray-500 font-semibold uppercase">Languages</h4>
                <div class="grid grid-cols-2 gap-y-1">
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">English</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Japanese</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Mandarin</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Spanish</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">German</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Hindi</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Korean</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">French</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Sinhala</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Tamil</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Malayalam</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Kannada</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Italian</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Telugu</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Russian</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Arabic</a>
                </div>
            </div>
            <div class="pt-4 pb-3 border-t border-gray-700">
                <div class="px-2 space-y-1">
                    </div>
            </div>
        </div>
        <div class="hidden sm:flex sm:items-center sm:ml-6">
            <div id="search-bar-desktop-wrapper" class="relative">
                <form id="search-bar-desktop" onsubmit="handleSearch(event)" class="search-bar absolute right-0 mt-3 w-80 hidden opacity-0 scale-95 origin-top-right bg-dark-card p-3 rounded-lg shadow-2xl ring-1 ring-primary-red ring-opacity-20 z-20" style="min-width: 300px;">
                    <div class="flex items-center">
                        <input id="search-input-desktop" type="text" placeholder="Search movies, series..." class="w-full bg-dark-bg text-white border border-gray-600 rounded-md py-2 px-3 focus:outline-none focus:border-primary-red" autocomplete="off">
                        <button type="submit" class="ml-2 text-primary-red hover:text-white transition duration-200">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </nav>
    <div id="pro-modal" class="fixed inset-0 bg-black bg-opacity-80 z-[100] hidden flex items-center justify-center p-4 overflow-y-auto" onclick="closeProModal(event)">
        <div class="bg-dark-bg rounded-xl shadow-2xl p-6 md:p-8 w-full max-w-4xl transform transition-all duration-300 scale-100 border border-primary-red/50 max-h-[90vh] flex flex-col" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center border-b border-gray-700 pb-4 mb-4 shrink-0">
                <h2 class="text-3xl font-bold text-white text-glow-red"> Unlock <span class="text-theme-orange">PRO</span> Features </h2>
                <button onclick="closeProModal()" class="text-gray-400 hover:text-primary-red transition duration-200 p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="flex-grow overflow-y-auto pr-2">
                <p class="text-lg text-gray-300 mb-6">Choose the plan that fits your ultimate cinematic experience.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <div class="bg-dark-card p-6 rounded-xl border-2 border-primary-red shadow-2xl shadow-primary-red/20 flex flex-col relative">
                        <div class="absolute -top-3 -right-3 bg-theme-orange text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-lg transform rotate-6">
                            Best Value
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">Yearly</h3>
                        <p class="text-gray-400 mb-4 h-12">Save big with a one-time yearly payment.</p>
                        <div class="text-4xl font-extrabold text-white mb-6">$59.99 <span class="text-base font-normal text-gray-500">/ Year</span></div>
                        
                        <ul class="text-gray-300 space-y-2 mb-8 flex-grow">
                            <li class="flex items-center"><span class="text-primary-red mr-2">•</span> <span class="font-bold">4K Ultra HD</span> Streaming</li>
                            <li class="flex items-center"><span class="text-primary-red mr-2">•</span> 4 simultaneous screens</li>
                            <li class="flex items-center"><span class="text-primary-red mr-2">•</span> <span class="font-bold">Unlimited</span> Offline Downloads</li>
                            <li class="flex items-center"><span class="text-primary-red mr-2">•</span> Priority Support</li>
                        </ul>
                        
                        <button class="mt-auto w-full bg-primary-red text-white font-bold py-3 rounded-full hover:bg-red-600 transition duration-200">
                            Subscribe Yearly
                        </button>
                    </div>

                    <div class="bg-dark-card p-6 rounded-xl border border-gray-600 shadow-md flex flex-col">
                        <h3 class="text-2xl font-bold text-white mb-2">Monthly</h3>
                        <p class="text-gray-400 mb-4 h-12">Flexible, no long-term contract.</p>
                        <div class="text-4xl font-extrabold text-white mb-6">$5.99 <span class="text-base font-normal text-gray-500">/ Month</span></div>
                        <ul class="text-gray-300 space-y-2 mb-8 flex-grow">
                            <li class="flex items-center"><span class="text-primary-red mr-2">•</span> HD Streaming</li>
                            <li class="flex items-center"><span class="text-primary-red mr-2">•</span> 2 simultaneous screens</li>
                            <li class="flex items-center"><span class="text-primary-red mr-2">•</span> Offline Downloads</li>
                            <li class="flex items-center text-gray-500"><span class="text-gray-700 mr-2">•</span> Standard Support</li>
                        </ul>
                        <button class="mt-auto w-full bg-primary-red text-white font-bold py-3 rounded-full hover:bg-red-600 transition duration-200"> Subscribe Monthly </button>
                    </div>

                    <div class="bg-dark-card p-6 rounded-xl border border-gray-600 shadow-md flex flex-col">
                        <h3 class="text-2xl font-bold text-white mb-2">Weekly Pass</h3>
                        <p class="text-gray-400 mb-4 h-12">Perfect for a short binge-watching session.</p>
                        <div class="text-4xl font-extrabold text-white mb-6">$1.99 <span class="text-base font-normal text-gray-500">/ Week</span></div>
                        <ul class="text-gray-300 space-y-2 mb-8 flex-grow">
                            <li class="flex items-center"><span class="text-primary-red mr-2">•</span> HD Streaming</li>
                            <li class="flex items-center"><span class="text-primary-red mr-2">•</span> 1 simultaneous screen</li>
                            <li class="flex items-center text-gray-500"><span class="text-gray-700 mr-2">•</span> No Downloads</li>
                            <li class="flex items-center text-gray-500"><span class="text-gray-700 mr-2">•</span> Standard Support</li>
                        </ul>
                        <button class="mt-auto w-full bg-primary-red text-white font-bold py-3 rounded-full hover:bg-red-600 transition duration-200"> Get Weekly Pass </button>
                    </div>

                </div>
                
                <p class="text-sm text-center text-gray-500 mt-6">All plans include ad-free viewing and access to exclusive content.</p>
            </div>
            
        </div>
    </div>
    <div id="update-profile-modal" class="fixed inset-0 bg-black bg-opacity-80 z-[100] hidden flex items-center justify-center p-4 overflow-y-auto" onclick="closeUpdateProfileModal(event)">
        <div class="modal-content bg-dark-bg rounded-xl shadow-2xl p-6 md:p-8 w-full max-w-lg transform transition-all duration-300 border border-primary-red/50 max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            
            <div class="flex justify-between items-center border-b border-gray-700 pb-4 mb-6 sticky top-0 bg-dark-bg z-10">
                <h2 class="text-3xl font-bold text-white text-glow-red">Update Profile</h2>
                <button onclick="closeUpdateProfileModal()" class="text-gray-400 hover:text-primary-red transition duration-200 p-2 close-btn">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form id="update-profile-form" method="POST" enctype="multipart/form-data">
                <div class="space-y-6">

                    <div class="flex flex-col items-center">
                        <img id="up_image_preview" src="https://via.placeholder.com/100x100/222222/FFFFFF?text=No+Image" alt="Profile Preview" class="image-preview show ring-offset-dark-bg ring-offset-2">
                        <div class="mt-4">
                            <label for="up_profile_image" class="cursor-pointer bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium py-2 px-4 rounded-md transition duration-200">
                                Change Profile Image
                            </label>
                            <input type="file" id="up_profile_image" name="profile_image" accept="image/*" class="hidden" onchange="previewUpdateImage(this)">
                            <input type="hidden" name="current_image" id="up_current_image"> </div>
                        <small class="text-gray-500 mt-2">Max 1MB (JPG, PNG, GIF)</small>
                    </div>

                    <hr class="border-gray-700"> <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1" for="up_first_name">First Name</label>
                            <input id="up_first_name" name="first_name" type="text" placeholder="First Name" class="input-field" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1" for="up_last_name">Last Name</label>
                            <input id="up_last_name" name="last_name" type="text" placeholder="Last Name" class="input-field" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1" for="up_username">Username</label>
                        <input id="up_username" name="username" type="text" placeholder="Username" class="input-field" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1" for="up_email">Email</label>
                        <input id="up_email" name="email" type="email" placeholder="Email Address" class="input-field" required>
                    </div>

                    <hr class="border-gray-700 my-8">
                    
                    <h3 class="text-xl font-semibold text-primary-red mb-4 border-l-4 border-primary-red pl-3">Password Change</h3>
                    
                    <!-- <p class="text-sm text-gray-400 mb-6 bg-dark-card p-3 rounded-md border border-gray-700">
                        Only fill these fields if you want to change your password. <span class="text-primary-red font-semibold">Current Password is required to change it.</span>
                    </p> -->

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1" for="up_current_password">Current Password</label>
                        <input id="up_current_password" name="current_password" type="password" placeholder="Your Current Password" class="input-field">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1" for="up_password">New Password</label>
                            <input id="up_password" name="password" type="password" placeholder="New Password" class="input-field">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1" for="up_confirm_password">Confirm Password</label>
                            <input id="up_confirm_password" name="confirm_password" type="password" placeholder="Confirm New Password" class="input-field">
                        </div>
                    </div>

                    <button id="update-profile-btn" type="submit" class="w-full pro-button-gradient text-white font-bold py-3 rounded-lg shadow-xl shadow-primary-red/20 hover:shadow-primary-red/50 transition duration-300 uppercase tracking-wider mt-8">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="../Site/js/modalHandler.js"></script>
    <script src="../Site/js/registration.js"></script>
</body>
</html>