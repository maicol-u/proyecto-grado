<?php

namespace App\Listeners;

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
        $valMax = $lectura->sensor->valor_max;
        if ($lectura->valor > $valMax) {
            ProcesarAlertaLecturaJob::dispatch($lectura);
        }
    }
}
