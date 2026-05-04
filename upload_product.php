<?php
/**
 * Seller upload – adds new product with admin_approved=0 (pending approval).
 */

session_start();
if($_SESSION['role'] != 'seller') die("Access denied");
include 'DBConn.php';
$msg = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = $_POST['name'];
    $desc = $_POST['desc'];
    $price = $_POST['price'];
    $image = "default.jpg";
    $stmt = $woodDb->prepare("INSERT INTO tblProduct (name, description, price, image, seller_id, admin_approved) VALUES (?,?,?,?,?,0)");
    $stmt->bind_param("ssdsi", $name, $desc, $price, $image, $_SESSION['user_id']);
    if($stmt->execute()){
        $msg = "Item uploaded, pending admin approval.";
    } else {
        $msg = "Error uploading item.";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Upload Product - Pastimes Wood</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="wood-wrapper">
    <h2>Upload New Clothing Item</h2>
    <?php if($msg) echo "<p class='success'>$msg</p>"; ?>
    <form method="post">
        <input type="text" name="name" placeholder="Product Name" required>
        <textarea name="desc" placeholder="Description" rows="3"></textarea>
        <input type="number" step="0.01" name="price" placeholder="Price (R)" required>
        <button type="submit" class="nude-btn">Upload</button>
    </form>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
</div>
</body>
</html>