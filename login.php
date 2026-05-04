<?php
/**
 * User login – accepts username or email, sticky form on error, MD5 hash comparison.
 */

session_start();
include 'DBConn.php';
$loginErr = '';
$stickyInput = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $loginCred = trim($_POST['loginCred']);
    $passWord = $_POST['passWord'];
    $stickyInput = $loginCred;

    if (filter_var($loginCred, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT * FROM tblUser WHERE email = ? AND is_verified = 1";
    } else {
        $sql = "SELECT * FROM tblUser WHERE username = ? AND is_verified = 1";
    }

    $stmt = $woodDb->prepare($sql);
    $stmt->bind_param("s", $loginCred);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if ($user['password'] == md5($passWord)) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['is_verified'] = $user['is_verified'];
            header("Location: dashboard.php");
            exit();
        } else {
            $loginErr = "Invalid password.";
        }
    } else {
        $loginErr = "User not found or not verified.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head><title>Login - Pastimes Wood</title><link rel="stylesheet" href="style.css"></head>
<body>
<div style="min-height:100vh; display:flex; align-items:center; justify-content:center;">
<div class="wood-wrapper" style="max-width:520px; margin:0; padding:45px 40px;">
    <h2>Login to Pastimes</h2>
    <?php if($loginErr) echo "<p class='error'>$loginErr</p>"; ?>
    <form method="post">
        <input type="text" name="loginCred" placeholder="Username or Email" value="<?php echo htmlspecialchars($stickyInput); ?>" required>
        <input type="password" name="passWord" placeholder="Password" required>
        <button type="submit" class="nude-btn">Login</button>
    </form>
    <div style="text-align:center; margin-top:25px; padding-top:15px; border-top:1px solid #e2cfb3;">
        <p>Don't have an account? <a href="register.php">Register here</a></p>
        <p><a href="admin_login.php">Admin Login</a></p>
    </div>
</div>
</div>
</body>
</html>