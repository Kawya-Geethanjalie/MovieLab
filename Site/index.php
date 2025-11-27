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
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        // Original font kept
                        'inter': ['Inter', 'sans-serif'], 
                        // New Carousel fonts
                        'bebas': ['"Bebas Neue"', 'sans-serif'],
                        'poppins': ['"Poppins"', 'sans-serif'],
                    },
                    colors: {
                        'neon-red': '#FF4500', 
                        'neon-purple': '#bc13fe',
                    },
                    // New Carousel Animations
                    animation: {
                        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                        'zoom-in': 'zoomIn 10s linear infinite',
                        'pop-in': 'popIn 0.5s ease-out forwards',
                        'slide-up': 'slideUp 0.5s ease-out forwards',
                        // Removed: 'slide-out-left' and 'slide-in-right' as the new logic uses 'fade-in-up'
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
                        // Removed: slideOutLeft and slideInRight keyframes
                    }
                }
            }
        }
    </script>
    <style>
        /* Hide scrollbar for the thumbnail container */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        /* Active state for thumbnail */
        .thumbnail.active {
            border-color: #FF4500;
            box-shadow: 0 0 15px rgba(255, 69, 0, 0.5);
            transform: scale(1.05);
        }
    </style>
</head>

<body class="bg-black text-white font-poppins overflow-hidden w-full relative">
    
    <section class="relative overflow-hidden h-[70vh] md:h-[85vh] w-full">
        <div id="bg-layer" class="absolute inset-0 w-full h-full overflow-hidden">
            <div id="active-bg" class="w-full h-full bg-cover bg-center transition-all duration-800 ease-in-out animate-zoom-in brightness-100"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-black via-black/60 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-black/40"></div>
        </div>

        <div class="relative z-10 container mx-auto px-6 h-full flex flex-col justify-center md:justify-start pt-12 pb-24 md:pt-40 md:pb-16">
            
           

            <div id="text-content" class="max-w-2xl mt-[100px] pl-4 border-l-4 border-red-500">
                <div class="flex items-center gap-3 mb-4">
                    <span id="movie-rating" class="bg-yellow-500 text-black font-bold px-2 py-1 rounded text-xs">IMDb 7.8</span>
                    <span id="movie-year" class="text-gray-300 text-sm">2022</span>
                    <span id="movie-genre" class="text-red-500 text-sm uppercase tracking-wider font-semibold">Sci-Fi • Adventure</span>
                </div>
                
                <h1 id="movie-title" class="font-bebas text-4xl md:text-7xl leading-none mb-4 text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400 drop-shadow-lg">
                    AVATAR: WAY OF WATER
                </h1>
                
                <p id="movie-desc" class="text-gray-300 text-lg mb-6 line-clamp-3 md:line-clamp-none max-w-lg">
                    Jake Sully lives with his newfound family formed on the extrasolar moon Pandora. Once a familiar threat returns to finish what was previously started, Jake must work with Neytiri to protect their home.
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

        <div class="absolute bottom-0 left-0 w-full z-20">
            <div class="container mx-auto px-6 py-6 relative">
                
                <div class="flex justify-between items-end w-full">

                    <div class="hidden md:flex gap-4 flex-shrink-0">
                        <button id="prev-btn" class="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-red-600 hover:border-red-600 transition duration-300 z-30">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button id="next-btn" class="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-red-600 hover:border-red-600 transition duration-300 z-30">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    
                    <div class="flex items-end gap-6 ml-auto w-full md:w-auto">
                        <div id="thumbnails-container-wrapper" class="max-w-full md:max-w-[800px] w-full flex-grow overflow-hidden relative">
                            
                            <div class="absolute inset-0 flex justify-between items-center z-30 md:hidden pointer-events-none px-2">
                                <button id="mobile-prev-btn" class="w-10 h-10 rounded-full bg-black/70 border border-white/20 flex items-center justify-center text-white hover:bg-red-600 hover:border-red-600 transition duration-300 pointer-events-auto">
                                    <i class="fas fa-chevron-left text-sm"></i>
                                </button>
                                <button id="mobile-next-btn" class="w-10 h-10 rounded-full bg-black/70 border border-white/20 flex items-center justify-center text-white hover:bg-red-600 hover:border-red-600 transition duration-300 pointer-events-auto">
                                    <i class="fas fa-chevron-right text-sm"></i>
                                </button>
                            </div>

                            <div class="bg-black/40 backdrop-blur-xl border border-white/10 p-4 rounded-2xl flex overflow-x-auto hide-scrollbar">
                                <div id="thumbnails-container" class="flex flex-row gap-4 pr-4">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main class="container mx-auto px-4 py-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div class="flex flex-wrap gap-2">
                <button class="px-3 py-1.5 md:px-4 md:py-2 bg-red-600 text-white rounded-lg text-sm">All Movies</button>
                <button class="px-3 py-1.5 md:px-4 md:py-2 bg-gray-900 text-gray-300 hover:bg-gray-800 rounded-lg transition-colors text-sm">Popular</button>
                <button class="px-3 py-1.5 md:px-4 md:py-2 bg-gray-900 text-gray-300 hover:bg-gray-800 rounded-lg transition-colors text-sm">New</button>
                <button class="px-3 py-1.5 md:px-4 md:py-2 bg-gray-900 text-gray-300 hover:bg-gray-800 rounded-lg transition-colors text-sm">Top Rated</button>
            </div>
        </div>

        <section class="mb-10">
            <h2 class="text-xl md:text-2xl font-bold mb-5">Popular Songs</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="song-card transition-all duration-300 ease-in-out hover:-translate-y-2 hover:shadow-[0_20px_25px_-5px_rgba(220,38,38,0.3)] bg-gray-900 rounded-lg overflow-hidden relative group">
                    <div class="relative">
                        <img src="https://source.unsplash.com/random/300x300/?music,sinhala" alt="Song cover" class="w-full h-40 md:h-48 object-cover" loading="lazy">
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <button class="p-2.5 bg-red-600 rounded-full text-white hover:bg-red-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                <div class="song-card transition-all duration-300 ease-in-out hover:-translate-y-2 hover:shadow-[0_20px_25px_-5px_rgba(220,38,38,0.3)] bg-gray-900 rounded-lg overflow-hidden relative group">
                    <div class="relative">
                        <img src="../images/poster1.jpg" alt="Song cover" class="w-full h-40 md:h-48 object-cover" loading="lazy">
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <button class="p-2.5 bg-red-600 rounded-full text-white hover:bg-red-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                </div>
        </section>

        </main>
    
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
                                <span id="popup-rating" class="bg-yellow-500 text-black font-bold px-2 py-1 rounded text-xs">IMDb 7.8</span>
                                <span id="popup-year" class="text-gray-300 text-sm">2022</span>
                                <span id="popup-genre" class="text-red-500 text-sm uppercase tracking-wider font-semibold">Sci-Fi • Adventure</span>
                            </div>
                            
                            <h2 id="popup-title" class="font-bebas text-4xl md:text-5xl leading-tight mb-4 text-white">
                                AVATAR: WAY OF WATER
                            </h2>
                            
                            <p id="popup-desc" class="text-gray-300 text-base mb-6">
                                Jake Sully lives with his newfound family formed on the extrasolar moon Pandora. Once a familiar threat returns to finish what was previously started, Jake must work with Neytiri to protect their home.
                            </p>
                            
                            <div class="mb-6">
                                <h3 class="text-white font-semibold mb-2">Cast</h3>
                                <p id="popup-cast" class="text-gray-400 text-sm">Sam Worthington, Zoe Saldana, Sigourney Weaver</p>
                            </div>
                            
                            <div>
                                <h3 class="text-white font-semibold mb-2">Director</h3>
                                <p id="popup-director" class="text-gray-400 text-sm">James Cameron</p>
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

    <script>
        // --- Movie Data (5 Slides) ---
        const movies = [
            {
                title: "AVATAR: WAY OF WATER",
                year: "2022",
                rating: "IMDb 7.8",
                genre: "Sci-Fi • Adventure",
                desc: "Jake Sully lives with his newfound family formed on the extrasolar moon Pandora. Once a familiar threat returns to finish what was previously started, Jake must work with Neytiri to protect their home.",
               image: "https://www.yashrajfilms.com/images/default-source/movies/hrithik-vs-tiger/hrithik-v-s-tiger47bda6a026f56f7f9f64ff0b00090313.jpg?sfvrsn=9e48c9cc_17",
                teamColor: "Red",
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
                teamColor: "White",
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
                teamColor: "Black",
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
                teamColor: "Red",
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
                teamColor: "White",
                cast: "Keanu Reeves, Donnie Yen, Bill Skarsgård",
                director: "Chad Stahelski"
            }
        ];

        // --- State ---
        let currentIndex = 0;
        let autoPlayInterval;
        const slideDuration = 6000;
        const animationDuration = 500;

        // --- DOM Elements ---
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

        // --- Functions ---

        function initThumbnails() {
            thumbnailsContainer.innerHTML = '';
            movies.forEach((movie, index) => {
                const thumb = document.createElement('div');
                thumb.className = `thumbnail relative w-32 h-24 flex-shrink-0 rounded-lg overflow-hidden cursor-pointer border-2 border-transparent transition-all duration-300 group hover:scale-105`;
                thumb.onclick = () => {
                    // Update main slide when clicking thumbnail
                    if (currentIndex !== index) {
                        currentIndex = index;
                        updateSlide();
                        resetTimer(); // Reset the auto-play timer
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
         * FIXED: Simplified updateSlide function for reliable transitions.
         * It removes the complex 'slide-out-left' / 'slide-in-right' logic 
         * and uses the simpler 'fade-in-up' animation on every change.
         */
        function updateSlide() {
            const movie = movies[currentIndex];

            // 1. Immediately update the background image (with the 700ms CSS transition)
            bgImage.style.backgroundImage = `url('${movie.image}')`;
            
            // 2. Update the text content
            titleEl.textContent = movie.title;
            descEl.textContent = movie.desc;
            yearEl.textContent = movie.year;
            ratingEl.textContent = movie.rating;
            genreEl.textContent = movie.genre;

            // 3. Robustly apply a single entrance animation (animate-fade-in-up)
            // Remove all previous slide-related animation classes for a clean restart.
            textContainer.classList.remove('animate-slide-in-right', 'animate-slide-out-left', 'animate-fade-in-up');
            
            // Force reflow/re-render to ensure the animation restarts on every content change
            void textContainer.offsetWidth; 
            
            // Apply the simple, clean fade-in-up animation
            textContainer.classList.add('animate-fade-in-up');


            // 4. Update Thumbnails Active State and scroll (Mobile Responsive Thumbnail fix)
            const thumbs = document.querySelectorAll('.thumbnail');
            
            thumbs.forEach((t, i) => {
                if (i === currentIndex) {
                    t.classList.add('active');
                    
                    // *** FIX STARTS HERE ***
                    // By adding 'block: "nearest"', we prevent the main viewport (the page)
                    // from scrolling vertically to center the thumbnail.
                    t.scrollIntoView({
                        behavior: 'smooth',
                        inline: 'center',
                        block: 'nearest' // 👈 THIS IS THE FIX
                    });
                    // *** FIX ENDS HERE ***
                } else {
                    t.classList.remove('active');
                }
            });
        }

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
            document.body.style.overflow = 'hidden';
        }

        function hidePopup() {
            popupModal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        function nextSlide() {
            currentIndex = (currentIndex + 1) % movies.length;
            updateSlide();
        }

        function prevSlide() {
            currentIndex = (currentIndex - 1 + movies.length) % movies.length;
            updateSlide();
        }

        // --- Auto Play Logic ---
        function startTimer() {
            clearInterval(autoPlayInterval);
            autoPlayInterval = setInterval(() => {
                nextSlide();
            }, slideDuration);
        }

        function resetTimer() {
            startTimer();
        }

        // --- Event Listeners ---
        // Desktop Buttons
        document.getElementById('next-btn').addEventListener('click', () => {
            nextSlide();
            resetTimer();
        });

        document.getElementById('prev-btn').addEventListener('click', () => {
            prevSlide();
            resetTimer();
        });
        
        // Mobile Overlay Buttons
        document.getElementById('mobile-next-btn').addEventListener('click', () => {
            nextSlide();
            resetTimer();
        });

        document.getElementById('mobile-prev-btn').addEventListener('click', () => {
            prevSlide();
            resetTimer();
        });

        closePopup.addEventListener('click', hidePopup);

        popupModal.addEventListener('click', (e) => {
            if (e.target === popupModal) {
                hidePopup();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !popupModal.classList.contains('hidden')) {
                hidePopup();
            }
        });

        // --- Initialization ---
        initThumbnails();
        updateSlide();
        startTimer(); 

    </script>
</body>
</html>

<?php 
include("../include/footer.php");
?>