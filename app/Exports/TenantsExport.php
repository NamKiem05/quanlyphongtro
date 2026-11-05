<?php

namespace App\Exports;

use App\Models\Tenant;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TenantsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection(): Collection
    {
        return Tenant::all();
    }

    public function headings(): array
    {
        return [
            'Họ và tên',
            'Số điện thoại',
            'Email',
            'Số CCCD',
            'Ngày sinh',
            'Giới tính',
            'Địa chỉ thường trú',
            'Ghi chú',
        ];
    }

    public function map($tenant): array
    {
        return [
            $tenant->full_name,
            $tenant->phone,
            $tenant->email,
            $tenant->identity_number,
            $tenant->date_of_birth,
            $tenant->gender === "male" ? "Nam" : "Nữ",
            $tenant->permanent_address,
            $tenant->note,
        ];
    }
}