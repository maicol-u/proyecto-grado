<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sensor extends Model
{
    protected $table = 'sensores';

    protected $fillable = [
        'id_invernadero',
        'id_tipo',
        'nombre',
        'modelo',
        'unidad',
        'estado',
        'intervalo_lectura',
        'valor_min',
        'valor_max',
    ];

    protected $casts = [
        'intervalo_lectura' => 'integer',
        'valor_min' => 'decimal:2',
        'valor_max' => 'decimal:2',
    ];

     /**
     * Sensor pertenece a un invernadero
     */
    public function invernadero(): BelongsTo
    {
        return $this->belongsTo(
            Invernadero::class,
            'id_invernadero'
        );
    }

    /**
     * Tipo de sensor (humedad, temperatura, etc.)
     */
    public function tipo(): BelongsTo
    {
        return $this->belongsTo(
            TipoSensor::class,
            'id_tipo'
        );
    }

    /**
     * Lecturas del sensor
     */
    public function lecturas(): HasMany
    {
        return $this->hasMany(
            Lectura::class,
            'id_sensor'
        );
    }
}
