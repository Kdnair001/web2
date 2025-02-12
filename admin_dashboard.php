<?php
session_start();
require 'db.php';

// Check if user is admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all posts
$collection = $db->posts;
$posts = $collection->find();
?>

<h1>Admin Dashboard</h1>
<a href="add_post.php">Add New Post</a> | <a href="logout.php">Logout</a>

<h2>Manage Posts</h2>
<?php foreach ($posts as $post): ?>
    <div>
        <h3><?= $post['title'] ?></h3>
        <p><?= $post['content'] ?></p>
        <a href="edit_post.php?id=<?= $post['_id'] ?>">Edit</a> | 
        <a href="delete_post.php?id=<?= $post['_id'] ?>">Delete</a>
    </div>
<?php endforeach; ?>
