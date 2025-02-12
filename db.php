<?php
require 'vendor/autoload.php'; // Load MongoDB PHP driver

// Fetch credentials from Render environment variables
$username = getenv("MONGO_USER");
$password = getenv("MONGO_PASSWORD");
$cluster = getenv("MONGO_CLUSTER");
$database = getenv("MONGO_DATABASE");

// MongoDB connection string
$mongoUri = "mongodb+srv://$username:$password@$cluster/$database?retryWrites=true&w=majority&appName=Cluster0";

try {
    $client = new MongoDB\Client($mongoUri);
    $db = $client->$database; // Select the database
    echo "✅ Connected to MongoDB successfully!";
} catch (Exception $e) {
    die("❌ Connection failed: " . $e->getMessage());
}
?>
