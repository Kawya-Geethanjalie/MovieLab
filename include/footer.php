</div>

    <footer >
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Styled CineStream Footer</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Custom Tailwind Configuration for the Primary Red Color
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-red': '#E50914', // Cinematic/Netflix Red
                    },
                }
            }
        }
    </script>
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

        /* Minor enhancement for form focus */
        .focus\:ring-primary-red:focus {
            box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.5);
            border-color: #E50914;
        }

        /* Ensure smooth scrolling when necessary */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="bg-gray-950 min-h-screen flex flex-col justify-end">

    <!-- Mock Content to give context for the footer -->
   

    <!-- The Styled Footer Content (Adapted from movie_site_footer_en.html) -->
    <footer class="bg-black border-t border-gray-800 pt-12 pb-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Main Footer Content Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
                
                <!-- Column 1: Brand & Description -->
                <div class="col-span-2 lg:col-span-1 pr-4">
                    <!-- Replicating the logo style: CineStream with working glow -->
                    <a href="#" class="text-3xl font-extrabold text-white">
                        <span class="text-primary-red text-glow-red">Movie Lab</span>
                    </a>
                    <p class="mt-4 text-sm text-gray-400">
                        Watch your favorite movies, songs and series all in one place.
                    </p>
                </div>

                <!-- Column 2: Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4 border-b border-gray-700 pb-2">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-primary-red transition duration-150">Home</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary-red transition duration-150">Movies</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary-red transition duration-150">TV Series</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary-red transition duration-150">Songs</a></li>
                    </ul>
                </div>

                <!-- Column 3: Legal & Support -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4 border-b border-gray-700 pb-2">Help & Legal</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-primary-red transition duration-150">FAQ</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary-red transition duration-150">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary-red transition duration-150">Terms of Service</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary-red transition duration-150">Contact Us</a></li>
                    </ul>
                </div>

                <!-- Column 4: Stay Connected (for smaller screens) -->
                <div class="md:hidden">
                    <h3 class="text-lg font-semibold text-white mb-4 border-b border-gray-700 pb-2">Connect</h3>
                    <div class="flex space-x-4">
                        
                        <!-- 1. Facebook Icon -->
                        <a href="#" class="text-gray-400 hover:text-primary-red transition duration-150" aria-label="Facebook">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.218 0-4.192 1.58-4.192 4.615v3.385z"/></svg>
                        </a>
                        
                        <!-- 2. YouTube Icon -->
                        <a href="#" class="text-gray-400 hover:text-primary-red transition duration-150" aria-label="YouTube">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M21.582 7.142c-.23-.847-.942-1.55-1.789-1.777C18.252 5 12 5 12 5s-6.252 0-7.793.365c-.847.23-1.55.942-1.777 1.789C2 8.748 2 12 2 12s0 3.252.365 4.793c.23.847.942 1.55 1.789 1.777C5.748 19 12 19 12 19s6.252 0 7.793-.365c.847-.23 1.55-.942 1.777-1.789C22 15.252 22 12 22 12s0-3.252-.365-4.793zM10 15V9l6 3-6 3z"/></svg>
                        </a>

                        <!-- 3. WhatsApp Icon (UPDATED - Clearer Full Circle Icon) -->
                        <a href="#" class="text-gray-400 hover:text-primary-red transition duration-150" aria-label="WhatsApp">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                <path d="M12 2C6.477 2 2 6.477 2 12c0 1.503.411 2.924 1.15 4.16L2 22l5.84-1.15A9.957 9.957 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm3.327 13.923c-.22-.12-.894-.436-1.034-.486-.14-.05-.24-.075-.34.125-.1.2-.387.67-.475.77-.088.1-.175.125-.325.05-.15-.075-.634-.234-1.206-.745-1.523-1.307-2.025-2.056-2.262-2.456-.237-.4-.025-.61.19-.82.175-.175.388-.436.525-.65.137-.214.183-.357.275-.583.092-.226.046-.425-.025-.562-.07-.137-.63-.497-.864-.997-.234-.5-.468-.436-.67-.436s-.44.025-.67.31c-.23.286-.88.854-.88 2.073s.904 2.404 1.03 2.564c.125.16 1.785 2.8 4.33 3.935 2.545 1.135 2.545.757 2.99 1.132.445.375.445.71.32.784-.125.075-.89.344-1.015.418z"/>
                            </svg>
                        </a>

                        <!-- 4. Twitter (X) Icon -->
                        <a href="#" class="text-gray-400 hover:text-primary-red transition duration-150" aria-label="Twitter">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.795-1.574 2.16-2.727-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.554-3.593-1.554-2.71 0-4.912 2.202-4.912 4.912 0 .385.045.758.132 1.115-4.085-.205-7.712-2.163-10.14-5.143-.424.729-.668 1.577-.668 2.485 0 1.704.869 3.209 2.188 4.098-.809-.026-1.57-.247-2.235-.619v.062c0 2.385 1.693 4.38 3.93 4.832-.41.111-.843.171-1.287.171-.315 0-.623-.031-.922-.088.623 1.956 2.433 3.383 4.587 3.423-1.685 1.321-3.81 2.109-6.14 2.109-.401 0-.799-.022-1.196-.079 2.179 1.396 4.768 2.209 7.558 2.209 9.064 0 14.01-7.514 14.01-14.01 0-.213-.005-.425-.015-.636.961-.699 1.794-1.577 2.457-2.572z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Column 5 (or 4 on md screen): Newsletter/Social Icons -->
                <div class="col-span-2 md:col-span-1">
                    <h3 class="text-lg font-semibold text-white mb-4 border-b border-gray-700 pb-2">Newsletter</h3>
                    <p class="text-sm text-gray-400 mb-4">
                        Get updates on new releases and exclusive offers.
                    </p>
                    <form>
                        <input type="email" placeholder="Your Email Address" class="w-full p-3 rounded-lg bg-gray-800 text-white border border-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-red mb-3 shadow-lg" required>
                        <button type="submit" class="w-full bg-primary-red text-white font-bold py-3 rounded-lg hover:bg-red-600 transition duration-300 transform hover:scale-105 shadow-xl shadow-red-900/40">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>

            <!-- Separator -->
            <hr class="my-8 border-gray-800">

            <!-- Bottom Row: Copyright and Social Icons (for desktop) -->
            <div class="flex flex-col md:flex-row items-center justify-between">
                
                <!-- Copyright Text -->
                <p class="text-sm text-gray-500 order-2 md:order-1 mt-4 md:mt-0">
                    &copy; <span id="currentYearEN"></span> MovieLab. All rights reserved.
                </p>

                <!-- Social Media Icons (for desktop) -->
                <div class="hidden md:flex space-x-6 order-1 md:order-2">
                    
                    <!-- 1. Facebook Icon (Desktop) -->
                    <a href="https://www.facebook.com" class="text-gray-400 hover:text-primary-red transition duration-150 p-1 rounded-full hover:bg-gray-800" aria-label="Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.218 0-4.192 1.58-4.192 4.615v3.385z"/></svg>
                    </a>
                    
                    <!-- 2. YouTube Icon (Desktop) -->
                    <a href="https://www.youtube.com" class="text-gray-400 hover:text-primary-red transition duration-150 p-1 rounded-full hover:bg-gray-800" aria-label="YouTube">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M21.582 7.142c-.23-.847-.942-1.55-1.789-1.777C18.252 5 12 5 12 5s-6.252 0-7.793.365c-.847.23-1.55.942-1.777 1.789C2 8.748 2 12 2 12s0 3.252.365 4.793c.23.847.942 1.55 1.789 1.777C5.748 19 12 19 12 19s6.252 0 7.793-.365c.847-.23 1.55-.942 1.777-1.789C22 15.252 22 12 22 12s0-3.252-.365-4.793zM10 15V9l6 3-6 3z"/></svg>
                    </a>

                    <!-- 3. WhatsApp Icon (UPDATED - Clearer Full Circle Icon) -->
                    <a href="https://www.youtube.com" class="text-gray-400 hover:text-primary-red transition duration-150 p-1 rounded-full hover:bg-gray-800" aria-label="WhatsApp">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path d="M12 2C6.477 2 2 6.477 2 12c0 1.503.411 2.924 1.15 4.16L2 22l5.84-1.15A9.957 9.957 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm3.327 13.923c-.22-.12-.894-.436-1.034-.486-.14-.05-.24-.075-.34.125-.1.2-.387.67-.475.77-.088.1-.175.125-.325.05-.15-.075-.634-.234-1.206-.745-1.523-1.307-2.025-2.056-2.262-2.456-.237-.4-.025-.61.19-.82.175-.175.388-.436.525-.65.137-.214.183-.357.275-.583.092-.226.046-.425-.025-.562-.07-.137-.63-.497-.864-.997-.234-.5-.468-.436-.67-.436s-.44.025-.67.31c-.23.286-.88.854-.88 2.073s.904 2.404 1.03 2.564c.125.16 1.785 2.8 4.33 3.935 2.545 1.135 2.545.757 2.99 1.132.445.375.445.71.32.784-.125.075-.89.344-1.015.418z"/>
                        </svg>
                    </a>

                    <!-- 4. Twitter (X) Icon (Desktop) -->
                    <a href="https://x.com" class="text-gray-400 hover:text-primary-red transition duration-150 p-1 rounded-full hover:bg-gray-800" aria-label="Twitter">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.795-1.574 2.16-2.727-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.554-3.593-1.554-2.71 0-4.912 2.202-4.912 4.912 0 .385.045.758.132 1.115-4.085-.205-7.712-2.163-10.14-5.143-.424.729-.668 1.577-.668 2.485 0 1.704.869 3.209 2.188 4.098-.809-.026-1.57-.247-2.235-.619v.062c0 2.385 1.693 4.38 3.93 4.832-.41.111-.843.171-1.287.171-.315 0-.623-.031-.922-.088.623 1.956 2.433 3.383 4.587 3.423-1.685 1.321-3.81 2.109-6.14 2.109-.401 0-.799-.022-1.196-.079 2.179 1.396 4.768 2.209 7.558 2.209 9.064 0 14.01-7.514 14.01-14.01 0-.213-.005-.425-.015-.636.961-.699 1.794-1.577 2.457-2.572z"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- JavaScript to automatically set the current year -->
        <script>
            document.getElementById('currentYearEN').textContent = new Date().getFullYear();
        </script>
    

</body>
</html>

</footer>