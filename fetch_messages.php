<?php
session_start();
require 'db.php';

date_default_timezone_set('Asia/Kolkata'); // Set timezone to IST

$messageCollection = $db->messages;

// Fetch latest 50 messages sorted in ascending order (oldest to newest)
$messages = $messageCollection->find([], [
    'limit' => 50,
    'sort' => ['timestamp' => 1] // Sorting in ascending order
]);

$response = ['success' => true, 'messages' => []];

foreach ($messages as $message) {
    $response['messages'][] = [
        'messageId' => (string)$message['_id'], 
        'username' => htmlspecialchars($message['username']),
        'message' => htmlspecialchars($message['message']),
        'timestamp' => isset($message['timestamp']) && $message['timestamp'] instanceof MongoDB\BSON\UTCDateTime 
            ? date('H:i:s d-m-Y', $message['timestamp']->toDateTime()->getTimestamp()) 
            : 'Unknown Time',
        'user_id' => $message['user_id']
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
