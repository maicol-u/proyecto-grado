<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reading extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'sensor_id',
        'value',
        'recorded_at',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'recorded_at' => 'datetime',
    ];

    /**
     * Lectura pertenece a un sensor
     */
    public function sensor(): BelongsTo
    {
        return $this->belongsTo(
            Sensor::class,
            'sensor_id'
        );
    }
}
