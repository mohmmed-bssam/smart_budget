<?php

namespace App\Notifications;

use App\Models\Goal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GoalCompletedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Goal $goal
    ) {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
{
    return [
        'type' => 'goal_completed',

        'goal_id' =>
            $this->goal->id,

        'goal_title' =>
            $this->goal->title,

        'amount' =>
            $this->goal->target_amount,

        'currency' =>
            $this->goal->currency,

        'url' => route(
            'goals.show',
            $this->goal
        ),

        'icon' =>
            'fa-solid fa-trophy',

        'level' =>
            'success',
    ];
}
}
