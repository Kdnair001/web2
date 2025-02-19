<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if (isset($_POST['message_id']) && !empty(trim($_POST['message_id']))) {
    $messageId = $_POST['message_id'];

    // Fetch the message from the database to check if the user has permission to delete it
    $messageCollection = $db->messages;
    $message = $messageCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($messageId)]);

    if ($message) {
        // Ensure that the user deleting the message is the one who posted it or an admin
        if ($message['user_id'] == $_SESSION['user_id'] || $user['role'] === 'admin') {
            // Delete the message
            $deleteResult = $messageCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($messageId)]);

            if ($deleteResult->getDeletedCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'Message deleted successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete message']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'You do not have permission to delete this message']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Message not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid message ID']);
}
