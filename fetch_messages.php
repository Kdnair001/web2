<?php
session_start();
require 'db.php';

date_default_timezone_set('Asia/Kolkata'); // Set timezone to IST

$messageCollection = $db->messages;

// Fetch messages (oldest to newest)
$messages = $messageCollection->find([], [
    'sort' => ['timestamp' => 1], // Sorting by timestamp in ascending order
]);

$response = ['success' => true, 'messages' => []];

foreach ($messages as $message) {
    $timestamp = isset($message['timestamp']) && $message['timestamp'] instanceof MongoDB\BSON\UTCDateTime
        ? $message['timestamp']->toDateTime()->format('H:i:s d-m-Y')
        : 'Unknown Time';

    $response['messages'][] = [
        'messageId' => (string)$message['_id'],
        'username' => htmlspecialchars($message['username']),
        'message' => htmlspecialchars($message['message']),
        'timestamp' => $timestamp,
        'user_id' => (string)$message['user_id']
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
