<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>@yield('title', __('Studio CMS')) · TechNova</title><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&family=Noto+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">@vite(['resources/css/app.css', 'resources/js/app.js'])</head>
<body class="admin-body">
<aside class="admin-sidebar">
    <a class="admin-brand" href="{{ route('admin.dashboard') }}"><span class="brand-mark">✦</span><span>TechNova<small>{{ __('CONTENT STUDIO') }}</small></span></a>
    <button class="admin-menu-toggle" type="button" data-admin-menu-toggle aria-expanded="false" aria-controls="admin-navigation">{{ __('Menu') }} <span>☰</span></button>
    <nav class="admin-navigation" id="admin-navigation">
    <div class="sidebar-label">{{ __('WORKSPACE') }}</div><a class="admin-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><span>◫</span>{{ __('Overview') }}</a>
    <div class="sidebar-label">{{ __('CONTENT') }}</div>
    @foreach(['pages'=>'Pages','sections'=>'Homepage','services'=>'Services','projects'=>'Projects','project-categories'=>'Project categories','technologies'=>'Technologies','team'=>'Team','posts'=>'Journal','blog-categories'=>'Journal categories','testimonials'=>'Testimonials','messages'=>'Inbox','navigation'=>'Navigation'] as $type=>$label)
        <a class="admin-link {{ request()->is('admin/'.$type.'*') ? 'active' : '' }}" href="{{ route('admin.content.index', $type) }}"><span>{{ ['pages'=>'▤','sections'=>'▦','services'=>'◈','projects'=>'▧','project-categories'=>'⌗','technologies'=>'⌘','team'=>'♙','posts'=>'▤','blog-categories'=>'⌗','testimonials'=>'✧','messages'=>'✉','navigation'=>'☷'][$type] }}</span>{{ __($label) }}@if($type==='messages' && isset($unreadCount) && $unreadCount)<i>{{ $unreadCount }}</i>@endif</a>
    @endforeach
    <div class="sidebar-label">{{ __('SITE') }}</div><a class="admin-link {{ request()->routeIs('admin.footer-settings.*') ? 'active' : '' }}" href="{{ route('admin.footer-settings.edit') }}"><span>▤</span>{{ __('Footer settings') }}</a><a class="admin-link" href="{{ route('admin.account.edit') }}"><span>♙</span>{{ __('Account security') }}</a><a class="admin-link" href="{{ route('admin.content.index', 'settings') }}"><span>⚙</span>{{ __('Settings') }}</a><a class="admin-link" href="{{ route('home') }}" target="_blank"><span>↗</span>{{ __('View website') }}</a>
    </nav>
    <div class="sidebar-user"><div class="admin-avatar">{{ str(auth()->user()->name)->substr(0, 1) }}</div><span><b>{{ auth()->user()->name }}</b><small>{{ __('Administrator') }}</small></span><form method="post" action="{{ route('admin.logout') }}">@csrf<button aria-label="{{ __('Sign out') }}">↪</button></form></div>
</aside>
<main class="admin-main"><header class="admin-topbar"><div><span class="breadcrumb">{{ __('TechNova Studio') }}</span><span class="breadcrumb-sep">/</span><span>@yield('crumb', __('Overview'))</span></div><div class="topbar-actions"><a href="{{ url()->current() }}?lang={{ app()->getLocale() === 'ar' ? 'en' : 'ar' }}" lang="{{ app()->getLocale() === 'ar' ? 'en' : 'ar' }}">{{ app()->getLocale() === 'ar' ? 'English' : 'العربية' }}</a><a href="{{ route('home') }}" target="_blank">{{ __('Open live site') }} ↗</a></div></header><div class="admin-content">@if(session('status'))<div class="admin-alert">{{ __(session('status')) }}</div>@endif
@yield('content')
</div></main>
</body></html>
