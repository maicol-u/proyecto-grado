<?php

namespace App\Jobs;

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

        $invernadero = $this->lectura->sensor->crop;
        $valMax = $this->lectura->sensor->max_value;
        $valMin = $this->lectura->sensor->min_value;
        
        if ($this->lectura->value > $valMax) {
            foreach ($invernadero->users as $usuario) { 
               $usuario->notify(
                    new AlertaLecturaNotification(
                        'Humedad alta',
                        $this->lectura
                    )
                );
            }
        }

        if ($this->lectura->value < $valMin) {
            foreach ($invernadero->users as $usuario) { 
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
