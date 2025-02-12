<?php
session_start();
require 'db.php';

// Ensure only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['email'] !== 'karthikdnair001@gmail.com') {
    header("Location: login.php");
    exit();
}

$noticeCollection = $db->notices;
$successMessage = "";
$errorMessage = "";

// Handle Notice Posting
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_notice'])) {
    $noticeTitle = trim($_POST['title']);
    $noticeMessage = trim($_POST['message']);

    if (empty($noticeTitle) || empty($noticeMessage)) {
        $errorMessage = "❌ Title and message cannot be empty!";
    } else {
        $noticeCollection->insertOne([
            'title' => htmlspecialchars($noticeTitle),
            'message' => htmlspecialchars($noticeMessage),
            'posted_by' => $_SESSION['name'],
            'posted_at' => new MongoDB\BSON\UTCDateTime()
        ]);
        $successMessage = "✅ Notice posted successfully!";
    }
}
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
        input, textarea, button {
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
        <h2>Admin Dashboard</h2>
        <hr>

        <h3>Post a Notice</h3>
        <?php if (!empty($errorMessage)) echo "<p class='error'>$errorMessage</p>"; ?>
        <?php if (!empty($successMessage)) echo "<p class='success'>$successMessage</p>"; ?>

        <form method="POST">
            <input type="text" name="title" placeholder="Notice Title" required>
            <textarea name="message" placeholder="Notice Message" rows="4" required></textarea>
            <button type="submit" name="post_notice">Post Notice</button>
        </form>

        <p><a href="index.php">Back to Home</a> | <a href="logout.php">Logout</a></p>
    </div>

</body>
</html>
