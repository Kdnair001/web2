<?php
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

// Fetch all normal users
$users = $collection->find(['role' => 'user']);

// Handle user promotion to admin
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['promote_user'])) {
    $userId = $_POST['user_id'];

    // Only the main admin can assign admin roles
    if ($loggedInUser['email'] !== "karthikdnair001@gmail.com") {
        die("❌ Only the owner can promote users to admin.");
    }

    $collection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($userId)],
        ['$set' => ['role' => 'admin']]
    );

    echo "✅ User promoted to admin!";
    header("Location: admin_dashboard.php");
    exit();
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

    <h2>Promote Users to Admin</h2>
    <form method="POST">
        <select name="user_id">
            <?php foreach ($users as $user): ?>
                <option value="<?= $user['_id'] ?>"><?= htmlspecialchars($user['email']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="promote_user">Promote to Admin</button>
    </form>

    <br>
    <a href="admin_panel.php">Go to Admin Panel</a> | <a href="logout.php">Logout</a>
</body>
</html>
