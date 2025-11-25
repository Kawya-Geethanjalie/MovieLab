<header>


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
            background-image: linear-gradient(to right, #C72600, #FA471B);
            transition: all 0.3s ease;
        }
        
        .pro-button-gradient:hover {
            /* Shadow eka poddak loku karanawa hover karaddi */
            box-shadow: 0 0 15px rgba(250, 71, 27, 0.8), 0 0 30px rgba(250, 71, 27, 0.5);
            transform: scale(1.05); /* Poddak loku wenawa */
        }
        
        /* PRO button for mobile menu */
        .pro-button-mobile {
            background-color: #FA471B; /* Darker red/orange for consistency */
            transition: background-color 0.3s ease;
        }
        .pro-button-mobile:hover {
            background-color: #C72600;
        }

        /* Search bar animation */
        .search-bar {
            transition: all 0.3s ease-in-out;
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
                    <span class="text-3xl font-extrabold text-primary-red tracking-wider cursor-pointer text-glow-red mr-6">
                        Movie Lab
                    </span>
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
                                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="years-menu-button">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2024</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2023</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150" role="menuitem">2022</a>
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
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">English</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">French</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Sinhala</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">German</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Tamil</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Italian</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Hindi</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Spanish</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Telugu</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Russian</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Malayalam</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Portuguese</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Kannada</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Arabic</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Japanese</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Turkish</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Korean</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Thai</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Chinese</a>
                                    <a href="#" class="px-4 py-1 text-sm text-gray-200 hover:bg-primary-red hover:text-white transition duration-150 rounded-md" role="menuitem">Indonesian</a>
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
                        
                        <!-- NEW PRO BUTTON (Highlighted, large, on the right, hidden on mobile) -->
                        <button onclick="openProModal()" class="pro-button-gradient px-4 py-2 text-sm font-bold text-white rounded-full transition duration-300 shadow-md shadow-theme-orange/50 uppercase tracking-widest hidden sm:inline-flex">
                            PRO
                        </button>

                        <!-- Sign In Link -->
                        <a href="../Site/login.php" class="inline-flex items-center px-3 py-1.5 text-sm font-medium bg-primary-red text-white rounded-full transition duration-300 hover:bg-red-600 hover:shadow-lg hover:shadow-primary-red/50">
                            Sign In
                        </a>
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
                <a href="#" class="text-white bg-primary-red block px-3 py-2 rounded-md text-base font-medium transition duration-150 hover:bg-red-600">
                    Sign In
                </a>
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

   

</body>
</html>
 </header>


<div class="mid_container" style="height:auto;">