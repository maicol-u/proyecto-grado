<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoSensor extends Model
{
    protected $table = 'tipos_sensor';

    protected $fillable = [
        'nombre',
        'unidad'
    ];
}
