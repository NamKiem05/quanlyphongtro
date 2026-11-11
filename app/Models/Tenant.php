<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Tenant extends Model
{
    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'identity_number',
        'date_of_birth',
        'gender',
        'permanent_address',
        'identity_front',
        'identity_back',
        'note',
    ];

    public function contractTenants(): HasMany
    {
        return $this->hasMany(ContractTenant::class);
    }


    public function contracts(): BelongsToMany
    {
        return $this->belongsToMany(
            Contract::class,
            'contract_tenants'
        )->withPivot('is_representative')
            ->withTimestamps();
    }
    public function contractSignatures(): HasMany
    {
        return $this->hasMany(ContractSignature::class);
    }

}
