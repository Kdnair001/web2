<?php
session_start();
require 'db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$collection = $db->posts;
$post = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($_GET['id'])]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $collection->updateOne(
        ['_id' => $post['_id']],
        ['$set' => ['title' => $_POST['title'], 'content' => $_POST['content']]]
    );
    header("Location: admin_dashboard.php");
    exit();
}
?>

<form method="POST">
    <input type="text" name="title" value="<?= $post['title'] ?>" required>
    <textarea name="content" required><?= $post['content'] ?></textarea>
    <button type="submit">Update Post</button>
</form>
