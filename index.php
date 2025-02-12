<?php
require 'db.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

$userCollection = $db->users;
$user = $userCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

?>

<h1>Welcome, <?= htmlspecialchars($user['name']) ?></h1>
<a href="profile.php">Profile</a> |
<a href="logout.php">Logout</a>

<?php if ($user['role'] === 'admin'): ?>
    <a href="admin_dashboard.php">Admin Dashboard</a>
<?php else: ?>
    <a href="user_dashboard.php">User Dashboard</a>
<?php endif; ?>
