<?php

namespace VanDmade\Cuztomisable\Mail\Authentication\Passwords;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use VanDmade\Cuztomisable\Models\Users\User;

class Reset extends Mailable
{

    use Queueable, SerializesModels;

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('cuztomisable/authentication.emails.subjects.reset'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: config('cuztomisable.account.notifications.reset.view'),
            with: [
                'name' => $this->user->name ?? null,
            ],
        );
    }

}
