<?php

namespace App\Notifications;

use App\Models\Reading;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlertaLecturaNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $title,
        public Reading $reading
    )
    {
        
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $this->reading->loadMissing('sensor.crop');

        $crop = $this->reading->sensor?->crop;
        $formattedRecordedAt = $this->reading->recorded_at?->format('d/m/Y h:i:s A') ?? 'No disponible';

        return (new MailMessage)
            ->subject($this->title)
            ->greeting('⚠️ Alerta en humedad del suelo - '.$this->title)
            ->line("Invernadero: {$crop->name}")
            ->line("Sensor: {$this->reading->sensor->name}")
            ->line("Valor detectado: {$this->reading->value} {$this->reading->sensor->unit}")
            ->line("Fecha y hora de la lectura: {$formattedRecordedAt}")
            ->line('Se ha detectado una lectura fuera del rango permitido.')
            ->salutation('Sistema de Monitoreo de Invernaderos');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
