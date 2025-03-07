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

if (isset($_POST['message']) && !empty(trim($_POST['message']))) {
    $messageText = trim($_POST['message']);

    $messageCollection = $db->messages;
    $timestamp = new MongoDB\BSON\UTCDateTime();

    // Insert the new message with timestamp
    $insertResult = $messageCollection->insertOne([
        'user_id' => $_SESSION['user_id'],
        'username' => $user['name'],
        'message' => $messageText,
        'timestamp' => $timestamp
    ]);

    // Fetch the inserted message's ID to return to the client
    $insertedId = (string)$insertResult->getInsertedId();

    // Return the message data as JSON for the client to display
    echo json_encode([
        'success' => true,
        'message' => $messageText,
        'username' => $user['name'],
        'timestamp' => $timestamp->toDateTime()->format('Y-m-d H:i:s'),
        'messageId' => $insertedId
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Message cannot be empty']);
}
