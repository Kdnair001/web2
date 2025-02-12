<?php
// Start session only if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'vendor/autoload.php';

// MongoDB Connection
$username = getenv("MONGO_USER");
$password = getenv("MONGO_PASSWORD");
$cluster = getenv("MONGO_CLUSTER");
$database = getenv("MONGO_DATABASE");

$mongoUri = "mongodb+srv://$username:$password@$cluster/$database?retryWrites=true&w=majority";
try {
    $client = new MongoDB\Client($mongoUri);
    $db = $client->$database;
} catch (Exception $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}
?>






