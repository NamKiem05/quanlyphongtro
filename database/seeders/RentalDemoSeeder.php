<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RentalDemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // =====================================
            // 1. TÀI KHOẢN
            // =====================================

            DB::table('users')->updateOrInsert(
                ['email' => 'admin@nhatro.com'],
                [
                    'name' => 'Chủ nhà trọ',
                    'number_phone' => '0901234567',
                    'password' => Hash::make('12345678'),
                    'email_verified_at' => now(),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $userId = DB::table('users')
                ->where('email', 'admin@nhatro.com')
                ->value('id');

            // =====================================
            // 2. PHÒNG TRỌ
            // =====================================

            $roomData = [
                ['P101', 'Tầng 1', 20, 2500000, 'occupied'],
                ['P102', 'Tầng 1', 22, 2800000, 'occupied'],
                ['P103', 'Tầng 1', 18, 2200000, 'occupied'],
                ['P201', 'Tầng 2', 25, 3000000, 'occupied'],
                ['P202', 'Tầng 2', 22, 2800000, 'occupied'],
                ['P203', 'Tầng 2', 20, 2500000, 'occupied'],
                ['P301', 'Tầng 3', 25, 3000000, 'available'],
                ['P302', 'Tầng 3', 18, 2200000, 'maintenance'],
            ];

            $roomIds = [];

            foreach ($roomData as $room) {
                $roomIds[] = DB::table('rooms')->insertGetId([
                    'name' => $room[0],
                    'floor' => $room[1],
                    'area' => $room[2],
                    'rent_price' => $room[3],
                    'status' => $room[4],
                    'description' => 'Phòng trọ ' . $room[0],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // =====================================
            // 3. NGƯỜI THUÊ
            // =====================================

            $tenantData = [
                ['Nguyễn Văn An', '0901234567', 'an@gmail.com', '079201000001'],
                ['Trần Thị Bình', '0902345678', 'binh@gmail.com', '079201000002'],
                ['Lê Văn Cường', '0903456789', 'cuong@gmail.com', '079201000003'],
                ['Phạm Thị Dung', '0904567890', 'dung@gmail.com', '079201000004'],
                ['Hoàng Văn Em', '0905678901', 'em@gmail.com', '079201000005'],
                ['Vũ Thị Hà', '0906789012', 'ha@gmail.com', '079201000006'],
                ['Đặng Văn Hùng', '0907890123', 'hung@gmail.com', '079201000007'],
            ];

            $tenantIds = [];

            foreach ($tenantData as $tenant) {
                $tenantIds[] = DB::table('tenants')->insertGetId([
                    'full_name' => $tenant[0],
                    'phone' => $tenant[1],
                    'email' => $tenant[2],
                    'identity_number' => $tenant[3],
                    'date_of_birth' => '2000-01-01',
                    'gender' => 'Nam',
                    'permanent_address' => 'Hà Nội, Việt Nam',
                    'note' => 'Dữ liệu mẫu',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // =====================================
            // 4. HỢP ĐỒNG
            // =====================================

            $contractIds = [];

            for ($i = 0; $i < 6; $i++) {
                $contractIds[$i] = DB::table('contracts')->insertGetId([
                    'room_id' => $roomIds[$i],
                    'start_date' => Carbon::now()->subMonths(3)->startOfMonth()->toDateString(),
                    'end_date' => Carbon::now()->addMonths(9)->endOfMonth()->toDateString(),
                    'rent_price' => $roomData[$i][3],
                    'deposit_amount' => $roomData[$i][3],
                    'billing_day' => 5,
                    'status' => 'active',
                    'signed_at' => Carbon::now()->subMonths(3)->toDateString(),
                    'terms' => 'Thanh toán tiền phòng trước ngày 5 hàng tháng.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Hợp đồng đã hết hạn
            $contractIds[6] = DB::table('contracts')->insertGetId([
                'room_id' => $roomIds[6],
                'start_date' => Carbon::now()->subMonths(12)->toDateString(),
                'end_date' => Carbon::now()->subMonths(1)->toDateString(),
                'rent_price' => $roomData[6][3],
                'deposit_amount' => $roomData[6][3],
                'billing_day' => 5,
                'status' => 'expired',
                'signed_at' => Carbon::now()->subMonths(12)->toDateString(),
                'terms' => 'Hợp đồng mẫu đã hết hạn.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // =====================================
            // 5. NGƯỜI THUÊ TRONG HỢP ĐỒNG
            // =====================================

            foreach ($contractIds as $index => $contractId) {
                DB::table('contract_tenants')->insert([
                    'contract_id' => $contractId,
                    'tenant_id' => $tenantIds[$index],
                    'is_representative' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // =====================================
            // 6. TIỀN CỌC
            // =====================================

            foreach ($contractIds as $index => $contractId) {
                DB::table('deposits')->insert([
                    'contract_id' => $contractId,
                    'transaction_type' => 'received',
                    'amount' => $roomData[$index][3],
                    'transaction_date' => Carbon::now()->subMonths(3)->toDateString(),
                    'note' => 'Nhận tiền đặt cọc',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Hoàn tiền cọc cho hợp đồng đã hết hạn
            DB::table('deposits')->insert([
                'contract_id' => $contractIds[6],
                'transaction_type' => 'refunded',
                'amount' => $roomData[6][3],
                'transaction_date' => Carbon::now()->subDays(20)->toDateString(),
                'note' => 'Hoàn trả tiền cọc khi kết thúc hợp đồng',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // =====================================
            // 7. DỊCH VỤ
            // =====================================

            $serviceData = [
                ['Điện', 'metered', 'kWh', 3500],
                ['Nước', 'metered', 'm3', 15000],
                ['Internet', 'fixed', 'Tháng', 100000],
                ['Vệ sinh', 'fixed', 'Tháng', 30000],
            ];

            $serviceIds = [];

            foreach ($serviceData as $service) {
                $serviceIds[] = DB::table('services')->insertGetId([
                    'name' => $service[0],
                    'type' => $service[1],
                    'unit' => $service[2],
                    'unit_price' => $service[3],
                    'description' => 'Dịch vụ ' . $service[0],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // =====================================
            // 8. DỊCH VỤ ÁP DỤNG CHO PHÒNG
            // =====================================

            for ($i = 0; $i < 6; $i++) {
                foreach ($serviceIds as $serviceIndex => $serviceId) {
                    DB::table('room_services')->insert([
                        'room_id' => $roomIds[$i],
                        'service_id' => $serviceId,
                        'quantity' => 1,
                        'unit_price' => $serviceData[$serviceIndex][3],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // =====================================
            // 9. CHỈ SỐ ĐIỆN NƯỚC
            // =====================================

            $usageData = [
                [120, 165, 8, 12],
                [200, 250, 10, 15],
                [80, 115, 5, 9],
                [300, 360, 15, 20],
                [150, 190, 9, 13],
                [90, 130, 6, 10],
            ];

            for ($i = 0; $i < 6; $i++) {

                // Điện
                DB::table('meter_readings')->insert([
                    'room_id' => $roomIds[$i],
                    'service_id' => $serviceIds[0],
                    'reading_month' => Carbon::now()->startOfMonth()->toDateString(),
                    'old_index' => $usageData[$i][0],
                    'new_index' => $usageData[$i][1],
                    'usage' => $usageData[$i][1] - $usageData[$i][0],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Nước
                DB::table('meter_readings')->insert([
                    'room_id' => $roomIds[$i],
                    'service_id' => $serviceIds[1],
                    'reading_month' => Carbon::now()->startOfMonth()->toDateString(),
                    'old_index' => $usageData[$i][2],
                    'new_index' => $usageData[$i][3],
                    'usage' => $usageData[$i][3] - $usageData[$i][2],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // =====================================
            // 10. HÓA ĐƠN VÀ CHI TIẾT HÓA ĐƠN
            // =====================================

            $paymentRates = [1, 1, 1, 0.5, 0, 0];

            for ($i = 0; $i < 6; $i++) {

                $electricUsage = $usageData[$i][1] - $usageData[$i][0];
                $waterUsage = $usageData[$i][3] - $usageData[$i][2];

                $electricMoney = $electricUsage * 3500;
                $waterMoney = $waterUsage * 15000;
                $internetMoney = 100000;
                $cleaningMoney = 30000;
                $rentMoney = $roomData[$i][3];

                $total = $rentMoney
                    + $electricMoney
                    + $waterMoney
                    + $internetMoney
                    + $cleaningMoney;

                $paid = (int) ($total * $paymentRates[$i]);

                $status = match ($paymentRates[$i]) {
                    1 => 'paid',
                    0 => 'unpaid',
                    default => 'partial',
                };

                $invoiceId = DB::table('invoices')->insertGetId([
                    'contract_id' => $contractIds[$i],
                    'invoice_code' => 'HD-' . Carbon::now()->format('Ym') . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                    'billing_month' => Carbon::now()->startOfMonth()->toDateString(),
                    'due_date' => Carbon::now()->startOfMonth()->addDays(4)->toDateString(),
                    'total_amount' => $total,
                    'paid_amount' => $paid,
                    'status' => $status,
                    'note' => 'Hóa đơn tiền phòng tháng này',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $details = [
                    ['Tiền phòng', 1, $rentMoney],
                    ['Tiền điện', $electricUsage, 3500],
                    ['Tiền nước', $waterUsage, 15000],
                    ['Internet', 1, $internetMoney],
                    ['Vệ sinh', 1, $cleaningMoney],
                ];

                foreach ($details as $detail) {
                    DB::table('invoice_details')->insert([
                        'invoice_id' => $invoiceId,
                        'item_name' => $detail[0],
                        'quantity' => $detail[1],
                        'unit_price' => $detail[2],
                        'amount' => $detail[1] * $detail[2],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Tạo giao dịch thanh toán nếu đã trả tiền
                if ($paid > 0) {
                    DB::table('payments')->insert([
                        'invoice_id' => $invoiceId,
                        'amount' => $paid,
                        'payment_method' => $i % 2 === 0
                            ? 'cash'
                            : 'bank_transfer',
                        'paid_at' => now(),
                        'transaction_code' => 'PAY-' . Str::upper(Str::random(8)),
                        'note' => 'Thanh toán hóa đơn mẫu',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // =====================================
            // 11. CHI PHÍ NHÀ TRỌ
            // =====================================

            $expenseData = [
                ['Sửa chữa đường ống nước', 350000],
                ['Mua bóng đèn', 150000],
                ['Vệ sinh khu vực chung', 200000],
                ['Sửa khóa cửa', 250000],
            ];

            foreach ($expenseData as $expense) {
                DB::table('expenses')->insert([
                    'room_id' => null,
                    'name' => $expense[0],
                    'amount' => $expense[1],
                    'expense_date' => now()->toDateString(),
                    'description' => 'Chi phí vận hành nhà trọ',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // =====================================
            // 12. YÊU CẦU SỬA CHỮA
            // =====================================

            DB::table('maintenance_requests')->insert([
                'room_id' => $roomIds[7],
                'title' => 'Sửa chữa hệ thống điện',
                'description' => 'Kiểm tra và thay thế ổ cắm điện bị hỏng.',
                'status' => 'processing',
                'cost' => 300000,
                'reported_at' => now()->subDays(2)->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // =====================================
            // 13. LỊCH SỬ HOẠT ĐỘNG
            // =====================================

            $activities = [
                ['create', 'rooms', $roomIds[0], 'Tạo phòng P101'],
                ['create', 'tenants', $tenantIds[0], 'Thêm người thuê Nguyễn Văn An'],
                ['create', 'contracts', $contractIds[0], 'Tạo hợp đồng phòng P101'],
                ['create', 'invoices', 1, 'Tạo hóa đơn tháng hiện tại'],
                ['update', 'rooms', $roomIds[7], 'Cập nhật phòng P302 đang sửa chữa'],
            ];

            foreach ($activities as $activity) {
                DB::table('activity_logs')->insert([
                    'user_id' => $userId,
                    'action' => $activity[0],
                    'table_name' => $activity[1],
                    'record_id' => $activity[2],
                    'description' => $activity[3],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
