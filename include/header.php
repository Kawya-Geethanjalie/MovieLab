<header class="sticky top-0 z-50">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Lab</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 for beautiful notifications -->
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
/* Dark & Red Premium Styles */
.modal-glass {
    background: rgba(15, 15, 15, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.input-dark {
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    color: white !important;
    transition: 0.3s;
}

.input-dark:focus {
    border-color: #e50914 !important;
    box-shadow: 0 0 10px rgba(229, 9, 20, 0.3);
}

.red-glow-border {
    border: 2px solid #e50914;
    box-shadow: 0 0 15px rgba(229, 9, 20, 0.4);
}

/* Scrollbar styling for details */
#plan-features::-webkit-scrollbar {
    width: 4px;
}
#plan-features::-webkit-scrollbar-thumb {
    background: #e50914;
    border-radius: 10px;
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
        
        /* Filter dropdown active state */
        .filter-active {
            background-color: #E50914;
            color: white;
        }
        
        .filter-active:hover {
            background-color: #d40813;
        }
        
        /* Search results styles */
        .search-results-container {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .search-result-item:hover {
            background-color: #2d2d2d;
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
        
        // Search and Filter variables
        let currentSearchTerm = '';
        let currentFilters = {
            genre: '',
            year: '',
            language: '',
            type: 'all',
            filter: 'all'
        };

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
                currentSearchTerm = searchTerm;
                // Redirect to search results page with search term
window.location.href = 'index.php?q=' + encodeURIComponent(searchTerm);            }
        }

        // Handle real-time search
        function handleRealTimeSearch() {
            const searchInput = document.getElementById('search-input');
            const searchTerm = searchInput.value.trim();
            
            if (searchTerm.length >= 2) {
                // Perform AJAX search
                performSearch(searchTerm);
            } else {
                hideSearchResults();
            }
        }

        // Perform AJAX search
        function performSearch(searchTerm) {
            // You can implement AJAX search here
            // For now, we'll just redirect to search page
            // fetch('../library/search.php?q=' + encodeURIComponent(searchTerm))
            // .then(response => response.json())
            // .then(data => {
            //     displaySearchResults(data);
            // });
        }

        // Display search results
        function displaySearchResults(results) {
            // Implement search results display
        }

        // Hide search results
        function hideSearchResults() {
            // Implement hide search results
        }

        // Apply filter from dropdown
        function applyFilter(filterType, value) {
            // Update current filters
            currentFilters[filterType] = value;
            
            // Build URL with filters
            let url = '../Site/index.php?';
            let params = [];
            
            // Add active filters to URL
            if (currentFilters.genre) params.push('genre=' + encodeURIComponent(currentFilters.genre));
            if (currentFilters.year) params.push('year=' + encodeURIComponent(currentFilters.year));
            if (currentFilters.language) params.push('language=' + encodeURIComponent(currentFilters.language));
            if (currentFilters.type !== 'all') params.push('type=' + currentFilters.type);
            if (currentFilters.filter !== 'all') params.push('filter=' + currentFilters.filter);
            
            // Redirect to filtered page
            if (params.length > 0) {
                url += params.join('&');
            }
            
            window.location.href = url;
            
            // Close dropdown
            toggleDropdown(filterType + 's-dropdown');
        }

        // Apply content type filter
        function applyContentType(type) {
            currentFilters.type = type;
            applyFilter('type', type);
        }

        // Apply sort filter
        function applySortFilter(filter) {
            currentFilters.filter = filter;
            applyFilter('filter', filter);
        }

        // Clear all filters
        function clearFilters() {
            // Reset all filters
            currentFilters = {
                genre: '',
                year: '',
                language: '',
                type: 'all',
                filter: 'all'
            };
            
            // Redirect to index page without filters
            window.location.href = '../Site/index.php';
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
        // 1. පරිශීලකයාට පිංතූරයක් තිබේදැයි පරීක්ෂා කිරීම (null හෝ empty නොවිය යුතුයි)
        if (currentUser.profile_image && currentUser.profile_image !== 'null' && currentUser.profile_image !== '') {
            profileImg.src = '../uploads/profile_images/' + currentUser.profile_image;
        } else {
            // 2. පිංතූරයක් නැතිනම් නමේ මුල් අකුර සහිත රූපයක් සාදා පෙන්වීම
            const name = currentUser.first_name || "User";
            
            // UI Avatars API එක භාවිතා කර රතු පැහැති පසුබිමක සුදු අකුරින් රූපය සාදයි
            // background=E50914 යනු ඔබගේ සයිට් එකේ තේමා වර්ණයයි (Red)
            profileImg.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=E50914&color=fff&rounded=true&bold=true`;
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

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            checkUserSession();
            
            // Get current URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            
            // Update current filters from URL
            currentFilters.genre = urlParams.get('genre') || '';
            currentFilters.year = urlParams.get('year') || '';
            currentFilters.language = urlParams.get('language') || '';
            currentFilters.type = urlParams.get('type') || 'all';
            currentFilters.filter = urlParams.get('filter') || 'all';
        });
    </script>
</head>
<body class="min-h-screen">

    <!-- NAVIGATION BAR START -->
    <nav class="bg-dark-bg shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">

                <!-- 1. Logo and Main Links (Left Side) -->
                <div class="flex-shrink-0 flex items-center">
                         <div class=" top-8 left-8  items-center gap-2 ms-8">
                            <a href="../Site/index.php" class="text-glow-red">
                         <i class="fas fa-film text-red-600 text-3xl" > </i>
                         </a>
            
                    <a  href="../Site/index.php"class="text-3xl font-extrabold text-primary-red tracking-wider cursor-pointer text-glow-red mr-6">
                        Movie Lab 
                      </a></div>
                      
                    <!-- Primary Desktop Links (Home + Dropdowns) -->
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-4 lg:space-x-6 items-center">
                       
                        

                        <!-- Dropdown: Movies -->
                        <div class="relative">
                            <button onclick="toggleDropdown('movies-dropdown')" class="nav-dropdown-btn inline-flex items-center px-1 pt-1 text-sm font-medium text-white transition duration-300 focus:outline-none">
                                <span class="nav-link-underline">Movies</span>
                                <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div id="movies-dropdown" class="absolute hidden mt-3 w-48 rounded-lg shadow-2xl bg-dark-card ring-1 ring-primary-red ring-opacity-20 z-20">
                                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="movies-menu-button">
                                    <a href="javascript:void(0);" onclick="applyContentType('movies')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">All Movies</a>
                                    <a href="javascript:void(0);" onclick="applySortFilter('popular')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">Popular Movies</a>
                                    <a href="javascript:void(0);" onclick="applySortFilter('new')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">New Movies</a>
                                    <a href="javascript:void(0);" onclick="applySortFilter('top_rated')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">Top Rated Movies</a>
                                </div>
                            </div>
                        </div>

                        <!-- Dropdown: Songs -->
                        <div class="relative">
                            <button onclick="toggleDropdown('songs-dropdown')" class="nav-dropdown-btn inline-flex items-center px-1 pt-1 text-sm font-medium text-white transition duration-300 focus:outline-none">
                                <span class="nav-link-underline">Songs</span>
                                <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div id="songs-dropdown" class="absolute hidden mt-3 w-48 rounded-lg shadow-2xl bg-dark-card ring-1 ring-primary-red ring-opacity-20 z-20">
                                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="songs-menu-button">
                                    <a href="javascript:void(0);" onclick="applyContentType('songs')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">All Songs</a>
                                    <a href="javascript:void(0);" onclick="applySortFilter('new')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">New Releases</a>
                                    <a href="javascript:void(0);" onclick="applySortFilter('top')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">Top Charts</a>
                                </div>
                            </div>
                        </div>

                        
                    </div>
                </div>

                <!-- 2. Right Side: Genres, Years, Languages, Search, Notifications, PRO, Sign In -->
                <div class="flex items-center">
                    <!-- Genres, Years, and Languages are now grouped together with minimal space -->
                    <div class="hidden sm:flex items-center space-x-0 lg:space-x-0">
                        <!-- Dropdown: Genres -->
                        <div class="relative">
                            <button onclick="toggleDropdown('genres-dropdown')" class="nav-dropdown-btn inline-flex items-center px-3 pt-1 text-sm font-medium text-white transition duration-300 focus:outline-none">
                                <span class="nav-link-underline">Genres</span>
                                <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div id="genres-dropdown" class="absolute hidden mt-3 w-72 rounded-lg shadow-2xl bg-dark-card ring-1 ring-primary-red ring-opacity-20 z-20 -right-24">
                                <div class="p-2 grid grid-cols-2 gap-x-4 gap-y-2" role="menu" aria-orientation="vertical" aria-labelledby="genres-menu-button">
                                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'Action')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem"><i class="fas fa-bolt"></i> Action</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'Adventure')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem"><i class="fas fa-compass"></i> Adventure</a>

                                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'Horror')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem"><i class="fas fa-skull"></i> Horror</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'Comedy')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem"><i class="fas fa-face-grin-tears"></i> Comedy</a>
                                     <a href="javascript:void(0);" onclick="applyFilter('genre', 'Crime')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem"><i class="fas fa-handcuffs"></i> Crime</a>

                                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'Sci-Fi')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem"><i class="fas fa-rocket"></i> Sci-Fi</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'History')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem"><i class="fas fa-monument"></i> History</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'Romance')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem"><i class="fas fa-heart"></i> Romance</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'Thriller')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem"><i class="fas fa-user-secret"></i> Thriller</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'Musical')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem"><i class="fas fa-guitar"></i> Musical</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'Animation')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem"><i class="fas fa-fire"></i> Animation</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'Fantasy')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem"><i class="fas fa-wand-sparkles"></i> Fantasy</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'Mystery')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem"><i class="fas fa-magnifying-glass"></i> Mystery</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'War')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem"><i class="fas fa-gun"></i> War</a>

                                </div>
                            </div>
                        </div>

                        <!-- Dropdown: Years -->
                        <div class="relative">
                            <button onclick="toggleDropdown('years-dropdown')" class="nav-dropdown-btn inline-flex items-center px-3 pt-1 text-sm font-medium text-white transition duration-300 focus:outline-none">
                                <span class="nav-link-underline">Years</span>
                                <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div id="years-dropdown" class="absolute hidden mt-3 w-48 rounded-lg shadow-2xl bg-dark-card ring-1 ring-primary-red ring-opacity-20 z-20 -right-8">
                                <div class="py-1 grid grid-cols-3 gap-x-1 gap-y-1" role="menu" aria-orientation="vertical" aria-labelledby="years-menu-button">
                                 <a href="javascript:void(0);" onclick="applyFilter('year', '2026')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2026</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('year', '2025')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2025</a>

                                    <a href="javascript:void(0);" onclick="applyFilter('year', '2024')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2024</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('year', '2023')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2023</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('year', '2022')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2022</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('year', '2021')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2021</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('year', '2020')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2020</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('year', '2019')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2019</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('year', '2018')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2018</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('year', '2017')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2017</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('year', '2016')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2016</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('year', '2015')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2015</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('year', '2014')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2014</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('year', '2013')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2013</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('year', '2012')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2012</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('year', '2011')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2011</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('year', '2010')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2010</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('year', 'older')" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">Older</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Dropdown: Languages -->
                        <div class="relative">
                            <button onclick="toggleDropdown('languages-dropdown')" class="nav-dropdown-btn inline-flex items-center px-3 pt-1 text-sm font-medium text-white transition duration-300 focus:outline-none">
                                <span class="nav-link-underline">Languages</span>
                                <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div id="languages-dropdown" class="absolute hidden mt-3 w-72 rounded-lg shadow-2xl bg-dark-card ring-1 ring-primary-red ring-opacity-20 z-20 -right-16">
                                <div class="p-2 grid grid-cols-2 gap-x-4 gap-y-1" role="menu" aria-orientation="vertical" aria-labelledby="languages-menu-button">
                                    <!-- <a href="javascript:void(0);" onclick="applyFilter('language', 'All')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">All</a> -->
                                    <a href="javascript:void(0);" onclick="applyFilter('language', 'English')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">English</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('language', 'Hindi')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Hindi</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('language', 'Korean')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Korean</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('language', 'French')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">French</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('language', 'Sinhala')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Sinhala</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('language', 'Tamil')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Tamil</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('language', 'Malayalam')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Malayalam</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('language', 'Kannada')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Kannada</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('language', 'Italian')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Italian</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('language', 'Telugu')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Telugu</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('language', 'Russian')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Russian</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('language', 'Arabic')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Arabic</a>
                                    <a href="javascript:void(0);" onclick="applyFilter('language', 'Turkish')" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Turkish</a>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <!-- Search, Notifications, PRO, and Sign In -->
                    <div class="flex items-center space-x-4 lg:space-x-6 ml-4">
                        
                        <!-- Search Button -->
                        <button type="button" onclick="toggleSearchBar()" class="p-2 text-gray-400 hover:text-white transition duration-300 focus:outline-none rounded-full hover:bg-dark-card hidden sm:block">
                            <i class="fas fa-search"></i>
                        </button>
                        
                        <!-- Bell/Notifications Button -->
                        <button type="button" class="p-2 text-gray-400 hover:text-primary-red transition duration-300 focus:outline-none rounded-full hover:bg-dark-card hidden sm:block">
                            <i class="fas fa-bell"></i>
                        </button>
                        

                     <!-- Sign In Link -->
                    <button id="sign-in-btn" onclick="openLoginModal()" class="inline-flex items-center px-2 py-1.5 text-sm font-medium text-white rounded-md transition duration-300 hover:bg-red-600 hover:shadow-lg hover:shadow-primary-red/50 border-2 border-red-600">
                        Sign In
                    </button>


                    <!-- User Profile Section (Hidden by default) -->
                    <div id="user-profile-section" class="relative hidden items-center space-x-3">
                        <button onclick="toggleProfileDropdown()" class="flex items-center space-x-2 text-white hover:text-primary-red transition duration-300">
                            <img id="user-profile-img" src="https://via.placeholder.com/40x40/E50914/FFFFFF?text=U" alt="Profile" class="profile-image">
                            <span id="user-name-display" class="hidden sm:inline text-sm font-medium">User</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        
                        <!-- Profile Dropdown -->
                        <div id="profile-dropdown" class="profile-dropdown absolute right-0 top-12 w-48 bg-dark-card rounded-lg shadow-xl border border-primary-red/20 py-2 ">
                          <button onclick="UpdateProfileUser()" class="w-full text-left px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150">
    <i class="fas fa-user-edit mr-2"></i>Update Details
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

                        <!-- NEW PRO BUTTON (Highlighted, large, on the right, hidden on mobile) -->
                        <button onclick="openProModal()" class="pro-button-gradient px-4 py-2 text-sm font-bold text-white rounded-md transition duration-300 shadow-md shadow-theme-orange/50 uppercase tracking-widest hidden sm:inline-flex">
                            PRO
                        </button>
                    </div>
                    
                    <!-- 3. Mobile Menu Button -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button onclick="toggleMenu()" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-dark-card focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-red transition duration-300" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <!-- Hamburger icon -->
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEARCH BAR (Hidden by default) -->
        <div id="search-bar" class="hidden opacity-0 scale-95 search-bar absolute top-16 left-0 right-0 bg-dark-card p-4 shadow-lg z-40 border-t border-primary-red/20">
            <div class="max-w-7xl mx-auto">
                <form onsubmit="handleSearch(event)" class="flex items-center">
                    <div class="relative flex-grow">
                        <input 
                            id="search-input"
                            type="text" 
                            placeholder="Search for movies, TV series, songs..." 
                            class="w-full bg-dark-bg text-white placeholder-gray-500 rounded-full py-3 pl-12 pr-4 focus:outline-none focus:ring-2 focus:ring-primary-red border border-gray-700"
                            oninput="handleRealTimeSearch()"
                        >
                        <i class="fas fa-search absolute left-4 top-3 h-5 w-5 text-gray-500"></i>
                    </div>
                    <button type="submit" class="ml-4 bg-primary-red text-white font-medium py-3 px-6 rounded-full hover:bg-red-600 transition duration-200">
                        Search
                    </button>
                </form>
                
                <!-- Search Results Container -->
                <div id="search-results" class="search-results-container mt-4 bg-dark-bg rounded-lg p-4 hidden">
                    <div class="text-gray-400 text-sm mb-2">Search results will appear here...</div>
                </div>
            </div>
        </div>

        <!-- 4. Mobile Menu (Flattened Navigation) -->
        <div class="sm:hidden hidden" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1 bg-dark-bg border-t border-primary-red/10 max-h-[calc(100vh-4rem)] overflow-y-auto">
                <!-- PRO Button for Mobile -->
                <button onclick="openProModal()" class="pro-button-mobile w-full px-3 py-2 text-base font-bold text-white rounded-md transition duration-300 hover:bg-red-700 hover:shadow-lg uppercase tracking-widest sm:hidden">
                    GET PRO ACCESS
                </button>
                
                <a href="../Site/index.php" class="bg-dark-card text-white block px-3 py-2 rounded-md text-base font-medium">Home</a>
                
                <!-- Sign In Mobile Link -->
                <button id="mobile-sign-in-btn" onclick="openLoginModal()"
                    class="px-4 py-1.5 text-sm text-white hover:bg-red-600 rounded-md">
                    Sign In
                </button>

                <!-- Mobile Filter Links -->
                <div class="px-3 pt-3">
                    <h4 class="text-sm text-gray-500 font-semibold uppercase mb-2">Content Type</h4>
                    <div class="flex flex-wrap gap-1">
                        <button onclick="applyContentType('all')" class="px-3 py-1 text-sm bg-dark-card text-gray-300 rounded hover:bg-primary-red hover:text-white">All</button>
                        <button onclick="applyContentType('movies')" class="px-3 py-1 text-sm bg-dark-card text-gray-300 rounded hover:bg-primary-red hover:text-white">Movies</button>
                        <button onclick="applyContentType('songs')" class="px-3 py-1 text-sm bg-dark-card text-gray-300 rounded hover:bg-primary-red hover:text-white">Songs</button>
                    </div>
                </div>
                
                <!-- Mobile Sort Filters -->
                <div class="px-3 pt-3">
                    <h4 class="text-sm text-gray-500 font-semibold uppercase mb-2">Sort By</h4>
                    <div class="flex flex-wrap gap-1">
                        <button onclick="applySortFilter('popular')" class="px-3 py-1 text-sm bg-dark-card text-gray-300 rounded hover:bg-primary-red hover:text-white">Popular</button>
                        <button onclick="applySortFilter('new')" class="px-3 py-1 text-sm bg-dark-card text-gray-300 rounded hover:bg-primary-red hover:text-white">New</button>
                        <button onclick="applySortFilter('top_rated')" class="px-3 py-1 text-sm bg-dark-card text-gray-300 rounded hover:bg-primary-red hover:text-white">Top Rated</button>
                    </div>
                </div>
                
                <!-- Clear Filters Button -->
                <div class="px-3 pt-3">
                    <button onclick="clearFilters()" class="w-full px-3 py-2 text-sm bg-red-600 text-white rounded hover:bg-red-700">
                        <i class="fas fa-times mr-2"></i>Clear All Filters
                    </button>
                </div>

                <!-- Notifications Mobile Link -->
                <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-primary-red flex items-center px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-bell mr-2"></i>
                    Notifications
                </a>
                
                <!-- Genres (Mobile Section) -->
                <h4 class="text-sm px-3 pt-3 text-gray-500 font-semibold uppercase">Genres (12)</h4>
                <div class="grid grid-cols-2 gap-y-1">
                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'Action')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Action</a>
                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'Horror')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Horror</a>
                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'Comedy')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Comedy</a>
                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'Sci-Fi')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Sci-Fi</a>
                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'Drama')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Drama</a>
                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'Romance')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Romance</a>
                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'Thriller')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Thriller</a>
                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'Documentary')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Documentary</a>
                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'Animation')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Animation</a>
                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'Fantasy')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Fantasy</a>
                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'Crime')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Crime</a>
                    <a href="javascript:void(0);" onclick="applyFilter('genre', 'Mystery')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Mystery</a>
                </div>

                <!-- Languages (Mobile Section) -->
                <h4 class="text-sm px-3 pt-3 text-gray-500 font-semibold uppercase">Languages</h4>
                <div class="grid grid-cols-2 gap-y-1">
                    <a href="javascript:void(0);" onclick="applyFilter('language', 'English')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">English</a>
                    <a href="javascript:void(0);" onclick="applyFilter('language', 'French')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">French</a>
                    <a href="javascript:void(0);" onclick="applyFilter('language', 'Sinhala')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Sinhala</a>
                    <a href="javascript:void(0);" onclick="applyFilter('language', 'Tamil')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Tamil</a>
                    <a href="javascript:void(0);" onclick="applyFilter('language', 'Italian')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Italian</a>
                    <a href="javascript:void(0);" onclick="applyFilter('language', 'Hindi')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Hindi</a>
                    <a href="javascript:void(0);" onclick="applyFilter('language', 'Telugu')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Telugu</a>
                    <a href="javascript:void(0);" onclick="applyFilter('language', 'Russian')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Russian</a>
                </div>

                <!-- Years (Common Filter) -->
                <h4 class="text-sm px-3 pt-3 text-gray-500 font-semibold uppercase">Filter by Year</h4>
                <div class="grid grid-cols-2 gap-y-1">
                    <a href="javascript:void(0);" onclick="applyFilter('year', '2024')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">2024</a>
                    <a href="javascript:void(0);" onclick="applyFilter('year', '2023')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">2023</a>
                    <a href="javascript:void(0);" onclick="applyFilter('year', '2022')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">2022</a>
                    <a href="javascript:void(0);" onclick="applyFilter('year', '2021')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">2021</a>
                    <a href="javascript:void(0);" onclick="applyFilter('year', '2020')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">2020</a>
                    <a href="javascript:void(0);" onclick="applyFilter('year', '2019')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">2019</a>
                    <a href="javascript:void(0);" onclick="applyFilter('year', '2018')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">2018</a>
                    <a href="javascript:void(0);" onclick="applyFilter('year', 'older')" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Older</a>
                </div>

                <!-- Mobile Search -->
                <div class="mt-4 px-3">
    <form onsubmit="handleMobileSearch(event)" class="relative">
        <input type="text" 
               id="search-input-mobile" 
               placeholder="Search..." 
               class="w-full bg-dark-card text-white placeholder-gray-500 rounded-md py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-primary-red"
        >
        <button type="submit" class="absolute left-3 top-2.5">
            <i class="fas fa-search h-5 w-5 text-gray-500"></i>
        </button>
    </form>
</div>
            </div>
        </div>
    </nav>
    <!-- NAVIGATION BAR END -->

    <!-- PRO SUBSCRIPTION MODAL START (hidden by default) -->
    <div id="pro-modal" class="fixed inset-0 bg-black bg-opacity-80 z-[100] hidden flex items-center justify-center p-4 overflow-y-auto" onclick="closeProModal(event)">
        
        <!-- Modal Content Container -->
        <div class="bg-dark-bg rounded-xl shadow-2xl p-6 md:p-8 w-full max-w-4xl transform transition-all duration-300 scale-100 border border-primary-red/50 max-h-[90vh] flex flex-col" onclick="event.stopPropagation()">
            
            <!-- Header and Close Button -->
            <div class="flex justify-between items-center border-b border-gray-700 pb-4 mb-4 shrink-0">
                <h2 class="text-3xl font-bold text-white text-glow-red">
                    Unlock <span class="text-theme-orange">PRO</span> Features
                </h2>
                <!-- Close Button -->
                <button onclick="closeProModal()" class="text-gray-400 hover:text-primary-red transition duration-200 p-2">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Scrollable Content Wrapper -->
            <div class="overflow-y-auto flex-grow">
                <!-- Pricing Tiers (Early, Monthly, Weekly) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- 1. Early Tier (Annual Plan) -->
                    <div class="bg-dark-card p-6 rounded-xl border-2 border-theme-orange/80 shadow-lg relative overflow-hidden flex flex-col">
                        <!-- Updated Tag to be Red/White -->
                        <div class="absolute top-0 right-0 bg-primary-red text-white text-xs font-bold py-1 px-4 rounded-bl-lg">BEST VALUE</div>
                        <h3 class="text-2xl font-bold text-theme-orange mb-2">Early Access</h3>
                        <p class="text-gray-400 mb-4 h-12">Limited time offer for long-term commitment.</p>
                        <div class="text-4xl font-extrabold text-white mb-6">$49.99 <span class="text-base font-normal text-gray-500">/ Year</span></div>
                        <ul class="text-gray-300 space-y-2 mb-8 flex-grow">
                            <li class="flex items-center"><span class="text-theme-orange mr-2">•</span> 4K Ultra HD Streaming</li>
                            <li class="flex items-center"><span class="text-theme-orange mr-2">•</span> 5 simultaneous screens</li>
                            <li class="flex items-center"><span class="text-theme-orange mr-2">•</span> Offline Downloads</li>
                            <li class="flex items-center"><span class="text-theme-orange mr-2">•</span> Priority Support</li>
                        </ul>
                     <button onclick="openCheckout('Early Access Plan', 49.99, 'Annual commitment with 4K streaming.')" 
    class="pro-button-gradient mt-auto w-full text-white font-bold py-3 rounded-full shadow-lg shadow-theme-orange/40 hover:shadow-theme-orange/80 transform hover:scale-[1.02]">
    Get Early Plan
</button>
                    </div>

                    <!-- 2. Monthly Tier -->
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
                       <!-- Monthly Plan button -->
<button onclick="openCheckout('Monthly Plan', 5.99, 'Flexible monthly billing, HD streaming.')" 
    class="mt-auto w-full bg-primary-red text-white font-bold py-3 rounded-full hover:bg-red-600 transition duration-200">
    Subscribe Monthly
</button>

                    </div>

                    <!-- 3. Weekly Tier (Shortest option) -->
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
                        <button onclick="openCheckout('Weekly Pass', 1.99, 'One week access, perfect for binging.')" 
    class="mt-auto w-full bg-primary-red text-white font-bold py-3 rounded-full hover:bg-red-600 transition duration-200">
    Get Weekly Pass
</button>
                    </div>
                </div>
                
                <!-- Adding some padding below the cards for better scroll margin on mobile -->
                <div class="h-4 md:hidden"></div> 
            </div>
        </div>
    </div>
    <!-- COMPACT PAYMENT MODAL -->
<div id="payment-modal" class="fixed inset-0 bg-black/95 hidden z-[999] flex items-center justify-center p-0 sm:p-4">
    <div class="bg-dark-card w-full h-full sm:h-auto sm:max-w-md sm:rounded-xl border-0 sm:border border-primary-red/20 shadow-xl" onclick="event.stopPropagation()">
        
        <!-- Mobile Header (Bottom Sheet Style) -->
        <div class="sm:hidden bg-gradient-to-r from-primary-red to-red-700 px-4 py-3 flex justify-between items-center sticky top-0">
            <h3 class="text-base font-bold text-white">
                <i class="fas fa-credit-card mr-2"></i>Payment
            </h3>
            <button onclick="closePaymentModal()" class="text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Desktop Header -->
      <div class="hidden sm:block bg-gradient-to-r from-primary-red to-red-700 px-4 py-2 rounded-t-xl flex justify-between items-center">
    <div class="flex items-center justify-between w-full">
        <h3 class="text-lg font-bold text-white">
            <i class="fas fa-credit-card mr-2"></i>Complete Payment
        </h3>
        
        <button 
            onclick="closePaymentModal()" 
            class="text-white hover:text-gray-200 flex items-center justify-center rounded"
        >
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
        
        <!-- Content - Scrollable on Mobile -->
        <div class="p-4 sm:p-5 h-[calc(100vh-56px)] sm:h-auto overflow-y-auto">
            <!-- Plan Summary -->
            <div class="mb-4 p-3 bg-dark-bg/50 rounded-lg border border-gray-700">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h4 id="payment-plan-name" class="font-bold text-white text-sm sm:text-base">Monthly Plan</h4>
                        <p id="payment-plan-desc" class="text-gray-400 text-xs mt-1">Flexible monthly billing, HD streaming.</p>
                    </div>
                    <span id="payment-plan-price" class="text-primary-red font-bold text-base sm:text-lg ml-3">$5.99</span>
                </div>
            </div>
            
            <!-- Payment Method Selection -->
            <div class="mb-5">
                <h4 class="text-white font-medium mb-3 text-sm">Choose Payment Method:</h4>
                
                <!-- Radio Buttons for Payment Methods -->
                <div class="space-y-2">
                    <!-- PayHere Option -->
                    <label class="flex items-center p-3 bg-dark-bg/30 rounded-lg border border-gray-700 hover:border-primary-red/50 cursor-pointer transition">
                        <input type="radio" name="paymentMethod" value="payhere" checked class="mr-3 h-4 w-4 text-primary-red">
                        <div class="flex items-center gap-2">
                            <i class="fab fa-cc-paypal text-yellow-500"></i>
                            <span class="text-white font-medium text-sm">Pay with PayHere</span>
                        </div>
                    </label>
                    
                    <!-- Credit Card Option -->
                    <label class="flex items-center p-3 bg-dark-bg/30 rounded-lg border border-gray-700 hover:border-primary-red/50 cursor-pointer transition">
                        <input type="radio" name="paymentMethod" value="card" class="mr-3 h-4 w-4 text-primary-red">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-credit-card text-gray-300"></i>
                            <span class="text-white font-medium text-sm">Credit/Debit Card</span>
                        </div>
                    </label>
                </div>
                
                <!-- Other Options -->
                <div class="mt-4 flex gap-2">
                    <button onclick="selectPayment('paypal')" class="flex-1 bg-gray-800 hover:bg-gray-700 text-white py-2 px-3 rounded text-xs flex items-center justify-center gap-1">
                        <i class="fab fa-paypal text-blue-400"></i>
                        PayPal
                    </button>
                    <button onclick="selectPayment('stripe')" class="flex-1 bg-gray-800 hover:bg-gray-700 text-white py-2 px-3 rounded text-xs flex items-center justify-center gap-1">
                        <i class="fab fa-stripe text-purple-400"></i>
                        Stripe
                    </button>
                </div>
            </div>
            
            <!-- Card Form (Initially Hidden) -->
            <div id="card-form" class="hidden">
                <div class="pt-4 border-t border-gray-800">
                    <h4 class="text-white font-medium mb-3 text-sm">Enter Card Details</h4>
                    
                    <div class="space-y-3">
                        <!-- Card Number -->
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Card Number</label>
                            <input type="text" placeholder="1234 5678 9012 3456" 
                                   class="w-full bg-dark-bg border border-gray-700 rounded px-3 py-2 text-white text-sm focus:border-primary-red outline-none">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Expiry Date -->
                            <div>
                                <label class="block text-xs text-gray-400 mb-1">Expiry Date</label>
                                <input type="text" placeholder="MM/YY" 
                                       class="w-full bg-dark-bg border border-gray-700 rounded px-3 py-2 text-white text-sm focus:border-primary-red outline-none">
                            </div>
                            
                            <!-- CVV -->
                            <div>
                                <label class="block text-xs text-gray-400 mb-1">CVV</label>
                                <input type="text" placeholder="123" 
                                       class="w-full bg-dark-bg border border-gray-700 rounded px-3 py-2 text-white text-sm focus:border-primary-red outline-none">
                            </div>
                        </div>
                        
                        <!-- Cardholder Name -->
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Cardholder Name</label>
                            <input type="text" placeholder="John Doe" 
                                   class="w-full bg-dark-bg border border-gray-700 rounded px-3 py-2 text-white text-sm focus:border-primary-red outline-none">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pay Button -->
            <button onclick="processPayment()" 
                    class="w-full bg-gradient-to-r from-primary-red to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold py-3 rounded-lg transition-all duration-200 mt-5 flex items-center justify-center gap-2">
                <i class="fas fa-lock"></i>
                Complete Payment
            </button>
            
            <!-- Security Note -->
            <div class="mt-4 pt-3 border-t border-gray-800">
                <div class="flex items-center justify-center text-xs text-gray-500">
                    <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                    <span>Secure 256-bit SSL encryption</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SUCCESS MODAL -->
<div id="payment-success-modal" class="fixed inset-0 bg-black/95 hidden z-[1000] flex items-center justify-center p-4">
    <div class="bg-dark-card w-full max-w-xs rounded-xl border border-green-500/20 p-5 text-center" onclick="event.stopPropagation()">
        <div class="w-12 h-12 bg-green-900/20 text-green-500 rounded-full flex items-center justify-center text-2xl mx-auto mb-3">
            <i class="fas fa-check"></i>
        </div>
        
        <h3 class="text-lg font-bold text-white mb-2">Payment Successful!</h3>
        <p class="text-gray-300 text-sm mb-4" id="success-message">Your subscription is now active.</p>
        
        <button onclick="closeSuccessModal()" 
                class="w-full bg-primary-red hover:bg-red-600 text-white font-semibold py-2.5 rounded-lg transition text-sm">
            Continue
        </button>
    </div>
</div>

<script>
// Payment variables
let selectedPlan = {
    name: 'Monthly Plan',
    price: 5.99,
    description: 'Flexible monthly billing, HD streaming.'
};

let selectedPaymentMethod = 'payhere';

// Open Payment Modal
function openCheckout(planName, price, description) {
    selectedPlan = { name: planName, price: price, description: description };
    
    // Update modal content
    document.getElementById('payment-plan-name').textContent = planName;
    document.getElementById('payment-plan-price').textContent = `$${price}`;
    document.getElementById('payment-plan-desc').textContent = description;
    
    // Reset form
    hideCardForm();
    document.querySelector('input[name="paymentMethod"][value="payhere"]').checked = true;
    selectedPaymentMethod = 'payhere';
    
    // Show modal
    document.getElementById('payment-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Close other modals
    if (document.getElementById('pro-modal')) {
        document.getElementById('pro-modal').classList.add('hidden');
    }
}

// Close Payment Modal
function closePaymentModal() {
    document.getElementById('payment-modal').classList.add('hidden');
    document.body.style.overflow = '';
    hideCardForm();
}

// Show/Hide Card Form
function showCardForm() {
    document.getElementById('card-form').classList.remove('hidden');
    selectedPaymentMethod = 'card';
    // Scroll to card form on mobile
    if (window.innerWidth < 640) {
        setTimeout(() => {
            document.getElementById('card-form').scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
        }, 100);
    }
}

function hideCardForm() {
    document.getElementById('card-form').classList.add('hidden');
}

// Radio Button Change Handler
document.addEventListener('DOMContentLoaded', function() {
    const radios = document.querySelectorAll('input[name="paymentMethod"]');
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            selectedPaymentMethod = this.value;
            if (this.value === 'card') {
                showCardForm();
            } else {
                hideCardForm();
            }
        });
    });
});

// Select Other Payment
function selectPayment(method) {
    selectedPaymentMethod = method;
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;
    
    // Show loading
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>';
    btn.disabled = true;
    
    setTimeout(() => {
        // Process payment
        processPayment();
        btn.innerHTML = originalText;
        btn.disabled = false;
    }, 1000);
}

// Process Payment
function processPayment() {
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;
    
    // Show loading
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
    btn.disabled = true;
    
    // Simulate payment
    setTimeout(() => {
        closePaymentModal();
        showSuccessModal();
        updateUserSubscription();
        
        // Reset button
        btn.innerHTML = originalText;
        btn.disabled = false;
    }, 1500);
}

// Show Success Modal
function showSuccessModal() {
    document.getElementById('success-message').textContent = 
        `Your ${selectedPlan.name} subscription is now active.`;
    document.getElementById('payment-success-modal').classList.remove('hidden');
}

// Close Success Modal
function closeSuccessModal() {
    document.getElementById('payment-success-modal').classList.add('hidden');
    window.location.reload();
}

// Update User Subscription
function updateUserSubscription() {
    console.log('Updating subscription:', selectedPlan);
    // Add your API call here
}

// Close Modal on Outside Click
document.addEventListener('click', function(event) {
    const paymentModal = document.getElementById('payment-modal');
    const successModal = document.getElementById('payment-success-modal');
    
    if (paymentModal && !paymentModal.classList.contains('hidden') && 
        !paymentModal.contains(event.target)) {
        closePaymentModal();
    }
    
    if (successModal && !successModal.classList.contains('hidden') && 
        !successModal.contains(event.target)) {
        closeSuccessModal();
    }
});

// ESC Key to Close
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closePaymentModal();
        closeSuccessModal();
    }
});
</script>

<style>
/* Payment Modal Styles */
#payment-modal {
    z-index: 999 !important;
}

#payment-success-modal {
    z-index: 1000 !important;
}

/* Mobile Bottom Sheet Effect */
@media (max-width: 640px) {
    #payment-modal {
        align-items: flex-end !important;
        padding: 0 !important;
    }
    
    #payment-modal > div {
        border-radius: 16px 16px 0 0 !important;
        max-height: 85vh !important;
        height: auto !important;
        animation: slideUp 0.3s ease-out !important;
    }
    
    #payment-modal .h-\[calc\(100vh-56px\)\] {
        height: calc(85vh - 56px) !important;
    }
    
    /* Prevent body scroll when modal is open */
    body.modal-open {
        overflow: hidden !important;
        position: fixed !important;
        width: 100% !important;
        height: 100% !important;
    }
}

/* Animations */
@keyframes slideUp {
    from { transform: translateY(100%); }
    to { transform: translateY(0); }
}

@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}

#payment-modal > div {
    animation: fadeIn 0.3s ease-out;
}

/* Custom Scrollbar */
#payment-modal > div > div:last-child::-webkit-scrollbar {
    width: 4px;
}

#payment-modal > div > div:last-child::-webkit-scrollbar-track {
    background: #1a1a1a;
}

#payment-modal > div > div:last-child::-webkit-scrollbar-thumb {
    background: #e50914;
    border-radius: 10px;
}
</style>
    <!-- PRO SUBSCRIPTION MODAL END -->

    <div id="update-profile-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-black/85 backdrop-blur-sm" onclick="closeUpdateModal()"></div>
    
    <div class="flex items-center justify-center min-h-screen p-2 sm:p-4 relative z-[101]">
        
        <div class="bg-dark-card w-full max-w-xl rounded-xl shadow-2xl border border-primary-red/20 flex flex-col max-h-[95vh]">
            
            <div class="bg-primary-red p-3 flex justify-between items-center shrink-0">
                <h3 class="text-lg font-bold text-white"><i class="fas fa-user-cog mr-2"></i>Update User Details</h3>
                <button onclick="closeUpdateModal()" class="text-white/80 hover:text-white transition"><i class="fas fa-times"></i></button>
            </div>

            <div class="overflow-y-auto p-4 sm:p-6 custom-scrollbar">
                <form id="update-details-form" class="space-y-4" enctype="multipart/form-data">
                    <input type="hidden" id="up-user-id" name="user_id">
                    
                    <div class="flex flex-col items-center pb-2">
                        <div class="relative">
                            <img id="modal-preview-img" src="../uploads/profile_images/default.png" 
                                 class="w-20 h-20 rounded-full border-2 border-primary-red object-cover shadow-md">
                            <label for="profile_image" class="absolute bottom-0 right-0 bg-primary-red text-white p-1.5 rounded-full cursor-pointer hover:bg-red-700 transition">
                                <i class="fas fa-camera text-[10px]"></i>
                                <input type="file" id="profile_image" name="profile_image" class="hidden" accept="image/*" onchange="previewImage(this)">
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 mb-1">First Name</label>
                            <input type="text" id="up-first-name" name="first_name" required 
                                   class="w-full bg-dark-bg border border-gray-700 rounded-lg px-3 py-2 text-sm text-white focus:border-primary-red outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 mb-1">Last Name</label>
                            <input type="text" id="up-last-name" name="last_name" required 
                                   class="w-full bg-dark-bg border border-gray-700 rounded-lg px-3 py-2 text-sm text-white focus:border-primary-red outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 mb-1">Username</label>
                            <input type="text" id="up-username" name="username" required 
                                   class="w-full bg-dark-bg border border-gray-700 rounded-lg px-3 py-2 text-sm text-white focus:border-primary-red outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 mb-1">Email Address</label>
                            <input type="email" id="up-email" name="email" required 
                                   class="w-full bg-dark-bg border border-gray-700 rounded-lg px-3 py-2 text-sm text-white focus:border-primary-red outline-none">
                        </div>
                    </div>

                    <div class="border-t border-gray-800 my-2"></div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 mb-1">New Password</label>
                            <div class="relative">
                                <input type="password" id="up-password" name="new_password" placeholder="Optional"
                                       class="w-full bg-dark-bg border border-gray-700 rounded-lg px-3 py-2 text-sm text-white focus:border-primary-red outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 mb-1">Confirm Password</label>
                            <div class="relative">
                                <input type="password" id="up-confirm-password" name="confirm_password"
                                       class="w-full bg-dark-bg border border-gray-700 rounded-lg px-3 py-2 text-sm text-white focus:border-primary-red outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 shrink-0">
                        <button type="button" onclick="closeUpdateModal()" class="px-4 py-2 text-sm rounded-lg border border-gray-700 text-gray-400 hover:bg-gray-800 transition">Cancel</button>
                        <button type="submit" id="update-save-btn" class="px-6 py-2 text-sm bg-primary-red hover:bg-red-700 text-white font-bold rounded-lg transition shadow-md">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Scrollbar for the Modal */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #0d0d0d;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #E50914;
    border-radius: 10px;
}
</style>


<!-- ============================================= -->
<!--                 LOGIN MODAL                   -->
<!-- ============================================= -->

<div id="login-modal"
     class="fixed inset-0 bg-black bg-opacity-80 hidden z-[200] flex items-center justify-center p-4"
     onclick="closeLoginModal(event)">

    <div onclick="event.stopPropagation()"
     class="bg-dark-card w-full max-w-md p-6 rounded-xl shadow-xl border border-primary-red/40 
     max-h-[90vh] overflow-y-auto modal-enter">


        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-white">Sign In</h2>
            <button onclick="closeLoginModal()" class="text-gray-400 hover:text-primary-red">✖</button>
        </div>


        <!-- EMAIL LOGIN FORM -->
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

        <!-- BEAUTIFUL RECAPTCHA UI -->
        <div class="recaptcha-box mt-3 mb-4">
            <input type="checkbox" class="w-3 h-3">
            <span class="text-gray-900 text-sm">I'm not a robot</span>
            <img src="https://www.gstatic.com/recaptcha/api2/logo_48.png" class="ml-auto w-5">
        </div>

        <!-- LOGIN BUTTON -->
        <button id="login-btn" class="w-full bg-primary-red text-white py-3 rounded-lg font-bold hover:bg-red-600">
            Log In
        </button>
            <!-- Separator -->
            <div class="flex items-center my-6">
                <div class="flex-grow border-t border-gray-700"></div>
                <span class="flex-shrink mx-4 text-gray-500 text-sm">Or continue with</span>
                <div class="flex-grow border-t border-gray-700"></div>
            </div>

        <!-- Social Logins -->
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

<!-- ============================================= -->
<!--             REGISTER MODAL                    -->
<!-- ============================================= -->
<div id="register-modal"
     class="fixed inset-0 bg-black bg-opacity-80 hidden z-[210] flex items-center justify-center p-4"
     onclick="closeRegisterModal(event)">

    <div onclick="event.stopPropagation()"
     class="bg-dark-card w-full max-w-2xl p-4 rounded-xl shadow-xl border border-primary-red/40 
     max-h-[90vh] overflow-y-auto modal-enter">

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
                            <i class="fas fa-plus text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">Click to upload</p>
                    </label>
                    <img id="image-preview" class="image-preview" alt="Preview">
                </div>
            </div>

            <!-- GRID START -->
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
                    <select id="countrySelect" name="country"
                class="w-full mt-2 mb-2 bg-[#0d0d0d] text-white p-3 rounded-lg border border-[#444]" onblur="validateField(this, 'country')" required>
                    </select>
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

            <!-- GRID END -->

            <!-- Terms -->
            <div class="flex items-start mt-4">
                <input id="terms-check" name="agree" type="checkbox"
                    class="h-4 w-4 text-primary-red bg-gray-700 border-gray-600 rounded focus:ring-primary-red mt-1" required>
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


<!-- ============================================= -->
<!--            FORGOT PASSWORD MODAL              -->
<!-- ============================================= -->
<div id="forgot-modal"
     class="fixed inset-0 bg-black bg-opacity-80 hidden z-[220] flex items-center justify-center p-4"
     onclick="closeForgotModal(event)">

    <div onclick="event.stopPropagation()"
         class="bg-dark-card w-full max-w-md p-6 rounded-xl border border-primary-red/40 modal-enter">

        <h2 class="text-xl font-bold text-white mb-4">Reset Password</h2>

        <input type="email" class="input-field" placeholder="Enter Email">

        <button class="w-full bg-primary-red py-3 rounded-lg font-bold text-white">Send Reset Link</button>
    </div>
</div>

<!-- ============================================= -->
<!--                JAVASCRIPT                     -->
<!-- ============================================= -->

<script>
    // පිටුව ලෝඩ් වන විට ක්‍රියාත්මක වේ
window.addEventListener('DOMContentLoaded', (event) => {
    const savedUser = localStorage.getItem('currentUser');
    if (savedUser) {
        currentUser = JSON.parse(savedUser);
        isLoggedIn = true;
        updateNavbarForLoggedInUser(); // Navbar එකේ නම පෙන්වීමට
    }
});
 function UpdateProfileUser() {
    const savedUser = localStorage.getItem('currentUser');
    if (savedUser) {
        const user = JSON.parse(savedUser);
        console.log("Checking User Email:", user.email);
        console.log("Full User Object:", user);
        // Input fields වලට දත්ත දැමීම
        document.getElementById('up-user-id').value = user.id || '';
        document.getElementById('up-first-name').value = user.first_name || '';
        document.getElementById('up-last-name').value = user.last_name || '';
        document.getElementById('up-username').value = user.username || '';
        document.getElementById('up-email').value = user.email || '';
        
        // Modal එක පෙන්වීම
        document.getElementById('update-profile-modal').classList.remove('hidden');
        document.getElementById('profile-dropdown').classList.add('hidden');
    } else {
        Swal.fire('Error', 'Session lost! Please login again.', 'error');
    }
}

// Modal එක වහන function එක
function closeUpdateModal() {
    document.getElementById('update-profile-modal').classList.add('hidden');
}

// Form එක Submit කරන විට ක්‍රියාත්මක වන කොටස
document.getElementById('update-details-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // මෙතනදී ඔබට Fetch API එක පාවිච්චි කරලා Database එකට දත්ත යවන්න පුළුවන්
    // දැනට මම පෙන්වන්නේ සාර්ථක වුණාම වෙන දේ:
    
    Swal.fire({
        icon: 'success',
        title: 'Updated!',
        text: 'Your profile details have been updated.',
        confirmButtonColor: '#E50914'
    });
    
    closeUpdateModal();
});
function openLoginModal(){ 
    const modal = document.getElementById("login-modal");
    modal.classList.remove("hidden"); 
    modal.querySelector('.modal-enter').classList.add('modal-enter');
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
    modal.querySelector('.modal-enter').classList.add('modal-enter');
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
    modal.querySelector('.modal-enter').classList.add('modal-enter');
}

function closeForgotModal(e){ 
    if(!e || e.target.id==="forgot-modal") {
        const modal = document.getElementById("forgot-modal");
        modal.classList.add("hidden");
    }
}

// Image preview function
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

// Validation Functions
function validateField(input, fieldType) {
    const value = input.value.trim();
    const messageEl = document.getElementById(fieldType + '_message');
    let isValid = true;
    let message = '';

    // Clear previous styles
    input.classList.remove('error', 'success');
    messageEl.classList.remove('show', 'error', 'success');

    switch(fieldType) {
        case 'first_name':
        case 'last_name':
            if (!value) {
                message = 'This field is required';
                isValid = false;
            } else if (!/^[a-zA-Z]{2,30}$/.test(value)) {
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
            } else if (value.length < 5) {
                message = 'Username must be at least 5 characters long';
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

    // Apply styles and show message
    if (value) { // Only show validation if user has entered something
        input.classList.add(isValid ? 'success' : 'error');
        messageEl.textContent = message;
        messageEl.classList.add('show', isValid ? 'success' : 'error');
    }

    return isValid;
}


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

const select = document.getElementById("countrySelect");

countries.forEach(country => {
    let option = document.createElement("option");
    option.value = country;
    option.textContent = country;
    select.appendChild(option);
});

// Register form submission
document.getElementById("register-form").addEventListener("submit", function (e) {
    e.preventDefault();

    // Add loading state
    const btn = document.getElementById("registerBtn");
    btn.classList.add('btn-loading');
    btn.textContent = '';

    let inputs = document.querySelectorAll("#register-modal .input-field");

    // Validate all fields before submission
    let allValid = true;
    const fieldsToValidate = ['first_name', 'last_name', 'email', 'username', 'birthday', 'password', 'confirm_password'];
    
    fieldsToValidate.forEach(fieldId => {
        const input = document.getElementById(fieldId);
        if (!validateField(input, fieldId)) {
            allValid = false;
        }
    });

    // Validate country
    const countrySelect = document.getElementById("countrySelect");
    if (!validateField(countrySelect, 'country')) {
        allValid = false;
    }

    // Check terms agreement
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

    // Create FormData for file upload
    let data = new FormData(this);

    fetch("../library/registerBackend.php", {
        method: "POST",
        body: data
    })
    .then(res => res.text())
    .then(text => {
        console.log("RAW:", text);

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
                // Clear form
                document.getElementById("register-form").reset();
                document.getElementById("countrySelect").value = "Select Country";
                document.getElementById("image-preview").classList.remove('show');
                
                // Clear all validation messages
                document.querySelectorAll('.validation-message').forEach(msg => {
                    msg.classList.remove('show', 'error', 'success');
                });
                document.querySelectorAll('.input-field').forEach(input => {
                    input.classList.remove('error', 'success');
                });

                closeRegisterModal();
                
                // Open login modal with a slight delay for smooth transition
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
        // Remove loading state
        btn.classList.remove('btn-loading');
        btn.textContent = 'Register';
    });
});

// Login form submission
document.getElementById("login-btn").addEventListener("click", function (e) {
    e.preventDefault();

    // Add loading state
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
        console.log("RAW:", text);

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
            // Update global user state
            localStorage.setItem('currentUser', JSON.stringify(data.user));
            currentUser = data.user;
            isLoggedIn = true;
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
                // Clear form
                document.getElementById("login-identifier").value = "";
                document.getElementById("login-password").value = "";
                
                closeLoginModal();
                updateNavbarForLoggedInUser();
                
                // Redirect to home page
                window.location.href = "../Site/index.php";
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
        // Remove loading state
        btn.classList.remove('btn-loading');
        btn.textContent = 'Log In';
    });
});

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    if (field.type === "password") {
        field.type = "text";
    } else {
        field.type = "password";
    }
}
function handleMobileSearch(event) {
    event.preventDefault(); // පිටුව රීලෝඩ් වීම වළක්වයි
    
    // මොබයිල් input එකෙන් සර්ච් පදය ලබා ගැනීම
    const mobileInput = document.getElementById('search-input-mobile');
    const searchTerm = mobileInput.value.trim();
    
    if (searchTerm) {
        // index.php වෙත සර්ච් පදය යැවීම
        window.location.href = 'index.php?q=' + encodeURIComponent(searchTerm);
    }
}
</script>
   

</body>
</html>
 </header>


<div class="mid_container" style="height:auto;">