@extends('layouts.admin')
@section('title', __('Footer settings'))
@section('crumb', __('Footer settings'))
@section('content')
<div class="admin-page-heading"><div><span class="admin-kicker">{{ __('PUBLIC WEBSITE') }}</span><h1>{{ __('Footer settings') }}</h1><p>{{ __('Edit the information and contact details shown in the public website footer.') }}</p></div><a class="text-link" href="{{ route('admin.content.index', 'navigation') }}">{{ __('Manage footer links') }} ↗</a></div>
<form class="cms-form" method="post" enctype="multipart/form-data" action="{{ route('admin.footer-settings.update') }}">
    @csrf @method('PUT')
    <div class="cms-form-grid">
        <section class="admin-card cms-fields">
            <div class="admin-card-heading"><div><h2>{{ __('Brand and footer text') }}</h2><p>{{ __('Enter Arabic and English content separately; each language displays on its matching website.') }}</p></div></div>
            <div class="form-grid">
                <label class="cms-field wide-field"><span>{{ __('Company logo') }}</span>
                    @if($values['logo_path'])<img class="image-preview" src="{{ Storage::disk('public')->url($values['logo_path']) }}" alt="{{ __('Current image') }}">@endif
                    <input type="file" name="logo" accept="image/jpeg,image/png,image/webp,image/gif"><small>{{ __('Leave empty to keep the current logo. JPG, PNG, WebP or GIF, up to 5 MB.') }}</small>
                    @error('logo')<small class="field-error">{{ __($message) }}</small>@enderror
                </label>
                @foreach(['company_name' => 'Company name', 'footer_brand_label' => 'Brand subtitle', 'footer_blurb' => 'Footer description', 'footer_contact_label' => 'Contact heading', 'location' => 'Location', 'copyright_text' => 'Copyright text'] as $key => $label)
                    <div class="localized-field-group wide-field">
                        <span class="localized-field-label">{{ __($label) }}</span>
                        <div class="localized-columns">
                            @foreach(['en' => 'English', 'ar' => 'العربية'] as $locale => $localeName)
                                <label class="cms-field locale-field" lang="{{ $locale }}" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
                                    <span>{{ $localeName }}@if(in_array($key, ['company_name', 'footer_brand_label', 'footer_contact_label'], true)) <b>*</b>@endif</span>
                                    @if(in_array($key, ['footer_blurb', 'copyright_text'], true))
                                        <textarea name="{{ $key }}[{{ $locale }}]" rows="3">{{ old($key.'.'.$locale, $values[$key][$locale]) }}</textarea>
                                    @else
                                        <input type="text" name="{{ $key }}[{{ $locale }}]" value="{{ old($key.'.'.$locale, $values[$key][$locale]) }}">
                                    @endif
                                    @error($key.'.'.$locale)<small class="field-error">{{ __($message) }}</small>@enderror
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        <section class="admin-card cms-fields">
            <div class="admin-card-heading"><div><h2>{{ __('Contact and social links') }}</h2><p>{{ __('These values update the contact area and social icons in the public footer.') }}</p></div></div>
            <div class="form-grid">
                <label class="cms-field"><span>{{ __('Contact email') }} <b>*</b></span><input type="email" name="contact_email" value="{{ old('contact_email', $values['contact_email']) }}" required>@error('contact_email')<small class="field-error">{{ __($message) }}</small>@enderror</label>
                <label class="cms-field"><span>LinkedIn URL</span><input type="url" name="linkedin_url" value="{{ old('linkedin_url', $values['linkedin_url']) }}" placeholder="https://www.linkedin.com/company/...">@error('linkedin_url')<small class="field-error">{{ __($message) }}</small>@enderror</label>
                <label class="cms-field"><span>GitHub URL</span><input type="url" name="github_url" value="{{ old('github_url', $values['github_url']) }}" placeholder="https://github.com/...">@error('github_url')<small class="field-error">{{ __($message) }}</small>@enderror</label>
            </div>
        </section>
        <aside class="admin-card publish-card"><h2>{{ __('Publishing') }}</h2><p>{{ __('Changes appear on the public website as soon as you save.') }}</p><div class="publish-state"><span class="publish-dot"></span>{{ __('Public footer') }}</div><button class="button button-primary" type="submit">{{ __('Save footer settings') }} <span>↗</span></button></aside>
    </div>
</form>
@endsection
