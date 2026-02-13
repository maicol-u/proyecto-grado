<?php

namespace App\Services;

use App\Events\ReadingCreated;
use App\Models\Reading;

class ReadingService {

    public function store(array $data): Reading
    {
        $lectura = Reading::create([
            'sensor_id' => $data['sensor_id'],
            'value' => $data['value'],
        ]);

        event(new ReadingCreated($lectura));
        return $lectura;
    } 
}
