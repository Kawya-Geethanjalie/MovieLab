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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .card-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.7);
        }
        
        .hover-shadow {
            box-shadow: 0 20px 25px -5px rgba(220, 38, 38, 0.3);
        }
        
        .movie-card-container {
            max-width: 100%;
            width: 100%;
        }
        
        .movie-card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .movie-poster, .movie-player {
            height: 250px;
        }
        
        .movie-info {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .movie-content {
            flex-grow: 1;
        }
        
        .download-button-container {
            margin-top: auto;
            padding-top: 12px;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Carousel Styles */
        .carousel-slide {
            transition: opacity 0.5s ease-in-out;
        }
        
        .parallax-bg {
            transition: transform 10s linear;
        }
    </style>
</head>
<body class="bg-black text-white font-sans">
 
    <!-- Hero Carousel Section -->
    <section class="relative overflow-hidden h-[70vh] md:h-[80vh]">
        <div id="carousel" class="relative h-full">
            <!-- Slide 1 -->
            <div class="carousel-slide absolute inset-0 w-full h-full transition-opacity duration-500 ease-in-out opacity-100 visible">
                <div class="parallax-bg absolute inset-0 w-full h-full bg-cover bg-center scale-110 transition-transform duration-[10000ms] ease-linear group-hover:scale-100" style="background-image: url('https://images.unsplash.com/photo-1489599809505-fb40ebc16f9f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80')"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/70 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6 md:p-12">
                    <div class="container mx-auto">
                        <h2 class="text-2xl md:text-4xl font-bold mb-3">Dune: Part Two</h2>
                        <p class="text-gray-300 mb-4 max-w-xl">Sci-Fi • Adventure • 2024</p>
                        <p class="text-gray-300 mb-6 max-w-xl">Paul Atreides unites with Chani and the Fremen while seeking revenge against the conspirators who destroyed his family.</p>
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
                <div class="parallax-bg absolute inset-0 w-full h-full bg-cover bg-center scale-110 transition-transform duration-[10000ms] ease-linear group-hover:scale-100" style="background-image: url('https://images.unsplash.com/photo-1534447677768-be436bb09401?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80')"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/70 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6 md:p-12">
                    <div class="container mx-auto">
                        <h2 class="text-2xl md:text-4xl font-bold mb-3">Oppenheimer</h2>
                        <p class="text-gray-300 mb-4 max-w-xl">Biography • Drama • 2023</p>
                        <p class="text-gray-300 mb-6 max-w-xl">The story of American scientist J. Robert Oppenheimer and his role in the development of the atomic bomb.</p>
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
                <div class="parallax-bg absolute inset-0 w-full h-full bg-cover bg-center scale-110 transition-transform duration-[10000ms] ease-linear group-hover:scale-100" style="background-image: url('https://images.unsplash.com/photo-1635805737707-575885ab0820?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80')"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/70 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6 md:p-12">
                    <div class="container mx-auto">
                        <h2 class="text-2xl md:text-4xl font-bold mb-3">John Wick: Chapter 4</h2>
                        <p class="text-gray-300 mb-4 max-w-xl">Action • Thriller • 2023</p>
                        <p class="text-gray-300 mb-6 max-w-xl">John Wick uncovers a path to defeating The High Table. But before he can earn his freedom, Wick must face off against a new enemy.</p>
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

        <!-- Popular Movies Section -->
        <section class="mb-10">
            <h2 class="text-xl md:text-2xl font-bold mb-5">Popular Movies</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                <!-- Movie Card 1 - Dune: Part Two -->
                <div class="movie-card-container">
                    <div class="bg-gray-800 rounded-xl overflow-hidden card-shadow transition-all duration-300 group relative hover:-translate-y-2 hover:hover-shadow movie-card">
                        <!-- Movie Poster (Initially Visible) -->
                        <div id="moviePoster1" class="movie-poster relative overflow-hidden">
                            <img 
                                src="https://images.unsplash.com/photo-1712675582493-6d51e3cde52e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" 
                                alt="Dune: Part Two" 
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            >
                            
                            <!-- Hover Overlay with Play Button -->
                            <div class="absolute inset-0 bg-black/70 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button 
                                    id="playButton1" 
                                    class="bg-red-600 hover:bg-red-700 text-white rounded-full p-4 transition-all duration-300 transform hover:scale-110"
                                >
                                    <i class="fas fa-play text-xl"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Movie Player (Initially Hidden) -->
                        <div id="moviePlayer1" class="movie-player hidden">
                            <div class="relative h-full">
                                <iframe 
                                    id="movieFrame1"
                                    class="w-full h-full" 
                                    src="" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen
                                ></iframe>
                                <button 
                                    id="closePlayer1" 
                                    class="absolute top-2 right-2 bg-black/70 text-white rounded-full p-2 hover:bg-red-600 transition-colors"
                                >
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Movie Info -->
                        <div class="movie-info p-4">
                            <div class="movie-content">
                                <div class="flex justify-between items-start mb-2">
                                    <h2 class="text-lg font-bold text-white">Dune: Part Two</h2>
                                    <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">PG-13</span>
                                </div>
                                <p class="text-gray-400 text-sm mb-3 line-clamp-2">Paul Atreides unites with Chani and the Fremen while seeking revenge against the conspirators who destroyed his family.</p>
                                
                                <div class="flex justify-between text-sm text-gray-400">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        <span>2h 46m</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar mr-1"></i>
                                        <span>2024</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        <span>8.7/10</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Download Button Container -->
                            <div class="download-button-container">
                                <button 
                                    id="downloadButton1" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-3 rounded-lg transition-colors duration-300 flex items-center justify-center text-sm"
                                >
                                    <i class="fas fa-download mr-2"></i>
                                    Download Movie
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Movie Card 2 - Oppenheimer -->
                <div class="movie-card-container">
                    <div class="bg-gray-800 rounded-xl overflow-hidden card-shadow transition-all duration-300 group relative hover:-translate-y-2 hover:hover-shadow movie-card">
                        <!-- Movie Poster (Initially Visible) -->
                        <div id="moviePoster2" class="movie-poster relative overflow-hidden">
                            <img 
                                src="https://images.unsplash.com/photo-1695048133142-1a6e73b0b944?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" 
                                alt="Oppenheimer" 
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            >
                            
                            <!-- Hover Overlay with Play Button -->
                            <div class="absolute inset-0 bg-black/70 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button 
                                    id="playButton2" 
                                    class="bg-red-600 hover:bg-red-700 text-white rounded-full p-4 transition-all duration-300 transform hover:scale-110"
                                >
                                    <i class="fas fa-play text-xl"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Movie Player (Initially Hidden) -->
                        <div id="moviePlayer2" class="movie-player hidden">
                            <div class="relative h-full">
                                <iframe 
                                    id="movieFrame2"
                                    class="w-full h-full" 
                                    src="" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen
                                ></iframe>
                                <button 
                                    id="closePlayer2" 
                                    class="absolute top-2 right-2 bg-black/70 text-white rounded-full p-2 hover:bg-red-600 transition-colors"
                                >
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Movie Info -->
                        <div class="movie-info p-4">
                            <div class="movie-content">
                                <div class="flex justify-between items-start mb-2">
                                    <h2 class="text-lg font-bold text-white">Oppenheimer</h2>
                                    <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">R</span>
                                </div>
                                <p class="text-gray-400 text-sm mb-3 line-clamp-2">The story of American scientist J. Robert Oppenheimer and his role in the development of the atomic bomb.</p>
                                
                                <div class="flex justify-between text-sm text-gray-400">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        <span>3h 0m</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar mr-1"></i>
                                        <span>2023</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        <span>8.3/10</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Download Button Container -->
                            <div class="download-button-container">
                                <button 
                                    id="downloadButton2" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-3 rounded-lg transition-colors duration-300 flex items-center justify-center text-sm"
                                >
                                    <i class="fas fa-download mr-2"></i>
                                    Download Movie
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Movie Card 3 - Spider-Man: Across the Spider-Verse -->
                <div class="movie-card-container">
                    <div class="bg-gray-800 rounded-xl overflow-hidden card-shadow transition-all duration-300 group relative hover:-translate-y-2 hover:hover-shadow movie-card">
                        <!-- Movie Poster (Initially Visible) -->
                        <div id="moviePoster3" class="movie-poster relative overflow-hidden">
                            <img 
                                src="https://images.unsplash.com/photo-1682687221363-72518513620e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" 
                                alt="Spider-Man: Across the Spider-Verse" 
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            >
                            
                            <!-- Hover Overlay with Play Button -->
                            <div class="absolute inset-0 bg-black/70 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button 
                                    id="playButton3" 
                                    class="bg-red-600 hover:bg-red-700 text-white rounded-full p-4 transition-all duration-300 transform hover:scale-110"
                                >
                                    <i class="fas fa-play text-xl"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Movie Player (Initially Hidden) -->
                        <div id="moviePlayer3" class="movie-player hidden">
                            <div class="relative h-full">
                                <iframe 
                                    id="movieFrame3"
                                    class="w-full h-full" 
                                    src="" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen
                                ></iframe>
                                <button 
                                    id="closePlayer3" 
                                    class="absolute top-2 right-2 bg-black/70 text-white rounded-full p-2 hover:bg-red-600 transition-colors"
                                >
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Movie Info -->
                        <div class="movie-info p-4">
                            <div class="movie-content">
                                <div class="flex justify-between items-start mb-2">
                                    <h2 class="text-lg font-bold text-white">Spider-Man: Across the Spider-Verse</h2>
                                    <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">PG</span>
                                </div>
                                <p class="text-gray-400 text-sm mb-3 line-clamp-2">Miles Morales catapults across the Multiverse, where he encounters a team of Spider-People charged with protecting its very existence.</p>
                                
                                <div class="flex justify-between text-sm text-gray-400">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        <span>2h 20m</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar mr-1"></i>
                                        <span>2023</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        <span>8.7/10</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Download Button Container -->
                            <div class="download-button-container">
                                <button 
                                    id="downloadButton3" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-3 rounded-lg transition-colors duration-300 flex items-center justify-center text-sm"
                                >
                                    <i class="fas fa-download mr-2"></i>
                                    Download Movie
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Movie Card 4 - John Wick: Chapter 4 -->
                <div class="movie-card-container">
                    <div class="bg-gray-800 rounded-xl overflow-hidden card-shadow transition-all duration-300 group relative hover:-translate-y-2 hover:hover-shadow movie-card">
                        <!-- Movie Poster (Initially Visible) -->
                        <div id="moviePoster4" class="movie-poster relative overflow-hidden">
                            <img 
                                src="https://images.unsplash.com/photo-1635805737707-575885ab0820?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" 
                                alt="John Wick: Chapter 4" 
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            >
                            
                            <!-- Hover Overlay with Play Button -->
                            <div class="absolute inset-0 bg-black/70 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button 
                                    id="playButton4" 
                                    class="bg-red-600 hover:bg-red-700 text-white rounded-full p-4 transition-all duration-300 transform hover:scale-110"
                                >
                                    <i class="fas fa-play text-xl"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Movie Player (Initially Hidden) -->
                        <div id="moviePlayer4" class="movie-player hidden">
                            <div class="relative h-full">
                                <iframe 
                                    id="movieFrame4"
                                    class="w-full h-full" 
                                    src="" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen
                                ></iframe>
                                <button 
                                    id="closePlayer4" 
                                    class="absolute top-2 right-2 bg-black/70 text-white rounded-full p-2 hover:bg-red-600 transition-colors"
                                >
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Movie Info -->
                        <div class="movie-info p-4">
                            <div class="movie-content">
                                <div class="flex justify-between items-start mb-2">
                                    <h2 class="text-lg font-bold text-white">John Wick: Chapter 4</h2>
                                    <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">R</span>
                                </div>
                                <p class="text-gray-400 text-sm mb-3 line-clamp-2">John Wick uncovers a path to defeating The High Table. But before he can earn his freedom, Wick must face off against a new enemy.</p>
                                
                                <div class="flex justify-between text-sm text-gray-400">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        <span>2h 49m</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar mr-1"></i>
                                        <span>2023</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        <span>7.7/10</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Download Button Container -->
                            <div class="download-button-container">
                                <button 
                                    id="downloadButton4" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-3 rounded-lg transition-colors duration-300 flex items-center justify-center text-sm"
                                >
                                    <i class="fas fa-download mr-2"></i>
                                    Download Movie
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Movies Section -->
        <section>
            <h2 class="text-xl md:text-2xl font-bold mb-5">Featured Movies</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Movie Card 1 - Avatar: The Way of Water -->
                <div class="movie-card-container">
                    <div class="bg-gray-800 rounded-xl overflow-hidden card-shadow transition-all duration-300 group relative hover:-translate-y-2 hover:hover-shadow movie-card">
                        <!-- Movie Poster (Initially Visible) -->
                        <div id="featuredPoster1" class="movie-poster relative overflow-hidden">
                            <img 
                                src="https://images.unsplash.com/photo-1419242902214-272b3f66ee7a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" 
                                alt="Avatar: The Way of Water" 
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            >
                            
                            <!-- Hover Overlay with Play Button -->
                            <div class="absolute inset-0 bg-black/70 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button 
                                    id="featuredPlayButton1" 
                                    class="bg-red-600 hover:bg-red-700 text-white rounded-full p-4 transition-all duration-300 transform hover:scale-110"
                                >
                                    <i class="fas fa-play text-xl"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Movie Player (Initially Hidden) -->
                        <div id="featuredPlayer1" class="movie-player hidden">
                            <div class="relative h-full">
                                <iframe 
                                    id="featuredFrame1"
                                    class="w-full h-full" 
                                    src="" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen
                                ></iframe>
                                <button 
                                    id="featuredClosePlayer1" 
                                    class="absolute top-2 right-2 bg-black/70 text-white rounded-full p-2 hover:bg-red-600 transition-colors"
                                >
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Movie Info -->
                        <div class="movie-info p-4">
                            <div class="movie-content">
                                <div class="flex justify-between items-start mb-2">
                                    <h2 class="text-lg font-bold text-white">Avatar: The Way of Water</h2>
                                    <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">PG-13</span>
                                </div>
                                <p class="text-gray-400 text-sm mb-3 line-clamp-2">Jake Sully lives with his newfound family formed on the planet of Pandora. Once a familiar threat returns to finish what was previously started.</p>
                                
                                <div class="flex justify-between text-sm text-gray-400">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        <span>3h 12m</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar mr-1"></i>
                                        <span>2022</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        <span>7.6/10</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Download Button Container -->
                            <div class="download-button-container">
                                <button 
                                    id="featuredDownloadButton1" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-3 rounded-lg transition-colors duration-300 flex items-center justify-center text-sm"
                                >
                                    <i class="fas fa-download mr-2"></i>
                                    Download Movie
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Movie Card 2 - Top Gun: Maverick -->
                <div class="movie-card-container">
                    <div class="bg-gray-800 rounded-xl overflow-hidden card-shadow transition-all duration-300 group relative hover:-translate-y-2 hover:hover-shadow movie-card">
                        <!-- Movie Poster (Initially Visible) -->
                        <div id="featuredPoster2" class="movie-poster relative overflow-hidden">
                            <img 
                                src="https://images.unsplash.com/photo-1594909122845-11baa439b7bf?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" 
                                alt="Top Gun: Maverick" 
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            >
                            
                            <!-- Hover Overlay with Play Button -->
                            <div class="absolute inset-0 bg-black/70 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button 
                                    id="featuredPlayButton2" 
                                    class="bg-red-600 hover:bg-red-700 text-white rounded-full p-4 transition-all duration-300 transform hover:scale-110"
                                >
                                    <i class="fas fa-play text-xl"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Movie Player (Initially Hidden) -->
                        <div id="featuredPlayer2" class="movie-player hidden">
                            <div class="relative h-full">
                                <iframe 
                                    id="featuredFrame2"
                                    class="w-full h-full" 
                                    src="" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen
                                ></iframe>
                                <button 
                                    id="featuredClosePlayer2" 
                                    class="absolute top-2 right-2 bg-black/70 text-white rounded-full p-2 hover:bg-red-600 transition-colors"
                                >
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Movie Info -->
                        <div class="movie-info p-4">
                            <div class="movie-content">
                                <div class="flex justify-between items-start mb-2">
                                    <h2 class="text-lg font-bold text-white">Top Gun: Maverick</h2>
                                    <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">PG-13</span>
                                </div>
                                <p class="text-gray-400 text-sm mb-3 line-clamp-2">After thirty years, Maverick is still pushing the envelope as a top naval aviator, but must confront ghosts of his past.</p>
                                
                                <div class="flex justify-between text-sm text-gray-400">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        <span>2h 11m</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar mr-1"></i>
                                        <span>2022</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        <span>8.2/10</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Download Button Container -->
                            <div class="download-button-container">
                                <button 
                                    id="featuredDownloadButton2" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-3 rounded-lg transition-colors duration-300 flex items-center justify-center text-sm"
                                >
                                    <i class="fas fa-download mr-2"></i>
                                    Download Movie
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Movie Card 3 - The Batman -->
                <div class="movie-card-container">
                    <div class="bg-gray-800 rounded-xl overflow-hidden card-shadow transition-all duration-300 group relative hover:-translate-y-2 hover:hover-shadow movie-card">
                        <!-- Movie Poster (Initially Visible) -->
                        <div id="featuredPoster3" class="movie-poster relative overflow-hidden">
                            <img 
                                src="https://images.unsplash.com/photo-1509347528160-9a9e33742cdb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" 
                                alt="The Batman" 
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            >
                            
                            <!-- Hover Overlay with Play Button -->
                            <div class="absolute inset-0 bg-black/70 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button 
                                    id="featuredPlayButton3" 
                                    class="bg-red-600 hover:bg-red-700 text-white rounded-full p-4 transition-all duration-300 transform hover:scale-110"
                                >
                                    <i class="fas fa-play text-xl"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Movie Player (Initially Hidden) -->
                        <div id="featuredPlayer3" class="movie-player hidden">
                            <div class="relative h-full">
                                <iframe 
                                    id="featuredFrame3"
                                    class="w-full h-full" 
                                    src="" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen
                                ></iframe>
                                <button 
                                    id="featuredClosePlayer3" 
                                    class="absolute top-2 right-2 bg-black/70 text-white rounded-full p-2 hover:bg-red-600 transition-colors"
                                >
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Movie Info -->
                        <div class="movie-info p-4">
                            <div class="movie-content">
                                <div class="flex justify-between items-start mb-2">
                                    <h2 class="text-lg font-bold text-white">The Batman</h2>
                                    <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">PG-13</span>
                                </div>
                                <p class="text-gray-400 text-sm mb-3 line-clamp-2">When a sadistic serial killer begins murdering key political figures in Gotham, Batman is forced to investigate the city's hidden corruption.</p>
                                
                                <div class="flex justify-between text-sm text-gray-400">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        <span>2h 56m</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar mr-1"></i>
                                        <span>2022</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        <span>7.8/10</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Download Button Container -->
                            <div class="download-button-container">
                                <button 
                                    id="featuredDownloadButton3" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-3 rounded-lg transition-colors duration-300 flex items-center justify-center text-sm"
                                >
                                    <i class="fas fa-download mr-2"></i>
                                    Download Movie
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Movie Card 4 - Black Panther: Wakanda Forever -->
                <div class="movie-card-container">
                    <div class="bg-gray-800 rounded-xl overflow-hidden card-shadow transition-all duration-300 group relative hover:-translate-y-2 hover:hover-shadow movie-card">
                        <!-- Movie Poster (Initially Visible) -->
                        <div id="featuredPoster4" class="movie-poster relative overflow-hidden">
                            <img 
                                src="https://images.unsplash.com/photo-1626814026160-2237a95fc5a0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" 
                                alt="Black Panther: Wakanda Forever" 
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            >
                            
                            <!-- Hover Overlay with Play Button -->
                            <div class="absolute inset-0 bg-black/70 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button 
                                    id="featuredPlayButton4" 
                                    class="bg-red-600 hover:bg-red-700 text-white rounded-full p-4 transition-all duration-300 transform hover:scale-110"
                                >
                                    <i class="fas fa-play text-xl"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Movie Player (Initially Hidden) -->
                        <div id="featuredPlayer4" class="movie-player hidden">
                            <div class="relative h-full">
                                <iframe 
                                    id="featuredFrame4"
                                    class="w-full h-full" 
                                    src="" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen
                                ></iframe>
                                <button 
                                    id="featuredClosePlayer4" 
                                    class="absolute top-2 right-2 bg-black/70 text-white rounded-full p-2 hover:bg-red-600 transition-colors"
                                >
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Movie Info -->
                        <div class="movie-info p-4">
                            <div class="movie-content">
                                <div class="flex justify-between items-start mb-2">
                                    <h2 class="text-lg font-bold text-white">Black Panther: Wakanda Forever</h2>
                                    <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">PG-13</span>
                                </div>
                                <p class="text-gray-400 text-sm mb-3 line-clamp-2">The nation of Wakanda is pitted against intervening world powers as they mourn the loss of their king T'Challa.</p>
                                
                                <div class="flex justify-between text-sm text-gray-400">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        <span>2h 41m</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar mr-1"></i>
                                        <span>2022</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        <span>6.7/10</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Download Button Container -->
                            <div class="download-button-container">
                                <button 
                                    id="featuredDownloadButton4" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-3 rounded-lg transition-colors duration-300 flex items-center justify-center text-sm"
                                >
                                    <i class="fas fa-download mr-2"></i>
                                    Download Movie
                                </button>
                            </div>
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
            // Initialize movie cards
            initializeMovieCards();
            initializeFeaturedMovieCards();
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
        }

        // Initialize Popular Movie Cards
        function initializeMovieCards() {
            const movieData = {
                1: {
                    trailerUrl: "https://www.youtube.com/embed/U2Qp5pL3ovA?autoplay=1",
                    downloadUrl: "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf",
                    fileName: "Dune_Part_Two.mp4"
                },
                2: {
                    trailerUrl: "https://www.youtube.com/embed/uYPbbksJxIg?autoplay=1",
                    downloadUrl: "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf",
                    fileName: "Oppenheimer.mp4"
                },
                3: {
                    trailerUrl: "https://www.youtube.com/embed/shW9i6k8cB0?autoplay=1",
                    downloadUrl: "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf",
                    fileName: "Spider-Man_Across_the_Spider-Verse.mp4"
                },
                4: {
                    trailerUrl: "https://www.youtube.com/embed/qEVUtrk8_B4?autoplay=1",
                    downloadUrl: "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf",
                    fileName: "John_Wick_Chapter_4.mp4"
                }
            };

            // Set up each movie card
            for (let i = 1; i <= 4; i++) {
                const playButton = document.getElementById(`playButton${i}`);
                const moviePoster = document.getElementById(`moviePoster${i}`);
                const moviePlayer = document.getElementById(`moviePlayer${i}`);
                const movieFrame = document.getElementById(`movieFrame${i}`);
                const closePlayer = document.getElementById(`closePlayer${i}`);
                const downloadButton = document.getElementById(`downloadButton${i}`);

                if (playButton && moviePoster && moviePlayer && movieFrame && closePlayer && downloadButton) {
                    const { trailerUrl, downloadUrl, fileName } = movieData[i];
                    
                    playButton.addEventListener('click', function() {
                        movieFrame.src = trailerUrl;
                        moviePoster.classList.add('hidden');
                        moviePlayer.classList.remove('hidden');
                    });
                    
                    closePlayer.addEventListener('click', function() {
                        moviePlayer.classList.add('hidden');
                        moviePoster.classList.remove('hidden');
                        movieFrame.src = "";
                    });
                    
                    downloadButton.addEventListener('click', function() {
                        // Create a temporary anchor element to trigger download
                        const a = document.createElement('a');
                        a.href = downloadUrl;
                        a.download = fileName;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        
                        // Show download confirmation
                        const originalText = downloadButton.innerHTML;
                        downloadButton.innerHTML = '<i class="fas fa-check mr-2"></i> Download Started!';
                        downloadButton.classList.remove('bg-red-600', 'hover:bg-red-700');
                        downloadButton.classList.add('bg-green-600');
                        
                        setTimeout(() => {
                            downloadButton.innerHTML = originalText;
                            downloadButton.classList.remove('bg-green-600');
                            downloadButton.classList.add('bg-red-600', 'hover:bg-red-700');
                        }, 3000);
                    });
                }
            }
        }

        // Initialize Featured Movie Cards
        function initializeFeaturedMovieCards() {
            const featuredMovieData = {
                1: {
                    trailerUrl: "https://www.youtube.com/embed/d9MyW72ELq0?autoplay=1",
                    downloadUrl: "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf",
                    fileName: "Avatar_The_Way_of_Water.mp4"
                },
                2: {
                    trailerUrl: "https://www.youtube.com/embed/qSqVVswa420?autoplay=1",
                    downloadUrl: "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf",
                    fileName: "Top_Gun_Maverick.mp4"
                },
                3: {
                    trailerUrl: "https://www.youtube.com/embed/mqqft2x_Aa4?autoplay=1",
                    downloadUrl: "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf",
                    fileName: "The_Batman.mp4"
                },
                4: {
                    trailerUrl: "https://www.youtube.com/embed/_Z3QKkl1WyM?autoplay=1",
                    downloadUrl: "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf",
                    fileName: "Black_Panther_Wakanda_Forever.mp4"
                }
            };

            // Set up each featured movie card
            for (let i = 1; i <= 4; i++) {
                const playButton = document.getElementById(`featuredPlayButton${i}`);
                const moviePoster = document.getElementById(`featuredPoster${i}`);
                const moviePlayer = document.getElementById(`featuredPlayer${i}`);
                const movieFrame = document.getElementById(`featuredFrame${i}`);
                const closePlayer = document.getElementById(`featuredClosePlayer${i}`);
                const downloadButton = document.getElementById(`featuredDownloadButton${i}`);

                if (playButton && moviePoster && moviePlayer && movieFrame && closePlayer && downloadButton) {
                    const { trailerUrl, downloadUrl, fileName } = featuredMovieData[i];
                    
                    playButton.addEventListener('click', function() {
                        movieFrame.src = trailerUrl;
                        moviePoster.classList.add('hidden');
                        moviePlayer.classList.remove('hidden');
                    });
                    
                    closePlayer.addEventListener('click', function() {
                        moviePlayer.classList.add('hidden');
                        moviePoster.classList.remove('hidden');
                        movieFrame.src = "";
                    });
                    
                    downloadButton.addEventListener('click', function() {
                        // Create a temporary anchor element to trigger download
                        const a = document.createElement('a');
                        a.href = downloadUrl;
                        a.download = fileName;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        
                        // Show download confirmation
                        const originalText = downloadButton.innerHTML;
                        downloadButton.innerHTML = '<i class="fas fa-check mr-2"></i> Download Started!';
                        downloadButton.classList.remove('bg-red-600', 'hover:bg-red-700');
                        downloadButton.classList.add('bg-green-600');
                        
                        setTimeout(() => {
                            downloadButton.innerHTML = originalText;
                            downloadButton.classList.remove('bg-green-600');
                            downloadButton.classList.add('bg-red-600', 'hover:bg-red-700');
                        }, 3000);
                    });
                }
            }
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