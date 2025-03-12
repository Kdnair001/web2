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
    
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Header */
        header {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 10px;
        }

        /* Navbar */
        nav {
            background-color: black;
            padding: 10px;
            text-align: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 10px;
            margin: 5px;
            display: inline-block;
        }

        /* Layout Container */
        .container {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: space-between;
            margin: 20px;
        }

        /* Sidebar (Notices) */
        .notices {
            flex: 1;
            min-width: 250px;
            background: white;
            padding: 15px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
        }

        /* Main Content */
        main {
            flex: 3;
            background: white;
            padding: 15px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
        }

        /* Footer */
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .notices, main {
                width: 100%;
            }

            nav a {
                display: block;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <?php include 'header.php'; ?>
    </header>

    <!-- Navigation Bar -->
    <nav>
        <?php include 'navbar.php'; ?>
    </nav>

    <div class="container">
        <!-- Sidebar for Notices -->
        <aside class="notices">
            <?php include 'notices.php'; ?>
        </aside>

        <!-- Main Content Area -->
        <main>
            <?php include 'main.php'; ?>
        </main>
    </div>

    <!-- Footer -->
    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>
</html>
