<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Check if registration is enabled.
     */
    private function registrationEnabled(): bool
    {
        return Setting::where('key', 'registration_enabled')
            ->value('value') !== '0';
    }


    /**
     * Display the registration view.
     */
    public function create(): View|RedirectResponse
    {
        if (! $this->registrationEnabled()) {

            flash()->warning(
                __('messages.registration_disabled')
            );

            return redirect()->route('login');
        }

        return view('auth.register');
    }


    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Registration Check
        |--------------------------------------------------------------------------
        |
        | مهم جدًا:
        | حتى لو حاول شخص إرسال POST مباشرة،
        | لا نسمح بإنشاء حساب إذا التسجيل مغلق.
        |
        */

        if (! $this->registrationEnabled()) {

            flash()->warning(
                __('messages.registration_disabled')
            );

            return redirect()->route('login');
        }


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
            ],

            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Create User
        |--------------------------------------------------------------------------
        */

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make(
                $request->password
            ),
        ]);


        event(new Registered($user));


        Auth::login($user);


        return redirect(
            route('dashboard', absolute: false)
        );
    }
}
