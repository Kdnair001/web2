<?php 
require 'db.php';

$noticeCollection = $db->notices;

// Fetch all notices, sorted by most recent
$notices = $noticeCollection->find([], ['sort' => ['created_at' => -1]]);

/**
 * Convert URLs in text into clickable links
 */
function makeLinks($text) {
    return preg_replace(
        '/(https?:\/\/[^\s]+)/',
        '<a href="$1" target="_blank">$1</a>',
        $text
    );
}
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
        margin: 0;
        padding: 0;
    }
    
    .container {
        width: 80%;
        margin: auto;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
        text-align: left; /* Aligns text properly inside notices */
    }
    
    .notice {
        border-bottom: 1px solid #ddd;
        padding: 10px 0;
        word-wrap: break-word; /* Ensures text doesn't overflow */
        overflow-wrap: break-word;
    }
    
    .notice h3 {
        margin: 0;
        color: #007bff;
        font-size: 1.4em;
    }
    
    .notice p {
        font-size: 1em;
        color: #333;
        margin: 5px 0;
    }
    
    .meta {
        font-size: 0.9em;
        color: #555;
        margin-top: 5px;
        font-style: italic;
    }
    
    /* Fix for long links */
    .notice a {
        word-wrap: break-word;
        overflow-wrap: break-word;
        display: inline-block;
        max-width: 100%;
        white-space: normal;
        color: #007bff;
        text-decoration: underline;
        font-weight: bold;
    }

    /* Responsive Design */
    @media screen and (max-width: 768px) {
        .container {
            width: 95%;
            padding: 15px;
        }

        .notice h3 {
            font-size: 1.2em;
        }

        .notice p {
            font-size: 0.95em;
        }
    }
</style>

</head>
<body>

    <div class="container">
        <h2>Notices</h2>

        <?php foreach ($notices as $notice): ?>
            <div class="notice">
                <h3><?= htmlspecialchars($notice['title'] ?? 'No Title') ?></h3>
                <p><?= nl2br(makeLinks(htmlspecialchars($notice['content'] ?? 'No Content'))) ?></p>
                <p class="meta">
                    Posted by: <?= htmlspecialchars($notice['author'] ?? 'Admin') ?> | 
                    <?= isset($notice['created_at']) ? date("H:i:s, d/m/Y", $notice['created_at']->toDateTime()->setTimezone(new DateTimeZone('Asia/Kolkata'))->getTimestamp()) : 'Unknown Date' ?>
                </p>
            </div>
        <?php endforeach; ?>

    </div>

</body>
</html>
