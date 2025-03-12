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

// Find the post
$post = $collection->findOne(['_id' => $postId]);

// If post not found, redirect with an error
if (!$post) {
    header("Location: admin_dashboard.php?error=PostNotFound");
    exit();
}

// Function to convert URLs into clickable links
function makeLinks($text) {
    return preg_replace(
        '~(https?://[^\s]+)~',
        '<a href="$1" target="_blank">$1</a>',
        $text
    );
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        header("Location: edit_post.php?id={$post['_id']}&error=EmptyFields");
        exit();
    }

    $collection->updateOne(
        ['_id' => $post['_id']],
        ['$set' => ['title' => $title, 'content' => $content]]
    );

    // Redirect back with success message
    header("Location: admin_dashboard.php?success=PostUpdated");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
</head>
<body>
    <h1>Edit Post</h1>

    <?php if (isset($_GET['error'])): ?>
        <p style="color: red;">❌ <?= htmlspecialchars($_GET['error']) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
        <textarea name="content" required><?= htmlspecialchars($post['content']) ?></textarea>
        <p><strong>Preview:</strong></p>
        <div style="border: 1px solid #ccc; padding: 10px; background: #f9f9f9;">
            <?= makeLinks(nl2br(htmlspecialchars($post['content']))) ?>
        </div>
        <br>
        <button type="submit">Update Post</button>
    </form>

    <br>
    <a href="admin_dashboard.php">⬅️ Back to Dashboard</a>
</body>
</html>
