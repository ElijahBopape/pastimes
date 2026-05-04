<?php
/**
 * Admin panel – verify customers, add/update/delete users.
 */

session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}
include 'DBConn.php';

// Verify a customer
if (isset($_GET['verify'])) {
    $uid = intval($_GET['verify']);
    $woodDb->query("UPDATE tblUser SET is_verified = 1 WHERE user_id = $uid");
    header("Location: admin_dashboard.php");
    exit();
}
// Add a new customer (verified immediately)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $pass = md5($_POST['password']);
    $role = $_POST['role'];
    $sellerStat = ($role == 'seller') ? 'pending' : 'none';
    $stmt = $woodDb->prepare("INSERT INTO tblUser (name, email, username, password, role, is_verified, seller_status) VALUES (?,?,?,?,?,1,?)");
    $stmt->bind_param("ssssss", $name, $email, $username, $pass, $role, $sellerStat);
    $stmt->execute();
    header("Location: admin_dashboard.php");
    exit();
}
// Update user details
if (isset($_POST['update_user'])) {
    $id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $woodDb->query("UPDATE tblUser SET name='$name', email='$email', role='$role' WHERE user_id=$id");
    header("Location: admin_dashboard.php");
    exit();
}
// Delete a user
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $woodDb->query("DELETE FROM tblUser WHERE user_id=$id");
    header("Location: admin_dashboard.php");
    exit();
}

$users = $woodDb->query("SELECT * FROM tblUser ORDER BY user_id");
?>
<!DOCTYPE html>
<html>
<head><title>Admin Panel - Pastimes Wood</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="wood-wrapper">
    <div style="display: flex; justify-content: space-between;">
        <h1>Admin Dashboard</h1>
        <a href="logout.php" class="nude-btn" style="background:#b86b4a;">Logout</a>
    </div>
    <h2>Users & Verification</h2>
    <table class="wood-table">
        <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Verified</th><th>Actions</th></tr></thead>
        <tbody>
        <?php while($row = $users->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['user_id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo $row['role']; ?></td>
            <td><?php echo $row['is_verified'] ? '✅ Yes' : '❌ No'; ?></td>
            <td>
                <?php if(!$row['is_verified'] && $row['role'] != 'admin'): ?>
                    <a href="?verify=<?php echo $row['user_id']; ?>" class="nude-btn" style="padding:5px 12px;">Verify</a>
                <?php endif; ?>
                <a href="?delete=<?php echo $row['user_id']; ?>" class="nude-btn" style="background:#b86b4a; padding:5px 12px;" onclick="return confirm('Delete user permanently?')">Delete</a>
                <button onclick="document.getElementById('edit-<?php echo $row['user_id']; ?>').style.display='inline-block'" class="nude-btn" style="padding:5px 12px;">Edit</button>
                <div id="edit-<?php echo $row['user_id']; ?>" style="display:none; margin-top:10px;">
                    <form method="post" style="display:inline-flex; gap:8px;">
                        <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                        <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required style="width:auto;">
                        <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required style="width:auto;">
                        <select name="role" style="width:auto;">
                            <option value="buyer" <?php if($row['role']=='buyer') echo 'selected'; ?>>Buyer</option>
                            <option value="seller" <?php if($row['role']=='seller') echo 'selected'; ?>>Seller</option>
                        </select>
                        <button type="submit" name="update_user" class="nude-btn">Update</button>
                    </form>
                </div>
            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <h2>Add New Customer</h2>
    <form method="post" style="max-width:600px;">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="role">
            <option value="buyer">Buyer</option>
            <option value="seller">Seller</option>
        </select>
        <button type="submit" name="add_user" class="nude-btn">Add Customer</button>
    </form>
</div>
</body>
</html>