@extends('layouts.site')
@section('title', $siteSettings['seo_title'] ?? 'TechNova — Software Solutions')
@section('content')
@if(!$hero || $hero->is_visible)
<section class="hero">
    <div class="hero-glow hero-glow-a"></div>
    <div class="hero-glow hero-glow-b"></div>
    <div class="shell hero-grid">
        <div class="hero-copy reveal">
            <div class="eyebrow"><span class="eyebrow-line"></span>{{ $hero?->eyebrow ?? '' }}</div>
            <h1>{!! nl2br(e($hero?->title ?? '')) !!}</h1>
            <p>{{ $hero?->body ?? '' }}</p>
            <div class="hero-actions">
                @if($hero?->primary_label && $hero?->primary_url)
                    <a class="button button-primary" href="{{ $hero->primary_url }}">{{ $hero->primary_label }} <span>↗</span></a>
                @endif
                @if($hero?->secondary_label && $hero?->secondary_url)
                    <a class="button button-ghost" href="{{ $hero->secondary_url }}">{{ $hero->secondary_label }}</a>
                @endif
            </div>
            <div class="hero-note"><span class="pulse"></span>{{ $siteSettings['brand_statement'] ?? '' }}</div>
        </div>
        <div class="hero-art reveal">
            @if($hero?->image_path)
                <img class="hero-custom-image" src="{{ Storage::disk('public')->url($hero->image_path) }}" alt="{{ $hero->title }}">
            @endif
            <div class="orbit orbit-one"></div>
            <div class="orbit orbit-two"></div>
            <div class="hero-panel">
                <div class="panel-top"><span class="window-dots"><i></i><i></i><i></i></span><span>technova / studio</span><span class="panel-menu">···</span></div>
                <div class="panel-inner">
                    <div class="panel-sidebar"><b class="side-active">▦</b><b>◈</b><b>⌁</b><b>◇</b></div>
                    <div class="panel-content">
                        <div class="panel-greeting"><span>{{ __('YOUR DIGITAL HQ') }}</span><b>{{ __('Good ideas, meet great execution.') }}</b></div>
                        <div class="panel-cards"><div><small>{{ __('PRODUCT HEALTH') }}</small><b>{{ __('Excellent') }} <span>↗</span></b><i class="bar bar-a"></i></div><div><small>{{ __('TEAM MOMENTUM') }}</small><b>{{ __('On track') }} <span>↗</span></b><i class="bar bar-b"></i></div></div>
                        <div class="chart-box"><div class="chart-heading"><span>{{ __('Progress over time') }}</span><small>{{ __('THIS QUARTER') }}⌄</small></div><svg viewBox="0 0 440 120" preserveAspectRatio="none" aria-label="{{ __('Decorative rising progress chart') }}"><defs><linearGradient id="chartfill" x1="0" x2="0" y1="0" y2="1"><stop offset="0" stop-color="#7a68ff" stop-opacity=".36"/><stop offset="1" stop-color="#7a68ff" stop-opacity="0"/></linearGradient></defs><path d="M0 95 C42 88 48 74 82 78 S123 44 164 60 S203 70 245 42 S297 61 335 36 S391 48 440 10 V120 H0Z" fill="url(#chartfill)"/><path d="M0 95 C42 88 48 74 82 78 S123 44 164 60 S203 70 245 42 S297 61 335 36 S391 48 440 10" fill="none" stroke="#8c7bff" stroke-width="3"/></svg></div>
                        <div class="panel-bottom"><span>● {{ __('All systems in motion') }}</span><b>↗</b></div>
                    </div>
                </div>
            </div>
            <div class="floating-chip chip-top"><span>✦</span> {{ $siteSettings['company_name'] ?? 'TechNova' }}</div>
            <div class="floating-chip chip-bottom"><span class="chip-avatar">T</span><span>{{ $siteSettings['brand_statement'] ?? '' }}<small>{{ __('Ideas into impact') }}</small></span></div>
        </div>
    </div>
    <div class="shell hero-scroll"><span>{{ __('SCROLL TO EXPLORE') }}</span><i></i></div>
</section>
@endif
@foreach($orderedSections as $section)
    @include('site.home-sections.'.$section->key, ['section' => $section])
@endforeach
@endsection
