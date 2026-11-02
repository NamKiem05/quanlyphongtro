<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}