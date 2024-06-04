<?php

namespace VanDmade\Cuztomisable\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Queue\InteractsWithQueue;
use VanDmade\Cuztomisable\Models\Logs;

class LogEmail
{

    public function handle(MessageSent $event): void
    {
        // Logs the email
        Logs\Email::create([
            'to' => self::getEmails($event->message->getTo()),
            'cc' => self::getEmails($event->message->getCc()),
            'bcc' => self::getEmails($event->message->getBcc()),
            'from' => self::getEmails($event->message->getFrom(), true),
            'subject' => $event->message->getSubject(),
            'parameters' => [
                'data' => $event->data ?? null,
                'template' => $event->message->template ?? null,
            ],
        ]);
    }

    protected function getEmails($array, $returnFirst = false)
    {
        $emails = collect($array)
            ->map(fn ($item) => $item->getAddress());
        return $returnFirst ? ($emails[0] ?? null) : $emails;
    }

}
