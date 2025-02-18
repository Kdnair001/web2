<?php
session_start();
require 'db.php';

if (!isset($_SESSION['logged_in'])) {
    die("Unauthorized");
}

$userCollection = $db->users;
$user = $userCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

$messageCollection = $db->messages;
$messageCollection->insertOne([
    'user_id' => $_SESSION['user_id'],
    'username' => $user['name'],
    'message' => $_POST['message'],
    'timestamp' => new MongoDB\BSON\UTCDateTime()
]);

echo "✅ Message sent!";
