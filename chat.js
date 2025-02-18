document.addEventListener("DOMContentLoaded", () => {
    const chatForm = document.getElementById("chat-form");

    chatForm.addEventListener("submit", (event) => {
        event.preventDefault();
        const messageInput = document.getElementById("message");
        const messageText = messageInput.value.trim();
        
        if (messageText !== "") {
            sendMessage(messageText); // Call the sendMessage function to send the message
            messageInput.value = ""; // Clear the input field
        }
    });
});

function sendMessage(messageText) {
    fetch("send_message.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `message=${encodeURIComponent(messageText)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Append the new message to the chatbox
            const chatBox = document.getElementById("chat-box");
            const messageDiv = document.createElement("div");
            messageDiv.classList.add("message");
            messageDiv.innerHTML = `<strong>${data.username}:</strong> <span id="text-${data.messageId}">${data.message}</span>
            <button onclick="editMessage('${data.messageId}')" class="edit-btn">Edit</button>
            <button onclick="deleteMessage('${data.messageId}')">Delete</button>`;
            chatBox.prepend(messageDiv); // Prepend to add new messages at the top
        } else {
            alert("Failed to send message.");
        }
    })
    .catch(error => {
        console.error("Error sending message:", error);
    });
}

function deleteMessage(messageId) {
    if (confirm("Are you sure you want to delete this message?")) {
        fetch("delete_message.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
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
        .catch(error => {
            console.error("Error deleting message:", error);
        });
    }
}

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
                messageSpan.innerText = newText; // Update the message text
            } else {
                alert("Failed to edit message.");
            }
        })
        .catch(error => {
            console.error("Error editing message:", error);
        });
    }
}
