document.addEventListener("DOMContentLoaded", () => {
    const chatForm = document.getElementById("chat-form");
    const chatBox = document.getElementById("chat-box");
    const messageInput = document.getElementById("message");

    // Fetch messages every second for real-time updates
    setInterval(fetchMessages, 1000);

    // Handle message sending
    chatForm.addEventListener("submit", (event) => {
        event.preventDefault();
        const messageText = messageInput.value.trim();

        if (messageText !== "") {
            sendMessage(messageText);
            messageInput.value = ""; // Clear input field after sending
        }
    });
});

// Function to send a new message
function sendMessage(messageText) {
    fetch("send_message.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `message=${encodeURIComponent(messageText)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Append the new message dynamically
            appendMessage(data.messageId, data.username, data.message, data.timestamp);
        } else {
            alert("Failed to send message.");
        }
    })
    .catch(error => {
        console.error("Error sending message:", error);
    });
}

// Function to fetch messages dynamically
function fetchMessages() {
    fetch("fetch_messages.php")
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const chatBox = document.getElementById("chat-box");
            chatBox.innerHTML = ""; // Clear existing messages

            data.messages.forEach(msg => {
                appendMessage(msg.messageId, msg.username, msg.message, msg.timestamp);
            });

            chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll to latest message
        }
    })
    .catch(error => console.error("Error fetching messages:", error));
}

// Function to append a message dynamically
function appendMessage(messageId, username, message, timestamp) {
    const chatBox = document.getElementById("chat-box");

    // Prevent duplicate messages
    if (document.getElementById(`message-${messageId}`)) return;

    const messageDiv = document.createElement("div");
    messageDiv.classList.add("message");
    messageDiv.id = `message-${messageId}`;

    messageDiv.innerHTML = `
        <strong>${username}:</strong> 
        <span id="text-${messageId}">${message}</span>
        <span class="timestamp">${timestamp}</span>
        <button onclick="editMessage('${messageId}')" class="edit-btn">Edit</button>
        <button onclick="deleteMessage('${messageId}')">Delete</button>
    `;

    chatBox.appendChild(messageDiv); // Add at the bottom for real-time chat
}

// Function to delete a message
function deleteMessage(messageId) {
    if (confirm("Are you sure you want to delete this message?")) {
        fetch("delete_message.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `message_id=${encodeURIComponent(messageId)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`message-${messageId}`).remove();
            } else {
                alert("Failed to delete message.");
            }
        })
        .catch(error => console.error("Error deleting message:", error));
    }
}

// Function to edit a message
function editMessage(messageId) {
    const messageSpan = document.getElementById(`text-${messageId}`);
    const currentText = messageSpan.innerText;
    const newText = prompt("Edit your message:", currentText);

    if (newText !== null && newText.trim() !== "") {
        fetch("edit_message.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id: messageId, message: newText })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageSpan.innerText = newText; // Update message text dynamically
            } else {
                alert("Failed to edit message.");
            }
        })
        .catch(error => console.error("Error editing message:", error));
    }
}
