<?php
session_start();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']) ? true : false;
$username = $isLoggedIn ? $_SESSION['username'] : "Guest";

// Default to pedestals if no category is set
$category = isset($_GET['cat']) ? $_GET['cat'] : 'pedestals';

// Product data dictionary
$products = [
    "pedestals" => [
        ["name" => "Modern Oak Dual-Drawer", "price" => 1250, "img" => "images/pedestal1.jpeg"],
        ["name" => "Floating Minimalist Pedestal", "price" => 850, "img" => "images/pedestal2.jpeg"],
        ["name" => "Industrial Metal & Wood", "price" => 1400, "img" => "images/pedestal3.jpeg"]
    ],
    "headboards" => [
        ["name" => "Deep Diamond Tufted", "price" => 2500, "img" => "images/headboard1.jpg"],
        ["name" => "Velvet Winged Headboard", "price" => 3200, "img" => "images/headboard2.jpg"],
        ["name" => "Slatted Wooden Headboard", "price" => 1800, "img" => "images/headboard3.jpg"]
    ],
    "kitchens" => [
        ["name" => "Modern Gloss Kitchen Unit", "price" => 15000, "img" => "images/kitchen1.jpg"],
        ["name" => "Compact Studio Kitchen", "price" => 9500, "img" => "images/kitchen2.jpg"],
        ["name" => "Island Breakfast Nook", "price" => 4500, "img" => "images/kitchen3.jpg"]
    ],
    "couches" => [
        ["name" => "3-Seater Velvet Sofa", "price" => 8500, "img" => "images/couch1.jpg"],
        ["name" => "L-Shape Corner Suite", "price" => 12000, "img" => "images/couch2.jpg"],
        ["name" => "Modern Armchair", "price" => 3200, "img" => "images/couch3.jpg"]
    ],
    "dining" => [
        ["name" => "8-Seater Marble Set", "price" => 18500, "img" => "images/dining1.jpg"],
        ["name" => "Round Glass Dining Set", "price" => 7200, "img" => "images/dining2.jpg"],
        ["name" => "Rustic Farmhouse Set", "price" => 14000, "img" => "images/dining3.jpg"]
    ],
    "tables" => [
        ["name" => "Live Edge Coffee Table", "price" => 2800, "img" => "images/table1.jpg"],
        ["name" => "Minimalist Work Desk", "price" => 3500, "img" => "images/table2.jpg"],
        ["name" => "Extendable Dining Table", "price" => 6500, "img" => "images/table3.jpg"]
    ],
    "chairs" => [
        ["name" => "Scandi Dining Chair", "price" => 950, "img" => "images/chair1.jpg"],
        ["name" => "Velvet Occasional Chair", "price" => 2400, "img" => "images/chair2.jpg"],
        ["name" => "Leather Executive Chair", "price" => 4200, "img" => "images/chair3.jpg"]
    ],
    "drawers" => [
        ["name" => "6-Drawer Tallboy", "price" => 4800, "img" => "images/drawers1.jpg"],
        ["name" => "Wide Dresser Unit", "price" => 5500, "img" => "images/drawers2.jpg"],
        ["name" => "Mid-Century Chest", "price" => 3900, "img" => "images/drawers3.jpg"]
    ]
];

