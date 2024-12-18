<?php

namespace App\Console\Commands;

use Axilweb\Vaccine\Events\VaccineEmailNotificationToEvent;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VaccineScheduleCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vaccine:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /**
         * user + user vaccination details + vaccination details
         */
        $results = DB::table('users')
            ->leftJoin('user_vaccination_details', 'users.id', '=', 'user_vaccination_details.user_id')
            ->leftJoin('vaccination_center', 'vaccination_center.id', '=', 'user_vaccination_details.center_id')
            ->select(
                'users.id as user_id',
                'users.first_name as first_name',
                'users.last_name as last_name',
                'users.email as email',
                'user_vaccination_details.vaccine_scheduled_date as vaccine_scheduled_date',
                'user_vaccination_details.status as status',
                'vaccination_center.center_name as center_name'
            )
            //->where('users.isAdmin', '=', false) // Check if the user is not an admin
            ->get();

        if ($results->count()) {
            foreach ($results as $item) {
                if (!empty($item->vaccine_scheduled_date)) {
                    $scheduledDate = Carbon::parse($item->vaccine_scheduled_date);
                    $oneDayBefore = $scheduledDate->copy()->subDay();

                    if (Carbon::now()->isSameDay($oneDayBefore)) {
                        // Your logic when today is one day before the scheduled date
                        //echo "Reminder: One day left for vaccination for user {$item->first_name} {$item->last_name}.";
                        /**
                         * send email to user
                         */
                        $mailData = [
                            'title' => 'Vacination Scheduled Reminder',
                            'body' => 'Hello '. $item->first_name .', Tomorrow is your vaccination date : ' . $item->vaccine_scheduled_date. ' Center is: '.$item->center_name . ' Kindly attend at morning. Thank You'
                        ];
                        // Dispatch the event
                        VaccineEmailNotificationToEvent::dispatch($item->email, $mailData);
                    }
                }
            }
        }
    }
}
