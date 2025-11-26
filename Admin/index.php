<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Lab Admin Panel</title>
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Load Chart.js for beautiful graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>

    <script>
        // --- CONSTANTS ---
        const PRIMARY_RED_COLOR = '#E50914'; // New requested bright red color

        // Tailwind Configuration for Color Palette
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-red': PRIMARY_RED_COLOR, // Using the new red
                        'dark-bg': '#1F2937',    // Deep Slate/Black
                        'secondary-light': '#F3F4F6', // Off-White
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        /* Custom scrollbar for a darker look */
        .custom-scroll::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: #374151; /* Dark grey track */
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background: #E50914; /* Primary red thumb (updated) */
            border-radius: 4px;
        }
        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #CC0812; /* Slightly darker red hover */
        }

        /* Red Glow for Title */
        .admin-title-glow {
            /* Updated glow color */
            text-shadow: 0 0 8px rgba(229, 9, 20, 0.8), 0 0 15px rgba(229, 9, 20, 0.5); 
            transition: all 0.3s ease-in-out;
        }

        /* --- LIGHT THEME STYLES (Colors updated for consistency) --- */

        .light-theme {
            background-color: #FFFFFF; 
            color: #1F2937;
        }
        .light-theme .bg-dark-bg { background-color: #FFFFFF; }
        .light-theme .text-secondary-light { color: #1F2937; }
        .light-theme .bg-gray-900 { background-color: #E5E7EB; }
        .light-theme .bg-gray-800 { background-color: #FFFFFF; border: 1px solid #D1D5DB; } 
        .light-theme .text-white { color: #1F2937; }
        .light-theme .text-gray-400 { color: #4B5563; }
        .light-theme .text-gray-500 { color: #6B7280; }
        .light-theme .text-gray-900 { color: #1F2937; }
        .light-theme .border-gray-700 { border-color: #D1D5DB !important; }
        .light-theme .divide-gray-700 { border-color: #D1D5DB !important; }
        .light-theme input, .light-theme textarea, .light-theme select {
            background-color: #FFFFFF !important;
            border-color: #D1D5DB !important;
            color: #1F2937 !important;
        }
        .light-theme .bg-gray-700 { background-color: #F3F4F6; }
        /* Using a lightened version of the new red for backgrounds */
        .light-theme .bg-primary-red\/10 { background-color: #FEEEEE; } 
    </style>
</head>
<body class="bg-dark-bg font-sans min-h-screen text-secondary-light antialiased">

    <!-- Main Application Container -->
    <div id="app" class="flex flex-col lg:flex-row h-screen">

        <!-- Sidebar Navigation (Responsive) -->
        <nav id="sidebar" class="bg-gray-900 lg:w-64 p-4 shadow-2xl flex-shrink-0 z-20">
            <!-- All primary-red classes are now the new color -->
            <div class="flex justify-between items-center mb-6 border-b border-primary-red/50 pb-4">
                <h1 class="text-2xl font-extrabold text-primary-red tracking-wider admin-title-glow">Movie Lab Admin</h1>
                <button id="menu-btn" class="lg:hidden p-2 rounded-lg hover:bg-primary-red/20 transition">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <div id="nav-links" class="hidden lg:block">
                <ul class="space-y-2">
                    <!-- Updated hover classes to new red color -->
                    <li class="nav-item" data-view="dashboard">
                        <a href="#" class="flex items-center p-3 text-white rounded-xl hover:bg-primary-red/70 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                            <span>Analytics & Reports</span>
                        </a>
                    </li>
                    <li class="nav-item" data-view="content-management">
                        <a href="#" class="flex items-center p-3 text-white rounded-xl hover:bg-primary-red/70 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <span>Content Management</span>
                        </a>
                    </li>
                    <li class="nav-item" data-view="user-management">
                        <a href="#" class="flex items-center p-3 text-white rounded-xl hover:bg-primary-red/70 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.195-1.282-.55-1.802M7 20h4V8a2 2 0 00-2-2a2 2 0 00-2 2v12zm10 0v-2c0-.653-.195-1.282-.55-1.802"></path></svg>
                            <span>User Management</span>
                        </a>
                    </li>
                    <li class="nav-item" data-view="subscription-management">
                        <a href="#" class="flex items-center p-3 text-white rounded-xl hover:bg-primary-red/70 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            <span>Subscriptions & Payments</span>
                        </a>
                    </li>
                    <li class="nav-item" data-view="genre-management">
                        <a href="#" class="flex items-center p-3 text-white rounded-xl hover:bg-primary-red/70 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h10a2 2 0 012 2v4"></path></svg>
                            <span>Genre/Category Tools</span>
                        </a>
                    </li>
                    <!-- New: Profile -->
                    <li class="nav-item" data-view="profile">
                        <a href="#" class="flex items-center p-3 text-white rounded-xl hover:bg-primary-red/70 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span>Profile</span>
                        </a>
                    </li>
                    <!-- New: Settings -->
                    <li class="nav-item" data-view="settings">
                        <a href="#" class="flex items-center p-3 text-white rounded-xl hover:bg-primary-red/70 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
                <!-- New: Logout Button -->
                <div class="mt-8 pt-4 border-t border-primary-red/50">
                    <button id="logout-btn" class="w-full flex items-center p-3 text-red-400 rounded-xl hover:bg-red-900/50 hover:text-white transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span>Logout</span>
                    </button>
                    <!-- Existing auth status display -->
                    <p id="auth-status" class="text-xs text-gray-400 mt-4">Loading Auth...</p>
                    <p class="text-xs text-gray-500 mt-1">User ID: <span id="user-id-display" class="font-mono text-primary-red">N/A</span></p>
                </div>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto custom-scroll p-4 sm:p-8">
            <!-- Loading Indicator (Border color updated) -->
            <div id="loading-overlay" class="fixed inset-0 bg-dark-bg/80 backdrop-blur-sm flex items-center justify-center z-50 transition-opacity duration-300">
                <div class="flex flex-col items-center">
                    <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-primary-red"></div>
                    <p class="mt-4 text-xl font-semibold text-white">Initializing System...</p>
                </div>
            </div>

            <!-- Content Views -->
            <div id="views-container">
                <!-- 1. Analytics & Reporting (Dashboard) View -->
                <section id="dashboard" class="view-content active" data-title="System Overview & Analytics">
                    <!-- Text/Border Color updated -->
                    <h2 class="text-3xl font-bold mb-6 text-white border-l-4 border-primary-red pl-4">Analytics & Reporting</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <!-- Border Color updated -->
                        <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-primary-red/30">
                            <p class="text-sm uppercase tracking-wider text-primary-red">Total Revenue (YTD)</p>
                            <p class="text-4xl font-extrabold text-white mt-2">LKR 45,670,000</p>
                        </div>
                        <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-primary-red/30">
                            <p class="text-sm uppercase tracking-wider text-primary-red">New Subscribers (MoM)</p>
                            <p class="text-4xl font-extrabold text-white mt-2">+2,100 <span class="text-sm font-normal text-green-400">(+12%)</span></p>
                        </div>
                        <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-primary-red/30">
                            <p class="text-sm uppercase tracking-wider text-primary-red">Active Users</p>
                            <p class="text-4xl font-extrabold text-white mt-2">15,892</p>
                        </div>
                    </div>

                    <!-- Usage Trends / Revenue Chart (Line Chart) -->
                    <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-white/10 mb-8">
                        <h3 class="text-xl font-semibold text-white mb-4">Monthly Revenue Trend (LKR Millions)</h3>
                        <div class="h-64">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- New User Growth Chart (Bar Chart) -->
                        <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-white/10">
                            <h3 class="text-xl font-semibold text-white mb-4">New User Acquisition (Quarterly)</h3>
                            <div class="h-64">
                                <canvas id="userGrowthChart"></canvas>
                            </div>
                        </div>
                        <!-- Content Popularity Chart (Doughnut Chart) -->
                        <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-white/10">
                            <h3 class="text-xl font-semibold text-white mb-4">Content Popularity by Genre</h3>
                            <div class="h-64">
                                <canvas id="genrePopularityChart"></canvas>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 2. Content Management View -->
                <section id="content-management" class="view-content hidden" data-title="Content Management">
                    <!-- Text/Border Color updated -->
                    <h2 class="text-3xl font-bold mb-6 text-white border-l-4 border-primary-red pl-4">Content Management System (CMS)</h2>

                    <!-- Tab Navigation for CMS (Border color updated) -->
                    <div class="flex space-x-2 border-b border-gray-700 mb-6">
                        <button class="cms-tab active-cms-tab p-3 text-sm font-medium text-white border-b-2 border-primary-red transition-all duration-300" data-tab="add-content">Media Upload/Addition</button>
                        <button class="cms-tab p-3 text-sm font-medium text-gray-400 hover:text-white transition-colors" data-tab="manage-content">Content Moderation</button>
                        <button class="cms-tab p-3 text-sm font-medium text-gray-400 hover:text-white transition-colors" data-tab="batch-upload">Batch Upload</button>
                    </div>

                    <!-- Add Content Tab -->
                    <div id="add-content" class="cms-tab-content">
                        <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-white/10">
                            <h3 class="text-xl font-semibold text-white mb-4">Add New Movie or Song</h3>
                            <form id="add-content-form" class="space-y-4">
                                <!-- Focus Ring color updated -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <input type="text" id="content-title" placeholder="Title (e.g., Kusa Paba)" required class="w-full p-3 bg-gray-900 border border-gray-700 rounded-lg focus:ring-primary-red focus:border-primary-red text-white placeholder-gray-500">
                                    <input type="number" id="content-year" placeholder="Release Year" required class="w-full p-3 bg-gray-900 border border-gray-700 rounded-lg focus:ring-primary-red focus:border-primary-red text-white placeholder-gray-500">
                                    <select id="content-language" required class="w-full p-3 bg-gray-900 border border-gray-700 rounded-lg focus:ring-primary-red focus:border-primary-red text-white placeholder-gray-500">
                                        <option value="" disabled selected>Select Language</option>
                                        <option value="sinhala">Sinhala</option>
                                        <option value="english">English</option>
                                        <option value="tamil">Tamil</option>
                                    </select>
                                </div>

                                <!-- Genre/Category (Multi-select style) -->
                                <div>
                                    <label for="content-genre" class="block text-sm font-medium text-gray-400 mb-1">Genre/Category (Select Multiple)</label>
                                    <select id="content-genre" multiple class="w-full p-3 bg-gray-900 border border-gray-700 rounded-lg focus:ring-primary-red focus:border-primary-red text-white placeholder-gray-500 h-24">
                                        <option value="action">Action</option>
                                        <option value="drama">Drama</option>
                                        <option value="comedy">Comedy</option>
                                        <option value="romance">Romance</option>
                                        <option value="thriller">Thriller</option>
                                    </select>
                                </div>

                                <!-- Cast List and Synopsis -->
                                <div>
                                    <input type="text" id="content-cast" placeholder="Cast List (Comma Separated: e.g., Ravindu, Senali)" required class="w-full p-3 bg-gray-900 border border-gray-700 rounded-lg focus:ring-primary-red focus:border-primary-red text-white placeholder-gray-500">
                                </div>
                                <div>
                                    <textarea id="content-synopsis" rows="3" placeholder="Synopsis/Summary" required class="w-full p-3 bg-gray-900 border border-gray-700 rounded-lg focus:ring-primary-red focus:border-primary-red text-white placeholder-gray-500"></textarea>
                                </div>

                                <!-- Media File/URL and Poster Image -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="content-media" class="block text-sm font-medium text-gray-400 mb-1">Media File URL (or upload later)</label>
                                        <input type="url" id="content-media" placeholder="https://media-storage.com/movie-file.mp4" class="w-full p-3 bg-gray-900 border border-gray-700 rounded-lg focus:ring-primary-red focus:border-primary-red text-white placeholder-gray-500">
                                    </div>
                                    <div>
                                        <label for="content-poster" class="block text-sm font-medium text-gray-400 mb-1">Poster Image URL (or upload file)</label>
                                        <input type="url" id="content-poster" placeholder="https://storage.com/poster.jpg" class="w-full p-3 bg-gray-900 border border-gray-700 rounded-lg focus:ring-primary-red focus:border-primary-red text-white placeholder-gray-500">
                                    </div>
                                </div>
                                
                                <!-- DRM Confirmation (Checkbox color updated) -->
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" id="content-drm" class="w-4 h-4 text-primary-red bg-gray-900 border-gray-700 rounded focus:ring-primary-red">
                                    <label for="content-drm" class="text-sm font-medium text-white">DRM protection confirmed/applied automatically.</label>
                                </div>

                                <!-- Button color updated -->
                                <button type="submit" class="w-full bg-primary-red hover:bg-red-700 text-white font-bold py-3 rounded-xl shadow-md transition duration-300 transform hover:scale-[1.01] focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-gray-900">
                                    Add New Content
                                </button>
                                <p id="add-content-message" class="text-center text-sm mt-3"></p>
                            </form>
                        </div>
                    </div>

                    <!-- Content Moderation Tab (No changes needed, mostly static table) -->
                    <div id="manage-content" class="cms-tab-content hidden">
                        <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-white/10">
                            <h3 class="text-xl font-semibold text-white mb-4">Content Moderation (Edit/Delete/Deactivate)</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-700">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Title</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider hidden sm:table-cell">Year</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider hidden md:table-cell">Status</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="content-list-table-body" class="divide-y divide-gray-700">
                                        <!-- Content items will be loaded here -->
                                        <tr><td colspan="4" class="text-center py-4 text-gray-500">No content found. Add a movie first!</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Batch Upload Tab (Button color updated) -->
                    <div id="batch-upload" class="cms-tab-content hidden">
                        <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-white/10">
                            <h3 class="text-xl font-semibold text-white mb-4">Batch Upload & Processing</h3>
                            <div class="p-6 border-2 border-dashed border-gray-600 rounded-xl text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.885-7.144M15 16a4 4 0 01-1.447.894M15 16l-3 3-3-3m0 0V4m12 12a3 3 0 100-6 3 3 0 000 6z"></path></svg>
                                <p class="mt-2 text-sm text-gray-400">Drag and drop multiple video/music files here, or click to browse.</p>
                                <input type="file" multiple class="hidden" id="file-batch-upload" accept="video/*,audio/*">
                                <button onclick="document.getElementById('file-batch-upload').click()" class="mt-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-red hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Select Files for Batch Upload
                                </button>
                                <p class="mt-4 text-xs text-gray-500">Feature Note: Batch processing mock-up. Files would be queued on a server for simultaneous metadata extraction and DRM application.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 3. User Management View (Button color updated) -->
                <section id="user-management" class="view-content hidden" data-title="User Management">
                    <h2 class="text-3xl font-bold mb-6 text-white border-l-4 border-primary-red pl-4">User Management</h2>
                    <div class="mb-6 flex flex-col sm:flex-row gap-4">
                        <input type="text" placeholder="Search users by name or email..." class="flex-grow p-3 bg-gray-900 border border-gray-700 rounded-lg focus:ring-primary-red focus:border-primary-red text-white placeholder-gray-500">
                        <select class="p-3 bg-gray-900 border border-gray-700 rounded-lg focus:ring-primary-red focus:border-primary-red text-white">
                            <option>Filter: All</option>
                            <option>Filter: Active Subscriptions</option>
                            <option>Filter: Suspended</option>
                        </select>
                    </div>

                    <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-white/10 overflow-x-auto">
                        <h3 class="text-xl font-semibold text-white mb-4">Registered Users List</h3>
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">User ID / Email</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider hidden sm:table-cell">Subscription</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider hidden md:table-cell">Expiry</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Status & Actions</th>
                                </tr>
                            </thead>
                            <tbody id="user-list-body" class="divide-y divide-gray-700">
                                <!-- Mock User Data -->
                                <tr class="hover:bg-gray-700 transition">
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-white">user-01@example.com</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm hidden sm:table-cell"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-800 text-green-300">Premium</span></td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-400 hidden md:table-cell">2026-03-15</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button class="text-primary-red hover:text-red-300 mr-3">Suspend</button>
                                        <button class="text-gray-400 hover:text-white">View</button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-700 transition">
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-white">user-02@example.com</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm hidden sm:table-cell"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-800 text-yellow-300">Basic</span></td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-400 hidden md:table-cell">2025-12-01</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button class="text-primary-red hover:text-red-300 mr-3">Suspend</button>
                                        <button class="text-gray-400 hover:text-white">View</button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-700 transition">
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-white">user-03@example.com</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm hidden sm:table-cell"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-800 text-red-300">Deactivated</span></td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-400 hidden md:table-cell">N/A</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button class="text-green-500 hover:text-green-300 mr-3">Activate</button>
                                        <button class="text-gray-400 hover:text-white">View</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- 4. Subscription & Payment Management View (Colors updated) -->
                <section id="subscription-management" class="view-content hidden" data-title="Subscription & Payment Management">
                    <h2 class="text-3xl font-bold mb-6 text-white border-l-4 border-primary-red pl-4">Subscription & Payment Management</h2>

                    <!-- Plan Configuration Section -->
                    <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-white/10 mb-8">
                        <h3 class="text-xl font-semibold text-white mb-4">Subscription Plan Configuration</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Basic Plan (Text/Border/Button color updated) -->
                            <div class="border border-primary-red/50 rounded-xl p-4 bg-gray-900">
                                <h4 class="text-lg font-bold text-primary-red">Basic Plan</h4>
                                <p class="text-white text-2xl font-extrabold my-2">LKR 999 <span class="text-sm font-normal text-gray-400">/ Month</span></p>
                                <p class="text-sm text-gray-400 mb-3">SD quality, 1 concurrent screen.</p>
                                <button class="w-full bg-primary-red/50 text-white py-2 rounded-lg text-sm hover:bg-primary-red transition">Edit Plan</button>
                            </div>
                            <!-- Standard Plan (Button color updated) -->
                            <div class="border border-white/50 rounded-xl p-4 bg-gray-900">
                                <h4 class="text-lg font-bold text-white">Standard Plan</h4>
                                <p class="text-white text-2xl font-extrabold my-2">LKR 1499 <span class="text-sm font-normal text-gray-400">/ Month</span></p>
                                <p class="text-sm text-gray-400 mb-3">HD quality, 2 concurrent screens.</p>
                                <button class="w-full bg-primary-red/50 text-white py-2 rounded-lg text-sm hover:bg-primary-red transition">Edit Plan</button>
                            </div>
                            <!-- Premium Plan (Border/Background/Button color updated) -->
                            <div class="border border-primary-red rounded-xl p-4 bg-primary-red/10 border-2">
                                <h4 class="text-lg font-bold text-primary-red">Premium Plan</h4>
                                <p class="text-white text-2xl font-extrabold my-2">LKR 1999 <span class="text-sm font-normal text-gray-400">/ Month</span></p>
                                <p class="text-sm text-gray-400 mb-3">4K Ultra HD, 4 concurrent screens.</p>
                                <button class="w-full bg-primary-red text-white py-2 rounded-lg text-sm hover:bg-red-700 transition">Edit Plan</button>
                            </div>
                        </div>
                        <button class="mt-4 bg-white/10 text-white p-3 rounded-xl text-sm hover:bg-white/20 transition">
                            + Create New Plan
                        </button>
                    </div>

                    <!-- Transaction Reports Section (No changes needed) -->
                    <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-white/10">
                        <h3 class="text-xl font-semibold text-white mb-4">Transaction Reports</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">ID</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider hidden sm:table-cell">Amount</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider hidden md:table-cell">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700">
                                    <tr class="hover:bg-gray-700 transition">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-white">TRX-7890</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-white hidden sm:table-cell">LKR 1999.00</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-800 text-green-300">Successful</span></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-400 hidden md:table-cell">2025-11-25</td>
                                    </tr>
                                    <tr class="hover:bg-gray-700 transition">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-white">TRX-7891</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-white hidden sm:table-cell">LKR 1499.00</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-800 text-yellow-300">Refunded</span></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-400 hidden md:table-cell">2025-11-24</td>
                                    </tr>
                                    <tr class="hover:bg-gray-700 transition">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-white">TRX-7892</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-white hidden sm:table-cell">LKR 999.00</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-800 text-red-300">Failed</span></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-400 hidden md:table-cell">2025-11-23</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- 5. Genre/Category Tools View (Colors updated) -->
                <section id="genre-management" class="view-content hidden" data-title="Genre & Category Tools">
                    <h2 class="text-3xl font-bold mb-6 text-white border-l-4 border-primary-red pl-4">Genre/Category Tools</h2>

                    <!-- Add New Genre -->
                    <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-white/10 mb-8 max-w-lg">
                        <h3 class="text-xl font-semibold text-white mb-4">Create New Genre</h3>
                        <div class="flex space-x-2">
                            <input type="text" id="new-genre-name" placeholder="New Genre Name (e.g., Historical Fiction)" class="flex-grow p-3 bg-gray-900 border border-gray-700 rounded-lg focus:ring-primary-red focus:border-primary-red text-white placeholder-gray-500">
                            <button id="add-genre-btn" class="bg-primary-red hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl shadow-md transition duration-300">
                                Add
                            </button>
                        </div>
                        <p id="genre-message" class="text-sm mt-3 text-green-400 hidden">Genre added successfully!</p>
                    </div>

                    <!-- Existing Genres List -->
                    <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-white/10">
                        <h3 class="text-xl font-semibold text-white mb-4">Existing Genres</h3>
                        <div class="flex flex-wrap gap-3" id="genre-list-container">
                            <span class="bg-primary-red/20 text-primary-red text-sm font-medium px-3 py-1 rounded-full flex items-center">Action <button class="ml-2 text-primary-red/70 hover:text-white transition">x</button></span>
                            <span class="bg-primary-red/20 text-primary-red text-sm font-medium px-3 py-1 rounded-full flex items-center">Drama <button class="ml-2 text-primary-red/70 hover:text-white transition">x</button></span>
                            <span class="bg-primary-red/20 text-primary-red text-sm font-medium px-3 py-1 rounded-full flex items-center">Comedy <button class="ml-2 text-primary-red/70 hover:text-white transition">x</button></span>
                            <span class="bg-primary-red/20 text-primary-red text-sm font-medium px-3 py-1 rounded-full flex items-center">Romance <button class="ml-2 text-primary-red/70 hover:text-white transition">x</button></span>
                        </div>
                    </div>
                </section>
                
                <!-- 6. Profile View (Colors updated) -->
                <section id="profile" class="view-content hidden" data-title="Admin Profile">
                    <h2 class="text-3xl font-bold mb-6 text-white border-l-4 border-primary-red pl-4">Admin Profile</h2>

                    <div class="bg-gray-800 p-8 rounded-xl shadow-lg border border-white/10 max-w-2xl">
                        <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-8">
                            <!-- Profile Image Mock (Background color updated) -->
                            <div class="w-24 h-24 bg-primary-red rounded-full flex items-center justify-center text-4xl font-bold text-white flex-shrink-0">
                                A
                            </div>
                            <!-- Profile Details (Text color updated) -->
                            <div class="text-center sm:text-left">
                                <h3 class="text-2xl font-bold text-white">The Administrator</h3>
                                <p class="text-primary-red mb-4">Super User / Movie Lab Authority</p>
                                <div class="space-y-2 text-sm">
                                    <p class="text-gray-400">Email: <span class="text-white">admin@movielab.lk</span></p>
                                    <p class="text-gray-400">Role: <span class="text-white">Full Access (Level 1)</span></p>
                                    <p class="text-gray-400">Last Login: <span class="text-white">2025-11-26, 1:40 PM</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 pt-6 border-t border-gray-700">
                            <h4 class="text-lg font-semibold text-white mb-3">Update Contact Information</h4>
                            <form class="space-y-4">
                                <input type="text" placeholder="Full Name" value="The Administrator" class="w-full p-3 bg-gray-900 border border-gray-700 rounded-lg focus:ring-primary-red focus:border-primary-red text-white placeholder-gray-500">
                                <button type="submit" class="bg-primary-red hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl shadow-md transition duration-300">
                                    Save Changes
                                </button>
                            </form>
                        </div>
                    </div>
                </section>

                <!-- 7. Settings View (Colors updated) -->
                <section id="settings" class="view-content hidden" data-title="System Settings">
                    <h2 class="text-3xl font-bold mb-6 text-white border-l-4 border-primary-red pl-4">System Settings</h2>

                    <!-- Theme Switcher (Text color updated) -->
                    <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-white/10 mb-8 max-w-xl">
                        <h3 class="text-xl font-semibold text-white mb-4">Display Theme</h3>
                        <div class="flex items-center justify-between">
                            <p class="text-gray-400">Current Theme: <span id="current-theme-display" class="font-bold text-primary-red">Dark</span></p>
                            <!-- Toggle Switch (Background color updated) -->
                            <label class="flex items-center cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" id="theme-toggle" class="sr-only">
                                    <div class="block bg-gray-600 w-14 h-8 rounded-full transition duration-300" id="toggle-bg"></div>
                                    <!-- Dot color is set via JS based on state -->
                                    <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition duration-300" id="toggle-dot"></div>
                                </div>
                                <div class="ml-3 text-gray-400 font-medium">
                                    Light Mode
                                </div>
                            </label>
                        </div>
                        <p class="mt-4 text-xs text-gray-500">Note: The theme preference is set for this session.</p>
                    </div>
                    
                    <!-- Other Settings Mock -->
                    <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-white/10 max-w-xl">
                        <h3 class="text-xl font-semibold text-white mb-4">System Actions</h3>
                        <!-- Button color updated -->
                        <button class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl shadow-md transition duration-300">
                            Force System Sync
                        </button>
                    </div>
                </section>

            </div>

            <!-- Custom Alert Modal (Button color updated) -->
            <div id="custom-alert-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden items-center justify-center z-50 p-4 transition-opacity duration-300">
                <div class="bg-white p-6 rounded-xl shadow-2xl max-w-sm w-full transform transition-all duration-300 scale-95 opacity-0" id="alert-content">
                    <h4 class="text-xl font-bold text-gray-900 mb-3">System Notification</h4>
                    <p id="alert-message" class="text-gray-700 mb-4"></p>
                    <button onclick="document.getElementById('custom-alert-modal').classList.add('hidden'); document.getElementById('custom-alert-modal').classList.remove('flex');" class="w-full bg-primary-red hover:bg-red-700 text-white font-bold py-2 rounded-lg transition">
                        OK
                    </button>
                </div>
            </div>

        </main>
    </div>

    <!-- Firebase Imports and Logic -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-app.js";
        import { getAuth, signInAnonymously, signInWithCustomToken, onAuthStateChanged, signOut } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-auth.js";
        import { getFirestore, doc, addDoc, onSnapshot, collection, query, setLogLevel } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-firestore.js";

        // --- GLOBAL CONFIGURATION AND UTILITIES ---
        const PRIMARY_RED_HEX = '#E50914'; // New requested bright red color

        // Mandatory global variables from Canvas environment
        const appId = typeof __app_id !== 'undefined' ? __app_id : 'default-app-id';
        const firebaseConfig = typeof __firebase_config !== 'undefined' ? JSON.parse(__firebase_config) : null;
        const initialAuthToken = typeof __initial_auth_token !== 'undefined' ? __initial_auth_token : null;

        let app, db, auth, userId = null;
        let movieLabCharts = {}; // Stores Chart.js instances

        // Function to display custom alert (replaces alert())
        function showAlert(message) {
            const modal = document.getElementById('custom-alert-modal');
            const content = document.getElementById('alert-content');
            document.getElementById('alert-message').textContent = message;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            // Animate in
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
        
        // --- CHART.JS LOGIC ---

        /**
         * Determines chart colors based on the current theme.
         */
        function getChartColors(isLight) {
            const primaryRed = PRIMARY_RED_HEX; // Use the new constant
            const darkText = '#1F2937';
            const lightText = '#F3F4F6';
            const greyText = isLight ? '#6B7280' : '#9CA3AF'; // Gray-500 or Gray-400

            return {
                primary: primaryRed,
                // Adjusted secondary color to match the new bright red hue
                secondary: '#F87171', // A slightly lighter red for contrast/gradients
                text: isLight ? darkText : lightText,
                grid: isLight ? '#E5E7EB' : '#374151',
                grey: greyText
            };
        }
        
        /**
         * Destroys existing chart instances.
         */
        function destroyCharts() {
            Object.values(movieLabCharts).forEach(chart => chart.destroy());
            movieLabCharts = {};
        }

        /**
         * Initializes and renders all dashboard charts.
         */
        function createCharts() {
            destroyCharts(); // Ensure previous charts are destroyed

            const isLight = document.body.classList.contains('light-theme');
            const colors = getChartColors(isLight);
            
            // Global Chart Defaults (to handle text color)
            Chart.defaults.color = colors.grey;
            Chart.defaults.font.family = 'Inter, sans-serif';
            Chart.defaults.borderColor = colors.grid;
            Chart.defaults.animation = { duration: 1000, easing: 'easeOutQuart' };

            // 1. Revenue Trend Chart (Line)
            movieLabCharts.revenue = new Chart(document.getElementById('revenueChart'), {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Revenue (LKR Mn)',
                        data: [5.2, 5.5, 6.1, 7.0, 6.8, 7.5, 8.2, 8.5, 9.0, 9.5, 10.1, 11.0],
                        borderColor: colors.primary,
                        backgroundColor: colors.primary + '33', // Red with 20% opacity
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: colors.primary,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { labels: { color: colors.grey, boxWidth: 10 } },
                        title: { display: false }
                    },
                    scales: {
                        y: {
                            ticks: { color: colors.grey },
                            grid: { color: colors.grid, drawBorder: false },
                            title: { display: true, text: 'LKR (Millions)', color: colors.grey }
                        },
                        x: {
                            ticks: { color: colors.grey },
                            grid: { display: false, color: colors.grid }
                        }
                    }
                }
            });

            // 2. User Growth Chart (Bar)
            movieLabCharts.userGrowth = new Chart(document.getElementById('userGrowthChart'), {
                type: 'bar',
                data: {
                    labels: ['Q1 2025', 'Q2 2025', 'Q3 2025', 'Q4 2025'],
                    datasets: [{
                        label: 'New Users',
                        data: [4500, 5200, 6100, 7500],
                        // Use color.primary for bars
                        backgroundColor: colors.primary, 
                        borderRadius: 5,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false } 
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { color: colors.grey },
                            grid: { color: colors.grid, drawBorder: false },
                        },
                        x: {
                            ticks: { color: colors.grey },
                            grid: { display: false }
                        }
                    }
                }
            });

            // 3. Genre Popularity Chart (Doughnut)
            const genreData = {
                labels: ['Action', 'Drama', 'Comedy', 'Thriller', 'Others'],
                datasets: [{
                    data: [35, 22, 15, 10, 18], // Percentage distribution
                    backgroundColor: [
                        colors.primary,
                        colors.secondary, // Uses the adjusted secondary red
                        '#FCA5A5', // Red-300
                        '#FEE2E2', // Red-100
                        '#808080'  // Grey
                    ],
                    hoverOffset: 8,
                    borderWidth: 2,
                    borderColor: isLight ? '#FFFFFF' : '#1F2937' // Border color matching card/body background
                }]
            };

            movieLabCharts.genre = new Chart(document.getElementById('genrePopularityChart'), {
                type: 'doughnut',
                data: genreData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { position: 'right', labels: { color: colors.grey, boxWidth: 10 } },
                        tooltip: { 
                            callbacks: { 
                                label: (context) => context.label + ': ' + context.formattedValue + '%' 
                            } 
                        }
                    },
                }
            });
        }

        // --- FIREBASE INITIALIZATION AND AUTHENTICATION ---
        window.addEventListener('DOMContentLoaded', async () => {
            const loadingOverlay = document.getElementById('loading-overlay');
            
            if (!firebaseConfig) {
                // HIDE LOADING OVERLAY IMMEDIATELY IF NO CONFIG
                loadingOverlay.style.opacity = '0';
                setTimeout(() => loadingOverlay.classList.add('hidden'), 300);
                // REMOVED: showAlert("Firebase configuration not found. Functionality will be limited to UI simulation.");
                createCharts(); // Render charts even without Firebase
                return;
            }

            try {
                // Initialize Firebase services
                setLogLevel('Debug');
                app = initializeApp(firebaseConfig);
                db = getFirestore(app);
                auth = getAuth(app);

                // Handle Authentication
                let authReadyPromise = new Promise(resolve => {
                    const unsubscribe = onAuthStateChanged(auth, async (user) => {
                        if (user) {
                            userId = user.uid;
                            document.getElementById('auth-status').textContent = 'Authenticated (User)';
                            document.getElementById('user-id-display').textContent = userId;
                            console.log("Firebase Auth State Changed. UID:", userId);
                        } else {
                            userId = null;
                            document.getElementById('auth-status').textContent = 'Anonymous';
                            document.getElementById('user-id-display').textContent = 'ANON';
                            // If sign-in failed or no user, try anonymous sign-in
                            if (!initialAuthToken) {
                                await signInAnonymously(auth);
                            }
                        }
                        resolve(true); 
                    });
                });

                // Sign in with custom token if available
                if (initialAuthToken) {
                    await signInWithCustomToken(auth, initialAuthToken);
                } else {
                    await signInAnonymously(auth); // Ensure anonymous sign-in if no token
                }

                await authReadyPromise;

                createCharts();

                if (userId) {
                    setupContentListener();
                } else {
                    console.log("Authentication still pending or failed after initial attempt.");
                }

            } catch (error) {
                console.error("Firebase initialization or authentication error:", error);
                showAlert("System Initialization Error: " + error.message);
            } finally {
                // Hide loading screen after initialization attempt
                loadingOverlay.style.opacity = '0';
                setTimeout(() => loadingOverlay.classList.add('hidden'), 300);
            }
        });


        // --- FIRESTORE LISTENERS ---

        /**
         * Sets up real-time listener for the 'movies' collection.
         */
        function setupContentListener() {
            if (!db || !userId) return;

            // Use the public collection path for shared content visibility in a movie lab context
            const contentCollectionPath = `/artifacts/${appId}/public/data/movies`;
            const q = collection(db, contentCollectionPath);

            onSnapshot(q, (snapshot) => {
                const contentListBody = document.getElementById('content-list-table-body');
                contentListBody.innerHTML = ''; // Clear existing list

                if (snapshot.empty) {
                    contentListBody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-gray-500">No content found in database.</td></tr>';
                    return;
                }

                snapshot.docs.forEach(doc => {
                    const data = doc.data();
                    const docId = doc.id;
                    
                    const statusClass = data.isActive ? 'bg-green-800 text-green-300' : 'bg-red-800 text-red-300';
                    const statusText = data.isActive ? 'Active' : 'Deactivated';

                    const row = `
                        <tr class="hover:bg-gray-700 transition">
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-white">${data.title}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-400 hidden sm:table-cell">${data.releaseYear}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm hidden md:table-cell"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">${statusText}</span></td>
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button class="text-white hover:text-primary-red mr-3" onclick="handleContentAction('${docId}', 'edit')">Edit</button>
                                <button class="text-red-500 hover:text-red-300" onclick="handleContentAction('${docId}', 'delete')">Delete</button>
                            </td>
                        </tr>
                    `;
                    contentListBody.innerHTML += row;
                });
            }, (error) => {
                console.error("Error listening to content data: ", error);
                showAlert("Real-time data synchronization failed.");
            });
        }


        // --- FIREBASE WRITE OPERATIONS (Add Content) ---

        /**
         * Handles the form submission for adding new content.
         */
        document.getElementById('add-content-form')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            if (!db || !userId) {
                showAlert("Database not connected. Please check system status.");
                return;
            }

            const form = e.target;
            const messageElement = document.getElementById('add-content-message');

            const contentData = {
                title: form['content-title'].value,
                releaseYear: parseInt(form['content-year'].value),
                language: form['content-language'].value,
                genres: Array.from(form['content-genre'].options).filter(option => option.selected).map(option => option.value),
                castList: form['content-cast'].value.split(',').map(s => s.trim()).filter(s => s.length > 0),
                synopsis: form['content-synopsis'].value,
                mediaUrl: form['content-media'].value,
                posterUrl: form['content-poster'].value,
                drmApplied: form['content-drm'].checked,
                isActive: true, // Default to active upon addition
                createdAt: new Date().toISOString(),
                addedBy: userId
            };

            messageElement.className = 'text-center text-sm mt-3 text-yellow-400';
            messageElement.textContent = 'Adding content... Please wait.';

            try {
                // Use the public collection path
                const contentCollectionPath = `/artifacts/${appId}/public/data/movies`;
                await addDoc(collection(db, contentCollectionPath), contentData);

                messageElement.className = 'text-center text-sm mt-3 text-green-400';
                messageElement.textContent = `Movie/Song "${contentData.title}" added successfully!`;
                form.reset();
            } catch (error) {
                console.error("Error adding content: ", error);
                messageElement.className = 'text-center text-sm mt-3 text-red-400';
                messageElement.textContent = `Failed to add content. Error: ${error.message}`;
            }
        });

        // --- UI LOGIC ---
        
        // Function to switch between main views
        function switchView(viewId) {
            document.querySelectorAll('.view-content').forEach(view => {
                view.classList.add('hidden');
                view.classList.remove('active');
            });
            document.getElementById(viewId).classList.remove('hidden');
            document.getElementById(viewId).classList.add('active');

            // Update navigation item active state (using primary-red/70)
            document.querySelectorAll('.nav-item a').forEach(item => {
                item.classList.remove('bg-primary-red/70', 'font-bold');
                if (item.parentElement.dataset.view === viewId) {
                     item.classList.add('bg-primary-red/70', 'font-bold');
                }
            });
            
            // Close mobile menu if open
            const navLinks = document.getElementById('nav-links');
            if (window.innerWidth < 1024 && navLinks.classList.contains('block')) {
                navLinks.classList.remove('block');
                navLinks.classList.add('hidden');
            }
        }

        // Function to switch between CMS tabs
        function switchCmsTab(tabId) {
            document.querySelectorAll('.cms-tab-content').forEach(tab => tab.classList.add('hidden'));
            document.getElementById(tabId).classList.remove('hidden');
            
            // Update CMS tab active state (using border-primary-red)
            document.querySelectorAll('.cms-tab').forEach(tabBtn => {
                tabBtn.classList.remove('active-cms-tab', 'border-primary-red', 'text-white');
                tabBtn.classList.add('text-gray-400');
                if (tabBtn.dataset.tab === tabId) {
                    tabBtn.classList.add('active-cms-tab', 'border-primary-red', 'text-white');
                    tabBtn.classList.remove('text-gray-400');
                }
            });
        }
        
        // Function to toggle theme
        function toggleTheme(isLight) {
            const body = document.body;
            const themeDisplay = document.getElementById('current-theme-display');
            const toggleBg = document.getElementById('toggle-bg');
            const toggleDot = document.getElementById('toggle-dot');

            if (isLight) {
                body.classList.add('light-theme');
                themeDisplay.textContent = 'Light';
                toggleBg.classList.remove('bg-gray-600');
                toggleBg.classList.add('bg-primary-red'); // New red color for light mode switch background
                toggleDot.classList.remove('left-1');
                toggleDot.classList.add('translate-x-full', 'bg-white');
            } else {
                body.classList.remove('light-theme');
                themeDisplay.textContent = 'Dark';
                toggleBg.classList.add('bg-gray-600');
                toggleBg.classList.remove('bg-primary-red');
                toggleDot.classList.add('left-1');
                toggleDot.classList.remove('translate-x-full', 'bg-white');
            }
            
            // Rerender charts to update colors for the new theme
            createCharts();
        }
        
        // Mock Logout Handler
        document.getElementById('logout-btn')?.addEventListener('click', async () => {
            if (!auth) {
                showAlert("System not fully initialized. Cannot perform logout.");
                return;
            }
            
            try {
                showAlert("Logging out user... Please wait.");
                await signOut(auth);
                // Auth state listener handles UI update
            } catch(error) {
                console.error("Logout failed:", error);
                showAlert(`Logout attempt failed. Error: ${error.message}`);
            }
        });

        // Event listeners for navigation
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                switchView(item.dataset.view);
            });
        });

        // Event listeners for CMS tabs
        document.querySelectorAll('.cms-tab').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                switchCmsTab(item.dataset.tab);
            });
        });
        
        // Add event listener to the theme toggle switch
        document.getElementById('theme-toggle')?.addEventListener('change', (e) => {
            toggleTheme(e.target.checked);
        });

        // Global handler for content actions (since Firestore operations are not implemented fully)
        window.handleContentAction = (docId, action) => {
            showAlert(`${action.toUpperCase()} action requested for content ID: ${docId}. (Implementation pending: Deactivate/Delete logic goes here)`);
        };

        // Initial view setup
        switchView('dashboard');

        // Mobile Menu Toggle
        document.getElementById('menu-btn').addEventListener('click', () => {
            document.getElementById('nav-links').classList.toggle('hidden');
            document.getElementById('nav-links').classList.toggle('block');
        });
    </script>
</body>
</html>