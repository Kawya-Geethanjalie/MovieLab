<?php 
include("../include/header.php");
?>

<!-- ******************** -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoviLab - Your Ultimate Movie & Music Destination</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary-dark: #000000;
            --primary-light: #1a1a1a;
            --accent-red: #dc2626;
            --accent-light-red: #ef4444;
            --text-light: #ffffff;
            --text-gray: #9ca3af;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--primary-dark);
            color: var(--text-light);
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--primary-light);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--accent-red);
            border-radius: 4px;
        }
        /* Custom animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        /* Parallax effect for carousel */
        .parallax-bg {
            transform: scale(1.1);
            transition: transform 10s ease;
        }
        .carousel-slide:hover .parallax-bg {
            transform: scale(1);
        }
        /* Card hover effects */
        .movie-card, .song-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .movie-card:hover, .song-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(220, 38, 38, 0.3);
        }
        /* Mobile menu animation */
        .mobile-menu {
            transform: translateY(-100%);
            transition: transform 0.3s ease;
        }
        .mobile-menu.open {
            transform: translateY(0);
        }
        /* Modal animations */
        .modal {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .modal.open {
            opacity: 1;
            visibility: visible;
        }
        .modal-content {
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }
        .modal.open .modal-content {
            transform: scale(1);
        }
        /* Notification badge */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--accent-red);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        /* Responsive Dropdown */
        .dropdown-content {
            transform: translateY(10px);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        .dropdown:hover .dropdown-content {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        /* Responsive Hero */
        .hero-section {
            height: 70vh;
        }
        @media (min-width: 768px) {
            .hero-section {
                height: 80vh;
            }
        }
        /* Responsive Grid */
        .movie-grid {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        }
        @media (min-width: 640px) {
            .movie-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }
        @media (min-width: 1024px) {
            .movie-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
        }
    </style>
</head>
<body class="bg-black text-white">
 

    <!-- Hero Carousel -->
    <section class="hero-section relative overflow-hidden">
        <!-- Carousel Slides -->
        <div id="carousel" class="relative h-full">
            <!-- Slide 1 -->
            <div class="carousel-slide absolute inset-0 w-full h-full fade-in">
                <div class="parallax-bg absolute inset-0 w-full h-full bg-cover bg-center" style="background-image: url('https://source.unsplash.com/random/1920x1080/?movie,action')"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/70 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6 md:p-12">
                    <div class="container mx-auto">
                        <h2 class="text-2xl md:text-4xl font-bold mb-3">The Last Frontier</h2>
                        <p class="text-gray-300 mb-4 max-w-xl">Action • Adventure • 2024</p>
                        <p class="text-gray-300 mb-6 max-w-xl">In a post-apocalyptic world, a group of survivors must navigate dangerous territories to find the last safe haven.</p>
                        <div class="flex flex-wrap gap-3">
                            <button class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center text-sm md:text-base">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Play Trailer
                            </button>
                            <button class="px-5 py-2.5 border border-white text-white rounded-lg hover:bg-white/10 transition-colors flex items-center text-sm md:text-base">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                Add to Watchlist
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Slide 2 -->
            <div class="carousel-slide absolute inset-0 w-full h-full hidden">
                <div class="parallax-bg absolute inset-0 w-full h-full bg-cover bg-center" style="background-image: url('https://source.unsplash.com/random/1920x1080/?movie,drama')"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/70 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6 md:p-12">
                    <div class="container mx-auto">
                        <h2 class="text-2xl md:text-4xl font-bold mb-3">Echoes of Yesterday</h2>
                        <p class="text-gray-300 mb-4 max-w-xl">Drama • Romance • 2023</p>
                        <p class="text-gray-300 mb-6 max-w-xl">A heartwarming story about second chances and the power of memory in a small coastal town.</p>
                        <div class="flex flex-wrap gap-3">
                            <button class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center text-sm md:text-base">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Play Trailer
                            </button>
                            <button class="px-5 py-2.5 border border-white text-white rounded-lg hover:bg-white/10 transition-colors flex items-center text-sm md:text-base">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                Add to Watchlist
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Slide 3 -->
            <div class="carousel-slide absolute inset-0 w-full h-full hidden">
                <div class="parallax-bg absolute inset-0 w-full h-full bg-cover bg-center" style="background-image: url('https://source.unsplash.com/random/1920x1080/?movie,horror')"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/70 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6 md:p-12">
                    <div class="container mx-auto">
                        <h2 class="text-2xl md:text-4xl font-bold mb-3">Shadow Realm</h2>
                        <p class="text-gray-300 mb-4 max-w-xl">Horror • Thriller • 2024</p>
                        <p class="text-gray-300 mb-6 max-w-xl">A family moves into their dream home, only to discover it holds a dark secret that threatens to consume them.</p>
                        <div class="flex flex-wrap gap-3">
                            <button class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center text-sm md:text-base">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Play Trailer
                            </button>
                            <button class="px-5 py-2.5 border border-white text-white rounded-lg hover:bg-white/10 transition-colors flex items-center text-sm md:text-base">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                Add to Watchlist
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Slide 4 -->
            <div class="carousel-slide absolute inset-0 w-full h-full hidden">
                <div class="parallax-bg absolute inset-0 w-full h-full bg-cover bg-center" style="background-image: url('https://source.unsplash.com/random/1920x1080/?movie,sci-fi')"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/70 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6 md:p-12">
                    <div class="container mx-auto">
                        <h2 class="text-2xl md:text-4xl font-bold mb-3">Cosmic Drifters</h2>
                        <p class="text-gray-300 mb-4 max-w-xl">Sci-Fi • Adventure • 2025</p>
                        <p class="text-gray-300 mb-6 max-w-xl">A team of interstellar explorers discovers an ancient alien civilization that holds the key to humanity's future.</p>
                        <div class="flex flex-wrap gap-3">
                            <button class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center text-sm md:text-base">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Play Trailer
                            </button>
                            <button class="px-5 py-2.5 border border-white text-white rounded-lg hover:bg-white/10 transition-colors flex items-center text-sm md:text-base">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                Add to Watchlist
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Carousel Controls -->
        <button id="prev-btn" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white p-2 rounded-full transition-colors md:p-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        <button id="next-btn" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white p-2 rounded-full transition-colors md:p-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
        <!-- Carousel Indicators -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
            <button class="carousel-indicator w-2.5 h-2.5 rounded-full bg-white/50 hover:bg-white transition-colors active"></button>
            <button class="carousel-indicator w-2.5 h-2.5 rounded-full bg-white/50 hover:bg-white transition-colors"></button>
            <button class="carousel-indicator w-2.5 h-2.5 rounded-full bg-white/50 hover:bg-white transition-colors"></button>
            <button class="carousel-indicator w-2.5 h-2.5 rounded-full bg-white/50 hover:bg-white transition-colors"></button>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Filter Bar -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div class="flex flex-wrap gap-2">
                <button class="px-3 py-1.5 md:px-4 md:py-2 bg-red-600 text-white rounded-lg text-sm">All Movies</button>
                <button class="px-3 py-1.5 md:px-4 md:py-2 bg-gray-900 text-gray-300 hover:bg-gray-800 rounded-lg transition-colors text-sm">Popular</button>
                <button class="px-3 py-1.5 md:px-4 md:py-2 bg-gray-900 text-gray-300 hover:bg-gray-800 rounded-lg transition-colors text-sm">New</button>
                <button class="px-3 py-1.5 md:px-4 md:py-2 bg-gray-900 text-gray-300 hover:bg-gray-800 rounded-lg transition-colors text-sm">Top Rated</button>
            </div>
            
        </div>

        <!-- Music Section -->
        <section class="mb-10">
            <h2 class="text-xl md:text-2xl font-bold mb-5">Popular Songs</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Song Card 1 -->
                <div class="song-card bg-gray-900 rounded-lg overflow-hidden relative group">
                    <div class="relative">
                        <img src="https://source.unsplash.com/random/300x300/?music,sinhala" alt="Song cover" class="w-full h-40 md:h-48 object-cover" loading="lazy">
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <button class="p-2.5 bg-red-600 rounded-full text-white hover:bg-red-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-3 md:p-4">
                        <h3 class="font-semibold text-base md:text-lg mb-1">Sinhala Hit Song</h3>
                        <p class="text-gray-400 text-xs md:text-sm mb-1.5">Artist Name</p>
                        <div class="flex justify-between items-center text-xs md:text-sm">
                            <span class="text-gray-400">Sinhala</span>
                            <span class="text-gray-400">3:45</span>
                        </div>
                    </div>
                </div>
                <!-- Song Card 2 -->
                <div class="song-card bg-gray-900 rounded-lg overflow-hidden relative group">
                    <div class="relative">
                        <img src="https://source.unsplash.com/random/300x300/?music,english" alt="Song cover" class="w-full h-40 md:h-48 object-cover" loading="lazy">
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <button class="p-2.5 bg-red-600 rounded-full text-white hover:bg-red-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-3 md:p-4">
                        <h3 class="font-semibold text-base md:text-lg mb-1">English Pop Hit</h3>
                        <p class="text-gray-400 text-xs md:text-sm mb-1.5">International Artist</p>
                        <div class="flex justify-between items-center text-xs md:text-sm">
                            <span class="text-gray-400">English</span>
                            <span class="text-gray-400">4:12</span>
                        </div>
                    </div>
                </div>
                <!-- Song Card 3 -->
                <div class="song-card bg-gray-900 rounded-lg overflow-hidden relative group">
                    <div class="relative">
                        <img src="https://source.unsplash.com/random/300x300/?music,hindi" alt="Song cover" class="w-full h-40 md:h-48 object-cover" loading="lazy">
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <button class="p-2.5 bg-red-600 rounded-full text-white hover:bg-red-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-3 md:p-4">
                        <h3 class="font-semibold text-base md:text-lg mb-1">Bollywood Hit</h3>
                        <p class="text-gray-400 text-xs md:text-sm mb-1.5">Bollywood Star</p>
                        <div class="flex justify-between items-center text-xs md:text-sm">
                            <span class="text-gray-400">Hindi</span>
                            <span class="text-gray-400">5:23</span>
                        </div>
                    </div>
                </div>
                <!-- Song Card 4 -->
                <div class="song-card bg-gray-900 rounded-lg overflow-hidden relative group">
                    <div class="relative">
                        <img src="https://source.unsplash.com/random/300x300/?music,pop" alt="Song cover" class="w-full h-40 md:h-48 object-cover" loading="lazy">
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <button class="p-2.5 bg-red-600 rounded-full text-white hover:bg-red-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-3 md:p-4">
                        <h3 class="font-semibold text-base md:text-lg mb-1">Latest Release</h3>
                        <p class="text-gray-400 text-xs md:text-sm mb-1.5">New Artist</p>
                        <div class="flex justify-between items-center text-xs md:text-sm">
                            <span class="text-gray-400">English</span>
                            <span class="text-gray-400">3:58</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Movie Grid -->
        <section>
            <h2 class="text-xl md:text-2xl font-bold mb-5">Featured Movies</h2>
            <div class="movie-grid grid gap-5">
                <!-- Movie Card 1 -->
                <div class="movie-card bg-gray-900 rounded-lg overflow-hidden relative group">
                    <div class="relative">
                        <img src="https://source.unsplash.com/random/300x450/?movie,action" alt="Movie poster" class="w-full h-48 md:h-64 object-cover" loading="lazy">
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <button class="p-2.5 bg-red-600 rounded-full text-white hover:bg-red-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-3 md:p-4">
                        <div class="flex justify-between items-start mb-1.5">
                            <h3 class="font-semibold text-base md:text-lg">The Last Frontier</h3>
                            <span class="text-gray-400 text-xs md:text-sm">2024</span>
                        </div>
                        <div class="flex flex-wrap gap-1 mb-2">
                            <span class="px-1.5 py-0.5 bg-gray-800 text-gray-300 text-xs rounded">Action</span>
                            <span class="px-1.5 py-0.5 bg-gray-800 text-gray-300 text-xs rounded">Adventure</span>
                        </div>
                        <p class="text-gray-400 text-xs md:text-sm mb-2">In a post-apocalyptic world, survivors search for the last safe haven against all odds.</p>
                        <div class="flex items-center">
                            <div class="flex text-yellow-400 mr-1.5">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                            <span class="text-gray-400 text-xs md:text-sm">4.0</span>
                        </div>
                    </div>
                </div>
                <!-- Movie Card 2 -->
                <div class="movie-card bg-gray-900 rounded-lg overflow-hidden relative group">
                    <div class="relative">
                        <img src="https://source.unsplash.com/random/300x450/?movie,drama" alt="Movie poster" class="w-full h-48 md:h-64 object-cover" loading="lazy">
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <button class="p-2.5 bg-red-600 rounded-full text-white hover:bg-red-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-3 md:p-4">
                        <div class="flex justify-between items-start mb-1.5">
                            <h3 class="font-semibold text-base md:text-lg">Echoes of Yesterday</h3>
                            <span class="text-gray-400 text-xs md:text-sm">2023</span>
                        </div>
                        <div class="flex flex-wrap gap-1 mb-2">
                            <span class="px-1.5 py-0.5 bg-gray-800 text-gray-300 text-xs rounded">Drama</span>
                            <span class="px-1.5 py-0.5 bg-gray-800 text-gray-300 text-xs rounded">Romance</span>
                        </div>
                        <p class="text-gray-400 text-xs md:text-sm mb-2">A heartwarming story about second chances and the power of memory in a small coastal town.</p>
                        <div class="flex items-center">
                            <div class="flex text-yellow-400 mr-1.5">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </div>
                            <span class="text-gray-400 text-xs md:text-sm">4.8</span>
                        </div>
                    </div>
                </div>
                <!-- Movie Card 3 -->
                <div class="movie-card bg-gray-900 rounded-lg overflow-hidden relative group">
                    <div class="relative">
                        <img src="https://source.unsplash.com/random/300x450/?movie,horror" alt="Movie poster" class="w-full h-48 md:h-64 object-cover" loading="lazy">
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <button class="p-2.5 bg-red-600 rounded-full text-white hover:bg-red-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-3 md:p-4">
                        <div class="flex justify-between items-start mb-1.5">
                            <h3 class="font-semibold text-base md:text-lg">Shadow Realm</h3>
                            <span class="text-gray-400 text-xs md:text-sm">2024</span>
                        </div>
                        <div class="flex flex-wrap gap-1 mb-2">
                            <span class="px-1.5 py-0.5 bg-gray-800 text-gray-300 text-xs rounded">Horror</span>
                            <span class="px-1.5 py-0.5 bg-gray-800 text-gray-300 text-xs rounded">Thriller</span>
                        </div>
                        <p class="text-gray-400 text-xs md:text-sm mb-2">A family moves into their dream home, only to discover it holds a dark secret that threatens to consume them.</p>
                        <div class="flex items-center">
                            <div class="flex text-yellow-400 mr-1.5">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                            <span class="text-gray-400 text-xs md:text-sm">4.2</span>
                        </div>
                    </div>
                </div>
                <!-- Movie Card 4 -->
                <div class="movie-card bg-gray-900 rounded-lg overflow-hidden relative group">
                    <div class="relative">
                        <img src="https://source.unsplash.com/random/300x450/?movie,sci-fi" alt="Movie poster" class="w-full h-48 md:h-64 object-cover" loading="lazy">
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <button class="p-2.5 bg-red-600 rounded-full text-white hover:bg-red-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-3 md:p-4">
                        <div class="flex justify-between items-start mb-1.5">
                            <h3 class="font-semibold text-base md:text-lg">Cosmic Drifters</h3>
                            <span class="text-gray-400 text-xs md:text-sm">2025</span>
                        </div>
                        <div class="flex flex-wrap gap-1 mb-2">
                            <span class="px-1.5 py-0.5 bg-gray-800 text-gray-300 text-xs rounded">Sci-Fi</span>
                            <span class="px-1.5 py-0.5 bg-gray-800 text-gray-300 text-xs rounded">Adventure</span>
                        </div>
                        <p class="text-gray-400 text-xs md:text-sm mb-2">A team of interstellar explorers discovers an ancient alien civilization that holds the key to humanity's future.</p>
                        <div class="flex items-center">
                            <div class="flex text-yellow-400 mr-1.5">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                            <span class="text-gray-400 text-xs md:text-sm">4.5</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Sticky CTA Panel -->
   
    <!-- Mobile FAB -->
    <div class="fixed bottom-4 right-4 z-40 md:hidden">
        <button id="mobile-fab" class="p-3 bg-gradient-to-r from-red-600 to-red-800 text-white rounded-full shadow-lg hover:opacity-90 transition-opacity">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        </button>
    </div>
   
    



    
    
    
    

    <!-- Success Message -->
    <div id="success-message" class="fixed top-4 right-4 z-50 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg hidden">
        <div class="flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span id="success-text" class="text-sm">Operation completed successfully!</span>
        </div>
    </div>

    <script>
        // DOM Elements
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const carousel = document.getElementById('carousel');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const carouselIndicators = document.querySelectorAll('.carousel-indicator');
        const carouselSlides = document.querySelectorAll('.carousel-slide');
        const proBtns = document.querySelectorAll('#pro-btn, #mobile-pro-btn, #sticky-pro-btn, #mobile-fab-pro-btn');
        const signinBtns = document.querySelectorAll('#signin-btn, #mobile-signin-btn, #sticky-signin-btn, #mobile-fab-signin-btn');
        const pricingModal = document.getElementById('pricing-modal');
        const closePricingModal = document.getElementById('close-pricing-modal');
        const signinModal = document.getElementById('signin-modal');
        const closeSigninModal = document.getElementById('close-signin-modal');
        const registerModal = document.getElementById('register-modal');
        const closeRegisterModal = document.getElementById('close-register-modal');
        const switchToRegister = document.getElementById('switch-to-register');
        const switchToSignin = document.getElementById('switch-to-signin');
        const signinForm = document.getElementById('signin-form');
        const registerForm = document.getElementById('register-form');
        const mobileFab = document.getElementById('mobile-fab');
        const mobileFabMenu = document.getElementById('mobile-fab-menu');
        const successMessage = document.getElementById('success-message');
        const successText = document.getElementById('success-text');
        // Carousel State
        let currentSlide = 0;
        let autoPlayInterval;
        // Initialize the page
        function init() {
            // Set up event listeners
            setupEventListeners();
            // Start auto-play for carousel
            startAutoPlay();
            // Set initial active slide
            updateCarousel();
        }
        // Set up all event listeners
        function setupEventListeners() {
            // Mobile menu toggle
            mobileMenuBtn.addEventListener('click', toggleMobileMenu);
            // Carousel controls
            prevBtn.addEventListener('click', showPrevSlide);
            nextBtn.addEventListener('click', showNextSlide);
            // Carousel indicators
            carouselIndicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => showSlide(index));
            });
            // Modal open/close
            proBtns.forEach(btn => {
                btn.addEventListener('click', () => openModal(pricingModal));
            });
            signinBtns.forEach(btn => {
                btn.addEventListener('click', () => openModal(signinModal));
            });
            closePricingModal.addEventListener('click', () => closeModal(pricingModal));
            closeSigninModal.addEventListener('click', () => closeModal(signinModal));
            closeRegisterModal.addEventListener('click', () => closeModal(registerModal));
            // Auth form switching
            switchToRegister.addEventListener('click', () => {
                closeModal(signinModal);
                openModal(registerModal);
            });
            switchToSignin.addEventListener('click', () => {
                closeModal(registerModal);
                openModal(signinModal);
            });
            // Form submissions
            signinForm.addEventListener('submit', handleSignin);
            registerForm.addEventListener('submit', handleRegister);
            // Mobile FAB
            mobileFab.addEventListener('click', toggleMobileFabMenu);
            // Close modals when clicking outside
            window.addEventListener('click', (e) => {
                if (e.target === pricingModal) closeModal(pricingModal);
                if (e.target === signinModal) closeModal(signinModal);
                if (e.target === registerModal) closeModal(registerModal);
            });
            // Touch events for carousel on mobile
            let touchStartX = 0;
            let touchEndX = 0;
            carousel.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            });
            carousel.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            });
        }
        // Mobile menu functions
        function toggleMobileMenu() {
            mobileMenu.classList.toggle('open');
        }
        // Carousel functions
        function showSlide(index) {
            currentSlide = index;
            updateCarousel();
        }
        function showPrevSlide() {
            currentSlide = (currentSlide - 1 + carouselSlides.length) % carouselSlides.length;
            updateCarousel();
        }
        function showNextSlide() {
            currentSlide = (currentSlide + 1) % carouselSlides.length;
            updateCarousel();
        }
        function updateCarousel() {
            // Hide all slides
            carouselSlides.forEach(slide => {
                slide.classList.add('hidden');
                slide.classList.remove('fade-in');
            });
            // Show current slide
            carouselSlides[currentSlide].classList.remove('hidden');
            setTimeout(() => {
                carouselSlides[currentSlide].classList.add('fade-in');
            }, 10);
            // Update indicators
            carouselIndicators.forEach((indicator, index) => {
                if (index === currentSlide) {
                    indicator.classList.add('active');
                    indicator.classList.remove('bg-white/50');
                    indicator.classList.add('bg-white');
                } else {
                    indicator.classList.remove('active');
                    indicator.classList.add('bg-white/50');
                    indicator.classList.remove('bg-white');
                }
            });
        }
        function startAutoPlay() {
            autoPlayInterval = setInterval(showNextSlide, 5000);
        }
        function stopAutoPlay() {
            clearInterval(autoPlayInterval);
        }
        function handleSwipe() {
            if (touchEndX < touchStartX - 50) {
                // Swipe left - next slide
                showNextSlide();
            } else if (touchEndX > touchStartX + 50) {
                // Swipe right - previous slide
                showPrevSlide();
            }
        }
        // Modal functions
        function openModal(modal) {
            modal.classList.add('open');
            document.body.style.overflow = 'hidden';
            // Stop carousel autoplay when modal is open
            if (modal === pricingModal || modal === signinModal || modal === registerModal) {
                stopAutoPlay();
            }
        }
        function closeModal(modal) {
            modal.classList.remove('open');
            document.body.style.overflow = 'auto';
            // Restart carousel autoplay when modal is closed
            if (modal === pricingModal || modal === signinModal || modal === registerModal) {
                startAutoPlay();
            }
        }
        // Mobile FAB functions
        function toggleMobileFabMenu() {
            mobileFabMenu.classList.toggle('hidden');
        }
        // Form handling functions
        function handleSignin(e) {
            e.preventDefault();
            const email = document.getElementById('signin-email').value;
            const password = document.getElementById('signin-password').value;
            let isValid = true;
            // Reset errors
            document.getElementById('signin-email-error').classList.add('hidden');
            document.getElementById('signin-password-error').classList.add('hidden');
            // Validate email
            if (!validateEmail(email)) {
                document.getElementById('signin-email-error').classList.remove('hidden');
                isValid = false;
            }
            // Validate password
            if (password.length < 8) {
                document.getElementById('signin-password-error').classList.remove('hidden');
                isValid = false;
            }
            if (isValid) {
                // Simulate successful sign in
                showSuccess('Successfully signed in!');
                closeModal(signinModal);
                signinForm.reset();
            }
        }
        function handleRegister(e) {
            e.preventDefault();
            const name = document.getElementById('register-name').value;
            const email = document.getElementById('register-email').value;
            const password = document.getElementById('register-password').value;
            const confirmPassword = document.getElementById('register-confirm-password').value;
            const acceptTerms = document.getElementById('accept-terms').checked;
            let isValid = true;
            // Reset errors
            document.getElementById('register-name-error').classList.add('hidden');
            document.getElementById('register-email-error').classList.add('hidden');
            document.getElementById('register-password-error').classList.add('hidden');
            document.getElementById('register-confirm-password-error').classList.add('hidden');
            document.getElementById('accept-terms-error').classList.add('hidden');
            // Validate name
            if (name.trim() === '') {
                document.getElementById('register-name-error').classList.remove('hidden');
                isValid = false;
            }
            // Validate email
            if (!validateEmail(email)) {
                document.getElementById('register-email-error').classList.remove('hidden');
                isValid = false;
            }
            // Validate password
            if (password.length < 8) {
                document.getElementById('register-password-error').classList.remove('hidden');
                isValid = false;
            }
            // Validate password confirmation
            if (password !== confirmPassword) {
                document.getElementById('register-confirm-password-error').classList.remove('hidden');
                isValid = false;
            }
            // Validate terms acceptance
            if (!acceptTerms) {
                document.getElementById('accept-terms-error').classList.remove('hidden');
                isValid = false;
            }
            if (isValid) {
                // Simulate successful registration
                showSuccess('Account created successfully!');
                closeModal(registerModal);
                registerForm.reset();
            }
        }
        // Utility functions
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
        function showSuccess(message) {
            successText.textContent = message;
            successMessage.classList.remove('hidden');
            setTimeout(() => {
                successMessage.classList.add('hidden');
            }, 3000);
        }
        // Pause autoplay when user interacts with carousel
        carousel.addEventListener('mouseenter', stopAutoPlay);
        carousel.addEventListener('mouseleave', startAutoPlay);
        // Initialize the application
        document.addEventListener('DOMContentLoaded', init);
    </script>
</body>
</html>










<!-- 
********************* -->


<?php 
include("../include/footer.php");
?>