<?php
ob_start(); // Start output buffering
session_start();
require 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get logged-in user details
$collection = $db->users;
$loggedInUser = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

// Restrict access to admins
if (!$loggedInUser || $loggedInUser['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Fetch all posts
$postCollection = $db->posts;
$posts = $postCollection->find([]);

// Handle adding new post
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_post'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (!empty($title) && !empty($content)) {
        $postCollection->insertOne([
            'title' => $title,
            'content' => $content,
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ]);
        header("Location: admin_dashboard.php?success=PostAdded");
        exit();
    } else {
        header("Location: admin_dashboard.php?error=EmptyFields");
        exit();
    }
}

// Handle deleting post
if (isset($_GET['delete'])) {
    $postId = $_GET['delete'];

    try {
        $postCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($postId)]);
        header("Location: admin_dashboard.php?success=PostDeleted");
        exit();
    } catch (Exception $e) {
        header("Location: admin_dashboard.php?error=DeleteFailed");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Welcome, <strong><?= htmlspecialchars($loggedInUser['name']) ?></strong> (<?= htmlspecialchars($loggedInUser['email']) ?>)</p>

    <?php if (isset($_GET['success'])): ?>
        <p style="color: green;">✅ <?= htmlspecialchars($_GET['success']) ?></p>
    <?php elseif (isset($_GET['error'])): ?>
        <p style="color: red;">❌ <?= htmlspecialchars($_GET['error']) ?></p>
    <?php endif; ?>

    <h2>Add New Post</h2>
    <form method="POST">
        <input type="text" name="title" placeholder="Post Title" required>
        <textarea name="content" placeholder="Content" required></textarea>
        <button type="submit" name="add_post">Add Post</button>
    </form>

    <h2>Existing Posts</h2>
    <table border="1">
        <tr>
            <th>Title</th>
            <th>Content</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($posts as $post): ?>
        <tr>
            <td><?= htmlspecialchars($post['title']) ?></td>
            <td><?= htmlspecialchars($post['content']) ?></td>
            <td>
                <a href="edit_post.php?id=<?= $post['_id'] ?>">✏️ Edit</a> | 
                <a href="admin_dashboard.php?delete=<?= $post['_id'] ?>" onclick="return confirm('Are you sure?')">🗑️ Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <a href="admin_panel.php">Go to Admin Panel</a> | <a href="logout.php">Logout</a>
</body>
</html>

<?php ob_end_flush(); // Flush the output buffer ?>
