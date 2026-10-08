<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $siteSettings['seo_title'] ?? __('TechNova — Software Solutions'))</title>
    <meta name="description" content="@yield('meta_description', $siteSettings['seo_description'] ?? __('Digital products designed and engineered for ambitious teams.'))">
    <meta property="og:title" content="@yield('title', $siteSettings['seo_title'] ?? 'TechNova — Software Solutions')">
    <meta property="og:description" content="@yield('meta_description', $siteSettings['seo_description'] ?? 'Digital products designed and engineered for ambitious teams.')">
    <meta property="og:type" content="website">
    <meta name="theme-color" content="#070819">
    @if($siteSettings['favicon_path'] ?? false)<link rel="icon" href="{{ Storage::disk('public')->url($siteSettings['favicon_path']) }}">@else<link rel="icon" href="/favicon.ico">@endif
    @php
        $socialLinks = collect(['LinkedIn' => $siteSettings['linkedin_url'] ?? null, 'GitHub' => $siteSettings['github_url'] ?? null])->filter(fn ($url) => filled($url) && filter_var($url, FILTER_VALIDATE_URL) && in_array(strtolower((string) parse_url($url, PHP_URL_SCHEME)), ['http', 'https'], true));
        $shareImage = isset($page) && $page->image_path ? Storage::disk('public')->url($page->image_path) : null;
    @endphp
    @if($shareImage)<meta property="og:image" content="{{ $shareImage }}">@endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&family=Noto+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="site-body">
<header class="site-header"><div class="shell nav-shell">
    <a class="brand" href="{{ route('home') }}">@if($siteSettings['logo_path'] ?? false)<img class="brand-image" src="{{ Storage::disk('public')->url($siteSettings['logo_path']) }}" alt="{{ $siteSettings['company_name'] ?? 'TechNova' }}">@else<span class="brand-mark">✦</span>@endif<span>{{ $siteSettings['company_name'] ?? 'TechNova' }}<small>{{ __('SOFTWARE SOLUTIONS') }}</small></span></a>
    <button class="menu-toggle" aria-label="Toggle navigation" aria-expanded="false" data-menu-toggle><span></span><span></span></button>
    <nav class="site-nav" data-menu>
        @forelse($navigationItems as $item)
            <a href="{{ str_starts_with($item->url, '/') ? url($item->url) : $item->url }}">{{ $item->label }}</a>
        @empty
            <a href="{{ route('home') }}">{{ __('Home') }}</a><a href="{{ route('services.index') }}">{{ __('Services') }}</a><a href="{{ route('projects.index') }}">{{ __('Work') }}</a><a href="{{ route('about') }}">{{ __('About') }}</a><a href="{{ route('posts.index') }}">{{ __('Journal') }}</a>
        @endforelse
        <a class="nav-cta" href="{{ route('contact') }}">{{ __('Let\'s talk') }} <span>↗</span></a>
        <a class="language-switch" href="{{ url()->current() }}?lang={{ app()->getLocale() === 'ar' ? 'en' : 'ar' }}" lang="{{ app()->getLocale() === 'ar' ? 'en' : 'ar' }}">{{ app()->getLocale() === 'ar' ? 'English' : 'العربية' }}</a>
    </nav>
</div></header>
<main>@yield('content')</main>
<footer class="site-footer"><div class="shell footer-top"><div><a class="brand" href="{{ route('home') }}">@if($siteSettings['logo_path'] ?? false)<img class="brand-image" src="{{ Storage::disk('public')->url($siteSettings['logo_path']) }}" alt="{{ $siteSettings['company_name'] ?? 'TechNova' }}">@else<span class="brand-mark">✦</span>@endif<span>{{ $siteSettings['company_name'] ?? 'TechNova' }}<small>{{ __('SOFTWARE SOLUTIONS') }}</small></span></a><p>{{ $siteSettings['footer_blurb'] ?? '' }}</p><div class="footer-social">@foreach($socialLinks as $label=>$url)<a href="{{ $url }}" target="_blank" rel="noopener noreferrer">{{ $label }} ↗</a>@endforeach</div></div><div class="footer-links"><a href="{{ route('services.index') }}">{{ __('Services') }}</a><a href="{{ route('projects.index') }}">{{ __('Work') }}</a><a href="{{ route('about') }}">{{ __('About') }}</a><a href="{{ route('posts.index') }}">{{ __('Journal') }}</a><a href="{{ route('contact') }}">{{ __('Contact') }}</a></div><div class="footer-contact"><span>{{ __('START A CONVERSATION') }}</span>@if($siteSettings['contact_email'] ?? false)<a href="mailto:{{ $siteSettings['contact_email'] }}">{{ $siteSettings['contact_email'] }}</a>@endif @if($siteSettings['location'] ?? false)<span>{{ $siteSettings['location'] }}</span>@endif</div></div><div class="shell footer-bottom"><span>© {{ now()->year }} {{ $siteSettings['company_name'] ?? 'TechNova' }}. {{ $siteSettings['copyright_text'] ?? '' }}</span><a href="{{ route('admin.login') }}">{{ __('Admin') }}</a></div></footer>
</body>
</html>
