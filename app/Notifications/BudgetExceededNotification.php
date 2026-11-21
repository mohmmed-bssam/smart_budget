<?php

namespace App\Notifications;

use App\Models\Budget;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BudgetExceededNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Budget $budget,
        public float $percentage,
        public float $exceededAmount
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
        'type' => 'budget_exceeded',

        'budget_id' => $this->budget->id,

        'percentage' =>
            $this->percentage,

        'exceeded_amount' =>
            $this->exceededAmount,

        'currency' =>
            $this->budget->base_currency
            ?? 'USD',

        'category_name' =>
            $this->budget->category?->name[app()->getLocale()]
            ?? $this->budget->category?->name['en']
            ?? '-',

        'url' => route(
            'budgets.show',
            $this->budget
        ),

        'icon' =>
            'fa-solid fa-circle-exclamation',

        'level' =>
            'danger',
    ];
}
}
