<?php
session_start();
// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']) ? true : false;
// Use username from session if logged in, otherwise default to "Guest"
$username = $isLoggedIn ? $_SESSION['username'] : "Guest";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collections | PJ Furniture Designers</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Montserrat:wght@300;400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --brand-red: #B20000;
            --deep-black: #0A0A0A;
            --pure-white: #FFFFFF;
            --soft-gray: #F9F9F9;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Montserrat', sans-serif; color: var(--deep-black); background: var(--pure-white); padding-top: 90px; }

        /* --- Header --- */
        header {
            background: #fff;
            padding: 0 5%;
            height: 90px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 2000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .header-left { display: flex; align-items: center; gap: 20px; }
        .menu-toggle { font-size: 1.4rem; cursor: pointer; color: var(--deep-black); }
        
        .logo { font-size: 1.1rem; font-weight: 800; text-transform: uppercase; border-left: 4px solid var(--brand-red); padding-left: 12px; text-decoration: none; color: black; letter-spacing: 1px; }
        .logo span { color: var(--brand-red); }

        /* --- Search Bar --- */
        .search-container { flex: 0 1 400px; margin: 0 20px; }
        .search-container form {
            display: flex;
            align-items: center;
            background: var(--soft-gray);
            padding: 5px 20px;
            border-radius: 50px;
            border: 1.5px solid var(--brand-red);
            transition: all 0.3s ease;
        }
        .search-container i { color: var(--brand-red); margin-right: 12px; }
        .search-container input { border: none; background: none; outline: none; padding: 10px 0; width: 100%; font-family: 'Montserrat'; font-size: 0.9rem; }
        .search-container button { background: none; border: none; font-weight: 700; text-transform: uppercase; font-size: 0.75rem; cursor: pointer; color: var(--brand-red); padding-left: 10px; }

        /* --- Icons --- */
        .nav-icons { display: flex; align-items: center; gap: 25px; }
        .nav-icons a { color: var(--deep-black) !important; text-decoration: none; font-size: 1.3rem; position: relative; }
        
        .cart-icon { position: relative; }
        .cart-count {
            position: absolute; top: -10px; right: -12px;
            background: var(--brand-red); color: white;
            font-size: 0.65rem; width: 20px; height: 20px;
            border-radius: 50%; display: flex; align-items: center;
            justify-content: center; font-weight: 700;
        }

        /* --- Sidebar --- */
        .sidebar {
            height: 100%; width: 0; position: fixed; z-index: 3000;
            top: 0; left: 0; background-color: #fff;
            overflow-x: hidden; transition: 0.5s;
            padding-top: 60px; box-shadow: 5px 0 15px rgba(0,0,0,0.1);
        }
        .sidebar-content a {
            padding: 15px 32px; text-decoration: none; font-size: 0.9rem;
            color: #444; display: block; transition: 0.3s;
            text-transform: uppercase; font-weight: 600; letter-spacing: 1px;
        }
        .sidebar-content a:hover { color: var(--brand-red); background: var(--soft-gray); }
        .closebtn { position: absolute; top: 20px; right: 25px; font-size: 36px; text-decoration: none; color: #000; }
        .user-greeting { padding: 0 32px 10px 32px; font-size: 0.8rem; color: var(--brand-red); font-weight: bold; text-transform: uppercase; }

        /* --- Category Nav --- */
        .category-nav { background: #fff; border-bottom: 1px solid #eee; padding: 15px 5%; overflow-x: auto; }
        .category-nav ul { list-style: none; display: flex; gap: 25px; justify-content: center; min-width: max-content; }
        .category-nav a { text-decoration: none; color: #666; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
        .category-nav a.active { color: var(--brand-red); }

        /* --- Grid --- */
        .products-container { padding: 40px 5%; max-width: 1400px; margin: 0 auto; }
        .collection-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 25px; }
        .collection-card { position: relative; height: 450px; overflow: hidden; display: block; background: #000; text-decoration: none; }
        .collection-card img { width: 100%; height: 100%; object-fit: cover; opacity: 0.8; transition: 1s; }
        .collection-card:hover img { transform: scale(1.05); opacity: 0.6; }
        .collection-overlay { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; color: white; text-align: center; }
        .collection-overlay h2 { font-family: 'Playfair Display'; font-size: 1.8rem; text-transform: uppercase; }

        footer { padding: 50px 8%; background: #0A0A0A; color: white; text-align: center; margin-top: 50px; }

        @media (max-width: 768px) { .search-container { display: none; } }
    </style>
</head>
<body>

    <!-- Sidebar Menu -->
    <div id="sidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" id="closeSidebar">&times;</a>
        <div class="sidebar-content">
            <?php if($isLoggedIn): ?>
                <div class="user-greeting">Welcome, <?php echo htmlspecialchars($username); ?></div>
            <?php endif; ?>

            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <a href="products.php" class="active"><i class="fas fa-couch"></i> Products</a>
            <a href="index.php#about"><i class="fas fa-info-circle"></i> About</a>
            <a href="index.php#contact"><i class="fas fa-envelope"></i> Contact Us</a>
            
            <hr style="margin: 20px 32px; border: 0; border-top: 1px solid #eee;">
            
            <a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a>

            <?php if($isLoggedIn): ?>
                <!-- Logout link for logged in users -->
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            <?php else: ?>
                <!-- Sign In and Register links for guests -->
                <a href="login.php"><i class="fas fa-sign-in-alt"></i> Sign In</a>
                <a href="register.php"><i class="fas fa-user-plus"></i> Register</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Header -->
    <header>
        <div class="header-left">
            <i class="fas fa-bars menu-toggle" id="openSidebar"></i>
            <a href="index.php" class="logo">PJ <span>FURNITURE DESIGNERS</span></a>
        </div>

        <div class="search-container">
            <form action="search.php" method="GET">
                <i class="fas fa-search"></i>
                <input type="text" name="query" placeholder="Search furniture...">
                <button type="submit">Search</button>
            </form>
        </div>

        <div class="nav-icons">
            <a href="https://www.google.com/maps/search/?api=1&query=6077+R101+Ramotse+Hammanskraal" target="_blank" title="Visit Us">
                <i class="fas fa-location-dot"></i>
            </a>

            <?php if($isLoggedIn): ?>
                <!-- Link to Logout if signed in -->
                <a href="logout.php" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
            <?php else: ?>
                <!-- Link to Login if signed out -->
                <a href="login.php" title="Sign In"><i class="fas fa-user"></i></a>
            <?php endif; ?>

            <a href="cart.php" class="cart-icon" title="View Cart">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count">
                    <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : '0'; ?>
                </span>
            </a>
        </div>
    </header>

    <!-- Category Nav -->
    <nav class="category-nav">
        <ul>
            <li><a href="products.php" class="active">All</a></li>
            <li><a href="collection_viewer.php?cat=pedestals">Pedestals</a></li>
            <li><a href="collection_viewer.php?cat=headboards">Headboards</a></li>
            <li><a href="collection_viewer.php?cat=chairs">Chairs</a></li>
            <li><a href="collection_viewer.php?cat=couches">Couches</a></li>
            <li><a href="collection_viewer.php?cat=tables">Tables</a></li>
            <li><a href="collection_viewer.php?cat=mattress">Mattress</a></li>
            <li><a href="collection_viewer.php?cat=drawers">Chest of Drawers</a></li>
            <li><a href="collection_viewer.php?cat=dining">Dining Room Suite</a></li>
            <li><a href="collection_viewer.php?cat=kitchen">Kitchen Unit</a></li>
            <li><a href="collection_viewer.php?cat=wardrobes">Wardrobes</a></li>
        </ul>
    </nav>

    <main class="products-container">
        <div class="collection-grid">
            <a href="collection_viewer.php?cat=pedestals" class="collection-card">
                <img src="images/pedestal modern designs.jpeg" alt="Pedestals">
                <div class="collection-overlay"><h2>Pedestals <i class="fas fa-arrow-right"></i></h2></div>
            </a>

            <a href="collection_viewer.php?cat=couches" class="collection-card">
                <img src="images/couch1.jpeg" alt="Couches">
                <div class="collection-overlay"><h2>Couches <i class="fas fa-arrow-right"></i></h2></div>
            </a>

            <a href="collection_viewer.php?cat=kitchen" class="collection-card">
                <img src="https://i.postimg.cc/d1wfnmDG/kitchen1.jpg" alt="Kitchen">
                <div class="collection-overlay"><h2>Kitchen Unit <i class="fas fa-arrow-right"></i></h2></div>
            </a>

            <a href="collection_viewer.php?cat=wardrobes" class="collection-card">
                <img src="https://i.postimg.cc/XvMmB0bk/wardrobes11.jpg" alt="Wardrobes">
                <div class="collection-overlay"><h2>Wardrobes <i class="fas fa-arrow-right"></i></h2></div>
            </a>

            <a href="collection_viewer.php?cat=dining" class="collection-card">
                <img src="images/Dining room suite.jpeg" alt="Dining Sets">
                <div class="collection-overlay"><h2>Dining Suite <i class="fas fa-arrow-right"></i></h2></div>
            </a>

            <a href="collection_viewer.php?cat=headboards" class="collection-card">
                <img src="images/front heardboard.jpeg" alt="Headboards">
                <div class="collection-overlay"><h2>Headboards <i class="fas fa-arrow-right"></i></h2></div>
            </a>

            <a href="collection_viewer.php?cat=tables" class="collection-card">
                <img src="https://i.postimg.cc/vmRkxdsz/modern-designs.jpg" alt="Tables">
                <div class="collection-overlay"><h2>Tables <i class="fas fa-arrow-right"></i></h2></div>
            </a>

            <a href="collection_viewer.php?cat=chairs" class="collection-card">
                <img src="images/front chair 2.jpeg" alt="Chairs">
                <div class="collection-overlay"><h2>Chairs <i class="fas fa-arrow-right"></i></h2></div>
            </a>

            <a href="collection_viewer.php?cat=drawers" class="collection-card">
                <img src="images/chest of drawer.jpeg" alt="Drawers">
                <div class="collection-overlay"><h2>Chest of Drawers <i class="fas fa-arrow-right"></i></h2></div>
            </a>
        </div>
    </main>

    <footer>
        <p>© 2026 PJ Furniture Designers | Created by Thabelo Mahlangu</p>
    </footer>

    <script>
        const openSidebar = document.getElementById('openSidebar');
        const closeSidebar = document.getElementById('closeSidebar');
        const sidebar = document.getElementById('sidebar');

        openSidebar.addEventListener('click', () => {
            sidebar.style.width = "300px";
        });

        closeSidebar.addEventListener('click', () => {
            sidebar.style.width = "0";
        });
    </script>
</body>
</html>