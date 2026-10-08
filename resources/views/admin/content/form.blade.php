@extends('layouts.admin')
@section('title', __($record ? 'Edit' : 'Add').' '.__(str($config['label'])->singular()->lower()->toString()))
@section('crumb', __($config['label']))
@section('content')
<div class="admin-page-heading">
    <div><span class="admin-kicker"><a href="{{ route('admin.content.index', $type) }}">{{ __($config['label']) }}</a> / {{ $record ? __('EDIT') : __('NEW') }}</span><h1>{{ $record ? __('Edit item') : __('Create item') }}</h1><p>{{ __('Update the details for this item. Published changes are visible on the website right away.') }}</p></div>
    <a class="text-link" href="{{ route('admin.content.index', $type) }}">← {{ __('Back to') }} {{ __($config['label']) }}</a>
</div>
<form class="cms-form" method="post" enctype="multipart/form-data" action="{{ $record ? route('admin.content.update', [$type, $record->id]) : route('admin.content.store', $type) }}">
    @csrf @if($record) @method('PUT') @endif
    <div class="cms-form-grid">
        <section class="admin-card cms-fields">
            <div class="admin-card-heading"><div><h2>{{ __('Content details') }}</h2><p>{{ __('Fields marked as required must be filled in.') }}</p></div></div>
            <div class="form-grid">
                @foreach($config['fields'] as $name => $kind)
                    @php
                        $localized = in_array($name, $config['translated_fields'], true)
                            && ! ($type === 'settings' && in_array(old('key', $record?->key), ['logo_path', 'favicon_path'], true));
                    @endphp
                    @if($localized)
                        <div class="localized-field-group {{ in_array($kind, ['textarea', 'richtext', 'list'], true) ? 'wide-field' : '' }}">
                            <span class="localized-field-label">{{ ucfirst(__(str($name)->replace('_', ' ')->lower()->toString())) }}</span>
                            <div class="localized-columns">
                                @foreach(['en' => 'English', 'ar' => 'العربية'] as $locale => $localeName)
                                    @php
                                        $translation = old("translations.$locale.$name", $record?->translationFor($name, $locale, $locale === 'en'));
                                        $required = in_array($name, ['title', 'name', 'role', 'person_name', 'quote'], true);
                                        $inputName = "translations[$locale][$name]";
                                        $errorKey = "translations.$locale.$name";
                                    @endphp
                                    <label class="cms-field locale-field" lang="{{ $locale }}" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
                                        <span>{{ $localeName }}@if($required) <b>*</b>@endif</span>
                                        @if(in_array($kind, ['textarea', 'richtext', 'list'], true))
                                            <textarea name="{{ $inputName }}" rows="{{ $kind === 'richtext' ? 10 : 4 }}">{{ is_array($translation) ? implode("\n", $translation) : $translation }}</textarea>
                                        @else
                                            <input type="text" name="{{ $inputName }}" value="{{ is_array($translation) ? implode(', ', $translation) : $translation }}">
                                        @endif
                                        @error($errorKey)<small class="field-error">{{ $message }}</small>@enderror
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @continue
                    @endif
                    @php $value = old($name, $record?->{$name}); @endphp
                    <label class="cms-field {{ in_array($kind, ['textarea', 'richtext', 'list', 'image'], true) ? 'wide-field' : '' }}">
                        <span>{{ ucfirst(__(str($name)->replace('_', ' ')->lower()->toString())) }}@if(in_array($name, ['slug', 'key'], true) && ! $record) <b>*</b>@endif</span>
                        @if($kind === 'textarea' || $kind === 'richtext')
                            <textarea name="{{ $name }}" rows="{{ $kind === 'richtext' ? 12 : 4 }}">{{ $value }}</textarea>
                        @elseif($kind === 'list')
                            <textarea name="{{ $name }}" rows="5" placeholder="{{ __('One item per line') }}">{{ is_array($value) ? implode("\n", $value) : $value }}</textarea><small>{{ __('Enter one item per line.') }}</small>
                        @elseif($kind === 'links')
                            <textarea name="{{ $name }}" rows="5" placeholder="LinkedIn|https://...">{{ is_array($value) ? collect($value)->map(fn ($url, $label) => $label.'|'.$url)->implode("\n") : $value }}</textarea><small>{{ __('One link per line in label|URL format. Only HTTP and HTTPS links are saved.') }}</small>
                        @elseif($kind === 'boolean')
                            <span class="toggle-field"><input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $record?->{$name} ?? false))><span>{{ ucfirst(__(str($name)->replace('_', ' ')->lower()->toString())) }}</span></span>
                        @elseif($kind === 'image')
                            @if($value)<img class="image-preview" src="{{ Storage::disk('public')->url($value) }}" alt="{{ __('Current image') }}">@endif<input type="file" name="{{ $name }}" accept="image/jpeg,image/png,image/webp,image/gif"><small>{{ __('JPG, PNG, WebP or GIF. Up to 5 MB.') }}</small>
                        @elseif($kind === 'setting_value')
                            @if($value)<img class="image-preview" src="{{ Storage::disk('public')->url($value) }}" alt="{{ __('Current brand asset') }}">@endif<input type="file" name="{{ $name }}" accept="image/jpeg,image/png,image/webp,image/gif"><small>{{ __('Upload a JPG, PNG, WebP or GIF up to 5 MB.') }}</small>
                        @elseif($kind === 'project_category' || $kind === 'blog_category')
                            <select name="{{ $name }}"><option value="">{{ __('No category') }}</option>@foreach(($kind === 'project_category' ? \App\Models\ProjectCategory::query()->orderBy('name')->get() : \App\Models\BlogCategory::query()->orderBy('name')->get()) as $option)<option value="{{ $option->id }}" @selected($value == $option->id)>{{ $option->name }}</option>@endforeach</select>
                        @elseif($kind === 'technology_list')
                            <select name="technologies[]" multiple size="5">@foreach(\App\Models\Technology::query()->orderBy('name')->get() as $option)<option value="{{ $option->id }}" @selected($record?->technologies?->contains('id', $option->id))>{{ $option->name }}</option>@endforeach</select><small>{{ __('Hold Ctrl or Command to select multiple.') }}</small>
                        @elseif($kind === 'date' || $kind === 'datetime')
                            <input type="{{ $kind === 'date' ? 'date' : 'datetime-local' }}" name="{{ $name }}" value="{{ $value ? \Illuminate\Support\Carbon::parse($value)->format($kind === 'date' ? 'Y-m-d' : 'Y-m-d\TH:i') : '' }}">
                        @elseif($kind === 'number')
                            <input type="number" min="0" name="{{ $name }}" value="{{ $value ?? 0 }}">
                        @else
                            <input type="{{ $kind === 'url' ? 'url' : 'text' }}" name="{{ $name }}" value="{{ $value }}" @readonly(in_array($name, ['key'], true) && $record) @readonly($type === 'pages' && $name === 'slug')>
                        @endif
                        @error($name)<small class="field-error">{{ __($message) }}</small>@enderror
                    </label>
                @endforeach
            </div>
        </section>
        <aside class="admin-card publish-card"><h2>{{ __('Publishing') }}</h2><p>{{ __('Save your changes when you are ready.') }}</p><div class="publish-state"><span class="publish-dot"></span>{{ $record ? __('Saved content') : __('New content') }}</div><button class="button button-primary" type="submit">{{ $record ? __('Save changes') : __('Create item') }} <span>↗</span></button><a class="cancel-link" href="{{ route('admin.content.index', $type) }}">{{ __('Cancel') }}</a></aside>
    </div>
</form>
@endsection
