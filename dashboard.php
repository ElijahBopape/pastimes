<?php
/**
 * User dashboard – shows product grid with images, seller info, add‑to‑cart button with price popup.
 * Sellers see extra upload link.
 */

session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_verified']) {
    header("Location: login.php");
    exit();
}
include 'DBConn.php';
$userId = $_SESSION['user_id'];
$userName = $_SESSION['name'];
$userRole = $_SESSION['role'];
?>
<!DOCTYPE html>
<html>
<head><title>Dashboard - Pastimes Wood</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="wood-wrapper">
    <div style="display: flex; justify-content: space-between;">
        <h2>Welcome, <?php echo htmlspecialchars($userName); ?>! (Logged in)</h2>
        <a href="logout.php" class="nude-btn">Logout</a>
    </div>
    <p><a href="cart.php">My Cart</a> | <a href="messages.php">Messages</a></p>
    <?php if($userRole == 'seller'): ?>
        <p><a href="upload_product.php" class="nude-btn">Upload New Clothing Item</a></p>
    <?php endif; ?>
    <h3>Available Clothing</h3>
    <div class="products-grid">
    <?php
    $result = $woodDb->query("SELECT p.*, u.name as seller_name FROM tblProduct p JOIN tblUser u ON p.seller_id = u.user_id WHERE p.status='available' AND p.admin_approved=1");
    while($item = $result->fetch_assoc()):
    ?>
        <div class="timber-card">
            <img src="assets/<?php echo htmlspecialchars($item['image']); ?>" onerror="this.src='https://via.placeholder.com/150'">
            <h4><?php echo htmlspecialchars($item['name']); ?></h4>
            <p>R<?php echo number_format($item['price'], 2); ?></p>
            <p>Seller: <?php echo htmlspecialchars($item['seller_name']); ?></p>
            <button class="nude-btn add-to-cart" data-id="<?php echo $item['product_id']; ?>" data-name="<?php echo htmlspecialchars($item['name']); ?>" data-price="<?php echo $item['price']; ?>">Add to Cart</button>
            <a href="messages.php?to=<?php echo $item['seller_id']; ?>">Message Seller</a>
        </div>
    <?php endwhile; ?>
    </div>
</div>
<script>
    // Price confirmation popup before adding to cart
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function() {
            const pid = this.dataset.id;
            const pname = this.dataset.name;
            const price = this.dataset.price;
            if(confirm(`Add "${pname}" to cart for R${price}?`)) {
                window.location.href = `cart.php?add=${pid}`;
            }
        });
    });
</script>
</body>
</html>