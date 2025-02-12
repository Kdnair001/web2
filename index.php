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
            background: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }

        /* Navigation */
        nav {
            background: #333;
            padding: 10px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        nav ul {
            list-style: none;
            display: flex;
            padding: 0;
            margin: 0;
        }

        nav ul li {
            margin: 0 10px;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            padding: 10px;
            background: #007bff;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        nav ul li a:hover {
            background: #0056b3;
        }

        /* Main Container */
        .container {
            display: flex;
            margin: 20px;
        }

        /* Sidebar */
        .sidebar {
            width: 25%;
            background: white;
            padding: 15px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Content */
        .content {
            flex: 1;
            background: white;
            padding: 20px;
            margin-left: 20px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Footer */
        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
        }

        /* Top Dashboard Links */
        .dashboard-links {
            text-align: center;
            margin: 10px 0;
        }

        .dashboard-links a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            padding: 10px;
            background: #28a745;
            border-radius: 5px;
            margin: 0 10px;
            display: inline-block;
            transition: background 0.3s ease;
        }

        .dashboard-links a:hover {
            background: #218838;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        Site Name
    </header>

    <!-- User and Admin Dashboard Links -->
    <div class="dashboard-links">
        <a href="user_dashboard.php">User Dashboard</a>
        <a href="admin_dashboard.php">Admin Dashboard</a>
    </div>

    <!-- Navigation Bar -->
    <nav>
        <ul>
            <li><a href="#">Chatroom</a></li>
            <li><a href="#">Syllabus</a></li>
            <li><a href="#">College</a></li>
            <li><a href="#">Activities</a></li>
            <li><a href="#">Departments</a></li>
            <li><a href="#">CESA</a></li>
            <li><a href="#">Logout</a></li>
        </ul>
    </nav>

    <!-- Main Layout -->
    <div class="container">
        <aside class="sidebar">
            <h2>Notice Board</h2>
            <p>Latest updates and announcements.</p>
        </aside>

        <main class="content">
            <h2>Welcome, Karthik D Nair</h2>
            <p>Main Content Area</p>
        </main>
    </div>

    <!-- Footer -->
    <footer>
        <p>About, Contact, Site Owner Details</p>
    </footer>

</body>
</html>
