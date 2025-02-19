<?php
session_start();
require 'vendor/autoload.php';

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] == 'user') {
    header("Location: index.php");
    exit();
}

// MongoDB Connection
$requiredEnv = ['MONGO_USER', 'MONGO_PASSWORD', 'MONGO_CLUSTER', 'MONGO_DATABASE'];
foreach ($requiredEnv as $env) {
    $value = getenv($env) ?: ($_ENV[$env] ?? null);
    if (!$value) {
        die("❌ Missing environment variable: $env");
    }
}

$username = getenv("MONGO_USER") ?: $_ENV["MONGO_USER"];
$password = getenv("MONGO_PASSWORD") ?: $_ENV["MONGO_PASSWORD"];
$cluster = getenv("MONGO_CLUSTER") ?: $_ENV["MONGO_CLUSTER"];
$database = getenv("MONGO_DATABASE") ?: $_ENV["MONGO_DATABASE"];

$mongoUri = "mongodb+srv://$username:$password@$cluster/$database?retryWrites=true&w=majority&appName=Cluster0";
$client = new MongoDB\Client($mongoUri);
$db = $client->selectDatabase($database);
$noticesCollection = $db->notices;

// Handle Create Notice
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['create'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $author = $_SESSION['email'] ?? "Admin"; // Store the email of the admin who posts the notice

    if (!empty($title) && !empty($content)) {
        $noticesCollection->insertOne([
            'title' => $title,
            'content' => $content,
            'author' => $author,
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ]);
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "❌ Title and content cannot be empty!";
    }
}

// Handle Edit Notice
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['edit'])) {
    $noticeId = new MongoDB\BSON\ObjectId($_POST['notice_id']);
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (!empty($title) && !empty($content)) {
        $noticesCollection->updateOne(
            ['_id' => $noticeId],
            ['$set' => ['title' => $title, 'content' => $content]]
        );
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "❌ Title and content cannot be empty!";
    }
}

// Handle Delete Notice
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete'])) {
    $noticeId = new MongoDB\BSON\ObjectId($_POST['notice_id']);
    $noticesCollection->deleteOne(['_id' => $noticeId]);
    header("Location: admin_dashboard.php");
    exit();
}

// Fetch All Notices
$notices = $noticesCollection->find([], ['sort' => ['created_at' => -1]]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 800px;
        }
        .notice {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .notice:last-child {
            border-bottom: none;
        }
        button {
            padding: 8px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }
        .edit-btn {
            background-color: #007bff;
            color: white;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
        .add-btn {
            background-color: #28a745;
            color: white;
            padding: 10px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 15px;
        }
        input, textarea {
            padding: 8px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Dashboard</h2>

        <!-- Manage Admins -->
        <a href="admin_panel.php">
            <button class="add-btn">👤 Manage Admins</button>
        </a>

        <h3>Create New Notice</h3>
        <form method="POST">
            <div class="form-group">
                <input type="text" name="title" placeholder="Notice Title" required>
                <textarea name="content" placeholder="Notice Content" required></textarea>
            </div>
            <button type="submit" name="create" class="add-btn">➕ Post Notice</button>
        </form>

        <h3>All Notices</h3>

        <?php foreach ($notices as $notice): ?>
            <div class="notice">
                <h4><?= htmlspecialchars($notice['title'] ?? 'No Title') ?></h4>
                <p><?= nl2br(htmlspecialchars($notice['content'] ?? 'No Content')) ?></p>
                <p>
                    <small>
                        Posted by: <?= htmlspecialchars($notice['author'] ?? 'Admin') ?> |
                        <?= isset($notice['created_at']) ? date('Y-m-d H:i:s', $notice['created_at']->toDateTime()->getTimestamp()) : 'Unknown Date' ?>
                    </small>
                </p>

                <!-- Edit Form -->
                <form method="POST">
                    <input type="hidden" name="notice_id" value="<?= $notice['_id'] ?>">
                    <div class="form-group">
                        <input type="text" name="title" value="<?= htmlspecialchars($notice['title'] ?? '') ?>" required>
                        <textarea name="content" required><?= htmlspecialchars($notice['content'] ?? '') ?></textarea>
                    </div>
                    <button type="submit" name="edit" class="edit-btn">✏ Update</button>
                    <button type="submit" name="delete" class="delete-btn">🗑 Delete</button>
                </form>
            </div>
        <?php endforeach; ?>

        <p><a href="index.php" target="_parent">🏠 Back to Home</a></p>
    </div>
</body>
</html>
