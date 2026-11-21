<?php

namespace App\Console\Commands;

use App\Models\Goal;
use App\Models\User;
use App\Notifications\GoalDeadlineReminderNotification;
use App\Notifications\GoalDeadlinePassedNotification;
use Illuminate\Console\Command;

class SendGoalDeadlineReminders extends Command
{
    protected $signature =
        'goals:deadline-reminders';

    protected $description =
        'Send goal deadline reminders and expired deadline notifications';


    public function handle(): int
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Goals with deadline in 3 days
        |--------------------------------------------------------------------------
        */

        $reminderDate = today()
            ->addDays(3)
            ->toDateString();


        $reminderGoals = Goal::where(
                'status',
                'active'
            )
            ->whereNotNull('deadline')
            ->whereDate(
                'deadline',
                $reminderDate
            )
            ->get();


        foreach ($reminderGoals as $goal) {

            $user = User::find(
                $goal->user_id
            );

            if (! $user) {
                continue;
            }


            $alreadyNotified = $user
                ->notifications()
                ->where(
                    'data->type',
                    'goal_deadline_reminder'
                )
                ->where(
                    'data->goal_id',
                    $goal->id
                )
                ->exists();


            if ($alreadyNotified) {
                continue;
            }


            $user->notify(
                new GoalDeadlineReminderNotification(
                    $goal
                )
            );
        }



        /*
        |--------------------------------------------------------------------------
        | 2. Goals whose deadline has passed
        |--------------------------------------------------------------------------
        */

        $expiredGoals = Goal::where(
                'status',
                'active'
            )
            ->whereNotNull('deadline')
            ->whereDate(
                'deadline',
                '<',
                today()
            )
            ->get();


        foreach ($expiredGoals as $goal) {

            $user = User::find(
                $goal->user_id
            );

            if (! $user) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate
            |--------------------------------------------------------------------------
            */

            $alreadyNotified = $user
                ->notifications()
                ->where(
                    'data->type',
                    'goal_deadline_passed'
                )
                ->where(
                    'data->goal_id',
                    $goal->id
                )
                ->exists();


            if ($alreadyNotified) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Send Notification
            |--------------------------------------------------------------------------
            */

            $user->notify(
                new GoalDeadlinePassedNotification(
                    $goal
                )
            );
        }


        $this->info(
            'Goal deadline notifications checked successfully.'
        );


        return self::SUCCESS;
    }
}