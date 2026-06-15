<?php

namespace App\Events;

use App\Models\Reading;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReadingCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Reading $lectura)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('sensor.'.$this->lectura->sensor_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'reading.created';
    }

    public function broadcastWith(): array
    {
        return [
            'reading_id' => $this->lectura->id,
            'sensor_id' => $this->lectura->sensor_id,
            'value' => (float) $this->lectura->value,
            'time' => optional($this->lectura->recorded_at)->toISOString(),
        ];
    }
}
