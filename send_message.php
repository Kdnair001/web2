<?php 
session_start();
require 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$userCollection = $db->users;
$user = $userCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit();
}

if (!isset($_POST['message']) || empty(trim($_POST['message']))) {
    echo json_encode(['success' => false, 'message' => 'Message cannot be empty']);
    exit();
}

$messageText = trim($_POST['message']);

$messageCollection = $db->messages;
$timestamp = new MongoDB\BSON\UTCDateTime(time() * 1000); // Ensure proper timestamp storage

$insertResult = $messageCollection->insertOne([
    'user_id' => (string)$_SESSION['user_id'],
    'username' => htmlspecialchars($user['name']),
    'message' => htmlspecialchars($messageText),
    'timestamp' => $timestamp
]);

$insertedId = (string)$insertResult->getInsertedId();

echo json_encode([
    'success' => true,
    'message' => htmlspecialchars($messageText),
    'username' => htmlspecialchars($user['name']),
    'timestamp' => date('H:i:s d-m-Y'), // Convert UTC to IST for immediate display
    'messageId' => $insertedId
]);
?>
