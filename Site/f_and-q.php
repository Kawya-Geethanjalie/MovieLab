<?php 
include("../include/header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Movie Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    
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

        .text-gradient-red {
            background: linear-gradient(135deg, #E50914 0%, #ff4d4d 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .premium-card {
            background: rgba(20, 20, 20, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .faq-item {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .faq-item:last-child {
            border-bottom: none;
        }

        .faq-question {
            cursor: pointer;
            padding: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.3s;
        }

        .faq-question:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s cubic-bezier(0, 1, 0, 1);
            background: rgba(0, 0, 0, 0.2);
        }

        .faq-item.active .faq-answer {
            max-height: 1000px;
            transition: max-height 1s ease-in-out;
        }

        .faq-item.active .icon-plus {
            transform: rotate(45deg);
            color: #E50914;
        }

        .icon-plus {
            transition: all 0.3s ease;
        }

        #loader {
            position: fixed; inset: 0; background: #050505; z-index: 999;
            display: flex; justify-content: center; align-items: center;
        }

        .glow-sphere {
            position: absolute;
            width: 400px; height: 400px;
            background: #E50914;
            filter: blur(150px);
            opacity: 0.1;
            z-index: -1;
            border-radius: 50%;
        }
    </style>
</head>
<body class="pt-24 pb-20">

    <div id="loader">
        <div class="text-red-600 font-bold animate-pulse tracking-widest uppercase text-xs">Movie Lab FAQ</div>
    </div>

    <div class="glow-sphere top-20 left-10"></div>
    <div class="glow-sphere bottom-20 right-10"></div>

    <section class="max-w-4xl mx-auto px-6">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-black m-4">F<span class="text-gradient-red">AQ</span></h1>
            <p class="text-gray-400">Everything you need to know about Movie Lab and how we work.</p>
        </div>

        <div class="premium-card overflow-hidden">
            <!-- FAQ Item 1 -->
            <div class="faq-item">
                <div class="faq-question">
                    <span class="text-lg font-semibold">How do I start watching movies?</span>
                    <i class="fas fa-plus icon-plus text-gray-500"></i>
                </div>
                <div class="faq-answer">
                    <div class="p-6 pt-0 text-gray-400 leading-relaxed">
                        To start watching, you simply need to create an account, browse our extensive collection, and click on any title you'd like to view. Some premium content may require a subscription.
                    </div>
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="faq-item">
                <div class="faq-question">
                    <span class="text-lg font-semibold">Is there a free trial available?</span>
                    <i class="fas fa-plus icon-plus text-gray-500"></i>
                </div>
                <div class="faq-answer">
                    <div class="p-6 pt-0 text-gray-400 leading-relaxed">
                        Yes! New members get a 7-day free trial of our Premium plan. You can cancel anytime before the trial ends and you won't be charged.
                    </div>
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="faq-item">
                <div class="faq-question">
                    <span class="text-lg font-semibold">Can I download movies to watch offline?</span>
                    <i class="fas fa-plus icon-plus text-gray-500"></i>
                </div>
                <div class="faq-answer">
                    <div class="p-6 pt-0 text-gray-400 leading-relaxed">
                        Currently, our platform supports high-speed streaming. Offline viewing is a feature we are actively working on for our mobile app users in the upcoming update.
                    </div>
                </div>
            </div>

            <!-- FAQ Item 4 -->
            <div class="faq-item">
                <div class="faq-question">
                    <span class="text-lg font-semibold">Which devices are supported?</span>
                    <i class="fas fa-plus icon-plus text-gray-500"></i>
                </div>
                <div class="faq-answer">
                    <div class="p-6 pt-0 text-gray-400 leading-relaxed">
                        Movie Lab is accessible on almost any device with a web browser, including Smartphones (Android/iOS), Tablets, Laptops, Desktops, and Smart TVs.
                    </div>
                </div>
            </div>

            <!-- FAQ Item 5 -->
            <div class="faq-item">
                <div class="faq-question">
                    <span class="text-lg font-semibold">How do I reset my password?</span>
                    <i class="fas fa-plus icon-plus text-gray-500"></i>
                </div>
                <div class="faq-answer">
                    <div class="p-6 pt-0 text-gray-400 leading-relaxed">
                        You can reset your password by clicking on the "Forgot Password" link on the login page. We'll send a reset link to your registered email address.
                    </div>
                </div>
            </div>
        </div>

        <!-- Still have questions? -->
        <div class="mt-12 text-center">
            <div class="premium-card p-8 inline-block w-full">
                <p class="text-gray-400 mb-4">Still have more questions?</p>
                <a href="../Site/contact_us.php" class="inline-flex items-center gap-2 bg-white text-black px-6 py-3 rounded-full font-bold hover:bg-red-600 hover:text-white transition-all">
                    Contact Our Support <i class="fas fa-arrow-right text-sm"></i>
                </a>
            </div>
        </div>
    </section>

    <script>
        // FAQ Accordion Logic
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const item = question.parentElement;
                
                // Close other items
                document.querySelectorAll('.faq-item').forEach(otherItem => {
                    if (otherItem !== item) {
                        otherItem.classList.remove('active');
                    }
                });

                // Toggle current item
                item.classList.toggle('active');
            });
        });

        window.onload = () => {
            gsap.to("#loader", { 
                opacity: 0, 
                duration: 0.8, 
                onComplete: () => {
                    document.getElementById('loader').style.display = 'none';
                    gsap.from(".premium-card", {
                        y: 30,
                        opacity: 0,
                        duration: 1,
                        ease: "power3.out"
                    });
                }
            });
        };
    </script>
</body>
</html>
<?php include("../include/footer.php"); ?>