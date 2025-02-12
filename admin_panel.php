<?php
session_start();
require 'vendor/autoload.php';

// Ensure the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Ensure only the main admin can promote users
if ($_SESSION['email'] !== 'karthikdnair001@gmail.com') {
    die("❌ Access Denied! Only the main admin can add new admins.");
}

// MongoDB Connection
$requiredEnv = ['MONGO_USER', 'MONGO_PASSWORD', 'MONGO_CLUSTER', 'MONGO_DATABASE'];
foreach ($requiredEnv as $env) {
    $value = getenv($env) ?: ($_ENV[$env] ?? null);
    if (!$value) {
        die("❌ Missing environment variable: $env");
    }
}

$username = getenv("MONGO_USER") ?: $_ENV["MONGO_USER"];
$password = getenv("MONGO_PASSWORD") ?: $_ENV["MONGO_PASSWORD"];
$cluster = getenv("MONGO_CLUSTER") ?: $_ENV["MONGO_CLUSTER"];
$database = getenv("MONGO_DATABASE") ?: $_ENV["MONGO_DATABASE"];

$mongoUri = "mongodb+srv://$username:$password@$cluster/$database?retryWrites=true&w=majority&appName=Cluster0";
$client = new MongoDB\Client($mongoUri);
$db = $client->selectDatabase($database);
$collection = $db->users;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);

    if (!empty($email)) {
        $user = $collection->findOne(['email' => new MongoDB\BSON\Regex("^$email$", 'i')]);

        if ($user) {
            if ($user['role'] === 'admin') {
                $message = "⚠️ User is already an admin!";
            } else {
                $collection->updateOne(
                    ['_id' => $user['_id']],
                    ['$set' => ['role' => 'admin']]
                );
                $message = "✅ User promoted to admin successfully!";
            }
        } else {
            $message = "❌ User not found!";
        }
    } else {
        $message = "❌ Email field cannot be empty!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Panel - Add New Admin</h2>
        <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="User Email" required>
            <button type="submit">Promote to Admin</button>
        </form>
        <p><a href="admin_dashboard.php">⬅ Back to Dashboard</a></p>
    </div>
</body>
</html>
