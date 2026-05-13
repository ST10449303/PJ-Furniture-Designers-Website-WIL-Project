<?php
include 'db_config.php';
session_start();

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic Validation
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Check if email or username already exists
        $check_user = "SELECT * FROM users WHERE email='$email' OR username='$username'";
        $result = mysqli_query($conn, $check_user);

        if (mysqli_num_rows($result) > 0) {
            $error = "Username or Email already exists!";
        } else {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO users (full_name, username, email, password) 
                    VALUES ('$full_name', '$username', '$email', '$hashed_password')";

            if (mysqli_query($conn, $sql)) {
                $success = "Registration successful! You can now sign in.";
                header("refresh:2;url=login.php"); // Redirect after 2 seconds
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | PJ Furniture Designers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .register-container { background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 400px; border-top: 5px solid #B20000; }
        h2 { text-align: center; text-transform: uppercase; letter-spacing: 2px; color: #333; margin-bottom: 30px; }
        .input-group { margin-bottom: 15px; position: relative; }
        .input-group i { position: absolute; left: 15px; top: 12px; color: #888; }
        .input-group input { width: 100%; padding: 12px 12px 12px 45px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-size: 0.9rem; }
        .btn-register { width: 100%; padding: 12px; background: #000; color: #fff; border: none; border-radius: 4px; font-weight: 700; cursor: pointer; transition: 0.3s; text-transform: uppercase; margin-top: 10px; }
        .btn-register:hover { background: #B20000; }
        .footer-link { text-align: center; margin-top: 20px; font-size: 0.85rem; color: #666; }
        .footer-link a { color: #B20000; text-decoration: none; font-weight: bold; }
        .alert { padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 0.85rem; text-align: center; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>

<div class="register-container">
    <h2>Register</h2>

    <?php if($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <div class="input-group">
            <i class="fas fa-user-circle"></i>
            <input type="text" name="full_name" placeholder="Full Name" required>
        </div>
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="Email Address" required>
        </div>
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="input-group">
            <i class="fas fa-check-circle"></i>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        </div>
        
        <button type="submit" class="btn-register">Register</button>
    </form>

    <div class="footer-link">
        Already have an account? <a href="login.php">Sign in</a>
    </div>
</div>

</body>
</html>