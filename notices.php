<?php
require 'db.php';

$noticeCollection = $db->notices;
$notices = $noticeCollection->find([], ['sort' => ['posted_at' => -1]]);

foreach ($notices as $notice) {
    // Ensure values exist before using them
    $title = isset($notice['title']) ? htmlspecialchars($notice['title']) : "No Title";
    $message = isset($notice['message']) ? htmlspecialchars($notice['message']) : "No Message";
    $postedBy = isset($notice['posted_by']) ? htmlspecialchars($notice['posted_by']) : "Unknown";
    $postedAt = isset($notice['posted_at']) && $notice['posted_at'] instanceof MongoDB\BSON\UTCDateTime 
                ? $notice['posted_at']->toDateTime()->format('Y-m-d H:i:s') 
                : "Unknown Date";

    echo "<div class='notice'>";
    echo "<h3>$title</h3>";
    echo "<p>$message</p>";
    echo "<small>Posted by: $postedBy | $postedAt</small>";
    echo "</div>";
}
?>

