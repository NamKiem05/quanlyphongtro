<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Hợp đồng thuê phòng</title>

    <style>
        /* {{-- Thêm @font-face Times New Roman ở đây (xem hướng dẫn ở tin nhắn trước) --}} */

        @page {
            margin: 50px 50px 60px 70px;
        }

        body {
            font-family: "Times New Roman", "Tinos", "DejaVu Serif", serif;
            font-size: 13px;
            line-height: 1.5;
            color: #000;
        }

        /* ===== Quốc hiệu ===== */
        .header {
            text-align: center;
        }

        .header .nation {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }

        .header .motto {
            font-size: 13px;
            font-weight: bold;
            margin: 2px 0 0;
        }

        .header .divider {
            font-size: 12px;
            margin: 0;
        }

        .header .title {
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 10px 0 12px;
        }

        .intro {
            font-style: italic;
            text-align: justify;
            margin: 0 0 12px;
        }

        /* ===== Tiêu đề các bên ===== */
        .party-title {
            margin: 12px 0 4px;
        }

        .party-title .name {
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
        }

        /* ===== Bảng thông tin (nhãn : giá trị | nhãn : giá trị) ===== */
        table.info {
            width: 100%;
            border-collapse: collapse;
        }

        table.info td {
            padding: 1px 0;
            vertical-align: top;
        }

        table.info .label {
            width: 85px;
        }

        table.info .value {
            width: 40%;
        }

        table.info .label2 {
            width: 80px;
            padding-left: 10px;
        }

        .after-text {
            font-style: italic;
            text-align: justify;
            margin: 10px 0 14px;
        }

        /* ===== Điều khoản ===== */
        .article-title {
            font-weight: bold;
            text-transform: uppercase;
            margin: 14px 0 6px;
        }

        table.item {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3px;
        }

        table.item td {
            vertical-align: top;
            text-align: justify;
        }

        table.item td.no {
            width: 38px;
        }

        .terms {
            white-space: pre-line;
            text-align: justify;
            margin-left: 38px;
        }

        /* ===== Chữ ký ===== */
        .signature {
            width: 100%;
            margin-top: 35px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }

        .signature td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .signature-space {
            height: 90px;
            text-align: center;
        }

        .signature-image {
            height: 75px;
            max-width: 180px;
            object-fit: contain;
        }

        /* ===== Số trang ===== */
        .footer {
            position: fixed;
            bottom: -35px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 13px;
        }

        .footer .pagenum:before {
            content: counter(page);
        }

        .service-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 12px;
        }

        .service-table th,
        .service-table td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: left;
        }

        .service-table th {
            font-weight: bold;
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    @php
    $representative = $contract->tenants->firstWhere('pivot.is_representative', true)
    ?? $contract->tenants->first();
    $dots = '................................';
    // Đường dẫn ảnh chữ ký
    $tenantSignatureImage = $tenantSignatureImage ?? null;
    $ownerSignatureImage = $ownerSignatureImage ?? null;
    @endphp

    <div class="footer"><span class="pagenum"></span></div>

    {{-- Quốc hiệu --}}
    <div class="header">
        <p class="nation">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</p>
        <p class="motto">Độc lập - Tự do - Hạnh phúc</p>
        <p class="divider">---------oOo---------</p>
        <p class="title">Hợp đồng thuê căn hộ</p>
    </div>

    <p class="intro">
        Hôm nay, ngày {{ now()->format('d') }} tháng {{ now()->format('m') }} năm {{ now()->format('Y') }},
        tại số nhà {{ $houseSetting?->address ?? '........................................................' }}
    </p>

    {{-- BÊN A --}}
    <div class="party-title">
        <span class="name">Bên cho thuê</span> (Sau đây gọi tắt là <strong>Bên A</strong>)
    </div>

    <table class="info">
        <tr>
            <td class="label">Ông (Bà)</td>
            <td class="value">: <strong>{{ $owner->name }}</strong></td>
            <td class="label2">Điện thoại:</td>
            <td>{{ $houseSetting?->phone ?? $dots }}</td>
        </tr>
        <tr>
            <td class="label">Email</td>
            <td colspan="3">: {{ $owner->email }}</td>
        </tr>
    </table>

    {{-- BÊN B --}}
    <div class="party-title">
        <span class="name">Đại diện bên thuê</span> (Sau đây gọi tắt là <strong>Bên B</strong>)
    </div>

    <table class="info">
        <tr>
            <td class="label">Ông (Bà)</td>
            <td class="value">: <strong>{{ $representative?->full_name ?? $dots }}</strong></td>
            <td class="label2">Điện thoại:</td>
            <td>{{ $representative?->phone ?? $dots }}</td>
        </tr>
        <tr>
            <td class="label">CCCD số</td>
            <td colspan="3">: {{ $representative?->identity_number ?? $dots }}</td>
        </tr>
        <tr>
            <td class="label" style="width: 120px;">Địa chỉ thường trú</td>
            <td colspan="3">: {{ $representative?->permanent_address ?? $dots }}</td>
        </tr>
    </table>



    <p class="after-text">
        Sau khi thảo luận và thống nhất, hai Bên đồng ý ký kết Hợp đồng thuê căn hộ
        (gọi tắt là <strong>“Hợp Đồng”</strong>) này cùng với các điều khoản nội dung như sau:
    </p>

    {{-- ĐIỀU 1 --}}
    <div class="article-title">Điều 1: Mục đích thuê và đặc điểm phòng</div>

    <table class="item">
        <tr>
            <td class="no">1.1.</td>
            <td>Bên A cho Bên B thuê phòng vào mục đích: Để ở.</td>
        </tr>
    </table>

    <table class="item">
        <tr>
            <td class="no">1.2.</td>
            <td>Số người ở là: <strong>{{ $contract->tenants->count() }}</strong></td>
        </tr>
    </table>

    <table class="item">
        <tr>
            <td class="no">1.3.</td>
            <td>
                <strong>Đặc điểm phòng:</strong> Phòng có địa chỉ tại:
                {{ $houseSetting?->house_name ? $houseSetting->house_name . ', ' : '' }}{{ $houseSetting?->address ?? $dots }}
                <br>
                Phòng số: <strong>{{ $contract->room->name }}</strong>
                &nbsp;&nbsp;Tầng: {{ $contract->room->floor ?? '....' }}
                &nbsp;&nbsp;Diện tích: {{ $contract->room->area ?? '....' }} m²
            </td>
        </tr>
    </table>

    <table class="item">
        <tr>
            <td class="no">1.4.</td>
            <td>
                <strong>Nội thất, trang thiết bị:</strong> Hai bên lập biên bản bàn giao (đính kèm hợp đồng này)
                ghi rõ tình trạng tại thời điểm bàn giao.
            </td>
        </tr>
    </table>

    {{-- ĐIỀU 2 --}}
    <div class="article-title">Điều 2: Thời hạn thuê và giá thuê</div>

    <table class="item">
        <tr>
            <td class="no">2.1.</td>
            <td>
                <strong>Thời hạn thuê:</strong> từ ngày
                <strong>{{ $contract->start_date?->format('d/m/Y') }}</strong>
                đến hết ngày
                <strong>{{ $contract->end_date?->format('d/m/Y') ?? $dots }}</strong>.
            </td>
        </tr>
    </table>

    <table class="item">
        <tr>
            <td class="no">2.2.</td>
            <td>
                <strong>Giá thuê:</strong>
                <strong>{{ number_format((float) $contract->rent_price, 0, ',', '.') }} đ</strong>/tháng.
            </td>
        </tr>
    </table>

    <table class="item">
        <tr>
            <td class="no">2.3.</td>
            <td>
                <strong>Tiền đặt cọc:</strong>
                <strong>{{ number_format((float) $contract->deposit_amount, 0, ',', '.') }} đ</strong>.
            </td>
        </tr>
    </table>

    <table class="item">
        <tr>
            <td class="no">2.4.</td>
            <td>
                <strong>Thanh toán:</strong> Bên B thanh toán tiền thuê vào ngày
                <strong>{{ $contract->billing_day }}</strong> hàng tháng.
            </td>
        </tr>
    </table>

    <table class="item">
        <tr>
            <td class="no">2.5.</td>
            <td>Tiền thuê hàng tháng nêu trên không bao gồm các chi phí như: điện, nước, các chi phí khác nếu có.</td>
        </tr>
    </table>
    <table class="item">
        <tr>
            <td class="no">2.6.</td>
            <td>
                <strong>Giá dịch vụ:</strong>

                @if ($contract->contractServices->isNotEmpty())
                <table class="service-table">
                    <thead>
                        <tr>
                            <th>Tên dịch vụ</th>
                            <th>Đơn vị</th>
                            <th>Đơn giá</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($contract->contractServices as $service)
                        <tr>
                            <td>{{ $service->service_name }}</td>
                            <td>{{ $service->unit }}</td>
                            <td>
                                {{ number_format((float) $service->unit_price, 0, ',', '.') }} đ
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                Không có dịch vụ áp dụng.
                @endif
            </td>
        </tr>
    </table>

    {{-- ĐIỀU 3 --}}
    <div class="article-title">Điều 3: Điều khoản thỏa thuận</div>
    <div class="terms">{{ $contract->terms ?? 'Các bên cam kết thực hiện đúng những nội dung đã thỏa thuận trong hợp đồng này.' }}</div>

    <!-- Điều 4 -->
    <div class="article-title">Điều 4: Quyền và trách nhiệm của bên a</div>
    <table class="item">
        <tr>
            <td class="no">4.1.</td>
            <td>Bàn giao căn hộ và các trang thiết bị cung cấp cho Bên B như Biên bản bàn giao đính kèm Hợp đồng này.</td>
        </tr>
    </table>
    <table class="item">
        <tr>
            <td class="no">4.2.</td>
            <td>Đảm bảo căn hộ cho thuê hiện không tranh chấp,không bị kê biên để đảm bảo thi hành án của Bên A.</td>
        </tr>
    </table>
    <table class="item">
        <tr>
            <td class="no">4.3.</td>
            <td>Sửa chữa hư hỏng về cơ sở hạ tầng, trang thiết bị thuộc khu vực chung của Tòa nhà khi xảy ra sự cố.
                Những hư hỏng mà bên A có trách nhiệm sửa chữa như sau: Hệ thống điện, nước tại khu vực chung, mạng
                internet tại hộp kỹ thuật của tòa nhà, thang máy, thấm tại hộp kỹ thuật, hệ thống điện chiếu sáng khu
                vực chung của tòa nhà (các hư hỏng bên trong căn hộ bên B có trách nhiệm tự sửa chữa).</td>
        </tr>
    </table>

    <!-- Điều 5 -->
    <div class="article-title">Điều 5: Quyền và trách nhiệm của bên B</div>
    <table class="item">
        <tr>
            <td class="no">5.1.</td>
            <td>Chấp hành nội quy, quy định tại Tòa nhà</td>
        </tr>
    </table>
    <table class="item">
        <tr>
            <td class="no">5.2.</td>
            <td>Sử dụng căn hộ đúng mục đích. Khi cần sửa chữa, cải tạo theo nhu cầu sử dụng riêng phải được sự đồng ý của
                Bên A. Không được tự ý sửa chữa khi chưa có sự đồng ý của Bên A bằng văn bản.</td>
        </tr>
    </table>
    <table class="item">
        <tr>
            <td class="no">5.3.</td>
            <td>Tự chịu trách nhiệm về tính mạng, tài sản, của cải, các đồ dùng cá nhân của Bên B và khách của Bên B tại địa điểm thuê.</td>
        </tr>
    </table>
    <table class="item">
        <tr>
            <td class="no">5.4.</td>
            <td>Khi ra vào Tòa nhà, Bên B hoặc khách của Bên B phải khóa cửa tại lối ra vào của tòa nhà, nếu không khóa cửa
                gây mất mát tài sản tại tòa nhà thì Bên B phải chịu trách nhiệm bồi thường hoàn toàn.</td>
        </tr>
    </table>
    <table class="item">
        <tr>
            <td class="no">5.5.</td>
            <td>Thực hiện nghiêm chỉnh các quy định của Nhà nước về an ninh, vệ sinh sạch sẽ và phòng cháy chữa cháy,
                trật tự chung tại khu vực cư trú. Bảo đảm vệ sinh môi trường, không gây ô nhiễm môi trường không khí,
                nguồn nước. Không được kinh doanh các mặt hàng quốc cấm, có nghĩa vụ kê khai và nộp các khoản thuế phí
                phát sinh từ việc thuê căn hộ này theo quy định của nhà nước nếu có. Không tụ tập bạn bè nhậu nhẹt,
                đông người, mở âm thanh lớn gây ồn ào tại tòa nhà. Khách đến chơi không được ở lại quá 23h. Trường hợp
                khách đến chơi ở lại qua đêm, Bên B phải thông báo cho Bên A và được sự đồng ý của Bên A thì mới được ở lại.</td>
        </tr>
    </table>
    <table class="item">
        <tr>
            <td class="no">5.6.</td>
            <td>Bên B không được sử dụng bếp ga để đun nấu, chỉ được dùng bếp từ, bếp điện. Bên B không được chứa các hàng hóa,
                vật liệu độc hại, vật liệu dễ cháy nổ trong căn hộ và khu vực chung của tòa nhà. Bên B tuân thủ các quy định về
                an toàn phòng cháy chữa cháy. Trong trường hợp xảy ra cháy nổ do lỗi của bên B thì bên B phải hoàn toàn chịu trách
                nhiệm trước pháp luật.</td>
        </tr>
    </table>
    <table class="item">
        <tr>
            <td class="no">5.7.</td>
            <td>Có trách nhiệm bảo quản tài sản theo phụ lục đính kèm Hợp đồng này. Khi Hợp đồng hết hạn hoặc thanh lý do một trong
                hai Bên chấm dứt Hợp đồng trước thời hạn thì Bên B phải bàn giao lại căn hộ cho Bên A về trạng thái ban đầu.
                Các trang thiết bị phải bàn giao cho Bên A đầy đủ và vẫn trong tình trạng sử dụng bình thường. Trong 02 (hai)
                ngày kể từ ngày Bên B nhận bàn giao căn hộ, Bên A có trách nhiệm bảo hành các trang thiết bị, đường điện,
                đường nước cho Bên B. Nếu có vấn đề, Bên A có trách nhiệm xử lý. Sau 02 (hai) ngày kể từ ngày Bên A bàn giao căn hộ cho Bên B,
                trong quá trình thuê căn hộ nếu các trang thiết bị được bàn giao như cửa sổ, cửa ra vào, điều hòa, bình nóng lạnh, tủ lạnh,
                giường tủ, hút mùi, tivi, thiết bị vệ sinh, bàn ghế, giường tủ, bộ phát wifi (nếu có),... hoặc đường điện, đường nước có bị
                hư hỏng thì Bên B tự chịu trách nhiệm sửa chữa.</td>
        </tr>
    </table>
    <table class="item">
        <tr>
            <td class="no">5.8.</td>
            <td>Có trách nhiệm ở đúng số người quy định trong Hợp đồng và phải tự đi khai báo tạm trú, tạm vắng theo quy định Pháp luật.
                Không được tự ý thêm người ở căn hộ mà chưa có sự đồng ý của Bên A.</td>
        </tr>
    </table>
    <table class="item">
        <tr>
            <td class="no">5.9.</td>
            <td>Cung cấp bản sao giấy tờ các thành viên ở tại căn hộ gồm: căn cước công dân hoặc hộ chiếu cho Bên A.</td>
        </tr>
    </table>
    <table class="item">
        <tr>
            <td class="no">5.10.</td>
            <td>Bên B không được chuyển nhượng hoặc cho thuê lại một phần hay toàn bộ căn hộ cho Bên Thứ Ba nào khác
                mà không được sự đồng ý của Bên A bằng văn bản.</td>
        </tr>
    </table>
    <!-- {{-- Chữ ký --}} -->
    <table class="signature">
        <tr>
            <td>
                <strong>ĐẠI DIỆN BÊN A</strong><br>
                <em>(Ký và ghi rõ họ tên)</em>

                <div class="signature-space">
                    @if ($ownerSignatureImage)
                    <img
                        src="{{ $ownerSignatureImage }}"
                        class="signature-image"
                        alt="Chữ ký chủ nhà">
                    @endif
                </div>

                <strong>{{ $owner->name }}</strong>
            </td>

            <td>
                <strong>ĐẠI DIỆN BÊN B</strong><br>
                <em>(Ký và ghi rõ họ tên)</em>

                <div class="signature-space">
                    @if ($tenantSignatureImage)
                    <img
                        src="{{ $tenantSignatureImage }}"
                        class="signature-image"
                        alt="Chữ ký người thuê">
                    @endif
                </div>

                <strong>{{ $representative?->full_name ?? $dots }}</strong>
            </td>
        </tr>
    </table>

</body>

</html>