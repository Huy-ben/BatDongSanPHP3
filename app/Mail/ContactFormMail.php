<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public array $contactData)
    {
    }

    public function build(): self
    {
        return $this
            ->subject('Lien he moi: '.$this->contactData['subject'])
            ->view('emails.contact-form');
    }
}
