<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'sensor_id',
        'value',
        'type',
        'message',
        'triggered_at',
        'resolved_at',
        'recorded_at',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'triggered_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];


    /**
     * Relación: una alerta pertenece a un sensor
     */
    public function sensor()
    {
        return $this->belongsTo(Sensor::class);
    }
}
