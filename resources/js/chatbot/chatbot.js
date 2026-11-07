const chatbot = document.getElementById("chatbot");
const closeChatbot = document.getElementById("closeChatbot");

closeChatbot?.addEventListener("click", () => {
    chatbot.style.display = "none";
});
document.getElementById("openChatbot")?.addEventListener("click", () => {
    chatbot.style.display = "flex";
});

const chatForm = document.getElementById("chatForm");
const chatInput = document.getElementById("chatInput");
const chatMessages = document.getElementById("chatMessages");

function addMessage(message, sender) {
    const messageDiv = document.createElement("div");

    messageDiv.classList.add("chat-message");

    if (sender === "user") {
        messageDiv.classList.add("user-message");
    } else {
        messageDiv.classList.add("bot-message");
    }

    const content = document.createElement("div");

    content.classList.add("message-content");

    const text = document.createElement("p");

    text.textContent = message;

    const time = document.createElement("span");

    time.textContent = new Date().toLocaleTimeString("vi-VN", {
        hour: "2-digit",
        minute: "2-digit",
    });

    content.appendChild(text);
    content.appendChild(time);

    messageDiv.appendChild(content);

    chatMessages.appendChild(messageDiv);

    chatMessages.scrollTop = chatMessages.scrollHeight;
}

async function sendMessage(message) {
    try {
        const response = await fetch("/admin/chatbot/send", {
            method: "POST",

            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",

                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
            },

            body: JSON.stringify({
                message: message,
            }),
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || "Có lỗi xảy ra!");
        }

        // Hiển thị câu trả lời của AI
        addMessage(data.reply, "bot");
    } catch (error) {
        console.error("Lỗi:", error);

        addMessage(
            "Xin lỗi, hiện tại tôi chưa thể xử lý yêu cầu của bạn.",
            "bot",
        );
    }
}

chatForm.addEventListener("submit", async function (e) {
    // Ngăn Form tải lại trang
    e.preventDefault();

    // Lấy nội dung người dùng nhập
    const message = chatInput.value.trim();

    // Kiểm tra tin nhắn rỗng
    if (message === "") {
        return;
    }

    // Hiển thị tin nhắn người dùng
    addMessage(message, "user");

    // Xóa nội dung Input
    chatInput.value = "";

    // Gửi tin nhắn lên Laravel
    await sendMessage(message);
});
