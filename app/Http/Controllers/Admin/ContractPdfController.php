<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\HouseSetting;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ContractPdfController extends Controller
{
    /**
     * Xuất hợp đồng thành PDF
     */

    public function export(Contract $contract)
    {
        // Lấy đầy đủ thông tin hợp đồng
        $contract->load([
            'owner',
            'room',
            'tenants',
            'contractServices',
        ]);

        $houseSetting = HouseSetting::first();

        // Lấy chủ nhà đã tạo hợp đồng
        $owner = $contract->owner ?? Auth::user();
        // Tạo file PDF
        $pdf = Pdf::loadView('admin.contracts.pdf.contract', [
            'contract' => $contract,
            'houseSetting' => $houseSetting,
            'owner' => $owner,
        ]);

        // Thiết lập khổ giấy A4
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('hop-dong-' . $contract->id . '.pdf');
    }
}
