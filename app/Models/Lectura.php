<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lectura extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id_sensor',
        'valor',
        'fecha',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'fecha' => 'datetime',
    ];

    /**
     * Lectura pertenece a un sensor
     */
    public function sensor(): BelongsTo
    {
        return $this->belongsTo(
            Sensor::class,
            'id_sensor'
        );
    }
}
