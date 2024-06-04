<?php

namespace VanDmade\Cuztomisable\Mail;

use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Illuminate\Mail\Mailable as BaseMailable;

abstract class VanDmadeMailable extends BaseMailable
{

    public function send($mailer)
    {
        // Initializes properties on the Swift Message object
        $this->withSymfonyMessage(function($message) {
            $message->template = get_class($this);
        });
        parent::send($mailer);
    }

}