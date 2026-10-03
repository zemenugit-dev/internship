<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "database/db.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        
        // Fetch user data by username
        $stmt = mysqli_prepare($conn, "SELECT id, username, password, role FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {

            // Verify plain-text password against hashed password in database
            if (password_verify($password, $row['password'])) {

                $_SESSION['user_id']  = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role']     = strtolower(trim($row['role']));

                if ($_SESSION['role'] === 'admin') {
                    header("Location: admin/dashboard.php");
                    exit();
                } else {
                    header("Location: user/dashboard.php");
                    exit();
                }

            } else {
                $error = "Invalid Username or Password";
            }

        } else {
            $error = "Invalid Username or Password";
        }

        mysqli_stmt_close($stmt);

    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Awi Zone System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="display:flex; justify-content:center; align-items:center; min-height:100vh; background-color:#006400; margin:0; font-family:sans-serif;">

<div style="background:#ffffff; padding:35px; border-radius:10px; width:360px; box-shadow:0 4px 15px rgba(0,0,0,0.3);">
    
    <h2 style="text-align:center; color:#006400; margin-top:0; margin-bottom:20px;">System Login</h2>

    <?php if (!empty($error)): ?>
        <div style="color:#d8000c; background-color:#ffd2d2; border:1px solid #d8000c; padding:10px; border-radius:5px; margin-bottom:15px; font-size:14px; text-align:center;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        
        <div style="margin-bottom:15px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold; color:#333;">Username</label>
            <input type="text" name="username" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px; box-sizing:border-box;">
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold; color:#333;">Password</label>
            <input type="password" name="password" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px; box-sizing:border-box;">
        </div>

        <button type="submit" name="login" style="width:100%; padding:12px; background-color:#006400; color:#ffffff; border:none; border-radius:5px; font-weight:bold; font-size:16px; cursor:pointer;">
            Login
        </button>

    </form>
<a href="admin/register.php" style="color:#006400; font-weight:bold; text-decoration:none;">Create Account</a>
    <p style="text-align:right; margin-top:20px; margin-bottom:0;">
        <a href="index.php" style="color:#006400; text-decoration:none; font-size:14px;">← Back to Home</a>
    </p>

</div>

</body>
</html>