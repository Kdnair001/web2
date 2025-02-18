<?php
session_start();
require 'db.php';

if (!isset($_SESSION['logged_in']) || !isset($_POST['message_id'])) {
    die("Unauthorized");
}

$messageCollection = $db->messages;
$message = $messageCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_POST['message_id'])]);

if (!$message) {
    die("Message not found!");
}

// Get user details
$userCollection = $db->users;
$user = $userCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

// Allow only the sender or an admin to delete
if ($message['user_id'] == $_SESSION['user_id'] || $user['role'] === 'admin') {
    $messageCollection->deleteOne(['_id' => $message['_id']]);
    echo "✅ Message deleted!";
} else {
    die("❌ You cannot delete this message!");
}
