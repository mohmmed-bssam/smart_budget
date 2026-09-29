<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }


    public function create()
    {
        return view('admin.users.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email',

            'password' => 'required|min:8',

            'role' => 'required|in:admin,user',

            'phone' => 'nullable|string',

            'currency' => 'nullable|string|max:10',

        ]);


        $validated['password'] = Hash::make($validated['password']);


        User::create($validated);


        flash()->success(__('messages.user_created'));


        return redirect()
            ->route('admin.users.index');
    }



    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }



    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }



    public function update(Request $request, User $user)
    {

        $validated = $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email,' . $user->id,

            'role' => 'required|in:admin,user',

            'phone' => 'nullable|string',

            'currency' => 'nullable|string|max:10',

            'is_active' => 'required|boolean',

        ]);


        $user->update($validated);
        flash()->Info(__('messages.user_updated'));
        return redirect()
            ->route('admin.users.index');
    }



    public function destroy(User $user)
    {

        $user->delete();


        flash()->warning(__('messages.user_deleted'));


        return redirect()
            ->route('admin.users.index');
    }
}