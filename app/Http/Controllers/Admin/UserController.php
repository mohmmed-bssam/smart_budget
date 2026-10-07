<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display all users.
     */
    public function index()
    {
        $users = User::latest()->get();

        return view('admin.users.index', compact('users'));
    }


    /**
     * Change user role: user <-> admin
     */
    public function updateRole(User $user)
    {
        // الأدمن ما بقدر يغيّر رول نفسه
        if ($user->id === Auth::id()) {
            flash()->warning(__('messages.cannot_change_own_role'));

            return back();
        }

        $newRole = $user->role === 'admin'
            ? 'user'
            : 'admin';

        $user->update([
            'role' => $newRole,
        ]);

        flash()->info(__('messages.role_updated'));

        return back();
    }


    /**
     * Activate / deactivate user.
     */
    public function toggleStatus(User $user)
    {
        if ($user->id === Auth::id()) {
            flash()->warning(__('messages.cannot_change_own_status'));

            return back();
        }

        $user->update([
            'status' => !$user->status,
        ]);

        flash()->info(__('messages.status_updated'));

        return back();
    }


    /**
     * Delete user.
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            flash()->warning(__('messages.cannot_delete_yourself'));

            return back();
        }

        $user->delete();

        flash()->success(__('messages.user_deleted'));

        return back();
    }
}