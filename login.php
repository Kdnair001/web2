<?php
ob_start(); // Prevent output before headers
session_start();
require 'vendor/autoload.php';

// MongoDB Credentials from Environment Variables
$username = getenv("MONGO_USER");
$password = getenv("MONGO_PASSWORD");
$cluster = getenv("MONGO_CLUSTER");
$database = getenv("MONGO_DATABASE");

// MongoDB Connection
$mongoUri = "mongodb+srv://$username:$password@$cluster/$database?retryWrites=true&w=majority&appName=Cluster0";
try {
    $client = new MongoDB\Client($mongoUri);
    $db = $client->$database;
} catch (Exception $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}

// Login Logic
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $collection = $db->users;
        $user = $collection->findOne(['email' => $email]);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = (string)$user['_id'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            header("Location: " . ($user['role'] === 'admin' ? "admin_dashboard.php" : "user_dashboard.php"));
            exit();
        } else {
            $error = "❌ Invalid email or password!";
        }
    } else {
        $error = "❌ All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            width: 350px;
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
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Not a member? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>

