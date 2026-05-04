<?php
/**
 * Checkout – capture delivery address, place order, empty cart.
 */

session_start();
if(!isset($_SESSION['user_id'])) header("Location: login.php");
include 'DBConn.php';
$uid = $_SESSION['user_id'];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $address = $_POST['address'];
    $woodDb->query("INSERT INTO tblOrder (user_id, address, status) VALUES ($uid, '$address', 'pending')");
    $woodDb->query("DELETE FROM tblCart WHERE user_id=$uid");
    echo "<!DOCTYPE html><html><head><title>Order Placed</title><link rel='stylesheet' href='style.csss'></head><body><div class='wood-wrapper'><p class='success'>Order placed successfully!</p><a href='dashboard.php' class='nude-btn'>Back to Dashboard</a></div></body></html>";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Checkout - Pastimes Wood</title><link rel="stylesheet" href="style_v6_wood.css"></head>
<body>
<div class="wood-wrapper">
    <h2>Checkout</h2>
    <form method="post">
        <label>Delivery Address</label>
        <textarea name="address" rows="3" placeholder="Street, City, Postal Code" required></textarea>
        <button type="submit" class="nude-btn">Place Order</button>
    </form>
</div>
</body>
</html>