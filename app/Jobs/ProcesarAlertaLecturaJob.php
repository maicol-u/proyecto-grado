<?php

namespace App\Jobs;

use App\Enums\SensorAlertLevel;
use App\Models\Alert;
use App\Models\Reading;
use App\Notifications\AlertaLecturaNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\TextbeltService;

class ProcesarAlertaLecturaJob implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $backoff = 10; // segundos

    /**
     * Create a new job instance.
     */
    public function __construct(public Reading $reading)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(TextbeltService  $textbelt): void
    {
        $this->reading->load([
            'sensor.crop.users'
        ]);

        $sensor = $this->reading->sensor;
        $crop = $sensor->crop; 

        $valMax = $sensor->max_value;
        $valMin = $sensor->min_value;

        $title = null;
        $type = null;
        
        if ($this->reading->value > $valMax) {
            $title = 'Humedad alta';
            $type = SensorAlertLevel::HIGH;
        }

        if ($this->reading->value < $valMin) {
           $title = 'Humedad baja';
           $type = SensorAlertLevel::LOW;
        }

        Alert::create([
            'sensor_id' => $sensor->id,
            'value' => $this->reading->value,
            'type' => $type,
            'message' => $title,
            'triggered_at' => now(),
        ]);

        foreach ($crop->users as $usuario) { 

            // Email notification
            $usuario->notify(
                new AlertaLecturaNotification(
                    $title,
                    $this->reading
                )
            );

            // SMS notification
            $date = $this->reading->recorded_at?->format('d/m/Y H:i:s') ?? 'No disponible';
            if ($usuario->phone_number) {
                $message = "Alerta en humedad del suelo: {$title}\n
Invernadero: {$crop->name}\n
Sensor: {$sensor->name}\nValor detectado: {$this->reading->value} {$sensor->unit}\n
Fecha y hora de la lectura: {$date}\n
Sistema de monitorización.\n";
             
                $textbelt->send($usuario->phone_number, $message);
            }
        }

    }
}
