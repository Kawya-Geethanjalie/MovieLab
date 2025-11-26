<?php 
include("../include/header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoviLab - Your Ultimate Movie & Music Destination</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white font-sans">
 
    <!-- Hero Carousel Section -->
    <section class="relative overflow-hidden h-[70vh] md:h-[80vh]">
        <div id="carousel" class="relative h-full">
            <!-- Slide 1 -->
            <div class="carousel-slide absolute inset-0 w-full h-full transition-opacity duration-500 ease-in-out opacity-100 visible">
                <div class="parallax-bg absolute inset-0 w-full h-full bg-cover bg-center scale-110 transition-transform duration-[10000ms] ease-linear group-hover:scale-100" style="background-image: url('https://source.unsplash.com/random/1920x1080/?movie,action')"></div>
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
            <div class="carousel-slide absolute inset-0 w-full h-full transition-opacity duration-500 ease-in-out opacity-0 invisible">
                <div class="parallax-bg absolute inset-0 w-full h-full bg-cover bg-center scale-110 transition-transform duration-[10000ms] ease-linear group-hover:scale-100" style="background-image: url('https://source.unsplash.com/random/1920x1080/?movie,drama')"></div>
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
            <div class="carousel-slide absolute inset-0 w-full h-full transition-opacity duration-500 ease-in-out opacity-0 invisible">
                <div class="parallax-bg absolute inset-0 w-full h-full bg-cover bg-center scale-110 transition-transform duration-[10000ms] ease-linear group-hover:scale-100" style="background-image: url('https://source.unsplash.com/random/1920x1080/?movie,horror')"></div>
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
            <div class="carousel-slide absolute inset-0 w-full h-full transition-opacity duration-500 ease-in-out opacity-0 invisible">
                <div class="parallax-bg absolute inset-0 w-full h-full bg-cover bg-center scale-110 transition-transform duration-[10000ms] ease-linear group-hover:scale-100" style="background-image: url('https://source.unsplash.com/random/1920x1080/?movie,sci-fi')"></div>
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
        <!-- Filter Buttons -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div class="flex flex-wrap gap-2">
                <button class="px-3 py-1.5 md:px-4 md:py-2 bg-red-600 text-white rounded-lg text-sm">All Movies</button>
                <button class="px-3 py-1.5 md:px-4 md:py-2 bg-gray-900 text-gray-300 hover:bg-gray-800 rounded-lg transition-colors text-sm">Popular</button>
                <button class="px-3 py-1.5 md:px-4 md:py-2 bg-gray-900 text-gray-300 hover:bg-gray-800 rounded-lg transition-colors text-sm">New</button>
                <button class="px-3 py-1.5 md:px-4 md:py-2 bg-gray-900 text-gray-300 hover:bg-gray-800 rounded-lg transition-colors text-sm">Top Rated</button>
            </div>
        </div>

        <!-- Popular Songs Section -->
        <section class="mb-10">
            <h2 class="text-xl md:text-2xl font-bold mb-5">Popular Songs</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Song Card 1 -->
                <div class="song-card transition-all duration-300 ease-in-out hover:-translate-y-2 hover:shadow-[0_20px_25px_-5px_rgba(220,38,38,0.3)] bg-gray-900 rounded-lg overflow-hidden relative group">
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
                <div class="song-card transition-all duration-300 ease-in-out hover:-translate-y-2 hover:shadow-[0_20px_25px_-5px_rgba(220,38,38,0.3)] bg-gray-900 rounded-lg overflow-hidden relative group">
                    <div class="relative">
                        <img src="../images/poster1.jpg" alt="Song cover" class="w-full h-40 md:h-48 object-cover" loading="lazy">
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
                <div class="song-card transition-all duration-300 ease-in-out hover:-translate-y-2 hover:shadow-[0_20px_25px_-5px_rgba(220,38,38,0.3)] bg-gray-900 rounded-lg overflow-hidden relative group">
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
                <div class="song-card transition-all duration-300 ease-in-out hover:-translate-y-2 hover:shadow-[0_20px_25px_-5px_rgba(220,38,38,0.3)] bg-gray-900 rounded-lg overflow-hidden relative group">
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

        <!-- Featured Movies Section -->
        <section>
            <h2 class="text-xl md:text-2xl font-bold mb-5">Featured Movies</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Movie Card 1 -->
                <div class="movie-card transition-all duration-300 ease-in-out hover:-translate-y-2 hover:shadow-[0_20px_25px_-5px_rgba(220,38,38,0.3)] bg-gray-900 rounded-lg overflow-hidden relative group">
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
                        <p class="text-gray-400 text-xs md:text-sm mb-2 line-clamp-2">In a post-apocalyptic world, survivors search for the last safe haven against all odds.</p>
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
                            <span class="text-gray-400 text-xs">4.8</span>
                        </div>
                    </div>
                </div>
                
                <!-- Movie Card 2 -->
                <div class="movie-card transition-all duration-300 ease-in-out hover:-translate-y-2 hover:shadow-[0_20px_25px_-5px_rgba(220,38,38,0.3)] bg-gray-900 rounded-lg overflow-hidden relative group">
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
                        <p class="text-gray-400 text-xs md:text-sm mb-2 line-clamp-2">A heartwarming story about second chances and the power of memory in a small coastal town.</p>
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
                            <span class="text-gray-400 text-xs">4.2</span>
                        </div>
                    </div>
                </div>
                
                <!-- Movie Card 3 -->
                <div class="movie-card transition-all duration-300 ease-in-out hover:-translate-y-2 hover:shadow-[0_20px_25px_-5px_rgba(220,38,38,0.3)] bg-gray-900 rounded-lg overflow-hidden relative group">
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
                        <p class="text-gray-400 text-xs md:text-sm mb-2 line-clamp-2">A family moves into their dream home, only to discover it holds a dark secret that threatens to consume them.</p>
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
                            <span class="text-gray-400 text-xs">4.0</span>
                        </div>
                    </div>
                </div>
                
                <!-- Movie Card 4 -->
                <div class="movie-card transition-all duration-300 ease-in-out hover:-translate-y-2 hover:shadow-[0_20px_25px_-5px_rgba(220,38,38,0.3)] bg-gray-900 rounded-lg overflow-hidden relative group">
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
                        <p class="text-gray-400 text-xs md:text-sm mb-2 line-clamp-2">A team of interstellar explorers discovers an ancient alien civilization that holds the key to humanity's future.</p>
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
                            <span class="text-gray-400 text-xs">4.5</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Success Message -->
    <div id="success-message" class="fixed bottom-4 right-4 z-50 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg hidden">
        <div class="flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span id="success-text" class="text-sm">Operation completed successfully!</span>
        </div>
    </div>

    <script>
        // DOM Elements
        const carousel = document.getElementById('carousel');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const carouselIndicators = document.querySelectorAll('.carousel-indicator');
        const carouselSlides = document.querySelectorAll('.carousel-slide');
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
            // Carousel controls
            prevBtn.addEventListener('click', showPrevSlide);
            nextBtn.addEventListener('click', showNextSlide);
            
            // Carousel indicators
            carouselIndicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => showSlide(index));
            });

            // Swipe functionality for carousel
            let touchStartX = 0;
            let touchEndX = 0;
            carousel.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            }, false);
            carousel.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            }, false);
            
            // Pause autoplay when user interacts with carousel
            carousel.addEventListener('mouseenter', stopAutoPlay);
            carousel.addEventListener('mouseleave', startAutoPlay);
            
            // Add click handlers for play buttons
            document.querySelectorAll('.song-card button, .movie-card button').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const card = this.closest('.song-card, .movie-card');
                    const title = card.querySelector('h3').textContent;
                    showSuccess(`Playing: ${title}`);
                });
            });
            
            // Add click handlers for cards
            document.querySelectorAll('.song-card, .movie-card').forEach(card => {
                card.addEventListener('click', function() {
                    const title = this.querySelector('h3').textContent;
                    showSuccess(`Opening details for: ${title}`);
                });
            });
        }

        // Carousel logic
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
                slide.classList.add('opacity-0', 'invisible');
                slide.classList.remove('opacity-100', 'visible');
            });

            // Show current slide
            const currentSlideElement = carouselSlides[currentSlide];
            currentSlideElement.classList.remove('opacity-0', 'invisible');
            currentSlideElement.classList.add('opacity-100', 'visible');

            // Update indicators
            carouselIndicators.forEach((indicator, index) => {
                if (index === currentSlide) {
                    indicator.classList.add('bg-white');
                    indicator.classList.remove('bg-white/50');
                } else {
                    indicator.classList.remove('bg-white');
                    indicator.classList.add('bg-white/50');
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
            if (touchEndX < touchStartX - 50) { // Swipe left - next slide
                showNextSlide();
            } else if (touchEndX > touchStartX + 50) { // Swipe right - previous slide
                showPrevSlide();
            }
        }

        // Utility functions
        function showSuccess(message) {
            successText.textContent = message;
            successMessage.classList.remove('hidden');
            setTimeout(() => {
                successMessage.classList.add('hidden');
            }, 3000);
        }
        
        // Initialize the application
        document.addEventListener('DOMContentLoaded', init);
    </script>
</body>
</html>

<?php 
include("../include/footer.php");
?>