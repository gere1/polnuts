<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit()
    {
        $setting = Setting::current();

        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::current();

        $data = $request->validate([
            'site_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'logo' => ['nullable', 'image', 'max:4096'],
            'remove_logo' => ['nullable', 'boolean'],
            'logo_height' => ['nullable', 'integer', 'min:16', 'max:200'],
            'show_top_bar' => ['nullable', 'boolean'],
            'favicon' => ['nullable', 'image', 'max:1024'],
            'remove_favicon' => ['nullable', 'boolean'],
            'primary_color' => ['nullable', 'string', 'max:20'],
            'text_color' => ['nullable', 'string', 'max:20'],
            'header_bg_color' => ['nullable', 'string', 'max:20'],
            'footer_bg_color' => ['nullable', 'string', 'max:20'],
            'top_bar_bg_color' => ['nullable', 'string', 'max:20'],
            'top_bar_text_color' => ['nullable', 'string', 'max:20'],
            'content_bg_color' => ['nullable', 'string', 'max:20'],
            'background_mode' => ['required', 'in:' . implode(',', array_keys(Setting::BACKGROUND_MODES))],
            'gradient_top_start' => ['nullable', 'string', 'max:20'],
            'gradient_top_end' => ['nullable', 'string', 'max:20'],
            'gradient_bottom_start' => ['nullable', 'string', 'max:20'],
            'gradient_bottom_end' => ['nullable', 'string', 'max:20'],
            'background_image' => ['nullable', 'image', 'max:8192'],
            'remove_background_image' => ['nullable', 'boolean'],
            'font' => ['required', 'in:' . implode(',', array_keys(Setting::FONTS))],
            'default_locale' => ['required', 'in:' . implode(',', array_keys(Setting::LOCALES))],
            'locales' => ['nullable', 'array'],
            'locales.*' => ['in:' . implode(',', array_keys(Setting::LOCALES))],
        ]);

        // The default locale is always implicitly active — never store it redundantly
        // in the "additional locales" list.
        $data['locales'] = array_values(array_diff($data['locales'] ?? [], [$data['default_locale']]));

        if ($request->boolean('remove_logo') && $setting->logo) {
            Storage::disk('public')->delete($setting->logo);
            $data['logo'] = null;
        }
        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }
            $data['logo'] = $request->file('logo')->store('settings', 'public');
        }

        if ($request->boolean('remove_background_image') && $setting->background_image) {
            Storage::disk('public')->delete($setting->background_image);
            $data['background_image'] = null;
        }
        if ($request->hasFile('background_image')) {
            if ($setting->background_image) {
                Storage::disk('public')->delete($setting->background_image);
            }
            $data['background_image'] = $request->file('background_image')->store('settings', 'public');
        }

        if ($request->boolean('remove_favicon') && $setting->favicon) {
            Storage::disk('public')->delete($setting->favicon);
            $data['favicon'] = null;
        }
        if ($request->hasFile('favicon')) {
            if ($setting->favicon) {
                Storage::disk('public')->delete($setting->favicon);
            }
            $data['favicon'] = $request->file('favicon')->store('settings', 'public');
        }

        $data['show_top_bar'] = $request->boolean('show_top_bar');
        $data['logo_height'] = $data['logo_height'] ?? 40;

        $setting->update($data);

        return back()->with('status', 'პარამეტრები შენახულია.');
    }
}
