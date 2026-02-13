<?php

namespace App\Listeners;

use App\Enums\SensorAlertLevel;
use App\Events\ReadingCreated;
use App\Jobs\ProcesarAlertaLecturaJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProcesarAlertasLectura
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ReadingCreated $event): void
    {
        $lectura = $event->lectura;
        $sensor = $lectura->sensor;

        if ($lectura->value > $sensor->max_value && $sensor->alert_level !== SensorAlertLevel::HIGH) {
            $sensor->alert_level = SensorAlertLevel::HIGH;
            $sensor->save();
            ProcesarAlertaLecturaJob::dispatch($lectura);
        } 

        if ($lectura->value < $sensor->min_value && $sensor->alert_level !== SensorAlertLevel::LOW) {
            $sensor->alert_level = SensorAlertLevel::LOW;
            $sensor->save();
            ProcesarAlertaLecturaJob::dispatch($lectura);
        } 
        
        if($lectura->value >= $sensor->min_value && $lectura->value <= $sensor->max_value){
            $sensor->alert_level = SensorAlertLevel::NORMAL;
            $sensor->save();
        }

    }
}
