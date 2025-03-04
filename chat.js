document.addEventListener("DOMContentLoaded", () => {
    const chatForm = document.getElementById("chat-form");
    const messageInput = document.getElementById("message");

    fetchMessages(); // Load messages initially
    setInterval(fetchMessages, 2000); // Auto-refresh messages every 2 sec

    chatForm.addEventListener("submit", (event) => {
        event.preventDefault();
        const messageText = messageInput.value.trim();
        
        if (messageText !== "") {
            sendMessage(messageText);
            messageInput.value = "";
        }
    });
});

function fetchMessages() {
    fetch("fetch_messages.php")
        .then(response => response.json())
        .then(messages => {
            const chatBox = document.getElementById("chat-box");
            chatBox.innerHTML = ""; // Clear chatbox to avoid duplicates

            messages.forEach(data => {
                const messageDiv = document.createElement("div");
                messageDiv.classList.add("message");
                messageDiv.id = `message-${data.id}`;

                messageDiv.innerHTML = `
                    <strong>${data.username}:</strong> 
                    <span id="text-${data.id}">${data.message}</span>
                    <span class="timestamp">${data.timestamp}</span>
                    ${data.user_id === getSessionUserId() ? `
                        <button onclick="editMessage('${data.id}')" class="edit-btn">Edit</button>
                        <button onclick="deleteMessage('${data.id}')">Delete</button>
                    ` : ""}
                `;

                chatBox.appendChild(messageDiv);
            });

            scrollToBottom();
        })
        .catch(error => console.error("Error fetching messages:", error));
}

function sendMessage(messageText) {
    fetch("send_message.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `message=${encodeURIComponent(messageText)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            fetchMessages(); // Fetch messages immediately after sending
        } else {
            alert("Failed to send message.");
        }
    })
    .catch(error => console.error("Error sending message:", error));
}

function getSessionUserId() {
    return "<?php echo $_SESSION['user_id']; ?>"; // Inject session user ID into JS
}

function scrollToBottom() {
    const chatBox = document.getElementById("chat-box");
    chatBox.scrollTop = chatBox.scrollHeight;
}
