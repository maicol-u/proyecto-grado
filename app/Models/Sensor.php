<?php

namespace App\Models;

use App\Enums\SensorAlertLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sensor extends Model
{

    protected $fillable = [
        'crop_id',
        'type_id',
        'name',
        'model',
        'unit',
        'status',
        'alert_level',
        'reading_interval',
        'min_value',
        'max_value',
    ];

    protected $casts = [
        'reading_interval' => 'integer',
        'min_value' => 'decimal:2',
        'max_value' => 'decimal:2',
        'alert_level' => SensorAlertLevel::class,
    ];

    /**
     * Sensor pertenece a un invernadero
     */
    public function crop(): BelongsTo
    {
        return $this->belongsTo(
            Crop::class,
            'crop_id'
        );
    }

    /**
     * Tipo de sensor (humedad, temperatura, etc.)
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(
            SensorType::class,
            'type_id'
        );
    }

    /**
     * Lecturas del sensor
     */
    public function readings(): HasMany
    {
        return $this->hasMany(
            Reading::class,
            'sensor_id'
        );
    }
}
