<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioInvernadero extends Model
{
    
    protected $table = 'usuario_invernaderos';

     protected $fillable = [
        'id_usuario',
        'id_invernadero',
        'rol'
    ];

}
