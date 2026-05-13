<?php
include 'db_config.php';
session_start();

$error = "";
$success = "";
$step = 1; // Step 1: Find User, Step 2: Reset Password

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // STEP 1: Verify User
    if (isset($_POST['verify_user'])) {
        $user_input = mysqli_real_escape_string($conn, $_POST['user_input']);
        
        $sql = "SELECT * FROM users WHERE username = '$user_input' OR email = '$user_input' LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['reset_user_id'] = $user['user_id'];
            $step = 2;
        } else {
            $error = "No account found with that username or email.";
        }
    }

    // STEP 2: Update Password
    if (isset($_POST['reset_password'])) {
        $new_pass = $_POST['new_password'];
        $conf_pass = $_POST['confirm_password'];
        $user_id = $_SESSION['reset_user_id'];

        if ($new_pass !== $conf_pass) {
            $error = "Passwords do not match!";
            $step = 2;
        } else {
            $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);
            $update_sql = "UPDATE users SET password = '$hashed_password' WHERE user_id = '$user_id'";
            
            if (mysqli_query($conn, $update_sql)) {
                $success = "Password updated successfully! Redirecting to login...";
                unset($_SESSION['reset_user_id']);
                header("refresh:3;url=login.php");
            } else {
                $error = "Error updating password.";
                $step = 2;
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
    <title>Reset Password | PJ Furniture Designers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .reset-container { background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 400px; border-top: 5px solid #B20000; }
        h2 { text-align: center; text-transform: uppercase; letter-spacing: 2px; color: #333; margin-bottom: 20px; font-size: 1.2rem; }
        p { text-align: center; color: #666; font-size: 0.9rem; margin-bottom: 25px; }
        .input-group { margin-bottom: 20px; position: relative; }
        .input-group i { position: absolute; left: 15px; top: 12px; color: #888; }
        .input-group input { width: 100%; padding: 12px 12px 12px 45px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-size: 0.9rem; }
        .btn-reset { width: 100%; padding: 12px; background: #000; color: #fff; border: none; border-radius: 4px; font-weight: 700; cursor: pointer; transition: 0.3s; text-transform: uppercase; }
        .btn-reset:hover { background: #B20000; }
        .alert { padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 0.85rem; text-align: center; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .back-link { text-align: center; margin-top: 20px; font-size: 0.85rem; }
        .back-link a { color: #B20000; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="reset-container">
    <h2>Reset Password</h2>

    <?php if($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <?php if($step == 1): ?>
        <p>Enter your username or email to find your account.</p>
        <form action="forgot_password.php" method="POST">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="user_input" placeholder="Username or Email" required>
            </div>
            <button type="submit" name="verify_user" class="btn-reset">Find Account</button>
        </form>
    <?php else: ?>
        <p>Set a new password for your account.</p>
        <form action="forgot_password.php" method="POST">
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="new_password" placeholder="New Password" required>
            </div>
            <div class="input-group">
                <i class="fas fa-check-circle"></i>
                <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
            </div>
            <button type="submit" name="reset_password" class="btn-reset">Update Password</button>
        </form>
    <?php endif; ?>

    <div class="back-link">
        <a href="login.php"><i class="fas fa-arrow-left"></i> Back to Login</a>
    </div>
</div>

</body>
</html>