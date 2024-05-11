<?php

namespace VanDmade\Cuztomisable\Mail\Authentication\Passwords;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use VanDmade\Cuztomisable\Models\Users\Passwords\Reset;

class Forgot extends Mailable
{

    use Queueable, SerializesModels;

    private $reset;

    public function __construct(Reset $reset)
    {
        $this->reset = $reset;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('cuztomisable/authentication.emails.subjects.forgot'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: config('cuztomisable.account.notifications.forgot.view'),
            with: [
                'name' => $this->reset->user->name ?? null,
                'reset' => $this->reset->code,
            ],
        );
    }

}
