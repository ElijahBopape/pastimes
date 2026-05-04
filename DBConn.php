<?php
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'ClothingStore';

$woodDb = new mysqli($dbHost, $dbUser, $dbPass);
if ($woodDb->connect_error) die("Connection failed: " . $woodDb->connect_error);

$woodDb->query("CREATE DATABASE IF NOT EXISTS `$dbName`");
$woodDb->select_db($dbName);
$woodDb->set_charset("utf8");
?>