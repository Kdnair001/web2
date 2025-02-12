<?php
require 'db.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

$userCollection = $db->users;
$user = $userCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newName = trim($_POST['name']);
    $newPassword = trim($_POST['password']);

    $updateData = ['name' => $newName];

    if (!empty($newPassword) && strlen($newPassword) >= 6) {
        $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
    }

    $userCollection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])],
        ['$set' => $updateData]
    );

    echo "✅ Profile updated!";
}

?>

<form method="POST">
    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
    <input type="password" name="password" placeholder="New Password (optional)">
    <button type="submit">Update</button>
</form>
