<?php 
require 'db.php';

$noticeCollection = $db->notices;

// Fetch all notices, sorted by most recent
$notices = $noticeCollection->find([], ['sort' => ['created_at' => -1]]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notices</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .notice {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .notice h3 {
            margin: 0;
            color: #007bff;
        }
        .meta {
            font-size: 0.9em;
            color: #555;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Notices</h2>

        <?php foreach ($notices as $notice): ?>
            <div class="notice">
                <h3><?= htmlspecialchars($notice['title'] ?? 'No Title') ?></h3>
                <p><?= nl2br(htmlspecialchars($notice['content'] ?? 'No Content')) ?></p>
                <p class="meta">
              Posted by: <?= htmlspecialchars($notice['author'] ?? 'Admin') ?> | <?= isset($notice['created_at']) ? date("H:i:s, d/m/Y", $notice['created_at']->toDateTime()->setTimezone(new DateTimeZone('Asia/Kolkata'))->getTimestamp()) : 'Unknown Date' ?>
                </p>
            </div>
        <?php endforeach; ?>

    </div>

</body>
</html>

