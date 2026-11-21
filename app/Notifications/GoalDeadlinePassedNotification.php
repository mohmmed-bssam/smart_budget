<?php

namespace App\Notifications;

use App\Models\Goal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GoalDeadlinePassedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Goal $goal
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'goal_deadline_passed',

            'goal_id' => $this->goal->id,

            'goal_title' => $this->goal->title,

            'deadline' =>
                $this->goal->deadline?->format('Y-m-d'),

            'url' => route(
                'goals.show',
                $this->goal
            ),

            'icon' =>
                'fa-solid fa-calendar-xmark',

            'level' =>
                'danger',
        ];
    }
}
