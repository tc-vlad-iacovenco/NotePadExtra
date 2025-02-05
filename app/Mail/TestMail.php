<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class TestMail extends Mailable
{
    public function build(): TestMail
    {
        return $this->view('emails.test')
            ->subject('Test Email');
    }
}
