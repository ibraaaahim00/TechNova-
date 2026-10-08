@extends('layouts.admin')
@section('title', __('Account security'))
@section('crumb', __('Account security'))
@section('content')
<div class="admin-page-heading"><div><span class="admin-kicker">{{ __('ADMINISTRATION') }}</span><h1>{{ __('Account security') }}</h1><p>{{ __('Update the administrator identity and password for this CMS.') }}</p></div></div>
<form class="account-form admin-card" method="post" action="{{ route('admin.account.update') }}">@csrf @method('PUT')
    <label class="cms-field"><span>{{ __('Name') }}</span><input name="name" value="{{ old('name', $user->name) }}" required>@error('name')<small class="field-error">{{ $message }}</small>@enderror</label>
    <label class="cms-field"><span>{{ __('Email address') }}</span><input type="email" name="email" value="{{ old('email', $user->email) }}" required>@error('email')<small class="field-error">{{ $message }}</small>@enderror</label>
    <div class="account-divider"><h2>{{ __('Change password') }}</h2><p>{{ __('Leave the new password blank to keep your current password.') }}</p></div>
    <label class="cms-field"><span>{{ __('Current password') }}</span><input type="password" name="current_password" required autocomplete="current-password">@error('current_password')<small class="field-error">{{ $message }}</small>@enderror</label>
    <label class="cms-field"><span>{{ __('New password') }}</span><input type="password" name="password" minlength="12" autocomplete="new-password">@error('password')<small class="field-error">{{ $message }}</small>@enderror</label>
    <label class="cms-field"><span>{{ __('Confirm new password') }}</span><input type="password" name="password_confirmation" minlength="12" autocomplete="new-password"></label>
    <button class="button button-primary" type="submit">{{ __('Save account') }} <span>↗</span></button>
</form>
@endsection
