<?php

namespace App\Notifications;

use Dotenv\Parser\Value;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Sensor extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $value;
    public function __construct($value)
    {
        $this->value = $value;
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
        return (new MailMessage)
                    // ->subject('XD')
                    ->from('correo@remite.com', 'Nombre del Remitente')
                    ->line('La Temperatura superó lo normal')
                    ->line('Actual temperatura: ' . $this->value . ' C')
                    // ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
