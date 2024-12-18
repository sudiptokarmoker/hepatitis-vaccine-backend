<?php

namespace Axilweb\Vaccine\Listeners;

use Axilweb\Vaccine\Events\VaccineEmailNotificationToEvent;
use Axilweb\Vaccine\Mail\SendVaccineUserNotificationEmailMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
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
    public function handle(VaccineEmailNotificationToEvent $event): void
    {

        Mail::to($event->email)->send(new SendVaccineUserNotificationEmailMail($event->data));

        // Log::info($event);
        // $data = $event->data;


        /*
        'email' => $user->email,
        'title' => 'Vacination Scheduled',
        'body' => 'Scheulded successfully'
    */

        //Mail::to('sudiptocsi@gmail.com')->send(new SendVaccineUserNotificationEmailMail($event->data));

        //Mail::to($data['email'])->send(new SendVaccineUserNotificationEmailMail($data));

        /*
        Mail::send('emails.notification', $data, function ($message) use ($data) {
            $message->to($data['email'])
                    ->subject($data['title']);
        });
        */
    }
}
