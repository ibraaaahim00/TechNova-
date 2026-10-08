<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class FooterSettingsController extends Controller
{
    private const TRANSLATED_SETTINGS = [
        'company_name' => ['en' => 'TechNova', 'ar' => 'TechNova'],
        'footer_brand_label' => ['en' => 'SOFTWARE SOLUTIONS', 'ar' => 'حلول برمجية'],
        'footer_blurb' => ['en' => '', 'ar' => ''],
        'footer_contact_label' => ['en' => 'START A CONVERSATION', 'ar' => 'ابدأ محادثة'],
        'location' => ['en' => '', 'ar' => ''],
        'copyright_text' => ['en' => '', 'ar' => ''],
    ];

    public function edit(): View
    {
        $settings = SiteSetting::query()->get()->keyBy('key');
        $values = [];

        foreach (self::TRANSLATED_SETTINGS as $key => $defaults) {
            $setting = $settings->get($key);
            foreach (['en', 'ar'] as $locale) {
                $values[$key][$locale] = $setting?->translationFor('value', $locale, true)
                    ?? $setting?->getRawOriginal('value')
                    ?? $defaults[$locale];
            }
        }

        foreach (['contact_email', 'linkedin_url', 'github_url'] as $key) {
            $values[$key] = $settings->get($key)?->getRawOriginal('value') ?? '';
        }
        $values['logo_path'] = $settings->get('logo_path')?->getRawOriginal('value');

        return view('admin.footer-settings', compact('values'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_name.en' => ['required', 'string', 'max:255'],
            'company_name.ar' => ['required', 'string', 'max:255'],
            'footer_brand_label.en' => ['required', 'string', 'max:255'],
            'footer_brand_label.ar' => ['required', 'string', 'max:255'],
            'footer_blurb.en' => ['nullable', 'string', 'max:2000'],
            'footer_blurb.ar' => ['nullable', 'string', 'max:2000'],
            'footer_contact_label.en' => ['required', 'string', 'max:255'],
            'footer_contact_label.ar' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'email:rfc', 'max:255'],
            'location.en' => ['nullable', 'string', 'max:255'],
            'location.ar' => ['nullable', 'string', 'max:255'],
            'linkedin_url' => ['nullable', 'url:http,https', 'max:2048'],
            'github_url' => ['nullable', 'url:http,https', 'max:2048'],
            'copyright_text.en' => ['nullable', 'string', 'max:500'],
            'copyright_text.ar' => ['nullable', 'string', 'max:500'],
            'logo' => ['nullable', 'image', 'max:5120'],
        ]);

        foreach (self::TRANSLATED_SETTINGS as $key => $_defaults) {
            $setting = SiteSetting::query()->firstOrNew(['key' => $key]);
            $translations = $setting->translations ?? [];
            foreach (['en', 'ar'] as $locale) {
                $translations[$locale]['value'] = $data[$key][$locale] ?? '';
            }
            $setting->translations = $translations;
            $setting->value = $data[$key]['en'] ?? $data[$key]['ar'] ?? '';
            $setting->save();
        }

        foreach (['contact_email', 'linkedin_url', 'github_url'] as $key) {
            $setting = SiteSetting::query()->firstOrNew(['key' => $key]);
            $setting->value = $data[$key] ?? '';
            $setting->translations = [];
            $setting->save();
        }

        if ($request->hasFile('logo')) {
            $logoSetting = SiteSetting::query()->firstOrNew(['key' => 'logo_path']);
            $previousLogo = $logoSetting->getRawOriginal('value');
            $logoSetting->value = $request->file('logo')->store('cms/branding', 'public');
            $logoSetting->translations = [];
            $logoSetting->save();

            if ($previousLogo) {
                Storage::disk('public')->delete($previousLogo);
            }
        }

        return redirect()->route('admin.footer-settings.edit')->with('status', __('Footer settings saved.'));
    }
}
