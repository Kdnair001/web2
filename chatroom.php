<?php
session_start();
require 'db.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

$userCollection = $db->users;
$user = $userCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

$messageCollection = $db->messages;
$messages = $messageCollection->find([], ['sort' => ['timestamp' => -1]]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatroom</title>
    <link rel="stylesheet" href="chat.css">
    <script src="chat.js" defer></script>
</head>
<body>
    <h1>Chatroom</h1>
    <div id="chat-box">
        <?php foreach ($messages as $message): ?>
            <div class="message">
                <strong><?= htmlspecialchars($message['username']) ?>:</strong>
                <?= htmlspecialchars($message['message']) ?>
                <?php if ($message['user_id'] == $_SESSION['user_id'] || $user['role'] === 'admin'): ?>
                    <button onclick="deleteMessage('<?= $message['_id'] ?>')">Delete</button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <form id="chat-form">
        <input type="text" name="message" id="message" placeholder="Type your message..." required>
        <button type="submit">Send</button>
    </form>

</body>
</html>
