<?php
session_start();
// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']) ? true : false;
// Use username from session if logged in
$username = $isLoggedIn ? $_SESSION['username'] : ""; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PJ Furniture Designers | Premium Craftsmanship</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Montserrat:wght@300;400;700&family=Playfair+Display:ital,wght@0,700;0,900;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="style.css">
    <style>
        .sidebar {
            height: 100%; width: 0;
            position: fixed; z-index: 3000;
            top: 0; left: 0;
            background-color: #fff;
            overflow-x: hidden; transition: 0.5s;
            padding-top: 60px; box-shadow: 5px 0 15px rgba(0,0,0,0.1);
        }
        .sidebar-content a {
            padding: 15px 32px; text-decoration: none;
            font-size: 0.9rem; color: #444; display: block;
            text-transform: uppercase; font-weight: 600; letter-spacing: 1px;
            font-family: 'Montserrat', sans-serif;
        }
        .sidebar-content a:hover { color: #B20000; background: #F9F9F9; }
        .closebtn { position: absolute; top: 20px; right: 25px; font-size: 36px; text-decoration: none; color: #000; }
        
        @media (max-width: 992px) {
            .main-nav { display: none; }
        }

        /* Styling for the user greeting in sidebar */
        .user-greeting {
            padding: 0 32px 10px 32px;
            font-size: 0.8rem;
            color: #B20000;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

    <!-- Sidebar Menu -->
    <div id="sidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div class="sidebar-content">
            <?php if($isLoggedIn): ?>
                <div class="user-greeting">Welcome, <?php echo htmlspecialchars($username); ?></div>
            <?php endif; ?>

            <a href="index.php">HOME</a>
            <a href="products.php">PRODUCTS</a>
            <a href="#about" onclick="closeNav()">ABOUT</a>
            <a href="#services" onclick="closeNav()">SERVICES</a>
            <a href="#contact" onclick="closeNav()">CONTACT US</a>
            
            <hr style="margin: 20px 32px; border: 0; border-top: 1px solid #eee;">
            
            <?php if($isLoggedIn): ?>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> LOGOUT</a>
            <?php else: ?>
                <!-- Links directed to your new auth files -->
                <a href="login.php"><i class="fas fa-sign-in-alt"></i> SIGN IN</a>
                <a href="register.php"><i class="fas fa-user-plus"></i> REGISTER</a>
            <?php endif; ?>
        </div>
    </div>

    <header>
        <div class="header-left">
            <div class="menu-toggle" onclick="openNav()">
                <i class="fas fa-bars"></i>
            </div>
            <a href="index.php" class="logo">PJ <span>Furniture Designers</span></a>
        </div>

        <nav class="main-nav">
            <ul class="nav-links">
                <li><a href="index.php">HOME</a></li>
                <li><a href="products.php">PRODUCTS</a></li>
                <li><a href="#about">ABOUT</a></li>
                <li><a href="#contact">CONTACT US</a></li>
                <!-- Login removed from here as requested -->
            </ul>
        </nav>
    </header>

    <section class="hero" id="home">
        <p class="accent-font">Welcome to PJ Furniture Designers</p>
        <h1>Quality Furniture.</h1>
        <a href="https://wa.me/27729794332?text=Hi%20PJ%20Furniture%20Designers,%20I%20would%20like%20to%20enquire%20about%20your%20furniture%20collection." class="cta-btn">Enquire Collection</a>
    </section>

    <section class="about-section" id="about">
        <div class="about-img">
            <img src="https://i.postimg.cc/xdrhNS21/logo.jpg" alt="Furniture Crafting">
        </div>
        <div class="about-content">
            <p class="accent-font">Since 2024</p>
            <h2>About PJ Furniture Designers</h2>
            <p>Based in the heart of Ramotse, we are dedicated artisans committed to redefining your living space. We don't just sell furniture; we design experiences.</p>
            <p>We sell and craft <strong>all kinds of furniture</strong>: Pedestals, Headboards, Chairs, Dining Room Suites, Wardrobes, and custom Kitchens.</p>
        </div>
    </section>

    <section class="services" id="services">
        <p class="accent-font" style="color: white;">Our Solutions</p>
        <h2>Bespoke Services</h2>
        <div class="service-grid">
            <div class="service-card">
                <i class="fas fa-tools"></i>
                <h3>Repairs</h3>
                <p>From structural fixing to aesthetic polishing, we bring furniture back to life.</p>
            </div>
            <div class="service-card">
                <i class="fas fa-truck-fast"></i>
                <h3>Delivery</h3>
                <p>Reliable delivery within Hammanskraal. Contact us for distance-based quotes.</p>
            </div>
            <div class="service-card">
                <i class="fas fa-credit-card"></i>
                <h3>Lay-by</h3>
                <p>Secure the piece you love today and pay it off with interest-free options.</p>
            </div>
        </div>
    </section>

    <section class="work-gallery">
        <div class="gallery-grid">
            <div class="gallery-item"><img src="https://i.postimg.cc/d1wfnmDG/kitchen1.jpg" alt="Kitchen"><div class="gallery-overlay">Kitchens</div></div>
            <div class="gallery-item"><img src="https://i.postimg.cc/XvMmB0bk/wardrobes11.jpg" alt="Wardrobe"><div class="gallery-overlay">Wardrobes</div></div>
            <div class="gallery-item"><img src="https://i.postimg.cc/ZKsCSwhs/chair3.jpg" alt="Chair"><div class="gallery-overlay">Chairs</div></div>
            <div class="gallery-item"><img src="https://i.postimg.cc/vmRkxdsz/modern-designs.jpg" alt="Modern"><div class="gallery-overlay">Modern Designs</div></div>
        </div>
    </section>

    <section class="contact-section" id="contact">
        <div class="contact-links">
            <h2 style="margin-bottom: 30px;">Get In Touch</h2>
            <a href="tel:0729794332"><i class="fas fa-phone"></i> 072 979 4332 || 071 599 3209</a>
            <a href="mailto:Info@pjfurnituredesigners.com"><i class="fas fa-envelope"></i> Info@pjfurnituredesigners.com</a>
            <a href="https://www.google.com/maps/search/?api=1&query=PJ+Furniture+Designers+Ramotse+Hammanskraal" target="_blank">
                <i class="fas fa-location-dot"></i> 6077 R101, Ramotse, Hammanskraal
            </a>
        </div>
        <div class="hours-card">
            <h3 style="margin-bottom: 25px;">Business Hours</h3>
            <div class="hour-item"><span>Mon - Fri</span> <span>09:00 - 17:00</span></div>
            <div class="hour-item"><span>Saturday</span> <span>09:00 - 16:00</span></div>
            <div class="hour-item" style="border:none;"><span>Sunday</span> <span>CLOSED</span></div>
        </div>
    </section>

    <footer>
        <p>© 2026 PJ Furniture Designers | Created by Thabelo Mahlangu</p>
    </footer>

    <a href="https://wa.me/27729794332?text=Hi%20PJ%20Furniture%20Designers,%20I%20have%20a%20question." class="wa-float" target="_blank"><i class="fab fa-whatsapp"></i></a>

    <script>
        function openNav() {
            document.getElementById("sidebar").style.width = "300px";
        }

        function closeNav() {
            document.getElementById("sidebar").style.width = "0";
        }

        document.addEventListener('click', function(event) {
            var sidebar = document.getElementById('sidebar');
            var toggle = document.querySelector('.menu-toggle');
            if (sidebar.style.width === "300px" && !sidebar.contains(event.target) && !toggle.contains(event.target)) {
                closeNav();
            }
        });
    </script>
</body>
</html>