<?php
require 'db.php';

// Fetch latest notices
$noticeCollection = $db->notices;
$notices = $noticeCollection->find([], ['sort' => ['posted_at' => -1], 'limit' => 5]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body { font-family: Arial, sans-serif; padding: 15px; background: #f4f4f4; }
        .notice { background: white; padding: 10px; border-radius: 5px; margin-bottom: 10px; box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1); }
        .notice h3 { margin: 0; font-size: 16px; }
        .notice p { margin: 5px 0; }
        .notice small { color: gray; }
    </style>
</head>
<body>
    <h2>📢 Notices & Announcements</h2>

    <?php if ($notices->isDead()): ?>
        <p>No notices available.</p>
    <?php else: ?>
        <?php foreach ($notices as $notice): ?>
            <div class="notice">
                <h3><?= htmlspecialchars($notice['title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($notice['message'])) ?></p>
                <small>Posted by: <?= htmlspecialchars($notice['posted_by']) ?> | <?= date('d M Y, H:i', $notice['posted_at']->toDateTime()->getTimestamp()) ?></small>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
