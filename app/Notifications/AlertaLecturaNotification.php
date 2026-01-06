<?php

namespace App\Notifications;

use App\Models\Lectura;
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
        public string $titulo,
        public Lectura $lectura
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
        $this->lectura->loadMissing('sensor.invernadero');

        $invernadero = $this->lectura->sensor?->invernadero;

        return (new MailMessage)
            ->subject($this->titulo)
            ->greeting('⚠️ Alerta del sistema')
            ->line("Invernadero: {$invernadero->nombre}")
            ->line("Sensor: {$this->lectura->sensor->nombre}")
            ->line("Valor detectado: {$this->lectura->valor}")
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
