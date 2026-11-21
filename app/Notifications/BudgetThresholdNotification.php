<?php

namespace App\Notifications;

use App\Models\Budget;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BudgetThresholdNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Budget $budget,
        public float $percentage
    ) {
        //
    }

    /**
     * Notification channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Database notification data.
     */
  public function toArray(object $notifiable): array
{
    return [
        'type' => 'budget_threshold',

        'budget_id' => $this->budget->id,

        'percentage' => $this->percentage,

        'category_name' =>
            $this->budget->category?->name[app()->getLocale()]
            ?? $this->budget->category?->name['en']
            ?? '-',

        'url' => route(
            'budgets.show',
            $this->budget
        ),

        'icon' =>
            'fa-solid fa-triangle-exclamation',

        'level' =>
            'warning',
    ];
}
}
