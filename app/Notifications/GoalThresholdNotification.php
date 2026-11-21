<?php

namespace App\Notifications;

use App\Models\Goal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GoalThresholdNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Goal $goal,
        public float $percentage
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
        'type' => 'goal_threshold',

        'goal_id' =>
            $this->goal->id,

        'goal_title' =>
            $this->goal->title,

        'percentage' =>
            $this->percentage,

        'url' => route(
            'goals.show',
            $this->goal
        ),

        'icon' =>
            'fa-solid fa-bullseye',

        'level' =>
            'warning',
    ];
}
}