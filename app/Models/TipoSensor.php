<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoSensor extends Model
{
    protected $table = 'tipos_sensor';

    protected $fillable = [
        'nombre',
        'unidad'
    ];

    /**
     * Un tipo de sensor puede tener muchos sensores
     */
    public function sensores(): HasMany
    {
        return $this->hasMany(Sensor::class, 'id_tipo');
    }
}
