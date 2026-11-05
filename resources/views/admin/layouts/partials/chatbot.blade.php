<button class="chatbot-btn" id="openChatbot">
    <img src="{{ asset('/build/assets/image/trolyao.png') }}">
</button>

<div class="box-chatbot" id="chatbot">
    <!-- Header -->
    <div class="chatbot-header">
        <div class="chatbot-info">
            <div class="chatbot-avatar">
                <img src="{{ asset('/build/assets/image/trolyao.png') }}"
                    class="tro_ly_ao" alt="Trợ lý ảo">
                <span class="online-dot"></span>
            </div>

            <div>
                <h2>Trợ lý thông minh</h2>
                <p><span></span> Đang hoạt động</p>
            </div>
        </div>

        <button type="button" class="chatbot-close" id="closeChatbot">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- Nội dung chat -->
    <div class="chatbot-body" id="chatMessages">
        <div class="chat-date">Hôm nay</div>

        <div class="chat-message bot-message">
            <img src="{{ asset('/build/assets/image/trolyao.png') }}"
                alt="Bot">

            <div class="message-content">
                <p>Xin chào! 👋</p>
                <p>Tôi là trợ lý thông minh. Tôi có thể giúp gì cho bạn hôm nay?</p>
                <span>Vừa xong</span>
            </div>
        </div>

        <div class="chat-suggestions">
            <p>Bạn có thể hỏi tôi:</p>
            <button type="button">Thông tin phòng trọ</button>
            <button type="button">Quản lý người thuê</button>
            <button type="button">Hóa đơn và thanh toán</button>
        </div>
    </div>

    <!-- Nhập tin nhắn -->
    <form class="chatbot-footer" id="chatForm">
        <input type="text" id="chatInput"
            placeholder="Nhập tin nhắn của bạn..."
            autocomplete="off">

        <button type="submit" aria-label="Gửi tin nhắn">
            <i class="fa-solid fa-paper-plane"></i>
        </button>
    </form>

    <div class="chatbot-powered">
        Được hỗ trợ bởi <strong>Trợ lý AI</strong>
    </div>
</div>
