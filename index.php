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
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Header */
        header {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 20px;
        }

        /* Navbar */
        nav {
            background-color: #222;
            display: flex;
            justify-content: center;
            gap: 15px;
            padding: 10px;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        nav a:hover {
            background: #007bff;
        }

        /* Main Layout */
        .container {
            display: flex;
            flex-grow: 1;
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: auto;
        }

        /* Sidebar (Notices) */
        .notices {
            flex: 1;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
            min-width: 250px;
        }

        /* Main Content */
        main {
            flex: 3;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
        }

        /* Footer */
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: auto;
        }

        /* Responsive Design */
        @media (max-width: 900px) {
            .container {
                flex-direction: column;
            }

            .notices, main {
                width: 100%;
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
        <a href="index.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
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
