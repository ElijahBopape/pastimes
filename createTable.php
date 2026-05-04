<?php
/**
 * Reset and reload tblUser – drops table, recreates it, loads data from userData.txt
 */

include 'DBConn.php';

$woodDb->query("SET FOREIGN_KEY_CHECKS = 0");
$woodDb->query("DROP TABLE IF EXISTS tblUser");
$woodDb->query("SET FOREIGN_KEY_CHECKS = 1");

$sql = "CREATE TABLE tblUser (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('buyer','seller','admin') NOT NULL DEFAULT 'buyer',
    is_verified TINYINT(1) NOT NULL DEFAULT 0,
    seller_status ENUM('pending','approved','rejected','none') NOT NULL DEFAULT 'none',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$woodDb->query($sql);
echo "Table 'tblUser' created.<br>";

$file = 'userData.txt';
if (!file_exists($file)) die("File $file not found.");

$lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$stmt = $woodDb->prepare("INSERT INTO tblUser (name, email, username, password, role, is_verified, seller_status) VALUES (?, ?, ?, ?, ?, ?, ?)");

foreach ($lines as $line) {
    $data = explode('|', $line);
    if (count($data) != 7) continue;
    $stmt->bind_param("sssssis", $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]);
    $stmt->execute();
}
echo "Data loaded from userData.txt.<br>";
$stmt->close();
$woodDb->close();
?>