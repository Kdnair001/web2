<?php
session_start();
require 'db.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

$userCollection = $db->users;
$user = $userCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

// CSRF Token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$successMessage = "";
$errorMessage = "";

// Handle Profile Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("❌ Invalid CSRF token!");
    }

    $newName = trim($_POST['name']);
    $newPassword = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    if (empty($newName)) {
        $errorMessage = "❌ Name cannot be empty!";
    } elseif (!empty($newPassword) && strlen($newPassword) < 6) {
        $errorMessage = "❌ Password must be at least 6 characters!";
    } elseif (!empty($newPassword) && $newPassword !== $confirmPassword) {
        $errorMessage = "❌ Passwords do not match!";
    } else {
        $updateData = ['name' => $newName];

        if (!empty($newPassword)) {
            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $userCollection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])],
            ['$set' => $updateData]
        );

        $_SESSION['name'] = $newName;
        $successMessage = "✅ Profile updated successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        .container {
            margin-top: 50px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin: auto;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .success {
            color: green;
            font-weight: bold;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Welcome, <?= htmlspecialchars($user['name']) ?></h2>
        <p>Email: <?= htmlspecialchars($user['email']) ?></p>
        <hr>

        <h3>Update Profile</h3>
        <?php if (!empty($errorMessage)) echo "<p class='error'>$errorMessage</p>"; ?>
        <?php if (!empty($successMessage)) echo "<p class='success'>$successMessage</p>"; ?>
        
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
            <input type="password" name="password" placeholder="New Password (optional)">
            <input type="password" name="confirm_password" placeholder="Confirm New Password">
            <button type="submit" name="update_profile">Update Profile</button>
        </form>

        <p><a href="index.php">Back to Home</a> | <a href="logout.php">Logout</a></p>
    </div>

</body>
</html>
