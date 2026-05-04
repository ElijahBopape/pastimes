<?php
/**
 * Full database setup – drops all tables, recreates them, loads data from text files.
 * Foreign key checks disabled during load to avoid constraint errors.
 */

include 'DBConn.php';

$woodDb->query("SET FOREIGN_KEY_CHECKS = 0");

// Drop tables in safe order
$woodDb->query("DROP TABLE IF EXISTS tblMessage");
$woodDb->query("DROP TABLE IF EXISTS tblCart");
$woodDb->query("DROP TABLE IF EXISTS tblOrder");
$woodDb->query("DROP TABLE IF EXISTS tblProduct");
$woodDb->query("DROP TABLE IF EXISTS tblUser");

// Create all tables
$woodDb->query("CREATE TABLE tblUser (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('buyer','seller','admin') NOT NULL DEFAULT 'buyer',
    is_verified TINYINT(1) NOT NULL DEFAULT 0,
    seller_status ENUM('pending','approved','rejected','none') NOT NULL DEFAULT 'none',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$woodDb->query("CREATE TABLE tblProduct (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    seller_id INT NOT NULL,
    status ENUM('available','sold') DEFAULT 'available',
    admin_approved TINYINT(1) NOT NULL DEFAULT 0,
    FOREIGN KEY (seller_id) REFERENCES tblUser(user_id) ON DELETE CASCADE
)");

$woodDb->query("CREATE TABLE tblCart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES tblUser(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES tblProduct(product_id) ON DELETE CASCADE
)");

$woodDb->query("CREATE TABLE tblOrder (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    address TEXT NOT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES tblUser(user_id)
)");

$woodDb->query("CREATE TABLE tblMessage (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message_text TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES tblUser(user_id),
    FOREIGN KEY (receiver_id) REFERENCES tblUser(user_id)
)");

// Helper to load pipe‑delimited text files
function loadWoodData($woodDb, $file, $columns, $insertSql) {
    if (!file_exists($file)) {
        echo "Warning: $file not found.<br>";
        return;
    }
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $stmt = $woodDb->prepare($insertSql);
    foreach ($lines as $line) {
        $data = explode('|', $line);
        if (count($data) != count($columns)) continue;
        $stmt->bind_param(str_repeat('s', count($data)), ...$data);
        $stmt->execute();
    }
    $stmt->close();
}

// Load users first, then products (so seller_id exists)
echo "Loading users...<br>";
loadWoodData($woodDb, 'userData.txt',
    ['name','email','username','password','role','is_verified','seller_status'],
    "INSERT INTO tblUser (name, email, username, password, role, is_verified, seller_status) VALUES (?,?,?,?,?,?,?)");

echo "Loading products...<br>";
loadWoodData($woodDb, 'productData.txt',
    ['name','description','price','image','seller_id','status','admin_approved'],
    "INSERT INTO tblProduct (name, description, price, image, seller_id, status, admin_approved) VALUES (?,?,?,?,?,?,?)");

$woodDb->query("SET FOREIGN_KEY_CHECKS = 1");
echo "All tables created and wood & nude data loaded successfully.<br>";
$woodDb->close();
?>