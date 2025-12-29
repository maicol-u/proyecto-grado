<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Invernadero extends Model
{
    protected $fillable = [
        'nombre',
        'ubicacion',
        'descripcion',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'usuario_invernaderos', 'id_invernadero', 'id_usuario');
    }
    
}
