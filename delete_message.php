<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || !isset($_POST['message_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$messageCollection = $db->messages;
$message = $messageCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_POST['message_id'])]);

if (!$message) {
    echo json_encode(['success' => false, 'message' => 'Message not found']);
    exit();
}

// Get user details
$userCollection = $db->users;
$user = $userCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

// Allow only the sender or an admin to delete
if ($message['user_id'] == $_SESSION['user_id'] || $user['role'] === 'admin') {
    $messageCollection->deleteOne(['_id' => $message['_id']]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'You cannot delete this message']);
}
?>
