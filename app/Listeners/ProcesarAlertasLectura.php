<?php

namespace App\Listeners;

use App\Enums\EstadoAlertaSensor;
use App\Events\LecturaCreada;
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
    public function handle(LecturaCreada $event): void
    {
        $lectura = $event->lectura;
        $sensor = $lectura->sensor;

        if ($lectura->valor > $sensor->valor_max && $sensor->estado_alerta !== EstadoAlertaSensor::ALTO) {
            $sensor->estado_alerta = EstadoAlertaSensor::ALTO;
            $sensor->save();
            ProcesarAlertaLecturaJob::dispatch($lectura);
        } 

        if ($lectura->valor < $sensor->valor_min && $sensor->estado_alerta !== EstadoAlertaSensor::BAJO) {
            $sensor->estado_alerta = EstadoAlertaSensor::BAJO;
            $sensor->save();
            ProcesarAlertaLecturaJob::dispatch($lectura);
        } 
        
        if($lectura->valor >= $sensor->valor_min && $lectura->valor <= $sensor->valor_max){
            $sensor->estado_alerta = EstadoAlertaSensor::NORMAL;
            $sensor->save();
        }

    }
}
