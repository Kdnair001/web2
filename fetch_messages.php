<?php
session_start();
require 'db.php';

date_default_timezone_set('Asia/Kolkata'); // Set timezone to IST

$messageCollection = $db->messages;
$messages = $messageCollection->find([], [
    'limit' => 20,
    'sort' => ['timestamp' => -1]
]);

$messages = iterator_to_array($messages);
$messages = array_reverse($messages);

$response = [];

foreach ($messages as $message) {
    $response[] = [
        'id' => (string)$message['_id'],
        'username' => htmlspecialchars($message['username']),
        'message' => htmlspecialchars($message['message']),
        'timestamp' => $message['timestamp'] instanceof MongoDB\BSON\UTCDateTime
            ? date('H:i:s d-m-Y', $message['timestamp']->toDateTime()->getTimestamp())
            : 'Invalid Time',
        'user_id' => $message['user_id']
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
