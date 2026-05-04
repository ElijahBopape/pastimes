<?php
/**
 * Shopping cart – add, remove items, display total.
 */

session_start();
if(!isset($_SESSION['user_id'])) header("Location: login.php");
include 'DBConn.php';
$uid = $_SESSION['user_id'];

if(isset($_GET['add'])) {
    $pid = intval($_GET['add']);
    $woodDb->query("INSERT INTO tblCart (user_id, product_id, quantity) VALUES ($uid, $pid, 1) ON DUPLICATE KEY UPDATE quantity = quantity + 1");
    header("Location: cart.php");
}
if(isset($_GET['remove'])) {
    $cid = intval($_GET['remove']);
    $woodDb->query("DELETE FROM tblCart WHERE cart_id=$cid");
    header("Location: cart.php");
}

$cartItems = $woodDb->query("SELECT c.cart_id, p.name, p.price, c.quantity FROM tblCart c JOIN tblProduct p ON c.product_id = p.product_id WHERE c.user_id = $uid");
?>
<!DOCTYPE html>
<html>
<head><title>Cart - Pastimes Wood</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="wood-wrapper">
    <h2>Your Shopping Cart</h2>
    <table class="wood-table">
        <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr></thead>
        <tbody>
        <?php $total = 0; while($row = $cartItems->fetch_assoc()): $sub = $row['price'] * $row['quantity']; $total += $sub; ?>
        <tr>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td>R<?php echo $row['price']; ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td>R<?php echo $sub; ?></td>
            <td><a href="?remove=<?php echo $row['cart_id']; ?>" class="nude-btn" style="background:#b86b4a;">Remove</a></td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <div style="text-align:right; margin-top:20px;">
        <strong>Total: R<?php echo $total; ?></strong><br><br>
        <a href="checkout.php" class="nude-btn">Proceed to Checkout</a>
        <a href="dashboard.php" class="nude-btn">Continue Shopping</a>
    </div>
</div>
</body>
</html>