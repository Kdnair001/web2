<?php
session_start();
require 'db.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $messageId = $data["id"];
    $newMessage = $data["message"];

    $messageCollection = $db->messages;
    $message = $messageCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($messageId)]);

    if ($message) {
        // Ensure session role is correctly checked
        if ($message['user_id'] == $_SESSION['user_id'] || $_SESSION['role'] === 'admin') {
            $messageCollection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($messageId)],
                ['$set' => ['message' => $newMessage]]
            );
            echo json_encode(["success" => true]);
            exit();
        }
    }
}

echo json_encode(["success" => false]);
