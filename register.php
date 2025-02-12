<?php
session_start();
require 'db.php'; // Include database connection

$message = "";

if (!isset($db)) { // Check if database connection failed
    die("❌ Database connection failed.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($name) || empty($email) || empty($password)) {
        $message = "❌ All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "❌ Invalid email format!";
    } elseif (strlen($password) < 6) {
        $message = "❌ Password must be at least 6 characters long!";
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $collection = $db->selectCollection('users'); // Fix issue here

        // Check if email already exists
        if ($collection->findOne(['email' => $email])) {
            $message = "❌ Email already exists!";
        } else {
            $result = $collection->insertOne([
                'name' => $name,
                'email' => $email,
                'password' => $passwordHash,
                'role' => 'user', // Default role
                'created_at' => new MongoDB\BSON\UTCDateTime()
            ]);

            if ($result->getInsertedCount() > 0) {
                $message = "✅ Registration successful! <a href='login.php'>Login</a>";
            } else {
                $message = "❌ Registration failed. Please try again.";
            }
        }
    }
}
?>
