<?php
session_start();
require 'db.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

$userCollection = $db->users;
$user = $userCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

// Ensure $messageCollection is defined
$messageCollection = $db->messages;

// Limit the number of messages to avoid overloading the page
$limit = 20;
$messages = $messageCollection->find([], [
    'limit' => $limit,
    'sort' => ['timestamp' => -1]
]);

// Reverse the order so the latest messages appear at the bottom
$messages = iterator_to_array($messages);
$messages = array_reverse($messages);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatroom</title>
    <link rel="stylesheet" href="chat.css">
    <script src="chat.js" defer></script>
    <script>
        function autoRefresh() {
            setTimeout(function() {
                location.reload();
            }, 5000); // Refresh every 5 seconds
        }
        window.onload = autoRefresh;
    </script>
</head>
<body>
    <div id="chat-container">
        <h1>Chatroom</h1>
        <a href="index.php" class="back-home">Go Back to Home</a>
        
        <div id="chat-box">
            <?php foreach ($messages as $message): ?>
                <div class="message <?= $message['user_id'] == $_SESSION['user_id'] ? 'user' : ($user['role'] === 'admin' ? 'admin' : '') ?>" id="message-<?= (string)$message['_id'] ?>">
                    <strong><?= htmlspecialchars($message['username']) ?>:</strong>
                    <span id="text-<?= (string)$message['_id'] ?>"><?= htmlspecialchars($message['message']) ?></span>
                    <span class="timestamp">
                        <?= date('H:i:s d-m-Y', strtotime($message['timestamp'])) ?>
                    </span>
                    
                    <?php if ($message['user_id'] == $_SESSION['user_id'] || $user['role'] === 'admin'): ?>
                        <button onclick="editMessage('<?= (string)$message['_id'] ?>')" class="edit-btn">Edit</button>
                        <button onclick="deleteMessage('<?= (string)$message['_id'] ?>')">Delete</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <form id="chat-form">
            <input type="text" name="message" id="message" placeholder="Type your message..." required>
            <button type="submit">Send</button>
        </form>
    </div>
</body>
</html>
