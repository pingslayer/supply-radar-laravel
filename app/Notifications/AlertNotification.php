<?php

namespace App\Notifications;

use App\Models\Alert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Alert $alert)
    {
    }

    public function via($notifiable): array
    {
        // By default, 'mail' will write to storage/logs/laravel.log if MAIL_MAILER=log
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Disruption Alert: ' . $this->alert->disruption->title)
                    ->line('Your supply chain might be affected by: ' . $this->alert->disruption->title)
                    ->line('Risk Score: ' . $this->alert->risk_score)
                    ->action('View details in Dashboard', url('/'));
    }
}
