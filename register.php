<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $collection = $db->users;

    // Check if email is already registered
    if ($collection->findOne(['email' => $email])) {
        echo "❌ Email already exists!";
    } else {
        // Insert new user (default role: user)
        $collection->insertOne([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => 'user' // Normal user by default
        ]);
        echo "✅ Registration successful! <a href='login.php'>Login</a>";
    }
}
?>

<form method="POST">
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Register</button>
</form>
