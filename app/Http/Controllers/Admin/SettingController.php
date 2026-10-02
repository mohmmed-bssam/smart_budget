<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::latest()->get();

        return view('admin.settings.index', compact('settings'));
    }


    public function create()
    {
        return view('admin.settings.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:settings,key',
            'value' => 'nullable|string',
        ]);


        Setting::create($validated);


        flash()->success(__('messages.setting_created'));


        return redirect()
            ->route('admin.settings.index');
    }


    public function show(Setting $setting)
    {
        return view('admin.settings.show', compact('setting'));
    }


    public function edit(Setting $setting)
    {
        return view('admin.settings.edit', compact('setting'));
    }


    public function update(Request $request, Setting $setting)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:settings,key,' . $setting->id,
            'value' => 'nullable|string',
        ]);


        $setting->update($validated);


        flash()->info(__('messages.setting_updated'));


        return redirect()
            ->route('admin.settings.index');
    }


    public function destroy(Setting $setting)
    {
        $setting->delete();


        flash()->warning(__('messages.setting_deleted'));


        return redirect()
            ->route('admin.settings.index');
    }
}
