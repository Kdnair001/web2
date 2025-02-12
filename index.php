<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}
require 'db.php';
?>
<h1>Welcome to the Secure Website</h1>
<a href="logout.php">Logout</a>
