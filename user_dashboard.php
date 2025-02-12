<?php
session_start();
require 'db.php';
require 'auth.php'; // Protect page

$collection = $db->posts;
$posts = $collection->find();
?>

<h1>Welcome, User</h1>
<a href="logout.php">Logout</a>

<h2>Latest Posts</h2>
<?php foreach ($posts as $post): ?>
    <div>
        <h3><?= $post['title'] ?></h3>
        <p><?= $post['content'] ?></p>
    </div>
<?php endforeach; ?>
