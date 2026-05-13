<?php
include 'db_config.php';
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $user_input = mysqli_real_escape_string($conn, $_POST['user_input']); // Can be username or email
    $password = $_POST['password'];

    if (empty($user_input) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        // Check database for username OR email
        $sql = "SELECT * FROM users WHERE username = '$user_input' OR email = '$user_input' LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            // Verify the hashed password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];

                // Redirect to the home page or products page
                header("Location: index.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No account found with that username or email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | PJ Furniture Designers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-container { background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 400px; border-top: 5px solid #B20000; }
        h2 { text-align: center; text-transform: uppercase; letter-spacing: 2px; color: #333; margin-bottom: 30px; }
        .input-group { margin-bottom: 20px; position: relative; }
        .input-group i { position: absolute; left: 15px; top: 12px; color: #888; }
        .input-group input { width: 100%; padding: 12px 12px 12px 45px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-size: 0.9rem; }
        .btn-login { width: 100%; padding: 12px; background: #000; color: #fff; border: none; border-radius: 4px; font-weight: 700; cursor: pointer; transition: 0.3s; text-transform: uppercase; }
        .btn-login:hover { background: #B20000; }
        .options { display: flex; justify-content: space-between; align-items: center; margin-top: 15px; font-size: 0.8rem; }
        .options a { color: #666; text-decoration: none; }
        .options a:hover { color: #B20000; text-decoration: underline; }
        .footer-link { text-align: center; margin-top: 25px; font-size: 0.85rem; color: #666; }
        .footer-link a { color: #B20000; text-decoration: none; font-weight: bold; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; border: 1px solid #f5c6cb; margin-bottom: 20px; font-size: 0.85rem; text-align: center; }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Sign In</h2>

    <?php if($error): ?>
        <div class="alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="user_input" placeholder="Username or Email" required>
        </div>
        
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>
        
        <button type="submit" class="btn-login">Sign In</button>

        <div class="options">
            <label style="color: #666;"><input type="checkbox"> Remember me</label>
            <a href="forgot_password.php">Forgot password?</a>
        </div>
    </form>

    <div class="footer-link">
        Don't have an account? <a href="register.php">Register now</a>
    </div>
</div>

</body>
</html>