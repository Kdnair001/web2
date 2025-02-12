<?php
session_start();
require 'db.php';

// Ensure only admins can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Check if post ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: admin_dashboard.php?error=MissingPostID");
    exit();
}

// Validate MongoDB ObjectId format
try {
    $postId = new MongoDB\BSON\ObjectId($_GET['id']);
} catch (Exception $e) {
    header("Location: admin_dashboard.php?error=InvalidID");
    exit();
}

// Get posts collection
$collection = $db->posts;

// Check if post exists before deleting
$post = $collection->findOne(['_id' => $postId]);

if (!$post) {
    header("Location: admin_dashboard.php?error=PostNotFound");
    exit();
}

// Delete the post
$collection->deleteOne(['_id' => $postId]);

// Redirect back with success message
header("Location: admin_dashboard.php?success=PostDeleted");
exit();
?>

