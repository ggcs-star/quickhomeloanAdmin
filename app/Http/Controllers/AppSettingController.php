<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppSettingController extends Controller
{
    public function index()
    {
        $setting = AppSetting::first();
        return view('app-settings.index', compact('setting'));
    }

    public function create()
    {
        $existing = AppSetting::first();
        if ($existing) {
            return redirect()->route('app-settings.edit', $existing->_id);
        }
        return view('app-settings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'splash_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'header_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = [
            'app_name' => $request->app_name,
            'is_active' => $request->is_active ?? true,
        ];

        if ($request->hasFile('app_logo')) {
            $data['app_logo'] = $request->file('app_logo')->store('app-settings', 'public');
        }
        if ($request->hasFile('splash_logo')) {
            $data['splash_logo'] = $request->file('splash_logo')->store('app-settings', 'public');
        }
        if ($request->hasFile('header_logo')) {
            $data['header_logo'] = $request->file('header_logo')->store('app-settings', 'public');
        }

        AppSetting::create($data);

        return redirect()->route('app-settings.index')->with('success', 'Settings created successfully!');
    }

    public function edit($id)
    {
        $setting = AppSetting::find($id);
        return view('app-settings.edit', compact('setting'));
    }

    public function update(Request $request, $id)
    {
        $setting = AppSetting::find($id);

        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'splash_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'header_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = [
            'app_name' => $request->app_name,
            'is_active' => $request->is_active ?? true,
        ];

        if ($request->hasFile('app_logo')) {
            if ($setting->app_logo) Storage::disk('public')->delete($setting->app_logo);
            $data['app_logo'] = $request->file('app_logo')->store('app-settings', 'public');
        }
        if ($request->hasFile('splash_logo')) {
            if ($setting->splash_logo) Storage::disk('public')->delete($setting->splash_logo);
            $data['splash_logo'] = $request->file('splash_logo')->store('app-settings', 'public');
        }
        if ($request->hasFile('header_logo')) {
            if ($setting->header_logo) Storage::disk('public')->delete($setting->header_logo);
            $data['header_logo'] = $request->file('header_logo')->store('app-settings', 'public');
        }

        $setting->update($data);

        return redirect()->route('app-settings.index')->with('success', 'Settings updated successfully!');
    }
}