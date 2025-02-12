<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Get logged-in user details from the database
$collection = $db->users;
$loggedInUser = $collection->findOne(['email' => $_SESSION['user']['email']]);

// Check if the logged-in user is an admin
if (!$loggedInUser || $loggedInUser['role'] !== 'admin') {
    die("❌ Access denied. Admins only.");
}

// Define the main admin (owner)
$main_admin_email = "karthikdnair001@gmail.com";

// Fetch all users
$users = $collection->find([]);

// Handle role updates securely
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_role'])) {
    $email = $_POST['email'];
    $new_role = $_POST['role'];

    // Prevent non-owner admins from assigning admin roles
    if ($new_role === 'admin' && $loggedInUser['email'] !== $main_admin_email) {
        die("❌ Only the owner (Karthik D Nair) can assign admin roles.");
    }

    // Prevent the owner from accidentally removing their own admin role
    if ($email === $main_admin_email && $new_role !== 'admin') {
        die("❌ The owner cannot remove their own admin role.");
    }

    // Update role in MongoDB
    $collection->updateOne(['email' => $email], ['$set' => ['role' => $new_role]]);
    
    // Refresh page to reflect changes
    header("Location: admin_panel.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel</h1>
    <p>Welcome, <strong><?= htmlspecialchars($loggedInUser['name']) ?></strong> (<?= htmlspecialchars($loggedInUser['email']) ?>)</p>

    <table border="1">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="email" value="<?= $user['email'] ?>">
                    <select name="role">
                        <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                    <button type="submit" name="update_role">Update Role</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <a href="logout.php">Logout</a>
</body>
</html>
