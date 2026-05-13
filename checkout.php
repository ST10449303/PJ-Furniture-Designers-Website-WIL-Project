<?php
session_start();

// Redirect if not logged in or cart is empty
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if (empty($_SESSION['cart'])) {
    header("Location: products.php");
    exit();
}

$order_placed = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_purchase'])) {
    // Here you would typically save to a database
    unset($_SESSION['cart']); // Clear cart
    $order_placed = true;
}

$total = 0;
foreach($_SESSION['cart'] ?? [] as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | PJ Furniture Designers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Montserrat', sans-serif; background: #f9f9f9; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .checkout-container { background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); width: 100%; max-width: 500px; }
        h1 { color: #B20000; text-transform: uppercase; font-size: 1.5rem; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; font-size: 0.9rem; }
        input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn { background: #000; color: #fff; width: 100%; padding: 15px; border: none; font-weight: bold; cursor: pointer; text-transform: uppercase; margin-top: 20px; }
        .btn:hover { background: #B20000; }
        .success-card { text-align: center; }
        .success-card i { color: green; font-size: 4rem; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="checkout-container">
    <?php if ($order_placed): ?>
        <div class="success-card">
            <i class="fas fa-check-circle"></i>
            <h2>Order placed successfully!</h2>
            <p>Thank you for shopping with PJ Furniture Designers.</p>
            <a href="index.php" class="btn" style="text-decoration:none; display:block;">Return Home</a>
        </div>
    <?php else: ?>
        <h1>Shipping Details</h1>
        <p>Total to pay: <strong>R <?php echo number_format($total, 2); ?></strong></p>
        <form method="POST">
            <div class="form-group">
                <label>Delivery Address</label>
                <input type="text" name="address" required placeholder="e.g. 123 Soweto St">
            </div>
            <div class="form-group">
                <label>City</label>
                <input type="text" name="city" required placeholder="Johannesburg">
            </div>
            <div class="form-group">
                <label>Postal Code</label>
                <input type="text" name="postal" required placeholder="1862">
            </div>
            <div class="form-group">
                <label>Contact Number</label>
                <input type="tel" name="phone" required placeholder="012 345 6789">
            </div>
            <button type="submit" name="confirm_purchase" class="btn">Confirm Purchase</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>