
document.addEventListener('DOMContentLoaded', () => {

    // Lấy tất cả các dropdown thao tác hợp đồng
    const dropdowns = document.querySelectorAll('.contract-actions-dropdown');

    // Đóng tất cả menu
    function closeAllDropdowns() {
        dropdowns.forEach(dropdown => {
            const button = dropdown.querySelector('.btn-contract-menu-toggle');
            const menu = dropdown.querySelector('.contract-actions-menu');

            menu.hidden = true;
            button.setAttribute('aria-expanded', 'false');
        });
    }

    // Xử lý nhấn nút 3 chấm
    dropdowns.forEach(dropdown => {
        const button = dropdown.querySelector('.btn-contract-menu-toggle');
        const menu = dropdown.querySelector('.contract-actions-menu');

        button.addEventListener('click', (event) => {
            event.stopPropagation();

            const isOpening = menu.hidden;

            // Đóng các menu khác trước
            closeAllDropdowns();

            // Nếu menu đang đóng thì mở
            if (isOpening) {
                menu.hidden = false;
                button.setAttribute('aria-expanded', 'true');
            }
        });
    });

    // Nhấn bên ngoài dropdown thì đóng menu
    document.addEventListener('click', (event) => {
        if (!event.target.closest('.contract-actions-dropdown')) {
            closeAllDropdowns();
        }
    });

    // Nhấn Escape thì đóng menu
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeAllDropdowns();
        }
    });

});
