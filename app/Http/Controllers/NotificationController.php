<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Mark one notification as read
     * and redirect to its related page.
     */
    public function read(
        Request $request,
        string $notification
    ): RedirectResponse {

        $notificationRecord = $request->user()
            ->notifications()
            ->where('id', $notification)
            ->firstOrFail();

        if (is_null($notificationRecord->read_at)) {
            $notificationRecord->markAsRead();
        }

        $url = $notificationRecord->data['url']
            ?? route('dashboard');

        return redirect($url);
    }


    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(
        Request $request
    ): RedirectResponse {

        $request->user()
            ->unreadNotifications
            ->markAsRead();

        return back();
    }
}
