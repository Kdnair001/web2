<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $collection = $db->users;
    $user = $collection->findOne(['email' => $email]);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = (string)$user['_id'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: admin_dashboard.php"); // Redirect admin
        } else {
            header("Location: user_dashboard.php"); // Redirect normal user
        }
        exit();
    } else {
        echo "❌ Invalid email or password!";
    }
}
?>

<form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

<!-- Add Register Link -->
<p>Not a member? <a href="register.php">Register here</a></p>

