<?php

namespace App\Listeners;

use App\Enums\SensorAlertLevel;
use App\Events\ReadingCreated;
use App\Events\SensorAlertLevelUpdated;
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
        $nextAlertLevel = SensorAlertLevel::NORMAL;

        if ($lectura->value > $sensor->max_value) {
            $nextAlertLevel = SensorAlertLevel::HIGH;
        } elseif ($lectura->value < $sensor->min_value) {
            $nextAlertLevel = SensorAlertLevel::LOW;
        }

        if ($sensor->alert_level === $nextAlertLevel) {
            return;
        }

        $sensor->alert_level = $nextAlertLevel;
        $sensor->save();

        event(new SensorAlertLevelUpdated($sensor, (float) $lectura->value));

        if (in_array($nextAlertLevel, [SensorAlertLevel::HIGH, SensorAlertLevel::LOW], true)) {
            ProcesarAlertaLecturaJob::dispatch($lectura);
        }
    }
}
