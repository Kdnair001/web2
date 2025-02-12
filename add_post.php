<?php
session_start();
require 'db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $collection = $db->posts;
    $collection->insertOne([
        'title' => $title,
        'content' => $content,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);

    echo "✅ Post added successfully! <a href='admin_dashboard.php'>Back to Dashboard</a>";
}
?>

<form method="POST">
    <input type="text" name="title" placeholder="Post Title" required>
    <textarea name="content" placeholder="Content" required></textarea>
    <button type="submit">Add Post</button>
</form>
