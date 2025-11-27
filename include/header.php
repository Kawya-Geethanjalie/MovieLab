<header class="sticky top-0 z-50">


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Lab</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* 'Inter' font for modern web apps */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0d0d0d; /* Slightly off-black background */
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
        }
        
        .input-field:focus {
            border-color: #E50914;
            box-shadow: 0 0 6px #E50914;
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

        // Close dropdown when clicking outside (on the window)
        window.onclick = function(event) {
            // Close all dropdowns if click is not on a dropdown button
            if (!event.target.closest('button')) {
                document.querySelectorAll('div.absolute[id$="-dropdown"]').forEach(d => {
                    d.classList.add('hidden');
                });
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
                    <!-- *** TV Series dropdown eka lagata ewith, gap eka nathi karala thiyenawa (space-x-4) *** -->
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-4 lg:space-x-6 items-center">
                        <!-- HOME Link using the new underline class -->
                        

                        <!-- Dropdown: Movies -->
                        <div class="relative">
                            <!-- Dropdown Button using a wrapper to apply underline effect on hover/focus -->
                            <button onclick="toggleDropdown('movies-dropdown')" class="nav-dropdown-btn inline-flex items-center px-1 pt-1 text-sm font-medium text-white transition duration-300 focus:outline-none">
                                <span class="nav-link-underline">Movies</span>
                                <!-- Down Arrow Icon -->
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

                        <!-- Dropdown: Songs -->
                        <div class="relative">
                            <button onclick="toggleDropdown('songs-dropdown')" class="nav-dropdown-btn inline-flex items-center px-1 pt-1 text-sm font-medium text-white transition duration-300 focus:outline-none">
                                <span class="nav-link-underline">Songs</span>
                                <!-- Down Arrow Icon -->
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

                        <!-- Dropdown: TV Series - Removed the margin on the right by using px-1 instead of default padding/margin -->
                        <div class="relative">
                            <button onclick="toggleDropdown('tv-series-dropdown')" class="nav-dropdown-btn inline-flex items-center px-1 pt-1 text-sm font-medium text-white transition duration-300 focus:outline-none">
                                <span class="nav-link-underline">TV Series</span>
                                <!-- Down Arrow Icon -->
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

                <!-- 2. Right Side: Genres, Years, Languages, Search, Notifications, PRO, Sign In -->
                <!-- *** space-x-0 class eka use karala Genres/Years/Languages atara thibuna gap eka ain karala, eka search button eka lagata damma *** -->
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

                        <!-- Dropdown: Years - Removed the margin on the left by using px-3 instead of default padding/margin -->
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
                        
                        <!-- Dropdown: Languages (UPDATED to multi-column) -->
                        <div class="relative">
                            <button onclick="toggleDropdown('languages-dropdown')" class="nav-dropdown-btn inline-flex items-center px-3 pt-1 text-sm font-medium text-white transition duration-300 focus:outline-none">
                                <span class="nav-link-underline">Languages</span>
                                <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <!-- Dropdown Menu for Languages: Wider, 2-column grid -->
                            <div id="languages-dropdown" class="absolute hidden mt-3 w-72 rounded-lg shadow-2xl bg-dark-card ring-1 ring-primary-red ring-opacity-20 z-20 -right-16">
                                <div class="p-2 grid grid-cols-2 gap-x-4 gap-y-1" role="menu" aria-orientation="vertical" aria-labelledby="languages-menu-button">
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">All</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">English</a>
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
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Turkish</a>
                                    
                                   
                                
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <!-- Search, Notifications, PRO, and Sign In -->
                    <div class="flex items-center space-x-4 lg:space-x-6 ml-4"> <!-- ml-4 (left margin) is the new gap between Years/Languages and Search -->
                        
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
                        
                        

                     <!-- Sign In Link (FIXED → modal now opens) -->
                    <button onclick="openLoginModal()" 
                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white rounded-md transition duration-300 hover:bg-red-600 hover:shadow-lg hover:shadow-primary-red/50">
                        Sign In
                    </button>

                        <!-- NEW PRO BUTTON (Highlighted, large, on the right, hidden on mobile) -->
                        <button onclick="openProModal()" class="pro-button-gradient px-4 py-2 text-sm font-bold text-white rounded-md transition duration-300 shadow-md shadow-theme-orange/50 uppercase tracking-widest hidden sm:inline-flex">
                            PRO
                        </button>
                        <!-- Sign Up CTA Button - REMOVED -->
                    </div>
                    
                    <!-- 3. Mobile Menu Button -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button onclick="toggleMenu()" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-dark-card focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-red transition duration-300" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <!-- Hamburger icon -->
                            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                            </svg>
                            <!-- Close icon (hidden by default) -->
                            <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
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

        <!-- 4. Mobile Menu (Flattened Navigation) -->
        <div class="sm:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <!-- PRO Button for Mobile - Using the new theme color -->
                <button onclick="openProModal()" class="pro-button-mobile w-full px-3 py-2 text-base font-bold text-white rounded-md transition duration-300 hover:bg-red-700 hover:shadow-lg uppercase tracking-widest sm:hidden">
                    GET PRO ACCESS
                </button>
                
                <a href="#" class="bg-dark-card text-white block px-3 py-2 rounded-md text-base font-medium">Home</a>
                
                <!-- Sign In Mobile Link -->
                 <!-- SIGN IN BUTTON -->
                <button onclick="openLoginModal()"
                    class="px-4 py-1.5 text-sm text-white hover:bg-red-600 rounded-md">
                    Sign In
                </button>
                <!-- Sign Up Mobile Link - REMOVED -->

                <!-- Notifications Mobile Link (Bell Icon) with primary-red hover -->
                <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-primary-red flex items-center px-3 py-2 rounded-md text-base font-medium">
                    <svg class="h-6 w-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    Notifications
                </a>
                
                <!-- Genres (Mobile Section) -->
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

                <!-- Languages (Mobile Section) - UPDATED to 20 languages and 2 columns -->
                <h4 class="text-sm px-3 pt-3 text-gray-500 font-semibold uppercase">Languages (20)</h4>
                <div class="grid grid-cols-2 gap-y-1">
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">English</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">French</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Sinhala</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">German</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Tamil</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Italian</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Hindi</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Spanish</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Telugu</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Russian</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Malayalam</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Portuguese</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Kannada</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Arabic</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Japanese</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Turkish</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Korean</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Thai</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Chinese</a>
                    <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Indonesian</a>
                </div>


                <!-- Years (Common Filter) -->
                <h4 class="text-sm px-3 pt-3 text-gray-500 font-semibold uppercase">Filter by Year</h4>
                <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">2024</a>
                <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">2023</a>
                <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">2022</a>
                <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Older</a>

                <!-- Movies Categories (Other filters) -->
                <h4 class="text-sm px-3 pt-3 text-gray-500 font-semibold uppercase">Movies</h4>
                <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Now Playing</a>
                <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Popular</a>
                <a href="#" class="text-gray-300 hover:bg-dark-card hover:text-white block pl-6 pr-3 py-1 rounded-md text-sm transition duration-150">Top Rated</a>
                
                <!-- Mobile Search -->
                <div class="mt-4 relative">
                    
                    <input type="text" placeholder="Search..." class="w-full bg-dark-card text-white placeholder-gray-500 rounded-md py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-primary-red">
                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </nav>
    <!-- NAVIGATION BAR END -->

    <!-- PRO SUBSCRIPTION MODAL START (hidden by default) -->
    <div id="pro-modal" class="fixed inset-0 bg-black bg-opacity-80 z-[100] hidden flex items-center justify-center p-4 overflow-y-auto" onclick="closeProModal(event)">
        
        <!-- Modal Content Container: Added flex-col and removed internal overflow -->
        <!-- The flex-col structure makes the header stick to the top and the body take up the rest of the available height -->
        <div class="bg-dark-bg rounded-xl shadow-2xl p-6 md:p-8 w-full max-w-4xl transform transition-all duration-300 scale-100 border border-primary-red/50 max-h-[90vh] flex flex-col" onclick="event.stopPropagation()">
            
            <!-- Header and Close Button (FIXED PART: shrink-0 ensures it doesn't shrink when content scrolls) -->
            <!-- The horizontal padding (p-6/p-8) of the container applies to this header -->
            <div class="flex justify-between items-center border-b border-gray-700 pb-4 mb-4 shrink-0">
                <h2 class="text-3xl font-bold text-white text-glow-red">
                    Unlock <span class="text-theme-orange">PRO</span> Features
                </h2>
                <!-- Close Button -->
                <button onclick="closeProModal()" class="text-gray-400 hover:text-primary-red transition duration-200 p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Scrollable Content Wrapper (SCROLLING PART: flex-grow ensures it fills the remaining vertical space) -->
            <div class="overflow-y-auto flex-grow">
                <!-- Pricing Tiers (Early, Monthly, Weekly) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- 1. Early Tier (Annual Plan) - Updated to use Red/Orange accents instead of Yellow -->
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
                        <!-- Button uses the new gradient -->
                        <button class="pro-button-gradient mt-auto w-full text-white font-bold py-3 rounded-full shadow-lg shadow-theme-orange/40 hover:shadow-theme-orange/80 transform hover:scale-[1.02]">
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
                        <button class="mt-auto w-full bg-primary-red text-white font-bold py-3 rounded-full hover:bg-red-600 transition duration-200">
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
                        <button class="mt-auto w-full bg-primary-red text-white font-bold py-3 rounded-full hover:bg-red-600 transition duration-200">
                            Get Weekly Pass
                        </button>
                    </div>
                </div>
                
                <!-- Adding some padding below the cards for better scroll margin on mobile -->
                <div class="h-4 md:hidden"></div> 
            </div>
        </div>
    </div>
    <!-- PRO SUBSCRIPTION MODAL END -->

    

<!-- ============================================= -->
<!--                 LOGIN MODAL                   -->
<!-- ============================================= -->

<div id="login-modal"
     class="fixed inset-0 bg-black bg-opacity-80 hidden z-[200] flex items-center justify-center p-4"
     onclick="closeLoginModal(event)">

    <div onclick="event.stopPropagation()"
     class="bg-dark-card w-full max-w-md p-6 rounded-xl shadow-xl border border-primary-red/40 
     max-h-[90vh] overflow-y-auto">


        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-white">Sign In</h2>
            <button onclick="closeLoginModal()" class="text-gray-400 hover:text-primary-red">✖</button>
        </div>


        <!-- EMAIL LOGIN FORM -->
        <div id="emailLogin">
            <label for="login-identifier" class="block text-sm font-medium text-gray-300 mb-1">Username or Email</label>
            <input type="email" placeholder="Enter Username or Email" class="input-field">
            <label for="login-password" class="block text-sm font-medium text-gray-300 mb-1">Password</label>
            <input type="password" placeholder="Enter Password" class="input-field">
        </div>

       
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
        <button class="w-full bg-primary-red text-white py-3 rounded-lg font-bold hover:bg-red-600">
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
            Don’t have an account?
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
     class="bg-dark-card w-full max-w-md p-6 rounded-xl shadow-xl border border-primary-red/40 
     max-h-[90vh] overflow-y-auto">

          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-white mb-4">Create Account</h2>
            <button onclick="closeRegisterModal()" class="text-gray-400 hover:text-primary-red">✖</button>
        </div>
        
         <label for="login-identifier" class="block text-sm font-medium text-gray-300 mb-1">First Name</label>
        <input type="text" placeholder="Enter First Name" class="input-field">
         <label for="login-identifier" class="block text-sm font-medium text-gray-300 mb-1">Last Name</label>
        <input type="text" placeholder="Enter Last Name" class="input-field">
         <label for="login-identifier" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
        <input type="email" placeholder="Enter Email" class="input-field">
        <!-- <input type="text" placeholder="Phone Number" class="input-field"> -->
          <label for="login-identifier" class="block text-sm font-medium text-gray-300 mb-1">Username</label>
         <input type="text" placeholder="Enter Usename" class="input-field">
          <label for="login-identifier" class="block text-sm font-medium text-gray-300 mb-1">Birthday</label>
         <input type="date" placeholder="Birthday" class="input-field">

          <!-- Country Selector -->
          <label class="block text-sm font-medium text-gray-300 mb-1">Country</label>
        <select id="countrySelect" class="input-field">
        <option value="" disabled selected>Select Country</option>
        </select>


         <label for="login-identifier" class="block text-sm font-medium text-gray-300 mb-1">Password</label>
        <input type="password" placeholder="Enter Password" class="input-field">
         <label for="login-identifier" class="block text-sm font-medium text-gray-300 mb-1">Confirm Password</label>
        <input type="password" placeholder="Confirm Password" class="input-field">

       

        <!-- Terms Checkbox -->
                    <div class="flex items-start">
                        <input id="terms-check" type="checkbox" class="h-4 w-4 text-primary-red bg-gray-700 border-gray-600 rounded focus:ring-primary-red mt-1">
                        <label for="terms-check" class="ml-2 text-gray-400 text-sm">
                            I agree to the <a href="#" class="text-primary-red hover:text-red-400">Terms of Service</a> and Privacy Policy.
                        </label>
                    </div>

        <button class="w-full bg-primary-red mt-3 py-3 rounded-lg font-bold text-white">Register</button>

        <p class="text-center text-gray-300 mt-4">
            Already have an account?
            <button onclick="openLoginModal()" class="text-primary-red">Sign In</button>
        </p>
    </div>
</div>

<!-- ============================================= -->
<!--            FORGOT PASSWORD MODAL              -->
<!-- ============================================= -->
<div id="forgot-modal"
     class="fixed inset-0 bg-black bg-opacity-80 hidden z-[220] flex items-center justify-center p-4"
     onclick="closeForgotModal(event)">

    <div onclick="event.stopPropagation()"
         class="bg-dark-card w-full max-w-md p-6 rounded-xl border border-primary-red/40">

        <h2 class="text-xl font-bold text-white mb-4">Reset Password</h2>

        <input type="email" class="input-field" placeholder="Enter Email">

        <button class="w-full bg-primary-red py-3 rounded-lg font-bold text-white">Send Reset Link</button>
    </div>
</div>

<!-- ============================================= -->
<!--                JAVASCRIPT                     -->
<!-- ============================================= -->

<script>
function openLoginModal(){ document.getElementById("login-modal").classList.remove("hidden"); }
function closeLoginModal(e){ if(!e || e.target.id==="login-modal") document.getElementById("login-modal").classList.add("hidden"); }

function openRegisterModal(){ closeLoginModal(); document.getElementById("register-modal").classList.remove("hidden"); }
function closeRegisterModal(e){ if(!e || e.target.id==="register-modal") document.getElementById("register-modal").classList.add("hidden"); }

function openForgotModal(){ closeLoginModal(); document.getElementById("forgot-modal").classList.remove("hidden"); }
function closeForgotModal(e){ if(!e || e.target.id==="forgot-modal") document.getElementById("forgot-modal").classList.add("hidden"); }

/* SWITCH Tabs */
function switchToEmail(){
    document.getElementById("emailLogin").classList.remove("hidden");
    document.getElementById("phoneLogin").classList.add("hidden");
    emailTab.classList.add("bg-primary-red","text-white");
    phoneTab.classList.remove("bg-primary-red","text-white");
}
function switchToPhone(){
    document.getElementById("emailLogin").classList.add("hidden");
    document.getElementById("phoneLogin").classList.remove("hidden");
    phoneTab.classList.add("bg-primary-red","text-white");
    emailTab.classList.remove("bg-primary-red","text-white");
}


const countries = [
    "Afghanistan","Albania","Algeria","Andorra","Angola","Argentina","Armenia","Australia",
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


</script>
   

</body>
</html>
 </header>


<div class="mid_container" style="height:auto;">