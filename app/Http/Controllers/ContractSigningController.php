<?php

namespace App\Http\Controllers;

use App\Models\ContractSignature;
use App\Services\ContractService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContractSigningController extends Controller
{
    /**
     * Tìm và kiểm tra chữ ký hợp đồng.
     */
    private function findValidSignature(string $token): ContractSignature
    {
        $tokenHash = hash('sha256', $token);

        $signature = ContractSignature::with([
            'contract.owner',
            'contract.room',
            'contract.tenants',
            'contract.contractServices',
            'tenant',
        ])
            ->where('token_hash', $tokenHash)
            ->first();

        if (!$signature) {
            abort(404, 'Đường dẫn ký hợp đồng không hợp lệ.');
        }

        if ($signature->status !== 'pending') {
            abort(403, 'Đường dẫn ký hợp đồng không còn hiệu lực.');
        }

        if (
            $signature->expires_at &&
            $signature->expires_at->isPast()
        ) {
            $signature->update([
                'status' => 'expired',
            ]);

            abort(403, 'Đường dẫn ký hợp đồng đã hết hạn.');
        }

        if ($signature->contract->status !== 'waiting_signature') {
            abort(403, 'Hợp đồng hiện không ở trạng thái chờ ký.');
        }

        return $signature;
    }

    /**
     * Hiển thị trang ký hợp đồng.
     */
    public function show(string $token)
    {
        $signature = $this->findValidSignature($token);

        return view('contracts.signing.show', [
            'signature' => $signature,
            'contract' => $signature->contract,
            'representative' => $signature->tenant,
        ]);
    }

    /**
     * Hiển thị PDF hợp đồng.
     */
    public function pdf(string $token)
    {
        $signature = $this->findValidSignature($token);

        $pdfPath = $signature->contract->pdf_path;

        if (!$pdfPath || !Storage::disk('local')->exists($pdfPath)) {
            abort(404, 'Không tìm thấy file PDF hợp đồng.');
        }

        return response()->file(
            Storage::disk('local')->path($pdfPath),
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="hop-dong-' .
                    $signature->contract->id . '.pdf"',
            ]
        );
    }

    /**
     * Xác nhận chữ ký của người thuê.
     */

    public function sign(
        Request $request,
        string $token,
        ContractService $contractService
    ) {
        // 1. Kiểm tra token
        $signature = $this->findValidSignature($token);

        // 2. Kiểm tra dữ liệu gửi lên
        $validated = $request->validate([
            'signature' => [
                'required',
                'string',
                'max:3000000',
            ],
        ], [
            'signature.required' => 'Anh/chị vui lòng ký tên trước khi xác nhận.',
            'signature.string' => 'Dữ liệu chữ ký không hợp lệ.',
            'signature.max' => 'Ảnh chữ ký vượt quá dung lượng cho phép.',
        ]);

        // 3. Lưu chữ ký người thuê
        $contract = $contractService->completeSigning(
            $signature,
            $validated['signature'],
            $request->ip(),
            substr($request->userAgent() ?? '', 0, 255)
        );

        // Chưa tạo PDF cuối cùng vì chủ nhà chưa ký
        return view('contracts.signing.success', [
            'contract' => $contract,
            'pdfGenerated' => false,
            'token' => $token,
        ]);
    }

    /**
     * Xem PDF hợp đồng đã ký
     */
    /**
     * Xem PDF hợp đồng đã ký
     */
    public function signedPdf(string $token)
    {
        $signature = $this->findSignedSignature($token);

        $contract = $signature->contract;

        if (!$contract->signed_pdf_path) {
            abort(404, 'Chưa có file PDF hợp đồng đã ký.');
        }

        $disk = Storage::disk('local');

        if (!$disk->exists($contract->signed_pdf_path)) {
            abort(404, 'File PDF hợp đồng không tồn tại.');
        }

        return response()->file(
            $disk->path($contract->signed_pdf_path),
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="hop-dong-da-ky.pdf"',
            ]
        );
    }

    /**
     * Tìm chữ ký đã hoàn tất để xem PDF hợp đồng
     */
    private function findSignedSignature(string $token): ContractSignature
    {
        $signature = ContractSignature::with([
            'contract.owner',
            'contract.room',
            'contract.tenants',
            'contract.contractServices',
            'tenant',
        ])
            ->where('token_hash', hash('sha256', $token))
            ->first();

        if (!$signature) {
            abort(404, 'Đường dẫn không hợp lệ.');
        }

        if (
            $signature->status !== 'signed' ||
            $signature->contract->status !== 'active'
        ) {
            abort(403, 'Hợp đồng chưa được ký hoặc chưa có hiệu lực.');
        }

        return $signature;
    }
}
