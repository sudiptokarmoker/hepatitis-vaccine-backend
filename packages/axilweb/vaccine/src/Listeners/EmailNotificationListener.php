<?php

namespace Axilweb\Vaccine\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class EmailNotificationListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $data = $event->data;

        Mail::send('emails.notification', $data, function ($message) use ($data) {
            $message->to($data['email'])
                    ->subject($data['subject']);
        });
    }
}
