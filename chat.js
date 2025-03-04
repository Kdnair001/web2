document.addEventListener("DOMContentLoaded", () => {
    const chatForm = document.getElementById("chat-form");
    const messageInput = document.getElementById("message");
    const chatBox = document.getElementById("chat-box");

    fetchMessages(true); // Load messages and scroll on first load
    setInterval(() => fetchMessages(false), 2000); // Fetch every 2s without auto-scrolling

    chatForm.addEventListener("submit", (event) => {
        event.preventDefault();
        const messageText = messageInput.value.trim();
        
        if (messageText !== "") {
            sendMessage(messageText);
            messageInput.value = "";
        }
    });
});

function fetchMessages(scrollOnFirstLoad = false) {
    fetch("fetch_messages.php")
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error("Error fetching messages:", data.message);
                return;
            }

            const chatBox = document.getElementById("chat-box");
            chatBox.innerHTML = ""; // Clear chatbox before appending new messages

            data.messages.forEach(msg => {
                const messageDiv = document.createElement("div");
                messageDiv.classList.add("message");
                messageDiv.id = `message-${msg.messageId}`;

                messageDiv.innerHTML = `
                    <strong>${msg.username}:</strong> 
                    <span id="text-${msg.messageId}">${msg.message}</span>
                    <span class="timestamp">(${msg.timestamp})</span>
                `;

                chatBox.appendChild(messageDiv);
            });

            if (scrollOnFirstLoad) {
                chatBox.scrollTop = chatBox.scrollHeight; // Keep chat scrolled to bottom
            }
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
            fetchMessages(true); // Fetch messages and scroll after sending
        } else {
            alert("Failed to send message.");
        }
    })
    .catch(error => console.error("Error sending message:", error));
}

function editMessage(messageId, oldMessage) {
    const newMessage = prompt("Edit your message:", oldMessage);
    
    if (newMessage !== null && newMessage.trim() !== "") {
        fetch("edit_message.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id: messageId, message: newMessage })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`text-${messageId}`).innerText = newMessage; // Update text instantly
            } else {
                alert("Failed to edit message.");
            }
        })
        .catch(error => console.error("Error editing message:", error));
    }
}

function deleteMessage(messageId) {
    if (confirm("Are you sure you want to delete this message?")) {
        fetch("delete_message.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `message_id=${messageId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`message-${messageId}`).remove(); // Remove message instantly
            } else {
                alert("Failed to delete message.");
            }
        })
        .catch(error => console.error("Error deleting message:", error));
    }
}
