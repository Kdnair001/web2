<?php
ob_start(); // Start output buffering to prevent "headers already sent" error
session_start();
require 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user details
$userCollection = $db->users;
$user = $userCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

if (!$user) {
    header("Location: logout.php"); // Logout if user is invalid
    exit();
}

ob_end_flush(); // Send output buffer
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>

<h1>Welcome, <?= htmlspecialchars($user['name']) ?></h1>
<a href="profile.php">Profile</a> |
<a href="logout.php">Logout</a>

<?php if ($user['role'] === 'admin'): ?>
    <a href="admin_dashboard.php">Admin Dashboard</a>
<?php else: ?>
    <a href="user_dashboard.php">User Dashboard</a>
<?php endif; ?>

</body>
</html>

