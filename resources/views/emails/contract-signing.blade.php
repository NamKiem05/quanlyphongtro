
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mời ký hợp đồng thuê nhà</title>
</head>

<body style="margin:0; padding:0; background:#f3f6fb; font-family:Arial,sans-serif; color:#263445;">

    <div style="max-width:600px; margin:30px auto; background:#ffffff; border-radius:12px; overflow:hidden;">

        {{-- Header --}}
        <div style="background:#155eef; padding:25px 30px; text-align:center; color:#ffffff;">
            <h2 style="margin:0; font-size:23px;">QUẢNLÝNHÀ</h2>
            <p style="margin:8px 0 0; font-size:14px;">
                Thông báo ký hợp đồng thuê nhà
            </p>
        </div>

        {{-- Nội dung --}}
        <div style="padding:30px;">

            <p style="font-size:15px;">
                Xin chào <strong>{{ $representative->full_name }}</strong>,
            </p>

            <p style="line-height:1.7; font-size:14px;">
                Hệ thống QuảnLýNhà gửi đến anh/chị lời mời xem và ký hợp đồng
                thuê nhà. Vui lòng kiểm tra thông tin bên dưới trước khi ký.
            </p>

            {{-- Thông tin hợp đồng --}}
            <div style="background:#f5f8ff; border:1px solid #dce6f8; border-radius:8px; padding:20px; margin:22px 0;">

                <h3 style="margin:0 0 15px; color:#155eef; font-size:16px;">
                    Thông tin hợp đồng
                </h3>

                <p style="margin:8px 0; font-size:14px;">
                    <strong>Mã hợp đồng:</strong>
                    HD-{{ str_pad($contract->id, 5, '0', STR_PAD_LEFT) }}
                </p>

                <p style="margin:8px 0; font-size:14px;">
                    <strong>Phòng:</strong>
                    {{ $contract->room?->name ?? 'Chưa xác định' }}
                </p>

                <p style="margin:8px 0; font-size:14px;">
                    <strong>Ngày bắt đầu:</strong>
                    {{ $contract->start_date?->format('d/m/Y') }}
                </p>

                <p style="margin:8px 0; font-size:14px;">
                    <strong>Ngày kết thúc:</strong>
                    {{ $contract->end_date?->format('d/m/Y') ?? 'Không xác định' }}
                </p>

                <p style="margin:8px 0; font-size:14px;">
                    <strong>Giá thuê:</strong>
                    {{ number_format((float) $contract->rent_price, 0, ',', '.') }} đ/tháng
                </p>

                <p style="margin:8px 0; font-size:14px;">
                    <strong>Tiền đặt cọc:</strong>
                    {{ number_format((float) $contract->deposit_amount, 0, ',', '.') }} đ
                </p>
            </div>

            <p style="font-size:14px; line-height:1.7;">
                Anh/chị vui lòng nhấn vào nút bên dưới để xem nội dung
                hợp đồng và thực hiện ký xác nhận trực tuyến.
            </p>

            {{-- Nút ký --}}
            <div style="text-align:center; margin:30px 0;">
                <a href="{{ $signingUrl }}"
                   style="display:inline-block; background:#155eef; color:#ffffff;
                          text-decoration:none; padding:14px 28px;
                          border-radius:7px; font-size:15px; font-weight:bold;">
                    XEM HỢP ĐỒNG & KÝ NGAY
                </a>
            </div>

            <p style="font-size:13px; color:#64748b; line-height:1.7;">
                Lưu ý: Đường dẫn ký hợp đồng có thời hạn sử dụng.
                Vui lòng không chia sẻ đường dẫn này cho người khác.
            </p>

            <p style="font-size:13px; color:#64748b; line-height:1.7;">
                Nếu nút phía trên không hoạt động, anh/chị có thể sao chép
                đường dẫn sau vào trình duyệt:
                <br>
                <a href="{{ $signingUrl }}" style="color:#155eef; word-break:break-all;">
                    {{ $signingUrl }}
                </a>
            </p>

            <p style="font-size:14px; margin-top:25px;">
                Trân trọng,<br>
                <strong>Hệ thống QuảnLýNhà</strong>
            </p>

        </div>

        {{-- Footer --}}
        <div style="background:#f8fafc; padding:18px; text-align:center;">
            <p style="margin:0; font-size:12px; color:#94a3b8;">
                Email tự động từ hệ thống QuảnLýNhà. Vui lòng không trả lời email này.
            </p>
        </div>

    </div>

</body>
</html>