$itemsToShow = isset($products[$category]) ? $products[$category] : [];
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($category); ?> | PJ Furniture Designers</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            height: 100%; width: 0; position: fixed; z-index: 3000;
            top: 0; left: 0; background-color: #fff; overflow-x: hidden;
            transition: 0.5s; padding-top: 60px; box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar-content { padding: 10px 30px; }
        .sidebar-content a {
            padding: 18px 0; text-decoration: none; font-size: 1rem;
            color: #444; display: flex; align-items: center; 
            font-weight: 700; text-transform: uppercase; letter-spacing: 1px;
            transition: 0.3s;
        }
        .sidebar-content a i { width: 35px; font-size: 1.2rem; color: #333; }
        .sidebar-content a:hover { color: #B20000; }
        .sidebar-content hr { border: 0; border-top: 1px solid #eee; margin: 15px 0; }
        .closebtn { position: absolute; top: 15px; right: 25px; font-size: 30px; color: #000; text-decoration: none; }
        .user-greeting { padding: 0 0 10px 0; font-size: 0.8rem; color: #B20000; font-weight: bold; text-transform: uppercase; }

        header { 
            position: fixed; top: 0; width: 100%; background: #fff; 
            height: 80px; display: flex; justify-content: space-between; 
            align-items: center; padding: 0 5%; z-index: 2000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .header-left { display: flex; align-items: center; gap: 20px; }
        .menu-toggle { font-size: 1.5rem; cursor: pointer; color: #000; }
        
        .logo { font-size: 1.2rem; font-weight: 800; text-transform: uppercase; text-decoration: none; color: #000; border-left: 4px solid #B20000; padding-left: 15px; }
        .logo span { color: #B20000; }

        .nav-links { display: flex; gap: 30px; align-items: center; }
        .nav-links a { text-decoration: none; color: #000; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; }
        .nav-links a:hover { color: #B20000; }

        .nav-icons { display: flex; align-items: center; gap: 25px; }
        .nav-icons a { color: #000; text-decoration: none; position: relative; font-size: 1.3rem; }
        .cart-count {
            position: absolute; top: -8px; right: -12px;
            background: #B20000; color: #fff; font-size: 0.7rem;
            width: 20px; height: 20px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }

        .item-card { background: #fff; border: 1px solid #eee; transition: 0.3s; overflow: hidden; }
        .item-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .add-cart-btn {
            background: #000; color: #fff; border: none; padding: 12px;
            width: 100%; font-weight: 700; cursor: pointer; transition: 0.3s;
            text-transform: uppercase;
        }
        .add-cart-btn:hover { background: #B20000; }
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

            <a href="index.php"><i class="fas fa-home"></i> HOME</a>
            <a href="products.php"><i class="fas fa-couch"></i> PRODUCTS</a>
            <a href="index.php#about"><i class="fas fa-info-circle"></i> ABOUT</a>
            <a href="index.php#contact"><i class="fas fa-envelope"></i> CONTACT US</a>
            
            <hr>
            
            <a href="cart.php"><i class="fas fa-shopping-cart"></i> CART</a>

            <?php if($isLoggedIn): ?>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> LOGOUT</a>
            <?php else: ?>
                <!-- Links directed to your authentication files -->
                <a href="login.php"><i class="fas fa-sign-in-alt"></i> SIGN IN</a>
                <a href="register.php"><i class="fas fa-user-plus"></i> REGISTER</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Top Navigation -->
    <header>
        <div class="header-left">
            <div class="menu-toggle" onclick="openNav()">
                <i class="fas fa-bars"></i>
            </div>
            <a href="index.php" class="logo">PJ <span>FURNITURE</span></a>
        </div>

        <nav class="nav-links">
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
        </nav>
        
        <div class="nav-icons">
            <?php if($isLoggedIn): ?>
                <a href="logout.php" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
            <?php else: ?>
                <a href="login.php" title="Sign In"><i class="fas fa-user"></i></a>
            <?php endif; ?>

            <a href="cart.php">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count"><?php echo $cart_count; ?></span>
            </a>
        </div>
    </header>

    <div style="margin-top: 130px; text-align: center; padding: 0 5%;">
        <h1 style="font-size: 2.2rem; text-transform: uppercase; letter-spacing: 2px; font-weight: 800;">
            <?php 
                if($category == 'drawers') echo "Chest of Drawers";
                elseif($category == 'dining') echo "Dining Sets";
                else echo ucfirst($category); 
            ?>
        </h1>
        <div style="width: 60px; height: 3px; background: #B20000; margin: 15px auto;"></div>
    </div>

    <!-- Product Grid -->
    <div class="item-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; padding: 40px 8%;">
        <?php if(!empty($itemsToShow)): ?>
            <?php foreach($itemsToShow as $item): ?>
                <div class="item-card">
                    <img src="<?php echo $item['img']; ?>" alt="<?php echo $item['name']; ?>" style="width:100%; height:320px; object-fit: cover;">
                    <div style="padding: 20px; text-align: center;">
                        <h3 style="margin-bottom: 10px; font-size: 1.1rem; color: #333; font-weight: 700;"><?php echo $item['name']; ?></h3>
                        <p style="color: #B20000; font-weight: 800; margin-bottom: 15px;">R <?php echo number_format($item['price'], 2); ?></p>
                        
                        <form action="cart.php" method="POST">
                            <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($item['name']); ?>">
                            <input type="hidden" name="price" value="<?php echo $item['price']; ?>">
                            <input type="hidden" name="size" value="Standard">
                            <input type="hidden" name="color" value="Default">
                            <button type="submit" class="add-cart-btn">ADD TO CART</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 50px;">
                <p>No products found in the <?php echo $category; ?> category.</p>
                <a href="products.php" style="color: #B20000; font-weight: 700;">Back to All Products</a>
            </div>
        <?php endif; ?>
    </div>

    <footer style="text-align: center; padding: 40px; background: #fdfdfd; border-top: 1px solid #eee; margin-top: 50px;">
        <p style="font-size: 0.8rem; letter-spacing: 1px; color: #888; font-weight: 700;">© 2026 PJ FURNITURE DESIGNERS | RAMOTSE</p>
    </footer>

    <script>
        function openNav() { document.getElementById("sidebar").style.width = "320px"; }
        function closeNav() { document.getElementById("sidebar").style.width = "0"; }
    </script>
</body>
</html>