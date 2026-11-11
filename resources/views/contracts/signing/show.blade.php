<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ký hợp đồng thuê nhà</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .header {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }

        .header h2 {
            color: #1d4ed8;
            margin-bottom: 12px;
        }

        .header p {
            margin-top: 8px;
            color: #475569;
        }

        .contract-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .info-item {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
        }

        .info-item span {
            display: block;
            color: #64748b;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .info-item strong {
            color: #0f172a;
        }

        .pdf-section,
        .signature-section {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            margin-bottom: 20px;
        }

        .pdf-section h3,
        .signature-section h3 {
            margin-bottom: 15px;
        }

        .pdf-viewer {
            width: 100%;
            height: 750px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
        }

        .pdf-link {
            display: inline-block;
            margin-top: 12px;
            color: #2563eb;
            text-decoration: none;
        }

        .signature-description {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .signature-canvas {
            display: block;
            width: 100%;
            height: 220px;
            border: 2px dashed #94a3b8;
            border-radius: 8px;
            background: #fff;
            cursor: crosshair;
            touch-action: none;
        }

        .signature-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .signature-actions button {
            border: none;
            border-radius: 7px;
            padding: 12px 20px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-clear {
            background: #e2e8f0;
            color: #334155;
        }

        .btn-submit {
            background: #2563eb;
            color: #fff;
            font-weight: 600;
        }

        .btn-submit:hover {
            background: #1d4ed8;
        }

        @media (max-width: 600px) {
            .contract-info {
                grid-template-columns: 1fr;
            }

            .pdf-viewer {
                height: 550px;
            }

            .signature-actions {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        @if ($errors->any())
        <div style="
        background: #fee2e2;
        color: #991b1b;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    ">
            <strong>Không thể xác nhận hợp đồng:</strong>

            <ul style="margin: 10px 0 0 20px;">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="header">

            <h2>KÝ HỢP ĐỒNG THUÊ NHÀ</h2>

            <p>Xin chào: <strong>{{ $representative->full_name }}</strong></p>

            <p>Vui lòng kiểm tra kỹ nội dung hợp đồng trước khi tiến hành ký.</p>

            <div class="contract-info">

                <div class="info-item">
                    <span>Mã hợp đồng</span>
                    <strong>HD-{{ str_pad($contract->id, 5, '0', STR_PAD_LEFT) }}</strong>
                </div>

                <div class="info-item">
                    <span>Phòng thuê</span>
                    <strong>{{ $contract->room?->name }}</strong>
                </div>

                <div class="info-item">
                    <span>Ngày bắt đầu</span>
                    <strong>{{ $contract->start_date?->format('d/m/Y') }}</strong>
                </div>

                <div class="info-item">
                    <span>Ngày kết thúc</span>
                    <strong>
                        {{ $contract->end_date?->format('d/m/Y') ?? 'Không xác định' }}
                    </strong>
                </div>

                <div class="info-item">
                    <span>Giá thuê</span>
                    <strong>{{ number_format($contract->rent_price, 0, ',', '.') }} VNĐ</strong>
                </div>

                <div class="info-item">
                    <span>Tiền đặt cọc</span>
                    <strong>{{ number_format($contract->deposit_amount, 0, ',', '.') }} VNĐ</strong>
                </div>

            </div>

        </div>

        {{-- PDF hợp đồng --}}
        <div class="pdf-section">

            <h3>📄 Nội dung hợp đồng</h3>

            <iframe
                class="pdf-viewer"
                src="{{ route('contract-signing.pdf', ['token' => request()->route('token')]) }}"
                title="Nội dung hợp đồng thuê nhà">
            </iframe>

            <a
                class="pdf-link"
                href="{{ route('contract-signing.pdf', ['token' => request()->route('token')]) }}"
                target="_blank">
                Mở PDF trong tab mới
            </a>

        </div>

        {{-- Khu vực ký tên --}}
        <div class="signature-section">

            <h3>✍️ Chữ ký người thuê</h3>

            <p class="signature-description">
                Vui lòng sử dụng chuột hoặc ngón tay để ký tên vào khung bên dưới.
            </p>

            <form
                id="signatureForm"
                method="POST"
                action="{{ route('contract-signing.sign', ['token' => request()->route('token')]) }}">

                @csrf

                <canvas
                    id="signatureCanvas"
                    class="signature-canvas">
                </canvas>

                <input
                    type="hidden"
                    name="signature"
                    id="signatureInput">

                <div class="signature-actions">

                    <button
                        type="button"
                        class="btn-clear"
                        id="clearSignature">
                        Xóa chữ ký
                    </button>

                    <button
                        type="submit"
                        class="btn-submit">
                        Xác nhận ký hợp đồng
                    </button>

                </div>

            </form>

        </div>

    </div>

    <script>
        const canvas = document.getElementById('signatureCanvas');
        const ctx = canvas.getContext('2d');

        const form = document.getElementById('signatureForm');
        const signatureInput = document.getElementById('signatureInput');
        const clearButton = document.getElementById('clearSignature');

        let isDrawing = false;
        let hasSignature = false;

        // Thiết lập kích thước Canvas theo màn hình
        function resizeCanvas() {
            const rect = canvas.getBoundingClientRect();
            const ratio = window.devicePixelRatio || 1;

            canvas.width = rect.width * ratio;
            canvas.height = rect.height * ratio;

            ctx.setTransform(ratio, 0, 0, ratio, 0, 0);

            ctx.strokeStyle = '#0f172a';
            ctx.lineWidth = 2.5;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
        }

        resizeCanvas();

        // Lấy tọa độ chuột hoặc ngón tay
        function getPosition(event) {
            const rect = canvas.getBoundingClientRect();

            return {
                x: event.clientX - rect.left,
                y: event.clientY - rect.top
            };
        }

        // Bắt đầu ký
        canvas.addEventListener('pointerdown', function(event) {
            event.preventDefault();

            isDrawing = true;
            hasSignature = true;

            canvas.setPointerCapture(event.pointerId);

            const position = getPosition(event);

            ctx.beginPath();
            ctx.moveTo(position.x, position.y);
        });

        // Di chuyển để vẽ chữ ký
        canvas.addEventListener('pointermove', function(event) {
            if (!isDrawing) return;

            event.preventDefault();

            const position = getPosition(event);

            ctx.lineTo(position.x, position.y);
            ctx.stroke();
        });

        // Kết thúc ký
        function stopDrawing() {
            isDrawing = false;
            ctx.beginPath();
        }

        canvas.addEventListener('pointerup', stopDrawing);
        canvas.addEventListener('pointercancel', stopDrawing);

        // Xóa chữ ký
        clearButton.addEventListener('click', function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            hasSignature = false;
            signatureInput.value = '';
        });

        // Chuyển chữ ký thành ảnh PNG trước khi gửi
        form.addEventListener('submit', function(event) {
            if (!hasSignature) {
                event.preventDefault();
                alert('Anh/chị vui lòng ký tên trước khi xác nhận.');
                return;
            }

            signatureInput.value = canvas.toDataURL('image/png');
        });
    </script>

</body>

</html>