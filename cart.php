<?php
session_start();

// --- 1. HANDLE ADDING ITEMS (POST REQUEST) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_name'])) {
    $product_name = $_POST['product_name'];
    $raw_price = isset($_POST['price']) ? $_POST['price'] : '0';
    $clean_price = preg_replace('/[^0-9.]/', '', $raw_price); 
    $price = (float)$clean_price;
    $size = isset($_POST['size']) ? $_POST['size'] : 'Standard';
    $color = isset($_POST['color']) ? $_POST['color'] : 'Default';
    $cart_item_id = md5($product_name . $size . $color);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$cart_item_id])) {
        $_SESSION['cart'][$cart_item_id]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$cart_item_id] = [
            'name'     => $product_name,
            'price'    => $price,
            'size'     => $size,
            'color'    => $color,
            'quantity' => 1
        ];
    }
    header("Location: cart.php");
    exit();
}

// --- 2. HANDLE REMOVING ITEMS (GET REQUEST) ---
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    if (isset($_SESSION['cart'][$remove_id])) {
        unset($_SESSION['cart'][$remove_id]);
    }
    header("Location: cart.php");
    exit();
}

// --- 3. PREPARE DATA FOR VIEW ---
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_price = 0;
$isLoggedIn = isset($_SESSION['user_id']) ? true : false;
$username = $isLoggedIn ? $_SESSION['username'] : "Guest";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart | PJ Furniture Designers</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&family=Playfair+Display:ital,wght@0,700;0,900;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        :root { --brand-red: #B20000; --deep-black: #0A0A0A; --soft-gray: #F9F9F9; }
        body { font-family: 'Montserrat', sans-serif; padding-top: 90px; background-color: #fff; }

        header {
            background: #fff; padding: 0 5%; height: 90px; display: flex;
            justify-content: space-between; align-items: center; position: fixed;
            width: 100%; top: 0; z-index: 2000; box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .header-left { display: flex; align-items: center; gap: 20px; }
        .menu-toggle { font-size: 1.4rem; cursor: pointer; color: var(--deep-black); }
        .logo { font-size: 1.1rem; font-weight: 800; text-transform: uppercase; border-left: 4px solid var(--brand-red); padding-left: 12px; text-decoration: none; color: black; letter-spacing: 1px; }
        .logo span { color: var(--brand-red); }

        .main-nav { display: flex; align-items: center; gap: 30px; }
        .nav-links { list-style: none; display: flex; gap: 25px; }
        .nav-links a { text-decoration: none; color: var(--deep-black); font-weight: 700; font-size: 0.75rem; letter-spacing: 1px; }

        .nav-icons { display: flex; align-items: center; gap: 20px; }
        .nav-icons a { color: var(--deep-black); font-size: 1.2rem; text-decoration: none; position: relative; }
        .cart-count { position: absolute; top: -10px; right: -12px; background: var(--brand-red); color: white; font-size: 0.6rem; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }

        .sidebar { height: 100%; width: 0; position: fixed; z-index: 3000; top: 0; left: 0; background-color: #fff; overflow-x: hidden; transition: 0.5s; padding-top: 60px; box-shadow: 5px 0 15px rgba(0,0,0,0.1); }
        .sidebar-content a { padding: 15px 32px; text-decoration: none; font-size: 0.9rem; color: #444; display: block; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; }
        .sidebar-content a:hover { color: var(--brand-red); background: var(--soft-gray); }
        .closebtn { position: absolute; top: 20px; right: 25px; font-size: 36px; text-decoration: none; color: #000; }

        .cart-page { padding: 60px 8% 80px; min-height: 80vh; }
        .cart-table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        .cart-table th { text-align: left; padding: 15px; border-bottom: 2px solid var(--brand-red); text-transform: uppercase; font-size: 0.8rem; }
        .cart-table td { padding: 20px 15px; border-bottom: 1px solid #eee; }
        .cart-item-info h4 { font-family: 'Playfair Display', serif; font-size: 1.2rem; margin-bottom: 5px; }
        .summary-box { background: var(--soft-gray); padding: 30px; width: 100%; max-width: 400px; text-align: right; margin-left: auto; margin-top: 40px; }
        .checkout-btn { display: block; background: var(--deep-black); color: white; text-align: center; padding: 15px; text-decoration: none; font-weight: 700; text-transform: uppercase; margin-top: 20px; transition: 0.3s; border: none; cursor: pointer; width: 100%; }
        .checkout-btn:hover { background: var(--brand-red); }
    </style>
</head>
<body>

    <div id="sidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div class="sidebar-content">
            <a href="index.php">HOME</a>
            <a href="products.php">PRODUCTS</a>
            <hr style="margin: 20px 32px; border: 0; border-top: 1px solid #eee;">
            <a href="cart.php" style="color: var(--brand-red);">VIEW CART</a>
            <?php if($isLoggedIn): ?>
                <a href="logout.php">LOGOUT (<?php echo htmlspecialchars($username); ?>)</a>
            <?php else: ?>
                <a href="login.php">SIGN IN</a>
                <a href="register.php">REGISTER</a>
            <?php endif; ?>
        </div>
    </div>

    <header>
        <div class="header-left">
            <div class="menu-toggle" onclick="openNav()"><i class="fas fa-bars"></i></div>
            <a href="index.php" class="logo">PJ <span>Furniture Designers</span></a>
        </div>
        <nav class="main-nav">
            <ul class="nav-links">
                <li><a href="index.php">HOME</a></li>
                <li><a href="products.php">PRODUCTS</a></li>
            </ul>
            <div class="nav-icons">
                <a href="cart.php" class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count"><?php echo count($cart_items); ?></span>
                </a>
                <a href="login.php" title="Login"><i class="fas fa-user"></i></a>
            </div>
        </nav>
    </header>

    <div class="cart-page">
        <h1 style="font-size: 3.5rem; font-family: 'Playfair Display', serif;">Shopping Cart</h1>

        <?php if (!empty($cart_items)): ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product Details</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $id => $item): 
                        $subtotal = $item['price'] * $item['quantity'];
                        $total_price += $subtotal;
                    ?>
                        <tr>
                            <td><div class="cart-item-info"><h4><?php echo htmlspecialchars($item['name']); ?></h4></div></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>R <?php echo number_format($item['price'], 2); ?></td>
                            <td>R <?php echo number_format($subtotal, 2); ?></td>
                            <td><a href="cart.php?remove=<?php echo $id; ?>" style="color:red; text-decoration:none;">Remove</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="summary-box">
                <h2>Total: R <?php echo number_format($total_price, 2); ?></h2>
                <?php if ($isLoggedIn): ?>
                    <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
                <?php else: ?>
                    <a href="register.php" class="checkout-btn">Register to Pay</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 100px 0;"><h2>Your cart is empty.</h2><a href="products.php" class="checkout-btn" style="width:auto; display:inline-block; padding:15px 40px;">Browse Collections</a></div>
        <?php endif; ?>
    </div>

    <script>
        function openNav() { document.getElementById("sidebar").style.width = "300px"; }
        function closeNav() { document.getElementById("sidebar").style.width = "0"; }
    </script>
</body>
</html>