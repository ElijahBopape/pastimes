<?php
// Destroy session and redirect to landing page
session_start();
session_destroy();
header("Location: index.php");
exit();
?>