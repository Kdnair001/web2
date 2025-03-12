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
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        /* Main Container */
        .container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Header & Footer */
        .header, .footer {
            background: #007bff;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 1.2em;
        }

        /* Navbar */
        .navbar {
            background: #333;
            color: white;
            text-align: center;
            padding: 10px;
        }

        /* Content Layout */
        .content {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* Sidebar (Notices Section) */
        .sidebar {
            width: 25%;
            background: #f4f4f4;
            padding: 15px;
            overflow-y: auto;
            border-right: 2px solid #ddd;
            transition: all 0.3s ease;
        }

        /* Main Content */
        .main {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Responsive Design */
        @media screen and (max-width: 1024px) { /* Tablets */
            .sidebar {
                width: 30%;
            }
        }

        @media screen and (max-width: 768px) { /* Mobile */
            .content {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 2px solid #ddd;
            }

            .main {
                padding: 10px;
            }
        }

        @media screen and (max-width: 480px) { /* Small Mobile */
            .header, .footer {
                font-size: 1em;
                padding: 10px;
            }

            .navbar {
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Header -->
    <div class="header">
        <?php include 'header.php'; ?>
    </div>

    <!-- Navigation Bar -->
    <div class="navbar">
        <?php include 'navbar.php'; ?>
    </div>

    <!-- Main Content Area -->
    <div class="content">
        <!-- Sidebar (Notices Section) -->
        <div class="sidebar">
            <iframe src="notices.php"></iframe>
        </div>

        <!-- Main Content -->
        <div class="main">
            <iframe src="main.php"></iframe>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <?php include 'footer.php'; ?>
    </div>
</div>

</body>
</html>
