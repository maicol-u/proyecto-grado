<?php

namespace App\Jobs;

use App\Enums\SensorAlertLevel;
use App\Models\Alert;
use App\Models\Reading;
use App\Notifications\AlertaLecturaNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcesarAlertaLecturaJob implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $backoff = 10; // segundos

    /**
     * Create a new job instance.
     */
    public function __construct(public Reading $lectura)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->lectura->load([
            'sensor.crop.users'
        ]);

        $sensor = $this->lectura->sensor;
        $invernadero = $sensor->crop;

        $valMax = $sensor->max_value;
        $valMin = $sensor->min_value;

        $title = null;
        $type = null;
        
        if ($this->lectura->value > $valMax) {
            $title = 'Humedad alta';
            $type = SensorAlertLevel::HIGH;
        }

        if ($this->lectura->value < $valMin) {
           $title = 'Humedad baja';
           $type = SensorAlertLevel::LOW;
        }

        $alert = Alert::create([
            'sensor_id' => $sensor->id,
            'value' => $this->lectura->value,
            'type' => $type,
            'message' => $title,
            'triggered_at' => now(),
        ]);

        foreach ($invernadero->users as $usuario) { 
            $usuario->notify(
                new AlertaLecturaNotification(
                    $title,
                    $this->lectura
                )
            );
        }

    }
}
