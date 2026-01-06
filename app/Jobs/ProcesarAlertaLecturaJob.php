<?php

namespace App\Jobs;

use App\Models\Lectura;
use App\Notifications\AlertaLecturaNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcesarAlertaLecturaJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Lectura $lectura)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->lectura->load([
            'sensor.invernadero.usuarios'
        ]);

        $invernadero = $this->lectura->sensor->invernadero;
        $valMax = $this->lectura->sensor->valor_max;

        dump('Job ejecutado', $valMax);
        if ($this->lectura->temperatura > $valMax) {
            foreach ($invernadero->usuarios as $usuario) { 
               $usuario->notify(
                    new AlertaLecturaNotification(
                        'Humedad baja',
                        $this->lectura
                    )
                );
            }
        }
    }
}
