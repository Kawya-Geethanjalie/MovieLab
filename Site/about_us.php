<?php 
include("../include/header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Movie Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Animation Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap');
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #050505;
            color: #ffffff;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .reveal { opacity: 0; transform: translateY(40px); }

        .glow-sphere {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            z-index: -1;
            opacity: 0.15;
            pointer-events: none;
        }

        .premium-card {
            background: rgba(20, 20, 20, 0.6);
            backdrop-filter: blur(15px);
            border: 2px solid rgba(255, 255, 255, 0.05);
            border-radius: 24px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .premium-card:hover {
            border-color: rgba(229, 9, 20, 0.4);
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -10px rgba(141, 9, 9, 0.5);
        }

        .text-gradient-red {
            background: linear-gradient(135deg, #E50914 0%, #ff4d4d 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Dark Social Overlay Styling */
        .social-overlay {
            position: absolute;
            bottom: 20px; /* Positioned at bottom */
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            opacity: 0;
            transform: translateY(15px);
            transition: all 0.4s ease;
            z-index: 20;
        }

        .group:hover .social-overlay {
            opacity: 1;
            transform: translateY(0);
        }

        .social-icon {
            width: 38px;
            height: 38px;
            background: rgba(0, 0, 0, 0.7); /* Dark background */
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            background: #e5091467;
            color: white;
            border-color: #e5091450;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(229, 9, 20, 0.4);
        }

        /* Image Gradient Overlay for visibility */
        .img-container::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 40%;
            background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        .group:hover .img-container::after {
            opacity: 1;
        }

        /* Loader */
        #loader {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: #050505;
            z-index: 10000;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .dot-loader { display: flex; gap: 10px; }
        .dot-loader div {
            width: 15px; height: 15px;
            background: #E50914;
            border-radius: 50%;
            animation: bounce 0.6s infinite alternate;
        }
        .dot-loader div:nth-child(2) { animation-delay: 0.2s; }
        .dot-loader div:nth-child(3) { animation-delay: 0.4s; }

        @keyframes bounce {
            to { opacity: 0.3; transform: translateY(-10px); }
        }

        .slider-container {
            height: 450px;
            overflow: hidden;
            position: relative;
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .slide {
            position: absolute;
            inset: 0; opacity: 0;
            transition: opacity 1.2s ease-in-out;
            background-size: cover;
            background-position: center;
        }
        .slide.active { opacity: 1; }
    </style>
</head>
<body class="pt-20">

    <div id="loader">
        <div class="flex flex-col items-center">
            <div class="dot-loader mb-4">
                <div></div>
                <div></div>
                <div></div>
            </div>
            <h5 class="text-red-600 font-bold tracking-widest text-xs uppercase">About Us</h5>
        </div>
    </div>

    <div class="glow-sphere bg-red-600 w-[500px] h-[500px] -top-20 -left-20"></div>

    <section class="relative py-20 px-6 text-center max-w-5xl mx-auto">
        <div id="hero-tag" class="reveal inline-block px-4 py-1.5 mb-6 border border-red-900/50 bg-red-950/20 rounded-full text-red-500 text-xs font-bold tracking-widest uppercase">
            About Our World
        </div>
        <h1 id="hero-title" class="reveal text-5xl md:text-5xl font-black mb-8 tracking-tight">
             Explore <span class="text-gradient-red">the</span> Movie Lab
        </h1>
        <p class="reveal text-gray-400 text-lg md:text-medium leading-relaxed max-w-3xl mx-auto"> We are a team of movie enthusiasts and tech geeks dedicated to bringing you the best entertainment experience. From the latest blockbusters to evergreen melodies, Movie Lab is your ultimate digital theater. </p>
    </section>

    <!-- Stats Section -->
    <section class="py-10 px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="premium-card p-10 text-center reveal border-b-2 border-b-red-600/30">
                <div class="text-red-600 text-3xl mb-4"><i class="fas fa-film"></i></div>
                <h3 class="text-4xl font-extrabold mb-1"><span class="counter">500</span>+</h3>
                <p class="text-gray-400 font-bold text-xs uppercase tracking-widest">Movies Collection</p>
            </div>
            <div class="premium-card p-10 text-center reveal border-b-2 border-b-red-600/30">
                <div class="text-red-600 text-3xl mb-4"><i class="fas fa-users"></i></div>
                <h3 class="text-4xl font-extrabold mb-1"><span class="counter">1000</span>+</h3>
                <p class="text-gray-400 font-bold text-xs uppercase tracking-widest">Happy Users</p>
            </div>
            <div class="premium-card p-10 text-center reveal border-b-2 border-b-red-600/30">
                <div class="text-red-600 text-3xl mb-4"><i class="fas fa-music"></i></div>
                <h3 class="text-4xl font-extrabold mb-1"><span class="counter">200</span>+</h3>
                <p class="text-gray-400 font-bold text-xs uppercase tracking-widest">Audio Tracks</p>
            </div>
            <div class="premium-card p-10 text-center reveal border-b-2 border-b-red-600/30">
                <div class="text-red-600 text-3xl mb-4"><i class="fas fa-headset"></i></div>
                <h3 class="text-4xl font-extrabold mb-1">24/7</h3>
                <p class="text-gray-400 font-bold text-xs uppercase tracking-widest">Premium Support</p>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="reveal py-20 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col items-center mb-16 text-center reveal">
                <span class="text-red-600 font-semibold tracking-widest uppercase text-sm mb-2">The Brains Behind</span>
                <h2 class="text-4xl md:text-4xl font-bold mb-4">Meet Our <span class="text-gradient-red">Team</span></h2>
                <div class="w-24 h-1 bg-red-600 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Member 1 -->
                <div class="premium-card reveal-card p-6 group">
                    <div class="mb-6 relative overflow-hidden rounded-2xl aspect-square img-container">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=1000&auto=format&fit=crop" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                        <!-- Dark Social Icons Overlay - Now at the bottom -->
                        <div class="social-overlay">
                            <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="text-center">
                        <h4 class="text-2xl font-bold">Dinesh Wickramasinghe</h4>
                        <p class="text-red-500 text-sm uppercase tracking-widest font-semibold mt-1">Founder & Lead</p>
                        <p class="text-gray-400 text-sm mt-4 leading-relaxed px-2">Expert in high-end streaming architectures and system security with over a decade of experience.</p>
                    </div>
                </div>

                <!-- Member 2 -->
                <div class="premium-card reveal-card p-6 group">
                    <div class="mb-6 relative overflow-hidden rounded-2xl aspect-square img-container">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=1000&auto=format&fit=crop" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                        <div class="social-overlay">
                            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-behance"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-dribbble"></i></a>
                        </div>
                    </div>
                    <div class="text-center">
                        <h4 class="text-2xl font-bold">Sarah Perera</h4>
                        <p class="text-red-500 text-sm uppercase tracking-widest font-semibold mt-1">Lead Designer</p>
                        <p class="text-gray-400 text-sm mt-4 leading-relaxed px-2">Passionate designer focused on creating immersive digital experiences that captivate the eye.</p>
                    </div>
                </div>

                <!-- Member 3 -->
                <div class="premium-card reveal-card p-6 group">
                    <div class="mb-6 relative overflow-hidden rounded-2xl aspect-square img-container">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=1000&auto=format&fit=crop" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                        <div class="social-overlay">
                            <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-github"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="text-center">
                        <h4 class="text-2xl font-bold">Kasun Silva</h4>
                        <p class="text-red-500 text-sm uppercase tracking-widest font-semibold mt-1">Content Manager</p>
                        <p class="text-gray-400 text-sm mt-4 leading-relaxed px-2">Dedicated to sourcing and organizing the highest quality cinematic content for our global users.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trending Gallery Section -->
    <section class="py-20 px-6 max-w-7xl mx-auto reveal">
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-4xl font-bold">Trending <span class="text-gradient-red">Gallery</span></h2>
            <p class="text-gray-500 mt-4">Experience the latest highlights from our collection</p>
        </div>
        <div class="slider-container">
            <div class="slide active" style="background-image: linear-gradient(to bottom, transparent, #050505), url('https://images.unsplash.com/photo-1536440136628-849c177e76a1?q=80&w=2000&auto=format&fit=crop');">
                <div class="absolute bottom-12 left-12"><h4 class="text-4xl md:text-3xl font-black uppercase">Cinematic Experience</h4></div>
            </div>
            <div class="slide" style="background-image: linear-gradient(to bottom, transparent, #050505), url('https://images.unsplash.com/photo-1478720568477-152d9b164e26?q=80&w=2000&auto=format&fit=crop');">
                <div class="absolute bottom-12 left-12"><h4 class="text-4xl md:text-3xl font-black uppercase">Epic Storytelling</h4></div>
            </div>
            <div class="slide" style="background-image: linear-gradient(to bottom, transparent, #050505), url('https://images.unsplash.com/photo-1598899134739-24c46f58b8c0?q=80&w=2000&auto=format&fit=crop');">
                <div class="absolute bottom-12 left-12"><h4 class="text-4xl md:text-3xl font-black uppercase">Pure Soundtracks</h4></div>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="py-24 px-6 bg-[#0a0a0a] relative overflow-hidden">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center gap-16">
            <div class="flex-1 reveal">
                <h2 class="text-4xl font-bold mb-8">Our <span class="text-gradient-red">Mission</span></h2>
                <p class="text-gray-400 text-lg mb-8 leading-relaxed">
                    Our mission is simple: To provide a seamless, high-quality, and accessible platform for everyone to enjoy their favorite visual and audio content. We believe that entertainment should have no boundaries.
                </p>
                <div class="space-y-6">
                    <div class="flex items-start group">
                        <div class="w-12 h-12 bg-red-600/10 border border-red-600/30 rounded-xl flex items-center justify-center mr-5 shrink-0 transition-colors group-hover:bg-red-600/20">
                            <i class="fas fa-check-circle text-red-600"></i>
                        </div>
                        <div>
                            <h5 class="font-bold text-white">High Definition Quality</h5>
                            <p class="text-gray-500 text-sm">Experience visual excellence in every frame.</p>
                        </div>
                    </div>
                    <div class="flex items-start group">
                        <div class="w-12 h-12 bg-red-600/10 border border-red-600/30 rounded-xl flex items-center justify-center mr-5 shrink-0 transition-colors group-hover:bg-red-600/20">
                            <i class="fas fa-check-circle text-red-600"></i>
                        </div>
                        <div>
                            <h5 class="font-bold text-white">Ad-free Experience</h5>
                            <p class="text-gray-500 text-sm">Seamless streaming for our premium members.</p>
                        </div>
                    </div>
                    <div class="flex items-start group">
                        <div class="w-12 h-12 bg-red-600/10 border border-red-600/30 rounded-xl flex items-center justify-center mr-5 shrink-0 transition-colors group-hover:bg-red-600/20">
                            <i class="fas fa-check-circle text-red-600"></i>
                        </div>
                        <div>
                            <h5 class="font-bold text-white">Cross-device Compatibility</h5>
                            <p class="text-gray-500 text-sm">Watch anywhere, anytime, on any device.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-1 w-full  reveal">
                <div class="relative z-10 premium-card p-3 md:rotate-3">
                    <img src="https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?q=80&w=2070&auto=format&fit=crop" class="rounded-2xl" alt="Cinema Hall">
                <!-- </div> -->
                <div class="absolute -bottom-6 -left-6 w-full h-full border-2 border-red-600/20 rounded-2xl -z-10 -rotate-2"></div>
            </div>
        </div>
    </section>

    <script>
        gsap.registerPlugin(ScrollTrigger);

        window.addEventListener('load', () => {
            gsap.to("#loader", { 
                opacity: 0, 
                duration: 0.8, 
                delay: 0.5,
                onComplete: () => {
                    document.getElementById('loader').style.display = 'none';
                    initAnimations();
                    startSlider();
                }
            });
        });

        function startSlider() {
            const slides = document.querySelectorAll('.slide');
            let current = 0;
            setInterval(() => {
                slides[current].classList.remove('active');
                current = (current + 1) % slides.length;
                slides[current].classList.add('active');
            }, 4500);
        }

        function initAnimations() {
            const tl = gsap.timeline();
            tl.to("#hero-tag", { opacity: 1, y: 0, duration: 0.8 })
              .to("#hero-title", { opacity: 1, y: 0, duration: 0.8 }, "-=0.4")
              .to("p.reveal", { opacity: 1, y: 0, duration: 0.8 }, "-=0.2");

            gsap.utils.toArray(".reveal").forEach(item => {
                gsap.to(item, {
                    opacity: 1, y: 0, duration: 1,
                    scrollTrigger: { trigger: item, start: "top 85%" }
                });
            });

            gsap.to(".reveal-card", {
                opacity: 1, y: 0, duration: 0.8, stagger: 0.2,
                scrollTrigger: { trigger: ".reveal-card", start: "top 80%" }
            });

            document.querySelectorAll('.counter').forEach(el => {
                const target = parseInt(el.innerText);
                gsap.fromTo(el, { innerText: 0 }, {
                    innerText: target, 
                    duration: 2.5, 
                    snap: { innerText: 1 },
                    scrollTrigger: { trigger: el, start: "top 95%" },
                    onUpdate: function() {
                        el.innerText = Math.floor(el.innerText);
                    }
                });
            });
        }
    </script>
</body>
</html>
<?php 
include("../include/footer.php");
?>