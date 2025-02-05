<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to NotePadExtra')
            ->line('Welcome to NotePadExtra! We\'re excited to have you on board.')
            ->line('Start creating notes and insights today!')
            ->action('Go to Dashboard', url('/dashboard'));
    }
}
