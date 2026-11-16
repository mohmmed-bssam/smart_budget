<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');

        return view('admin.settings.index', compact('settings'));
    }


    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => [
                'required',
                'string',
                'max:100',
            ],

            'default_currency' => [
                'required',
                'string',
                'size:3',
            ],

            'support_email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'items_per_page' => [
                'required',
                'integer',
                'min:5',
                'max:100',
            ],

            'registration_enabled' => [
                'nullable',
                'boolean',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Registration Setting
        |--------------------------------------------------------------------------
        |
        | Checkbox لا يرسل قيمة إذا كان غير محدد،
        | لذلك نحوله يدويًا إلى 1 أو 0.
        |
        */

        $validated['registration_enabled'] =
            $request->boolean('registration_enabled')
                ? '1'
                : '0';


        /*
        |--------------------------------------------------------------------------
        | Save Settings
        |--------------------------------------------------------------------------
        */

        foreach ($validated as $key => $value) {

            Setting::updateOrCreate(
                [
                    'key' => $key,
                ],
                [
                    'value' => $value,
                ]
            );
        }


        flash()->success(
            __('messages.settings_updated')
        );


        return back();
    }
}
