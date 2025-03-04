document.addEventListener("DOMContentLoaded", () => {
    const chatForm = document.getElementById("chat-form");
    const messageInput = document.getElementById("message");
    const chatBox = document.getElementById("chat-box");

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
        .then(data => {
            if (!data.success) {
                console.error("Error fetching messages:", data.message);
                return;
            }

            const chatBox = document.getElementById("chat-box");
            chatBox.innerHTML = ""; // Clear chatbox to avoid duplicates

            data.messages.forEach(msg => {
                const messageDiv = document.createElement("div");
                messageDiv.classList.add("message");
                messageDiv.id = `message-${msg.messageId}`;

                messageDiv.innerHTML = `
                    <strong>${msg.username}:</strong> 
                    <span id="text-${msg.messageId}">${msg.message}</span>
                    <span class="timestamp">${msg.timestamp}</span>
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

function scrollToBottom() {
    const chatBox = document.getElementById("chat-box");
    chatBox.scrollTop = chatBox.scrollHeight;
}
