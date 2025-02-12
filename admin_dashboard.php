<?php
require 'db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$userCollection = $db->users;
$users = $userCollection->find(['role' => 'user']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['user_id'];
    $userCollection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($userId)],
        ['$set' => ['role' => 'admin']]
    );
    echo "✅ User promoted to admin!";
}

?>

<h1>Admin Dashboard</h1>
<form method="POST">
    <select name="user_id">
        <?php foreach ($users as $user): ?>
            <option value="<?= $user['_id'] ?>"><?= htmlspecialchars($user['email']) ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Promote to Admin</button>
</form>
