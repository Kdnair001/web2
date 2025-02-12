<?php
session_start();
require 'db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$collection = $db->posts;
$collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($_GET['id'])]);

header("Location: admin_dashboard.php");
exit();
?>
