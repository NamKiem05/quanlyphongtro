<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AIService;
use Illuminate\Support\Facades\Log;
use Throwable;

class ChatbotController extends Controller
{
    public function send(Request $request, AIService $aiService)
    {
        // Bước 1: Kiểm tra dữ liệu người dùng gửi lên
        $validated = $request->validate([
            'message' => [
                'required',
                'string',
                'max:2000',
            ],
        ]);

        try {

            // Bước 2: Gửi câu hỏi đến AIService
            $reply = $aiService->chat(
                $validated['message']
            );

            // Bước 3: Trả câu trả lời về JavaScript
            return response()->json([
                'success' => true,
                'reply' => $reply,
            ]);
        } catch (Throwable $e) {

            // Bước 4: Ghi nhận lỗi để debug
            Log::error('Chatbot Error', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Trợ lý AI hiện không thể xử lý yêu cầu.',
            ], 502);
        }
    }
}
