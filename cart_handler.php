<?php
session_start();

// Check if the form was actually submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. Capture the data from the form
    $product_name = isset($_POST['product_name']) ? $_POST['product_name'] : 'Unknown Product';
    $price = isset($_POST['price']) ? (float)$_POST['price'] : 0;
    
    // Capture the custom options (Size and Color)
    $size = isset($_POST['size']) ? $_POST['size'] : 'Standard';
    $color = isset($_POST['color']) ? $_POST['color'] : 'Default';
    
    // 2. Create a unique ID for this specific combination
    // This allows a user to have the same headboard in two different colors in the cart
    $cart_item_id = md5($product_name . $size . $color);

    // 3. Initialize the cart session if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // 4. If the exact same item (same size/color) is already in the cart, increase quantity
    if (isset($_SESSION['cart'][$cart_item_id])) {
        $_SESSION['cart'][$cart_item_id]['quantity'] += 1;
    } else {
        // 5. Otherwise, add the new item to the cart array
        $_SESSION['cart'][$cart_item_id] = [
            'name'     => $product_name,
            'price'    => $price,
            'size'     => $size,
            'color'    => $color,
            'quantity' => 1
        ];
    }

    // 6. Redirect back to the previous page with a success message
    // Using HTTP_REFERER sends them back to the specific category they were browsing
    header("Location: " . $_SERVER['HTTP_REFERER'] . "&status=added");
    exit();
} else {
    // If someone tries to access this file directly without POSTing
    header("Location: products.php");
    exit();
}