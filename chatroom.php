<?php
session_start();
require 'db.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

date_default_timezone_set('Asia/Kolkata'); // Set timezone to IST

$userCollection = $db->users;
$user = $userCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatroom</title>
    <link rel="stylesheet" href="chat.css">
    <script src="chat.js" defer></script>
   <script>
    function fetchMessages(scrollOnFirstLoad = false) {
        fetch("fetch_messages.php")
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error("Error fetching messages:", data.message);
                    return;
                }

                const chatBox = document.getElementById("chat-box");
                const userId = "<?= $_SESSION['user_id'] ?>"; // Get logged-in user ID
                const userRole = "<?= $_SESSION['role'] ?? '' ?>"; // Get user role (if available)
                const isAdmin = userRole === "admin"; // Check if user is admin

                chatBox.innerHTML = ""; // Clear chatbox

                data.messages.forEach(msg => {
                    const messageDiv = document.createElement("div");
                    messageDiv.classList.add("message");
                    messageDiv.id = `message-${msg.messageId}`;

                    messageDiv.innerHTML = `
                        <strong>${msg.username}:</strong> 
                        <span id="text-${msg.messageId}">${msg.message}</span>
                        <span class="timestamp">${msg.timestamp}</span>
                        ${(msg.user_id === userId || isAdmin) ? `
                            <button class="edit-btn" onclick="editMessage('${msg.messageId}', '${msg.message}')">✏️ Edit</button>
                            <button class="delete-btn" onclick="deleteMessage('${msg.messageId}')">🗑️ Delete</button>
                        ` : ""}
                    `;

                    chatBox.appendChild(messageDiv);
                });

                if (scrollOnFirstLoad) {
                    chatBox.scrollTop = chatBox.scrollHeight; // Scroll to bottom only on first load
                }
            })
            .catch(error => console.error("Error fetching messages:", error));
    }
</script>


</head>
<body>
    <div id="chat-container">
        <h1>Chatroom</h1>
        <a href="index.php" target="_parent">🏠 Back to Home</a>
        
        <div id="chat-box"></div>

        <form id="chat-form">
            <input type="text" name="message" id="message" placeholder="Type your message..." required>
            <button type="submit">Send</button>
        </form>
    </div>
</body>
</html>
