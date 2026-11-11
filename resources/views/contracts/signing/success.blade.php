<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ký hợp đồng thành công</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #1e293b;
        }

        .success-card {
            width: 100%;
            max-width: 500px;
            background: #fff;
            padding: 40px 30px;
            border-radius: 16px;
            text-align: center;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        }

        .success-icon {
            width: 75px;
            height: 75px;
            background: #dcfce7;
            color: #16a34a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
            margin: 0 auto 22px;
        }

        h2 {
            color: #15803d;
            margin-bottom: 15px;
        }

        p {
            color: #64748b;
            line-height: 1.7;
            margin-bottom: 12px;
        }

        .contract-code {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            color: #334155;
        }

        .pdf-status {
            padding: 13px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 14px;
            line-height: 1.6;
        }

        .pdf-success {
            background: #dcfce7;
            color: #166534;
        }

        .pdf-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .note {
            font-size: 13px;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="success-card">

        <div class="success-icon">✓</div>

        <h2>Ký hợp đồng thành công!</h2>

        <p>
            Cảm ơn anh/chị đã xác nhận và ký hợp đồng thuê nhà.
        </p>

        <div class="contract-code">
            <p>Mã hợp đồng</p>

            <strong>
                HD-{{ str_pad($contract->id, 5, '0', STR_PAD_LEFT) }}
            </strong>
        </div>

        <p>
            Hợp đồng đã được kích hoạt thành công.
        </p>

        @if ($pdfGenerated ?? false)
        <div class="pdf-status pdf-success">
            ✓ Bản PDF hợp đồng đã ký được tạo và lưu thành công.
        </div>
        <a
            href="{{ route('contract-signing.signed-pdf', ['token' => $token]) }}"
            target="_blank"
            class="btn btn-success">
            <i class="fa-solid fa-file-pdf"></i>
            Xem hợp đồng đã ký (PDF)
        </a>
        @else
        <div class="pdf-status pdf-warning">
            ⚠ Hợp đồng đã ký thành công nhưng hệ thống chưa tạo được file PDF.
            Chủ nhà cần kiểm tra và tạo lại tài liệu.
        </div>
        @endif

        <p class="note">
            Vui lòng liên hệ chủ nhà nếu cần hỗ trợ hoặc xác nhận thêm thông tin.
        </p>

    </div>

</body>

</html>