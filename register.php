<?php
/**
 * User registration – all fields required, stores MD5 hash, sets is_verified=0 (pending).
 */

session_start();
include 'DBConn.php';
$woodError = '';
$woodSuccess = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = trim($_POST['fullName']);
    $emailAddr = trim($_POST['emailAddr']);
    $userName = trim($_POST['userName']);
    $passWord = $_POST['passWord'];
    $confirmPass = $_POST['confirmPass'];
    $userRole = $_POST['userRole'];

    if (empty($fullName) || empty($emailAddr) || empty($userName) || empty($passWord)) {
        $woodError = "All fields are required.";
    } elseif ($passWord !== $confirmPass) {
        $woodError = "Passwords do not match.";
    } else {
        $hashedPass = md5($passWord);
        $sellerStat = ($userRole == 'seller') ? 'pending' : 'none';
        $stmt = $woodDb->prepare("INSERT INTO tblUser (name, email, username, password, role, is_verified, seller_status) VALUES (?, ?, ?, ?, ?, 0, ?)");
        $stmt->bind_param("ssssss", $fullName, $emailAddr, $userName, $hashedPass, $userRole, $sellerStat);
        if ($stmt->execute()) {
            $woodSuccess = "Registration successful! Wait for admin verification.";
        } else {
            $woodError = "Username or email already exists.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Register - Pastimes Wood</title><link rel="stylesheet" href="style.css"></head>
<body>
<div style="min-height:100vh; display:flex; align-items:center; justify-content:center;">
<div class="wood-wrapper" style="max-width:520px; margin:0; padding:45px 40px;">
    <h2>Create Account</h2>
    <?php if($woodError) echo "<p class='error'>$woodError</p>"; ?>
    <?php if($woodSuccess) echo "<p class='success'>$woodSuccess</p>"; ?>
    <form method="post">
        <input type="text" name="fullName" placeholder="Full Name" required>
        <input type="email" name="emailAddr" placeholder="Email" required>
        <input type="text" name="userName" placeholder="Username" required>
        <input type="password" name="passWord" placeholder="Password" required>
        <input type="password" name="confirmPass" placeholder="Confirm Password" required>
        <select name="userRole" required>
            <option value="buyer">Buyer</option>
            <option value="seller">Seller (needs admin approval)</option>
        </select>
        <button type="submit" class="nude-btn">Register</button>
    </form>
    <div style="text-align:center; margin-top:25px; padding-top:15px; border-top:1px solid #e2cfb3;">
        <p>Already have an account? <a href="login.php">Login here</a></p>
        <p><a href="admin_login.php">Admin Login</a></p>
    </div>
</div>
</div>
</body>
</html>