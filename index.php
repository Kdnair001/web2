<?php
ob_start(); // Start output buffering to prevent "headers already sent" error
session_start();
require 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user details
$userCollection = $db->users;
$user = $userCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

if (!$user) {
    header("Location: logout.php"); // Logout if user is invalid
    exit();
}

ob_end_flush(); // Send output buffer
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
</head>
<body>
    <header>
        <h1>Welcome, <?= htmlspecialchars($user['name']) ?></h1>
    </header>
    <nav>
        <ul>
            <li><a href="chatroom.php">Chatroom</a></li>
            <li><a href="#">Syllabus</a></li>
            <li><a href="#">College</a></li>
            <li class="dropdown">
                <a href="#" class="dropbtn">Activities</a>
                <div class="dropdown-content">
                    <a href="#">Sports</a>
                    <a href="#">Cultural</a>
                    <a href="#">Workshops</a>
                </div>
            </li>
            <li><a href="#">Departments</a></li>
            <li><a href="#">CESA</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <main>
        <section id="notice-board">Notice Board</section>
        <section id="content">Main Content Area</section>
        <section>
            <a href="user_dashboard.php">User Dashboard</a>
            <?php if ($user['role'] === 'admin'): ?>
                | <a href="admin_dashboard.php">Admin Dashboard</a>
            <?php endif; ?>
        </section>
    </main>
    <footer>
        About, Contact, Site Owner Details
    </footer>
</body>
</html>

