<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;



class Contract extends Model
{
    protected $fillable = [
        'owner_id',
        'room_id',
        'start_date',
        'end_date',
        'rent_price',
        'deposit_amount',
        'billing_day',
        'status',
        'signed_at',
        'pdf_path',
        'signed_pdf_path',
        'terms',

        // Chữ ký chủ nhà
        'owner_signature_path',
        'owner_signed_at',
        'owner_signed_ip',
        'owner_user_agent',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'signed_at' => 'datetime',
        'owner_signed_at' => 'datetime',
        'rent_price' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function contractTenants(): HasMany
    {
        return $this->hasMany(ContractTenant::class);
    }

    public function deposits(): HasMany
    {
        return $this->hasMany(Deposit::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(
            Tenant::class,
            'contract_tenants'
        )->withPivot('is_representative')
            ->withTimestamps();
    }
    public function contractServices(): HasMany
    {
        return $this->hasMany(ContractService::class);
    }
    public function signature(): HasOne
    {
        return $this->hasOne(ContractSignature::class);
    }
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
