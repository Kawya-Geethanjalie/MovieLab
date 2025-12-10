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
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <script>
        // Custom Tailwind Configuration for Carousel Fonts and Animations
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'], 
                        'bebas': ['"Bebas Neue"', 'sans-serif'],
                        'poppins': ['"Poppins"', 'sans-serif'],
                    },
                    colors: {
                        'neon-red': '#FF4500', 
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                        'zoom-in': 'zoomIn 10s linear infinite',
                        'pop-in': 'popIn 0.5s ease-out forwards',
                        'slide-up': 'slideUp 0.5s ease-out forwards',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(40px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        zoomIn: {
                            '0%': { transform: 'scale(1)' },
                            '100%': { transform: 'scale(1.1)' },
                        },
                        popIn: {
                            '0%': { opacity: '0', transform: 'scale(0.8)' },
                            '100%': { opacity: '1', transform: 'scale(1)' },
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                    }
                }
            }
        }
    </script>
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
        
        /* Thumbnails Styles */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .thumbnail.active {
            border-color: #FF4500;
            box-shadow: 0 0 15px rgba(255, 69, 0, 0.5);
            transform: scale(1.05);
        }
    </style>
</head>
<body class="bg-black text-white font-sans">
 
    <!-- New Hero Carousel Section (from caro.html) -->
    <section class="relative overflow-hidden h-[70vh] md:h-[85vh] w-full">
        <div id="bg-layer" class="absolute inset-0 w-full h-full overflow-hidden">
            <!-- Active Background Image -->
            <div id="active-bg" class="w-full h-full bg-cover bg-center transition-all duration-800 ease-in-out animate-zoom-in brightness-100"></div>
            <!-- Gradient Overlays for readability -->
            <div class="absolute inset-0 bg-gradient-to-r from-black via-black/60 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-black/40"></div>
        </div>

        <div class="relative z-10 container mx-auto px-6 h-full flex flex-col justify-center md:justify-start pt-12 pb-24 md:pt-40 md:pb-16">
            
            <!-- Main Text Content (Animated) -->
            <div id="text-content" class="max-w-2xl  pl-4 border-l-4 border-red-500">
                <div class="flex items-center gap-3 mb-4">
                    <span id="movie-rating" class="bg-yellow-500 text-black font-bold px-2 py-1 rounded text-xs"></span>
                    <span id="movie-year" class="text-gray-300 text-sm"></span>
                    <span id="movie-genre" class="text-red-500 text-sm uppercase tracking-wider font-semibold"></span>
                </div>
                
                <h1 id="movie-title" class="font-bebas text-xl md:text-4xl leading-none mb-4 text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400 drop-shadow-lg">
                </h1>
                
                <p id="movie-desc" class="text-gray-300 text-lg mb-6 line-clamp-3 md:line-clamp-none max-w-lg">
                </p>

                <div class="flex gap-4">
                    <button class="bg-red-600 hover:bg-red-500 text-white font-bold py-3 px-8 rounded-full transition transform hover:scale-105 shadow-[0_0_20px_rgba(255,69,0,0.4)] flex items-center gap-2">
                        <i class="fas fa-play"></i> Watch Now
                    </button>
                    <button class="bg-black/40 backdrop-blur-md border border-white/10 hover:bg-white/10 text-white font-bold py-3 px-8 rounded-full transition flex items-center gap-2">
                        <i class="fas fa-plus"></i> My List
                    </button>
                </div>
            </div>

        </div>

        <!-- Carousel Controls and Thumbnails -->
        <div class="absolute bottom-0 left-0 w-full z-20">
            <div class="container mx-auto px-6 py-6 relative">
                
                <div class="flex justify-between items-end w-full">

                    <!-- Desktop Navigation Buttons -->
                    <div class="hidden md:flex gap-4 flex-shrink-0">
                        <button id="prev-btn" class="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-red-600 hover:border-red-600 transition duration-300 z-30">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button id="next-btn" class="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-red-600 hover:border-red-600 transition duration-300 z-30">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    
                    <div class="flex items-end gap-6 ml-auto w-full md:w-auto">
                        <!-- Thumbnail Container Wrapper -->
                        <div id="thumbnails-container-wrapper" class="max-w-full md:max-w-[800px] w-full flex-grow overflow-hidden relative">
                            
                            <!-- Mobile Overlay Navigation Buttons -->
                            <div class="absolute inset-0 flex justify-between items-center z-30 md:hidden pointer-events-none px-2">
                                <button id="mobile-prev-btn" class="w-10 h-10 rounded-full bg-black/70 border border-white/20 flex items-center justify-center text-white hover:bg-red-600 hover:border-red-600 transition duration-300 pointer-events-auto">
                                    <i class="fas fa-chevron-left text-sm"></i>
                                </button>
                                <button id="mobile-next-btn" class="w-10 h-10 rounded-full bg-black/70 border border-white/20 flex items-center justify-center text-white hover:bg-red-600 hover:border-red-600 transition duration-300 pointer-events-auto">
                                    <i class="fas fa-chevron-right text-sm"></i>
                                </button>
                            </div>

                            <!-- Thumbnails Scroll Area -->
                            <div class="bg-black/40 backdrop-blur-xl border border-white/10 p-4 rounded-2xl flex overflow-x-auto hide-scrollbar">
                                <div id="thumbnails-container" class="flex flex-row gap-4 pr-4">
                                    <!-- Thumbnails are generated here by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- POPUP MODAL (Hidden by default, used to show details when thumbnail is clicked) -->
    <div id="popup-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md hidden">
        <div class="max-w-4xl w-full rounded-2xl overflow-hidden animate-pop-in bg-neutral-900 border border-red-500/30 shadow-[0_0_40px_rgba(255,69,0,0.2)]">
            <div class="relative">
                <button id="close-popup" class="absolute top-4 right-4 z-10 w-10 h-10 rounded-full bg-black/70 flex items-center justify-center text-white hover:bg-red-600 transition">
                    <i class="fas fa-times"></i>
                </button>
                
                <div id="popup-content" class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6">
                    <div class="md:col-span-1">
                        <img id="popup-poster" src="" class="w-full h-80 md:h-96 object-cover rounded-xl shadow-lg">
                    </div>
                    
                    <div class="md:col-span-2 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <span id="popup-rating" class="bg-yellow-500 text-black font-bold px-2 py-1 rounded text-xs"></span>
                                <span id="popup-year" class="text-gray-300 text-sm"></span>
                                <span id="popup-genre" class="text-red-500 text-sm uppercase tracking-wider font-semibold"></span>
                            </div>
                            
                            <h2 id="popup-title" class="font-bebas text-4xl md:text-5xl leading-tight mb-4 text-white"></h2>
                            
                            <p id="popup-desc" class="text-gray-300 text-base mb-6"></p>
                            
                            <div class="mb-6">
                                <h3 class="text-white font-semibold mb-2">Cast</h3>
                                <p id="popup-cast" class="text-gray-400 text-sm"></p>
                            </div>
                            
                            <div>
                                <h3 class="text-white font-semibold mb-2">Director</h3>
                                <p id="popup-director" class="text-gray-400 text-sm"></p>
                            </div>
                        </div>
                        
                        <div class="flex gap-4 mt-6">
                            <button class="bg-red-600 hover:bg-red-500 text-white font-bold py-3 px-8 rounded-full transition transform hover:scale-105 shadow-[0_0_20px_rgba(255,69,0,0.4)] flex items-center gap-2 flex-1 justify-center">
                                <i class="fas fa-play"></i> Watch Now
                            </button>
                            <button class="bg-black/40 backdrop-blur-md border border-white/10 hover:bg-white/10 text-white font-bold py-3 px-6 rounded-full transition flex items-center gap-2">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button class="bg-black/40 backdrop-blur-md border border-white/10 hover:bg-white/10 text-white font-bold py-3 px-6 rounded-full transition flex items-center gap-2">
                                <i class="fas fa-share"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- POPUP MODAL END -->

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
        // --- Carousel Movie Data (5 Slides) ---
        const movies = [
            {
                title: "AVATAR: WAY OF WATER",
                year: "2022",
                rating: "IMDb 7.8",
                genre: "Sci-Fi • Adventure",
                desc: "Jake Sully lives with his newfound family formed on the extrasolar moon Pandora. Once a familiar threat returns to finish what was previously started, Jake must work with Neytiri to protect their home.",
                image: "https://www.yashrajfilms.com/images/default-source/movies/hrithik-vs-tiger/hrithik-v-s-tiger47bda6a026f56f7f9f64ff0b00090313.jpg?sfvrsn=9e48c9cc_17",
                cast: "Sam Worthington, Zoe Saldana, Sigourney Weaver",
                director: "James Cameron"
            },
            {
                title: "OPPENHEIMER",
                year: "2023",
                rating: "IMDb 8.6",
                genre: "Biography • Drama",
                desc: "The story of American scientist J. Robert Oppenheimer and his role in the development of the atomic bomb. A cinematic masterpiece that explores the paradox of saving the world to destroy it.",
                image: "https://mir-s3-cdn-cf.behance.net/project_modules/hd/62804b18669443.562cd567cbcd8.jpg",
                cast: "Cillian Murphy, Emily Blunt, Matt Damon",
                director: "Christopher Nolan"
            },
            {
                title: "SPIDER-MAN: ACROSS THE SPIDER-VERSE",
                year: "2023",
                rating: "IMDb 8.9",
                genre: "Animation • Action",
                desc: "Miles Morales catapults across the Multiverse, where he encounters a team of Spider-People charged with protecting its very existence. A visual spectacle of color and emotion.",
                image: "https://i.ytimg.com/vi/s7njeTw9lSU/maxresdefault.jpg",
                cast: "Shameik Moore, Hailee Steinfeld, Oscar Isaac",
                director: "Joaquim Dos Santos, Kemp Powers, Justin K. Thompson"
            },
            {
                title: "DUNE: PART TWO",
                year: "2024",
                rating: "IMDb 8.8",
                genre: "Sci-Fi • Epic",
                desc: "Paul Atreides unites with Chani and the Fremen while on a warpath of revenge against the conspirators who destroyed his family. The sand worms await in this epic conclusion.",
                image: "https://wallpapercave.com/wp/wp8807385.jpg",
                cast: "Timothée Chalamet, Zendaya, Rebecca Ferguson",
                director: "Denis Villeneuve"
            },
            {
                title: "JOHN WICK: CHAPTER 4",
                year: "2023",
                rating: "IMDb 7.9",
                genre: "Action • Thriller",
                desc: "John Wick uncovers a path to defeating The High Table. But before he can earn his freedom, Wick must face off against a new enemy with powerful alliances across the globe.",
                image: "https://wallpapercave.com/wp/wp1945939.jpg",
                cast: "Keanu Reeves, Donnie Yen, Bill Skarsgård",
                director: "Chad Stahelski"
            }
        ];

        // --- Carousel State and Constants ---
        let currentIndex = 0;
        let autoPlayInterval;
        const slideDuration = 6000;
        
        // --- Carousel DOM Elements ---
        const bgImage = document.getElementById('active-bg');
        const titleEl = document.getElementById('movie-title');
        const descEl = document.getElementById('movie-desc');
        const yearEl = document.getElementById('movie-year');
        const ratingEl = document.getElementById('movie-rating');
        const genreEl = document.getElementById('movie-genre');
        const textContainer = document.getElementById('text-content');
        const thumbnailsContainer = document.getElementById('thumbnails-container');
        
        // Popup elements
        const popupModal = document.getElementById('popup-modal');
        const popupPoster = document.getElementById('popup-poster');
        const popupTitle = document.getElementById('popup-title');
        const popupDesc = document.getElementById('popup-desc');
        const popupYear = document.getElementById('popup-year');
        const popupRating = document.getElementById('popup-rating');
        const popupGenre = document.getElementById('popup-genre');
        const popupCast = document.getElementById('popup-cast');
        const popupDirector = document.getElementById('popup-director');
        const closePopup = document.getElementById('close-popup');

        // Movie Cards Elements
        const successMessage = document.getElementById('success-message');
        const successText = document.getElementById('success-text');

        // --- Carousel Functions ---

        /**
         * Creates and initializes the clickable thumbnails.
         */
        function initThumbnails() {
            thumbnailsContainer.innerHTML = '';
            movies.forEach((movie, index) => {
                const thumb = document.createElement('div');
                thumb.className = `thumbnail relative w-32 h-24 flex-shrink-0 rounded-lg overflow-hidden cursor-pointer border-2 border-transparent transition-all duration-300 group hover:scale-105`;
                
                // Add event listener to change the slide or show popup
                thumb.onclick = () => {
                    if (currentIndex !== index) {
                        currentIndex = index;
                        updateSlide();
                        resetTimer(); 
                    }
                    showPopup(movie); // Show popup on click
                };
                
                thumb.innerHTML = `
                    <img src="${movie.image}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-black/40 group-hover:bg-transparent transition"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black to-transparent">
                        <p class="text-white text-xs font-semibold truncate">${movie.title}</p>
                    </div>
                `;
                thumbnailsContainer.appendChild(thumb);
            });
        }

        /**
         * Updates the main hero slide content and background.
         */
        function updateSlide() {
            const movie = movies[currentIndex];

            // 1. Update background image
            bgImage.style.backgroundImage = `url('${movie.image}')`;
            
            // 2. Update the text content
            titleEl.textContent = movie.title;
            descEl.textContent = movie.desc;
            yearEl.textContent = movie.year;
            ratingEl.textContent = movie.rating;
            genreEl.textContent = movie.genre;

            // 3. Apply entrance animation for text
            textContainer.classList.remove('animate-fade-in-up');
            void textContainer.offsetWidth; // Force reflow to restart animation
            textContainer.classList.add('animate-fade-in-up');

            // 4. Update Thumbnails Active State and scroll
            const thumbs = document.querySelectorAll('.thumbnail');
            
            thumbs.forEach((t, i) => {
                if (i === currentIndex) {
                    t.classList.add('active');
                    // Scroll the active thumbnail into view
                    t.scrollIntoView({
                        behavior: 'smooth',
                        inline: 'center',
                        block: 'nearest' 
                    });
                } else {
                    t.classList.remove('active');
                }
            });
        }

        /**
         * Shows the detailed movie popup.
         */
        function showPopup(movie) {
            popupPoster.src = movie.image;
            popupTitle.textContent = movie.title;
            popupDesc.textContent = movie.desc;
            popupYear.textContent = movie.year;
            popupRating.textContent = movie.rating;
            popupGenre.textContent = movie.genre;
            popupCast.textContent = movie.cast;
            popupDirector.textContent = movie.director;
            
            popupModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        /**
         * Hides the detailed movie popup.
         */
        function hidePopup() {
            popupModal.classList.add('hidden');
            document.body.style.overflow = ''; // Restore background scrolling
        }

        /**
         * Moves to the next slide, wrapping around.
         */
        function nextSlide() {
            currentIndex = (currentIndex + 1) % movies.length;
            updateSlide();
        }

        /**
         * Moves to the previous slide, wrapping around.
         */
        function prevSlide() {
            currentIndex = (currentIndex - 1 + movies.length) % movies.length;
            updateSlide();
        }

        // --- Carousel Auto Play Logic ---
        function startTimer() {
            clearInterval(autoPlayInterval);
            autoPlayInterval = setInterval(() => {
                nextSlide();
            }, slideDuration);
        }

        function resetTimer() {
            startTimer();
        }

        // --- Movie Cards Functions ---

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

        // --- Utility functions ---
        function showSuccess(message) {
            successText.textContent = message;
            successMessage.classList.remove('hidden');
            setTimeout(() => {
                successMessage.classList.add('hidden');
            }, 3000);
        }

        // --- Event Listeners ---
        document.getElementById('next-btn').addEventListener('click', () => {
            nextSlide();
            resetTimer();
        });

        document.getElementById('prev-btn').addEventListener('click', () => {
            prevSlide();
            resetTimer();
        });
        
        document.getElementById('mobile-next-btn').addEventListener('click', () => {
            nextSlide();
            resetTimer();
        });

        document.getElementById('mobile-prev-btn').addEventListener('click', () => {
            prevSlide();
            resetTimer();
        });

        closePopup.addEventListener('click', hidePopup);

        // Close popup when clicking outside the content
        popupModal.addEventListener('click', (e) => {
            if (e.target === popupModal) {
                hidePopup();
            }
        });

        // Close popup with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !popupModal.classList.contains('hidden')) {
                hidePopup();
            }
        });

        // --- Initialization ---
        window.onload = function() {
            // Initialize carousel
            initThumbnails();
            updateSlide();
            startTimer();
            
            // Initialize movie cards
            initializeMovieCards();
            initializeFeaturedMovieCards();
        }

    </script>
</body>
</html>

<?php 
include("../include/footer.php");
?>