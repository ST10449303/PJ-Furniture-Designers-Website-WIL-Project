<?php
$servername = "localhost";
$username = "root"; // Default phpMyAdmin username
$password = "";     // Default phpMyAdmin password

// 1. Create Connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Create Database
$sql_db = "CREATE DATABASE IF NOT EXISTS pj_furniture_db";
if ($conn->query($sql_db) === TRUE) {
    echo "Database created or already exists.<br>";
} else {
    echo "Error creating database: " . $conn->error;
}

// 3. Select the Database
$conn->select_db("pj_furniture_db");

// 4. SQL to create tables
$tables = [
    "users" => "CREATE TABLE IF NOT EXISTS users (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",

    "products" => "CREATE TABLE IF NOT EXISTS products (
        product_id INT AUTO_INCREMENT PRIMARY KEY,
        category VARCHAR(50) NOT NULL,
        name VARCHAR(100) NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        image_path VARCHAR(255),
        description TEXT
    )",

    "orders" => "CREATE TABLE IF NOT EXISTS orders (
        order_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        total_amount DECIMAL(10, 2) NOT NULL,
        address VARCHAR(255) NOT NULL,
        city VARCHAR(100) NOT NULL,
        postal_code VARCHAR(10) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        status VARCHAR(20) DEFAULT 'Pending',
        order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id)
    )",

    "order_items" => "CREATE TABLE IF NOT EXISTS order_items (
        item_id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        product_name VARCHAR(100) NOT NULL,
        quantity INT NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        FOREIGN KEY (order_id) REFERENCES orders(order_id)
    )"
];

// Execute Table Creation
foreach ($tables as $name => $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Table '$name' ready.<br>";
    } else {
        echo "Error creating table '$name': " . $conn->error . "<br>";
    }
}

// 5. Seed Initial Products (Optional)
$check_products = $conn->query("SELECT COUNT(*) as total FROM products");
$row = $check_products->fetch_assoc();

if ($row['total'] == 0) {
    $seed_sql = "INSERT INTO products (category, name, price, image_path) VALUES 
        ('pedestals', 'Modern Oak Dual-Drawer', 1250.00, 'images/pedestal1.jpeg'),
        ('couches', '3-Seater Velvet Sofa', 8500.00, 'images/couch1.jpg'),
        ('dining', '8-Seater Marble Set', 18500.00, 'images/dining1.jpg')";
    
    $conn->query($seed_sql);
    echo "Initial furniture products seeded successfully!<br>";
}

$conn->close();
?>