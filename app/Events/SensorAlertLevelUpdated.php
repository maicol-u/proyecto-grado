<?php

namespace App\Events;

use App\Models\Sensor;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SensorAlertLevelUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Sensor $sensor,
        public float $value,
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('sensor.'.$this->sensor->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'sensor.alert-level-updated';
    }

    public function broadcastWith(): array
    {
        return [
            'sensor_id' => $this->sensor->id,
            'alert_level' => $this->sensor->alert_level->value,
            'value' => $this->value,
            'updated_at' => optional($this->sensor->updated_at)->toISOString(),
        ];
    }
}