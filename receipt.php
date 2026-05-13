<?php
session_start();
include 'db_config.php';

// 1. Security Check: Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$order_id = isset($_GET['order_id']) ? mysqli_real_escape_string($conn, $_GET['order_id']) : 0;
$user_id = $_SESSION['user_id'];

// 2. Fetch Order Data and verify ownership
$order_sql = "SELECT * FROM orders WHERE id = '$order_id' AND user_id = '$user_id'";
$order_result = $conn->query($order_sql);

if ($order_result->num_rows == 0) {
    die("Error: Order not found or you do not have permission to view this receipt.");
}

$order = $order_result->fetch_assoc();

// 3. Fetch all items associated with this specific order
$items_sql = "SELECT * FROM order_items WHERE order_id = '$order_id'";
$items_result = $conn->query($items_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt #<?php echo $order_id; ?> | PJ Furniture Designers</title>
    
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="receipt-style.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Montserrat:wght@400;700&family=Playfair+Display:wght@900&display=swap" rel="stylesheet">
</head>
<body>

    <header>
        <div class="header-left">
            <a href="index.php" class="logo">PJ <span>FURNITURE DESIGNERS</span></a>
        </div>
        <div class="nav-icons">
            <a href="index.php" class="back-home-link">
                <i class="fas fa-arrow-left"></i> BACK TO HOME
            </a>
        </div>
    </header>

    <main class="receipt-container">
        <div class="receipt-header">
            <div class="brand-info">
                <h1 class="accent-font">RECEIPT</h1>
                <p>Order ID: <strong>#<?php echo $order['id']; ?></strong></p>
                <p>Date: <?php echo date("d F Y", strtotime($order['order_date'])); ?></p>
            </div>
            <div class="customer-info">
                <span class="status-badge"><?php echo $order['status']; ?></span>
                <p style="margin-top: 15px;"><strong>Billed To:</strong></p>
                <p><?php echo $_SESSION['user_name']; ?></p>
            </div>
        </div>

        <table class="receipt-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Specifications</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php while($item = $items_result->fetch_assoc()): ?>
                <tr>
                    <td><strong><?php echo $item['product_name']; ?></strong></td>
                    <td>
                        <div class="item-meta">
                            Size: <?php echo $item['size']; ?><br>
                            Color: <?php echo $item['color']; ?>
                        </div>
                    </td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>R <?php echo number_format($item['price'], 2); ?></td>
                    <td>R <?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="total-section">
            <p>Total Amount Paid</p>
            <h2>R <?php echo number_format($order['total_amount'], 2); ?></h2>
        </div>

        <div class="receipt-footer">
            <p><strong>Thank you for choosing PJ Furniture Designers.</strong></p>
            <p>This document serves as your official proof of purchase and warranty certificate.</p>
            
            <div class="receipt-actions">
                <button id="printReceiptBtn" class="print-btn">
                    <i class="fas fa-print"></i> Print Proof of Purchase
                </button>
            </div>
        </div>
    </main>

    <footer>
        <p>© 2026 PJ Furniture Designers | Created by Thabelo Mahlangu</p>
    </footer>

    <script src="receipt-script.js"></script>

</body>
</html>