<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SensorType extends Model
{

    protected $fillable = [
        'name',
        'unit',
        'symbol'
    ];

    /**
     * Un tipo de sensor puede tener muchos sensores
     */
    public function sensors(): HasMany
    {
        return $this->hasMany(Sensor::class, 'type_id');
    }
}
