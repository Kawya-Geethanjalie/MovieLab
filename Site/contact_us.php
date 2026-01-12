<?php 
include("../include/header.php");
// Database connection එක අවශ්‍ය නම් මෙතනට include කරන්න
// include("../include/config.php"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            background: rgba(20, 20, 20, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 30px;
        }

        .input-field {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            padding: 15px 20px;
            border-radius: 15px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .input-field:focus {
            outline: none;
            border-color: #E50914;
            background: rgba(255, 255, 255, 0.05);
            box-shadow: 0 0 15px rgba(229, 9, 20, 0.2);
        }

        .btn-submit {
            background: linear-gradient(135deg, #E50914 0%, #b20710 100%);
            color: white;
            font-weight: 700;
            padding: 15px 35px;
            border-radius: 15px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
 /* Custom styling for Select Dropdown */
        select.input-field {
            appearance: none;
            cursor: pointer;
        }

        /* Updated option styling: Dark background and Gray text */
        select.input-field option {
            background-color: #0a0a0a;
            color: #9ca3af; /* Tailwind gray-400 */
        }

        select.input-field option:checked {
            color: #fdf8f8;
            background-color: #1a1a1a;
        }
        .btn-submit:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(229, 9, 20, 0.4);
        }

        .map-container {
            border-radius: 30px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.05);
            height: 100%;
            min-height: 400px;
        }

        .glow-sphere {
            position: absolute;
            width: 400px;
            height: 400px;
            background: #E50914;
            filter: blur(150px);
            opacity: 0.1;
            z-index: -1;
            border-radius: 50%;
        }

        #loader {
            position: fixed; inset: 0; background: #050505; z-index: 999;
            display: flex; justify-content: center; align-items: center;
        }
    </style>
</head>
<body class="pt-20">

    <div id="loader">
        <div class="text-red-600 font-bold animate-pulse tracking-widest uppercase text-xs">Movie Lab Support</div>
    </div>

    <div class="glow-sphere top-0 right-0"></div>
    <div class="glow-sphere bottom-0 left-0"></div>

    <section class="py-16 px-6 max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-black mb-4">Get in <span class="text-gradient-red">Touch</span></h1>
            <p class="text-gray-400 max-w-2xl mx-auto">Have questions or need assistance? Our team is here to help you 24/7 with any inquiries about our services.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            
            <!-- Contact Form -->
             
            <form action="../library/contact_process.php" method="POST" class="space-y-6">
    <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <div class="p-4 mb-4 text-sm text-green-500 bg-green-100/10 rounded-lg">
            Message sent successfully!
        </div>
    <?php elseif(isset($_GET['status']) && $_GET['status'] == 'error'): ?>
        <div class="p-4 mb-4 text-sm text-red-500 bg-red-100/10 rounded-lg">
            Something went wrong. Please try again.
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-gray-500 text-xs font-bold">Your Name</label>
            <input type="text" name="user_name" class="input-field" placeholder="John Doe" required>
        </div>
        <div>
            <label class="block text-gray-500 text-xs font-bold">Email Address</label>
            <input type="email" name="user_email" class="input-field" placeholder="john@example.com" required>
        </div>
    </div>
    
    <div class="relative">
        <label class="block text-gray-500 text-xs font-bold">Subject</label>
        <select name="subject" class="input-field" required>
            <option value="" disabled selected>Choose a category...</option>
            <option value="Question">As a Question</option>
            <option value="Comment">As a Comment</option>
            <option value="Other">Other</option>
        </select>
    </div>

    <div>
        <label class="block text-gray-500 text-xs font-bold">Message</label>
        <textarea name="message" class="input-field" rows="5" required></textarea>
    </div>

    <button type="submit" class="btn-submit w-full justify-center">
        Send Message <i class="fas fa-paper-plane"></i>
    </button>
</form>
            <!-- Contact Info & Map -->
            <div class="flex flex-col gap-6">
                <!-- Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="premium-card p-6 flex items-center gap-5">
                        <div class="w-12 h-12 bg-red-600/10 rounded-xl flex items-center justify-center text-red-600">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold tracking-widest">Call Us</p>
                            <p class="font-bold">+94 77 123 4567</p>
                        </div>
                    </div>
                    <div class="premium-card p-6 flex items-center gap-5">
                        <div class="w-12 h-12 bg-red-600/10 rounded-xl flex items-center justify-center text-red-600">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold tracking-widest">Email Us</p>
                            <p class="font-bold">support@movielab.com</p>
                        </div>
                    </div>
                    <div class="premium-card p-6 flex items-center gap-5 md:col-span-2">
                        <div class="w-12 h-12 bg-red-600/10 rounded-xl flex items-center justify-center text-red-600">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold tracking-widest">Location</p>
                            <p class="font-bold">No. 123, Movie Street, Colombo, Sri Lanka</p>
                        </div>
                    </div>
                </div>

                <!-- Map -->
                <div class="map-container flex-1">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126743.58585987134!2d79.786164!3d6.9218374!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae253d10f7a70ad%3A0x3964c4004907ee83!2sColombo!5e0!3m2!1sen!2slk!4v1700000000000!5m2!1sen!2slk" 
                        width="100%" 
                        height="100%" 
                        style="border:0; filter: grayscale(1) invert(0.92) contrast(0.85);" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

        </div>
    </section>

    <!-- Social Connect -->
    

    <script>
        window.onload = () => {
            gsap.to("#loader", { 
                opacity: 0, 
                duration: 0.8, 
                onComplete: () => {
                    document.getElementById('loader').style.display = 'none';
                    gsap.from(".premium-card", {
                        y: 50,
                        opacity: 0,
                        duration: 1,
                        stagger: 0.2,
                        ease: "power3.out"
                    });
                }
            });
        };
    </script>
</body>
</html>
<?php include("../include/footer.php"); ?>