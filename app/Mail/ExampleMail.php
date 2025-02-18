<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExampleMail extends Mailable
{
     use Queueable, SerializesModels;

    public function build()
    {
        // For a plain text email
        return $this->subject('Subject of the Email')
                    ->text('This is the raw text content of the email.');

        // OR

        // For an HTML email
        return $this->subject('Subject of the Email')
                    ->html('<h1>Hello!</h1><p>This is a test email sent from Laravel.</p>');
    }
}
