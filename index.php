<?php
session_start();

// Redirect to login if user is not logged in
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
</head>
<frameset rows="80px, 50px, *, 50px" border="0">
    <!-- Header -->
    <frame src="header.php" name="header" scrolling="no" noresize>

    <!-- Navigation Bar -->
    <frame src="navbar.php" name="navbar" scrolling="no" noresize>

    <frameset cols="25%,75%">
        <!-- Sidebar for Notices -->
        <frame src="notices.php" name="notices" noresize>

        <!-- Main Content -->
        <frame src="main.php" name="main">
    </frameset>

    <!-- Footer -->
    <frame src="footer.php" name="footer" scrolling="no" noresize>
</frameset>
</html>

