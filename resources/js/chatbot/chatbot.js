const chatbot = document.getElementById('chatbot');
const closeChatbot = document.getElementById('closeChatbot');

closeChatbot?.addEventListener('click', () => {
    chatbot.style.display = 'none';
});
document.getElementById('openChatbot')?.addEventListener('click', () => {
    chatbot.style.display = 'flex';
});