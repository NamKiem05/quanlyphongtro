<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'name',
        'unit',
        'price',
        'type',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function roomServices(): HasMany
    {
        return $this->hasMany(RoomService::class);
    }

    public function meterReadings(): HasMany
    {
        return $this->hasMany(MeterReading::class);
    }
}