<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ChatbotDataService
{
    public function getRoomStatistics(): array
    {
        return [
            'total_rooms' => DB::table('rooms')->count(),

            'available_rooms' => DB::table('rooms')
                ->where('status', 'available')
                ->count(),

            'occupied_rooms' => DB::table('rooms')
                ->where('status', 'occupied')
                ->count(),

            'maintenance_rooms' => DB::table('rooms')
                ->where('status', 'maintenance')
                ->count(),
        ];
    }

    public function getTenantStatistics(): array
    {
        return [
            'total_tenants' => DB::table('tenants')->count(),

            'active_tenants' => DB::table('contract_tenants')
                ->join(
                    'contracts',
                    'contract_tenants.contract_id',
                    '=',
                    'contracts.id'
                )
                ->where('contracts.status', 'active')
                ->distinct('contract_tenants.tenant_id')
                ->count('contract_tenants.tenant_id'),
        ];
    }
}
