<?php
ob_start(); // Prevent output before headers

// Ensure session settings are set BEFORE session starts
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    
    // Enable secure cookie only if HTTPS is detected
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        ini_set('session.cookie_secure', 1);
    }

    session_start();
}

session_regenerate_id(true);

require 'vendor/autoload.php';

// Validate Environment Variables
$requiredEnv = ['MONGO_USER', 'MONGO_PASSWORD', 'MONGO_CLUSTER', 'MONGO_DATABASE'];
foreach ($requiredEnv as $env) {
    $value = getenv($env) ?: ($_ENV[$env] ?? null); // Use $_ENV as a fallback
    if (!$value) {
        die("❌ Missing environment variable: $env");
    }
}

// MongoDB Credentials
$username = getenv("MONGO_USER") ?: $_ENV["MONGO_USER"];
$password = getenv("MONGO_PASSWORD") ?: $_ENV["MONGO_PASSWORD"];
$cluster = getenv("MONGO_CLUSTER") ?: $_ENV["MONGO_CLUSTER"];
$database = getenv("MONGO_DATABASE") ?: $_ENV["MONGO_DATABASE"];

// MongoDB Connection
$mongoUri = "mongodb+srv://$username:$password@$cluster/$database?retryWrites=true&w=majority&appName=Cluster0";
try {
    $client = new MongoDB\Client($mongoUri);
    $db = $client->selectDatabase($database);
} catch (Exception $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}

// Generate CSRF Token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("❌ Invalid CSRF token!");
    }

    $email = trim(strtolower($_POST['email'])); // Store email in lowercase
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $collection = $db->users;

        // Case-insensitive email lookup
        $user = $collection->findOne(['email' => new MongoDB\BSON\Regex("^$email$", 'i')]);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = (string)$user['_id'];
            $_SESSION['email'] = $email; // Store email in session
            $_SESSION['role'] = $user['role'];

            // Redirect all users to index.php
            header("Location: index.php");
            exit();
        } else {
            $error = "❌ Invalid email or password!";
        }
    } else {
        $error = "❌ All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Not a member? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>


