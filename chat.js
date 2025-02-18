document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("chat-form").addEventListener("submit", function (e) {
        e.preventDefault();
        let message = document.getElementById("message").value;

        fetch("send_message.php", {
            method: "POST",
            body: new URLSearchParams({ message: message }),
            headers: { "Content-Type": "application/x-www-form-urlencoded" }
        }).then(response => response.text()).then(() => {
            location.reload();
        });
    });
});

function deleteMessage(messageId) {
    fetch("delete_message.php", {
        method: "POST",
        body: new URLSearchParams({ message_id: messageId }),
        headers: { "Content-Type": "application/x-www-form-urlencoded" }
    }).then(response => response.text()).then(() => {
        location.reload();
    });
}
