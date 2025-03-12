<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    
    <style>
        /* Reset styles */
        /* Reset styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background: url('your-background.jpg') no-repeat center center fixed;
    background-size: cover;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    align-items: center;
}

/* Header */
header {
    width: 100%;
    background-color: #007bff;
    color: white;
    text-align: center;
    padding: 15px;
    font-size: 24px;
    font-weight: bold;
}

/* Navbar */
nav {
    width: 100%;
    background-color: #222;
    display: flex;
    justify-content: center;
    gap: 20px;
    padding: 12px 0;
}

nav a {
    color: white;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background 0.3s ease;
    font-size: 16px;
}

nav a:hover {
    background: #007bff;
}

/* Main Container */
.container {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
    padding: 20px;
    max-width: 1100px;
    width: 100%;
}

/* Notices Sidebar */
.notices {
    flex: 1;
    background: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
    min-width: 300px;
    max-width: 350px;
    word-wrap: break-word;
}

/* Main Content */
main {
    flex: 2;
    background: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
    min-width: 400px;
    word-wrap: break-word;
}

/* Footer */
footer {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 10px;
    margin-top: auto;
    width: 100%;
    position: relative;
    bottom: 0;
}

/* Responsive Fixes */
@media (max-width: 900px) {
    .container {
        flex-direction: column;
        align-items: center;
    }

    .notices, main {
        width: 90%;
        min-width: unset;
    }
}

    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        CTZENOCS
    </header>

    <!-- Navigation Bar -->
    <nav>
        <a href="index.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <!-- Notices Sidebar -->
        <aside class="notices">
            <h3>Notices</h3>
            <?php include 'notices.php'; ?>
        </aside>

        <!-- Main Section -->
        <main>
            <h2>Welcome, User</h2>
            <?php include 'main.php'; ?>
        </main>
    </div>

    <!-- Footer -->
    <footer>
        &copy; 2025 CTZENOCS. All Rights Reserved.
    </footer>
</body>
</html>
