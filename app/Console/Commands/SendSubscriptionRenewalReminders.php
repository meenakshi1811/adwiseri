<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Mail\SubscriptionRenewalReminder;

class SendSubscriptionRenewalReminders extends Command
{
    protected $signature = 'subscriptions:send-reminders';
    protected $description = 'Send subscription renewal reminders to subscribers';

    public function handle()
    {
        Log::info('Subscription reminder command started.');

        $reminderDays = [60, 30, 14, 7, 5, 2, 0]; // Reminder days
        $today = Carbon::today();
        $sentDays = []; // Track days for which reminders have been sent

        foreach ($reminderDays as $day) {
            // Calculate the target date for each reminder day
            $targetDate = $today->copy()->addDays($day);

            // Log the target date

            // Fetch subscribers whose membership expiry date matches the target date
            $subscribers = User::where('user_type', 'Subscriber')
                ->whereDate('membership_expiry_date', $targetDate)
                ->get();

            if ($subscribers->isEmpty()) {
                Log::info("No subscribers found for reminder of {$day} days remaining.");
            } else {
                foreach ($subscribers as $subscriber) {
                    // Calculate the difference in days between expiry date and today
                    $remainingDays = $today->diffInDays($subscriber->membership_expiry_date, false);

                    // Send reminder if this day has not been sent yet
                    if (!in_array($remainingDays, $sentDays)) {
                        // Send the reminder email
                        Mail::to($subscriber->email)->send(new SubscriptionRenewalReminder($subscriber, $remainingDays));

                        // Log the email sent
                        $this->info("Reminder sent to {$subscriber->email} for {$remainingDays} days remaining.");

                        // Mark this day as sent
                        $sentDays[] = $remainingDays;
                    }
                }
            }
        }

    }
}
