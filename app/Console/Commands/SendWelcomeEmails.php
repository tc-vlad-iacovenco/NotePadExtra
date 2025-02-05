<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Console\Command;

class SendWelcomeEmails extends Command
{
    protected $signature = 'emails:send-welcome';
    protected $description = 'Send welcome emails to recently verified users';

    public function handle(): void
    {
        $users = User::unwelcomed()->get();

        foreach ($users as $user) {
            $user->notify(new WelcomeNotification());
            $user->welcome_email_sent_at = now();
            $user->save();
        }

        $this->info("Sent {$users->count()} welcome emails.");
    }
}
