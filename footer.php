<!DOCTYPE html>
<html>
<head>
    <title>Bottom Frame</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            color: #fff;
            background: linear-gradient(to right, #000000, #434343);
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 15px;
            background: linear-gradient(to right, #000000, #434343);
            text-align: center;
            z-index: 1000;
        }

        p {
            margin: 5px 0;
        }

        .footer-links {
            list-style-type: none;
            padding: 0;
            margin: 10px 0;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .footer-links a {
            text-decoration: none;
            color: #1E90FF;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: #1c86ee;
        }
    </style>
</head>
<body>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> CTZENOCS. All rights reserved.</p>
        <ul class="footer-links">
            <li><a href="admin.html" target="main">Site Owner Details</a></li>
            <li><a href="about.html" target="main">About Us</a></li>
        </ul>
    </footer>
</body>
</html>

