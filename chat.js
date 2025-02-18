document.addEventListener("DOMContentLoaded", () => {
    const chatForm = document.getElementById("chat-form");

    chatForm.addEventListener("submit", (event) => {
        event.preventDefault();
        const messageInput = document.getElementById("message");
        const messageText = messageInput.value.trim();
        
        if (messageText !== "") {
            sendMessage(messageText);
            messageInput.value = "";
        }
    });
});

function deleteMessage(messageId) {
    if (confirm("Are you sure you want to delete this message?")) {
        fetch(`delete_message.php?id=${messageId}`, { method: "GET" })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`message-${messageId}`).remove();
                } else {
                    alert("Failed to delete message.");
                }
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
                messageSpan.innerText = newText;
            } else {
                alert("Failed to edit message.");
            }
        });
    }
}
