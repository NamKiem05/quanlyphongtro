<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class AIService
{
    protected ChatbotDataService $dataService;

    public function __construct(ChatbotDataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function chat(string $message): string
    {
        // 1. Lấy cấu hình Ollama
        $url = config('services.ollama.url');
        $model = config('services.ollama.model');

        if (!$url || !$model) {
            throw new RuntimeException('Chưa cấu hình Ollama.');
        }

        // 2. Lấy dữ liệu thực tế từ database
        $roomStats = $this->dataService->getRoomStatistics();
        $tenantStats = $this->dataService->getTenantStatistics();

        // 3. Chuyển dữ liệu thành JSON
        $databaseContext = json_encode([
            'thong_ke_phong' => $roomStats,
            'thong_ke_nguoi_thue' => $tenantStats,
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        // Kiểm tra dữ liệu trước khi gửi AI
        Log::info('CHATBOT DATABASE CONTEXT', [
            'room_stats' => $roomStats,
            'tenant_stats' => $tenantStats,
        ]);

        // 4. Gửi dữ liệu và câu hỏi đến Ollama
        $response = Http::timeout(180)
            ->acceptJson()
            ->post($url, [
                'model' => $model,
                'think' => false,

                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "
                            Bạn là trợ lý AI của phần mềm quản lý nhà trọ.

                            DỮ LIỆU THỰC TẾ TỪ DATABASE:
                            {$databaseContext}

                            QUY TẮC:
                            1. Luôn trả lời bằng tiếng Việt.
                            2. Trả lời ngắn gọn, đúng trọng tâm.
                            3. Ưu tiên 2-5 câu.
                            4. Không sử dụng Markdown.
                            5. Không sử dụng dấu *, ** hoặc #.
                            6. Chỉ sử dụng số liệu trong database.
                            7. Không tự bịa dữ liệu.
                            8. Khi hỏi tổng số phòng, sử dụng total_rooms.
                            9. Khi hỏi phòng trống, sử dụng available_rooms.
                            10. Khi hỏi phòng đang thuê, sử dụng occupied_rooms.
                            11. Khi hỏi người thuê, sử dụng dữ liệu thống kê người thuê.
                            12. Tuyệt đối không viết placeholder như [số lượng phòng].
                        ",
                    ],
                    [
                        'role' => 'user',
                        'content' => $message,
                    ],
                ],

                'stream' => false,

                'options' => [
                    'temperature' => 0.3,
                    'num_predict' => 250,
                ],
            ]);

        // 5. Kiểm tra lỗi HTTP
        if ($response->failed()) {
            Log::error('Ollama API Error', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            throw new RuntimeException(
                'Ollama trả về lỗi HTTP: ' . $response->status()
            );
        }

        // 6. Lấy câu trả lời
        $reply = $response->json('message.content');

        if (!is_string($reply) || trim($reply) === '') {
            throw new RuntimeException(
                'AI không trả về nội dung hợp lệ.'
            );
        }

        return trim($reply);
    }
}
