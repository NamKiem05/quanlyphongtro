<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractSignature extends Model
{
    protected $fillable = [
        'contract_id',
        'tenant_id',
        'token_hash',
        'expires_at',
        'signature_path',
        'status',
        'signed_at',
        'signed_ip',
        'user_agent',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'signed_at' => 'datetime',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
