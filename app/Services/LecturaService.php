<?php

namespace App\Services;

use App\Models\Lectura;

class LecturaService {

    public function ProcesarLectura(array $data): Lectura
    {
        $lectura = Lectura::create([
            'id_sensor' => $data['id_sensor'],
            'valor' => $data['valor'],
        ]);

        return $lectura;
    } 
}
