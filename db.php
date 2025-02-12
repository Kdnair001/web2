<?php
require 'vendor/autoload.php';

$username = getenv("MONGO_USER");
$password = getenv("MONGO_PASSWORD");
$cluster = getenv("MONGO_CLUSTER");
$database = getenv("MONGO_DATABASE");

if (!$username || !$password || !$cluster || !$database) {
    die("❌ Missing environment variables. Check Render settings.");
}

echo "✅ Env Variables Loaded:<br>";
echo "MONGO_USER: $username <br>";
echo "MONGO_CLUSTER: $cluster <br>";
echo "MONGO_DATABASE: $database <br>";

$mongoUri = "mongodb+srv://$username:$password@$cluster/$database?retryWrites=true&w=majority";

try {
    $client = new MongoDB\Client($mongoUri);
    echo "✅ Connected to MongoDB successfully!";
} catch (Exception $e) {
    die("❌ Connection failed: " . $e->getMessage());
}
?>


