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
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-4">
    <!-- Background Elements -->
    <div class="absolute top-10 left-10 w-20 h-20 rounded-full bg-red-500/10 blur-xl floating"></div>
    <div class="absolute bottom-10 right-10 w-32 h-32 rounded-full bg-red-500/5 blur-xl floating" style="animation-delay: 1.5s;"></div>
    <div class="absolute top-1/2 left-1/4 w-16 h-16 rounded-full bg-red-500/10 blur-lg floating" style="animation-delay: 2.5s;"></div>
    
    <!-- Film Strip Effect -->
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

    <div class="container max-w-md w-full">
        <main class="login-container rounded-2xl p-8 relative overflow-hidden">
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

                <form class="space-y-5" action="../library/login-backend.php" method="post">
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
                        <button type="submit" id="login-button" name="login"
                            class="login-btn w-full py-3 px-4 rounded-lg text-white font-bold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                            <i class="fas fa-sign-in-alt mr-2"></i>Access Dashboard
                        </button>
                    </div>
                </form>
                
                <?php
                if (isset($_GET['error'])) {
                    $error_message = match ($_GET['error']) {
                        'User_Name' => 'Username is required!',
                        'Password' => 'Password is required!',
                        'account_error' => 'Admin access only!',
                        'login_error' => 'Invalid credentials!',
                        default => 'An error occurred!',
                    };
                } else {
                    $error_message = null;
                }
                ?>
                
                <?php if (!empty($error_message)) { ?>
                    <div class="mt-4 error-alert rounded-lg px-4 py-3 relative">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-300"><?php echo htmlspecialchars($error_message); ?></p>
                            </div>
                            <button type="button" onclick="this.parentElement.parentElement.style.display='none';"
                                class="ml-auto flex-shrink-0 rounded-md text-red-400 hover:text-red-300 focus:outline-none">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                <?php } ?>
                
                <div class="text-center pt-4 border-t border-gray-800">
                    <p class="text-xs text-gray-500">
                        <i class="fas fa-shield-alt mr-1 text-red-500"></i>Secure Admin Access
                    </p>
                </div>
            </section>
        </main>
        
        <div class="text-center mt-6">
            <p class="text-gray-600 text-sm">© 202 MovieLab. All rights reserved.</p>
        </div>
    </div>

    <script>
        window.onload = function () {
            if (window.history.replaceState) {
                const url = new URL(window.location.href);
                url.searchParams.delete('error');
                window.history.replaceState({ path: url.href }, '', url.href);
            }
            
            // Add input field animations
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