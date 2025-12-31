<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invernadero extends Model
{
    protected $fillable = [
        'nombre',
        'ubicacion',
        'descripcion',
    ];

    /**
     * Relación muchos a muchos con usuarios
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'usuario_invernaderos', 'id_invernadero', 'id_usuario');
    }

    /**
     * Sensores del invernadero
     */
    public function sensores(): HasMany
    {
        return $this->hasMany(Sensor::class, 'id_sensor');
    }
}
