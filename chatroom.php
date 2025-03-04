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
        function fetchMessages() {
            fetch("fetch_messages.php")
                .then(response => response.json())
                .then(data => {
                    let chatBox = document.getElementById("chat-box");
                    chatBox.innerHTML = ""; // Clear chat box
                    
                    data.forEach(msg => {
                        let messageDiv = document.createElement("div");
                        messageDiv.classList.add("message");

                        if (msg.user_id === "<?= $_SESSION['user_id'] ?>") {
                            messageDiv.classList.add("user");
                        }

                        messageDiv.innerHTML = `
                            <strong>${msg.username}:</strong> 
                            <span>${msg.message}</span>
                            <span class="timestamp">${msg.timestamp}</span>
                        `;
                        
                        chatBox.appendChild(messageDiv);
                    });

                    chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll to bottom
                })
                .catch(error => console.error("Error fetching messages:", error));
        }

        // Fetch messages every 2 seconds without refreshing the page
        setInterval(fetchMessages, 2000);

        window.onload = fetchMessages;
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
