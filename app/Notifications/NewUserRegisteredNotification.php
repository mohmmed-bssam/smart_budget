<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewUserRegisteredNotification extends Notification
{
    use Queueable;

    public function __construct(
        public User $registeredUser
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_user_registered',

            'user_id' =>
                $this->registeredUser->id,

            'user_name' =>
                $this->registeredUser->name,

            'user_email' =>
                $this->registeredUser->email,

            'url' =>
                route('admin.users.index'),

            'icon' =>
                'fa-solid fa-user-plus',

            'level' =>
                'success',
        ];
    }
}
