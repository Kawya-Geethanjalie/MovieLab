<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - MovieLab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0c0c0c 0%, #1a1a1a 100%);
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(239, 68, 68, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(239, 68, 68, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(0, 0, 0, 0.2) 0%, transparent 50%);
            z-index: -1;
        }
        
        .login-container {
            background: rgba(23, 23, 23, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(239, 68, 68, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5), 0 1px 1px rgba(239, 68, 68, 0.2);
        }
        
        .input-field {
            background: rgba(15, 15, 15, 0.7);
            border: 1px solid rgba(239, 68, 68, 0.3);
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
        }
        
        .login-btn {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.6);
        }
        
        .login-btn:active {
            transform: translateY(0);
        }
        
        .login-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .login-btn:hover::after {
            left: 100%;
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
            }
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% {
                transform: translate(0, 0px);
            }
            50% {
                transform: translate(0, -10px);
            }
            100% {
                transform: translate(0, 0px);
            }
        }
        
        .glow {
            text-shadow: 0 0 10px rgba(239, 68, 68, 0.7);
        }
        
        .error-alert {
            background: rgba(127, 29, 29, 0.3);
            border: 1px solid rgba(239, 68, 68, 0.4);
            backdrop-filter: blur(5px);
        }
        
        /* --- START OF VGG.HTML CSS FOR JS EFFECT --- */
        
        /* Required CSS for particle burst effect on button click */
        .particle {
            position: absolute;
            width: 8px;
            height: 8px;
            background-color: #ef4444; /* Red */
            border-radius: 50%;
            pointer-events: none;
            opacity: 1;
            transition: transform 0.8s ease-out, opacity 0.8s ease-out, width 0.8s ease-out, height 0.8s ease-out;
            z-index: 50; 
        }

        /* Professional Glitch/Matrix Data Transfer Loading Effect for Next Page Load */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes glitch {
            0% { text-shadow: 2px 0 #dc2626, -2px 0 #ef4444; }
            50% { text-shadow: -2px 0 #dc2626, 2px 0 #ef4444; }
            100% { text-shadow: 2px 0 #dc2626, -2px 0 #ef4444; }
        }

        .glitch-loader {
            width: 120px;
            height: 120px;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .data-core {
            width: 100%;
            height: 100%;
            border: 6px solid transparent;
            border-top-color: #dc2626; /* Deep Red for the rotating part */
            border-radius: 50%;
            animation: spin 1s infinite linear; 
            position: absolute;
            box-shadow: 0 0 15px rgba(220, 38, 38, 0.7); /* Red glow */
        }

        .data-core::before {
            content: '';
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            bottom: 5px;
            border: 6px solid transparent;
            border-bottom-color: #ef4444; /* Lighter Red for secondary line */
            border-radius: 50%;
            animation: spin 1.5s infinite reverse linear;
        }

        .glitch-text {
            font-size: 1.5rem; /* Adjust size if needed */
            font-weight: bold;
            color: #fff;
            animation: glitch 0.3s infinite alternate;
            z-index: 10;
        }
        
        .message-box-inner {
            background-color: rgba(127, 29, 29, 0.9); /* Dark red background for button message */
        }
        
        /* --- END OF VGG.HTML CSS FOR JS EFFECT --- */
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-4">
    <!-- Background Elements (Kept for design) -->
    <div class="absolute top-10 left-10 w-20 h-20 rounded-full bg-red-500/10 blur-xl floating"></div>
    <div class="absolute bottom-10 right-10 w-32 h-32 rounded-full bg-red-500/5 blur-xl floating" style="animation-delay: 1.5s;"></div>
    <div class="absolute top-1/2 left-1/4 w-16 h-16 rounded-full bg-red-500/10 blur-lg floating" style="animation-delay: 2.5s;"></div>
    
    <!-- Film Strip Effect (Kept for design) -->
    <div class="absolute top-0 left-0 w-full h-4 bg-black flex">
        <div class="h-full w-8 bg-red-500"></div>
        <div class="h-full w-8 bg-transparent"></div>
        <div class="h-full w-8 bg-red-500"></div>
        <div class="h-full w-8 bg-transparent"></div>
        <div class="h-full w-8 bg-red-500"></div>
        <div class="h-full w-8 bg-transparent"></div>
        <div class="h-full w-8 bg-red-500"></div>
        <div class="h-full w-8 bg-transparent"></div>
        <div class="h-full w-8 bg-red-500"></div>
        <div class="h-full w-8 bg-transparent"></div>
    </div>
    <div class="absolute bottom-0 left-0 w-full h-4 bg-black flex">
        <div class="h-full w-8 bg-red-500"></div>
        <div class="h-full w-8 bg-transparent"></div>
        <div class="h-full w-8 bg-red-500"></div>
        <div class="h-full w-8 bg-transparent"></div>
        <div class="h-full w-8 bg-red-500"></div>
        <div class="h-full w-8 bg-transparent"></div>
        <div class="h-full w-8 bg-red-500"></div>
        <div class="h-full w-8 bg-transparent"></div>
        <div class="h-full w-8 bg-red-500"></div>
        <div class="h-full w-8 bg-transparent"></div>
    </div>

    <!-- Main Content Wrapper for hiding/showing -->
    <div id="mainContentWrapper" class="container max-w-md w-full transition-opacity duration-500">
        <main id="loginCard" class="login-container rounded-2xl p-8 relative overflow-hidden">
            <!-- Decorative Corner Elements -->
            <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-red-500"></div>
            <div class="absolute top-0 right-0 w-6 h-6 border-t-2 border-r-2 border-red-500"></div>
            <div class="absolute bottom-0 left-0 w-6 h-6 border-b-2 border-l-2 border-red-500"></div>
            <div class="absolute bottom-0 right-0 w-6 h-6 border-b-2 border-r-2 border-red-500"></div>
            
            <section id="login-page" class="space-y-6 relative z-10">
                <div class="text-center">
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center pulse">
                            <i class="fas fa-film text-white text-2xl"></i>
                        </div>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-2 glow">MovieLab <span class="text-red-500">Admin</span></h1>
                    <p class="text-gray-400 text-sm">Enter your credentials to access the dashboard</p>
                </div>

                <!-- Form submission changed to JavaScript handleLogin function -->
                <form id="loginForm" class="space-y-5" onsubmit="handleLogin(event)">
                    <div class="space-y-2">
                        <label for="username" class="block text-sm font-medium text-gray-300">
                            <i class="fas fa-user mr-2 text-red-500"></i>Username or Email
                        </label>
                        <div class="relative">
                            <input type="text" id="username" name="username" required
                                class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-gray-500 focus:outline-none"
                                placeholder="Enter your username or email">
                            <div class="absolute right-3 top-3 text-gray-500">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-gray-300">
                            <i class="fas fa-lock mr-2 text-red-500"></i>Password
                        </label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required
                                class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-gray-500 focus:outline-none"
                                placeholder="Enter your password">
                            <div class="absolute right-3 top-3 text-gray-500">
                                <i class="fas fa-key"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-2">
                        <!-- 'login' name is included here for backend consistency, although we simplified the PHP check -->
                        <button type="submit" id="loginButton" name="login"
                            class="login-btn w-full py-3 px-4 rounded-lg text-white font-bold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out relative overflow-hidden transform hover:scale-[1.01] active:scale-[0.99]"
                            aria-label="Access Dashboard">
                            
                            <span id="buttonText" class="relative z-10"><i class="fas fa-sign-in-alt mr-2"></i>Access Dashboard</span>
                            <!-- Placeholder for temporary message (used during verification check) -->
                            <div id="messageBox" class="message-box-inner absolute inset-0 flex items-center justify-center opacity-0 transition-opacity duration-300 pointer-events-none"></div>
                        </button>
                    </div>
                </form>
                
                <!-- This is where AJAX errors will be displayed -->
                <div id="statusMessage" class="mt-6 p-3 text-sm rounded-lg text-center opacity-0 transition-opacity duration-500" role="alert"></div>

                <div class="text-center pt-4 border-t border-gray-800">
                    <p class="text-xs text-gray-500">
                        <i class="fas fa-shield-alt mr-1 text-red-500"></i>Secure Admin Access
                    </p>
                    <p class="text-xs text-gray-600 mt-2">
                        Default: admin / password
                    </p>
                </div>
            </section>
        </main>
        
        <div class="text-center mt-6">
            <p class="text-gray-600 text-sm">© 2025 MovieLab. All rights reserved.</p>
        </div>
    </div>
    
    <!-- Full Screen Loading Overlay - Professional Data Matrix Look (FROM VGG.HTML) -->
    <div id="loadingOverlay" class="fixed inset-0 bg-gray-900 flex flex-col items-center justify-center z-[100] transition-opacity duration-700 opacity-0 pointer-events-none">
        <!-- Glitch/Matrix Loader -->
        <div class="glitch-loader">
            <div class="data-core"></div>
            <span class="glitch-text">DATA</span>
        </div>
        <!-- Loading Text -->
        <div class="text-red-600 text-3xl font-extrabold tracking-widest relative z-10 mt-16 animate-pulse">
            LOADING ADMIN PANEL DATA CORE
        </div>
        <p class="text-gray-400 mt-4 relative z-10">Secure data transfer in progress...</p>
    </div>

    <script>
        // --- GLOBAL VARIABLES & CONSTANTS ---
        const loginButton = document.getElementById('loginButton');
        const loginForm = document.getElementById('loginForm');
        const statusMessage = document.getElementById('statusMessage');
        const messageBox = document.getElementById('messageBox');
        const buttonText = document.getElementById('buttonText');
        const mainContentWrapper = document.getElementById('mainContentWrapper');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const numParticles = 30; // Number of particles (FROM VGG.HTML)
        const loadingAnimationDuration = 1000; // Duration to wait for the spinning effect

        /**
         * Red particle burst animation (Button click confirmation effect - FROM VGG.HTML)
         */
        function createParticleBurst(element) {
            const rect = element.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;

            for (let i = 0; i < numParticles; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = `${centerX}px`;
                particle.style.top = `${centerY}px`;
                document.body.appendChild(particle);

                const angle = Math.random() * 2 * Math.PI; 
                const distance = Math.random() * 100 + 50; 
                const endX = centerX + distance * Math.cos(angle);
                const endY = centerY + distance * Math.sin(angle);

                setTimeout(() => {
                    particle.style.transform = `translate(${endX - centerX}px, ${endY - centerY}px)`;
                    particle.style.opacity = '0';
                    particle.style.width = '2px';
                    particle.style.height = '2px';
                }, 10);

                setTimeout(() => {
                    particle.remove();
                }, 850); 
            }
        }

        /**
         * Starts the next page load animation (The Data Matrix Effect - FROM VGG.HTML)
         */
        function startDataMatrixEffect() {
            // 1. Hide the Login Card
            mainContentWrapper.classList.add('opacity-0');

            setTimeout(() => {
                // 2. Show the Loading Overlay
                loadingOverlay.classList.remove('pointer-events-none', 'opacity-0');
            }, 500); // Wait for the login card to start fading out
        }
        
        /**
         * Stops the loading effect and resets the form (FROM VGG.HTML)
         */
        function stopDataMatrixEffect(error_message) {
            // 1. Hide the Loading Overlay (if visible)
            loadingOverlay.classList.add('opacity-0');
            
            setTimeout(() => {
                loadingOverlay.classList.add('pointer-events-none');
                
                // 2. Show the Login Form again
                mainContentWrapper.classList.remove('opacity-0');
                
                // 3. Reset button and message
                loginButton.disabled = false;
                // Re-enable hover effects
                loginButton.classList.add('transform', 'hover:scale-[1.01]');
                messageBox.classList.remove('opacity-100');
                buttonText.classList.remove('opacity-0');

                // 4. Show the error message
                showStatusMessage(error_message, 'bg-red-900/50', 'text-red-300');
                
            }, 700); // Overlay Opacity Transition time


            // Reset messageBox after a short delay
            setTimeout(() => {
                messageBox.innerHTML = '';
            }, 300);
        }


        /**
         * Handles the AJAX login submission (Corrected to use JSON response and effects)
         */
        async function handleLogin(event) {
            event.preventDefault(); 
            
            // 1. Prepare UI for validation check & Start effects
            loginButton.disabled = true;
            loginButton.classList.remove('transform', 'hover:scale-[1.01]'); // Disable hover/scale animation while disabled
            createParticleBurst(loginButton); // Particle Burst Effect
            
            messageBox.innerHTML = '<span class="text-white text-lg animate-pulse">Verifying access...</span>';
            messageBox.classList.add('opacity-100');
            buttonText.classList.add('opacity-0');

            const formData = new FormData(loginForm);
formData.append('login', '1');
            try {
                // 2. Send data to the backend
                // CORRECTED PATH: Changed from '../library/login-Backend.php' to './library/login-Backend.php'
                // This assumes loging.php and a 'library' folder (containing login-Backend.php) 
                // are in the same parent directory.
                const response = await fetch('../library/login-Backend.php', { 
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.status === 'success') {
                    // --- SUCCESS PATH: RUN ANIMATION AND REDIRECT ---
                    
                    // 3. Start the professional loading effect
                    startDataMatrixEffect();
                    
                    // 4. Wait for the animation duration, then redirect
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, loadingAnimationDuration + 1000); // 1s effect + 1s to ensure fade-out is complete

                } else {
                    // --- ERROR PATH: STOP ANIMATION AND SHOW ERROR ---
                    stopDataMatrixEffect(data.message || 'Login failed. Please try again.');
                }
            } catch (error) {
                console.error('Login Error (Network or Server):', error.message);
                // Modified error message for cleaner display
                stopDataMatrixEffect('A network or server error occurred. Check console for details.');
            }
        }

        /**
         * Shows a status message in the UI
         */
        function showStatusMessage(message, bgColor, textColor) {
            statusMessage.textContent = message;
            statusMessage.className = `mt-6 p-3 text-sm rounded-lg text-center ${bgColor} ${textColor} opacity-0 transition-opacity duration-500`;
            
            // Fade in
            setTimeout(() => {
                statusMessage.classList.add('opacity-100');
            }, 10);

            // Fade out the status message after 4 seconds
            setTimeout(() => {
                statusMessage.classList.remove('opacity-100');
            }, 4000);
        }

        // --- INITIAL SETUP ---
        window.onload = function () {
            // Clear URL error parameters on load
            if (window.history.replaceState) {
                const url = new URL(window.location.href);
                url.searchParams.delete('error');
                window.history.replaceState({ path: url.href }, '', url.href);
            }
            
            // Add input field animations (original logic)
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-red-500', 'ring-opacity-50');
                    this.parentElement.classList.remove('ring-0');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-red-500', 'ring-opacity-50');
                });
            });
        };
    </script>
</body>

</html>