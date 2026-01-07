<?php

namespace App\Jobs;

use App\Models\Lectura;
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
        $valMin = $this->lectura->sensor->valor_min;
        
        if ($this->lectura->valor > $valMax) {
            foreach ($invernadero->usuarios as $usuario) { 
               $usuario->notify(
                    new AlertaLecturaNotification(
                        'Humedad alta',
                        $this->lectura
                    )
                );
            }
        }

        if ($this->lectura->valor < $valMin) {
            foreach ($invernadero->usuarios as $usuario) { 
               $usuario->notify(
                    new AlertaLecturaNotification(
                        'Humedad Baja',
                        $this->lectura
                    )
                );
            }
        }
    }
}
