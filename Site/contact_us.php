<?php 
include("../include/header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Contact Us - Movie Lab</title>
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
            background: rgba(20, 20, 20, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
        }

        .input-field {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #ffffff !important;
            padding: 12px 15px;
            border-radius: 12px;
            width: 100%;
            font-size: 16px; 
            transition: all 0.3s ease;
        }

        /* Radio Button Styling */
        .radio-group { display: flex; gap: 10px; flex-wrap: wrap; }
        .radio-card {
            flex: 1;
            min-width: 100px;
            cursor: pointer;
            text-align: center;
            padding: 12px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.03);
            transition: 0.3s;
        }
        .radio-card input { display: none; }
        .radio-card:has(input:checked) {
            border-color: #E50914;
            background: rgba(229, 9, 20, 0.15);
        }

        /* Iframe Container */
        .map-wrapper {
            width: 100%;
            height: 350px; 
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .btn-submit {
            background: linear-gradient(135deg, #E50914 0%, #b20710 100%);
            color: white; font-weight: 700; padding: 15px;
            border-radius: 15px; width: 100%;
            transition: 0.3s;
        }
        .btn-submit:hover { opacity: 0.9; transform: translateY(-2px); }

        #loader {
            position: fixed; inset: 0; background: #050505; z-index: 999;
            display: flex; justify-content: center; align-items: center;
        }
    </style>
</head>
<body class="pt-16 md:pt-24">

    <div id="loader">
        <div class="text-red-600 font-bold animate-pulse uppercase text-xs tracking-widest">Movie Lab Support</div>
    </div>

    <section class="py-10 px-4 max-w-7xl mx-auto">
        <div class="text-center mb-10">
            <h1 class="text-4xl md:text-5xl font-black mb-2 uppercase ">Get in <span class="text-gradient-red">Touch</span></h1>
            <p class="text-gray-400 text-sm">We are here to help you 24/7. Reach out to us anytime.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            
            <div class="space-y-4">
                <div class="premium-card p-4 flex items-center gap-4 border-l-4 border-l-red-600">
                    <div class="w-10 h-10 bg-red-600/20 rounded-xl flex items-center justify-center text-red-600">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Our Location</p>
                        <p class="text-sm font-bold">No. 123, Movie Street, Colombo, Sri Lanka</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="premium-card p-4 flex items-center gap-4">
                        <div class="w-10 h-10 bg-red-600/20 rounded-xl flex items-center justify-center text-red-600">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-500 uppercase font-bold">Call Us</p>
                            <p class="text-sm font-bold">+94 77 123 4567</p>
                        </div>
                    </div>
                    <div class="premium-card p-4 flex items-center gap-4">
                        <div class="w-10 h-10 bg-red-600/20 rounded-xl flex items-center justify-center text-red-600">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-500 uppercase font-bold">Email Us</p>
                            <p class="text-sm font-bold">info@movielab.lk</p>
                        </div>
                    </div>
                </div>

                <div class="map-wrapper">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126743.5827211835!2d79.786164295943!3d6.921833543292429!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae253d10f7a70ad%3A0x2db351b3f194454!2sColombo!5e0!3m2!1sen!2slk!4v1700000000000" 
                        width="100%" height="100%" style="border:0; filter: grayscale(1) invert(0.9) contrast(1.2);" allowfullscreen="" loading="lazy">
                    </iframe>
                </div>
            </div>

            <div class="premium-card p-6 md:p-8">
                <form action="../library/contact_process.php" method="POST" class="space-y-5">
                    
                    <?php if(isset($_GET['status'])): ?>
                        <div class="p-3 text-sm rounded-lg <?php echo $_GET['status'] == 'success' ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500'; ?> ">
                            <?php echo $_GET['status'] == 'success' ? '<i class="fas fa-check-circle mr-2"></i> Message sent successfully!' : '<i class="fas fa-exclamation-circle mr-2"></i> Error occurred! Please try again.'; ?>
                        </div>
                    <?php endif; ?>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-500 text-[10px] font-bold uppercase mb-1">Full Name</label>
                            <input type="text" name="user_name" class="input-field" placeholder="John Doe" required>
                        </div>
                        <div>
                            <label class="block text-gray-500 text-[10px] font-bold uppercase mb-1">Email Address</label>
                            <input type="email" name="user_email" class="input-field" placeholder="john@example.com" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-400 text-[10px] font-bold uppercase mb-2">How can we help?</label>
                        <div class="radio-group">
                            <label class="radio-card">
                                <input type="radio" name="subject" value="Question" checked>
                                <span class="text-xs">Question</span>
                            </label>
                            <label class="radio-card">
                                <input type="radio" name="subject" value="Comment">
                                <span class="text-xs">Comment</span>
                            </label>
                            <label class="radio-card">
                                <input type="radio" name="subject" value="Other">
                                <span class="text-xs">Other</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-500 text-[10px] font-bold uppercase mb-1">Your Message</label>
                        <textarea name="message" class="input-field" rows="5" placeholder="Write your message here..." required></textarea>
                    </div>

                    <button type="submit" class="btn-submit uppercase tracking-widest text-xs">
                        Send Message <i class="fas fa-paper-plane ml-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <script>
        window.onload = () => {
            gsap.to("#loader", { opacity: 0, duration: 0.5, onComplete: () => { document.getElementById('loader').style.display = 'none'; }});
            
            // Fade in animation for cards
            gsap.from(".premium-card", {
                y: 20,
                opacity: 0,
                duration: 0.8,
                stagger: 0.1,
                ease: "power2.out"
            });
        };
    </script>
</body>
</html>
<?php include("../include/footer.php"); ?>