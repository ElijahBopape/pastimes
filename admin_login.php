<?php
/**
 * Admin login – accepts email or username, verifies against admin role, uses MD5 hash.
 */

session_start();
include 'DBConn.php';
$adminErr = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $loginCred = trim($_POST['loginCred']);
    $passWord = $_POST['passWord'];

    if (filter_var($loginCred, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT * FROM tblUser WHERE email = ? AND role = 'admin'";
    } else {
        $sql = "SELECT * FROM tblUser WHERE username = ? AND role = 'admin'";
    }
    $stmt = $woodDb->prepare($sql);
    $stmt->bind_param("s", $loginCred);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        if ($admin['password'] == md5($passWord)) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['user_id'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $adminErr = "Wrong password.";
        }
    } else {
        $adminErr = "Admin not found.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head><title>Admin Login - Pastimes Wood</title><link rel="stylesheet" href="style.css"></head>
<body>
<div style="min-height:100vh; display:flex; align-items:center; justify-content:center;">
<div class="wood-wrapper" style="max-width:500px; margin:0;">
    <h2>Administrator Login</h2>
    <?php if($adminErr) echo "<p class='error'>$adminErr</p>"; ?>
    <form method="post">
        <input type="text" name="loginCred" placeholder="Admin Email or Username" required>
        <input type="password" name="passWord" placeholder="Password" required>
        <button type="submit" class="nude-btn">Login</button>
    </form>
    <div style="text-align:center; margin-top:20px;">
        <p><a href="index.php">Back to main site</a></p>
        <p><a href="login.php">User login</a></p>
    </div>
</div>
</div>
</body>
</html>