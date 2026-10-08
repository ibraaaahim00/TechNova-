@extends('layouts.site')
@section('title', $page->seo_title ?: $page->title)
@section('meta_description', $page->seo_description ?: $page->intro)
@section('content')
<section class="page-hero"><div class="shell"><div class="eyebrow"><span class="eyebrow-line"></span>{{ $page->eyebrow }}</div><h1>{{ $page->title }}</h1><p>{{ $page->intro }}</p></div></section>
@php
    $approach = $sections['about_approach'] ?? null;
@endphp
@if(!$approach || $approach->is_visible)<section class="section"><div class="shell about-split"><div class="about-visual"><div class="about-orbit"></div><div class="about-card"><span>✦</span><b>{{ $siteSettings['company_name'] ?? 'TechNova' }}</b><small>{{ $siteSettings['brand_statement'] ?? '' }}</small></div></div><div><div class="eyebrow eyebrow-dark"><span class="eyebrow-line"></span>{{ $approach?->eyebrow }}</div><h2>{{ $approach?->title }}</h2><p>{{ $approach?->body }}</p><a class="button button-primary" href="{{ route('contact') }}">{{ __('Talk with our team') }} <span>↗</span></a></div></div></section>@endif
@if($team->isNotEmpty())<section class="section section-muted"><div class="shell"><div class="eyebrow eyebrow-dark"><span class="eyebrow-line"></span>{{ __('OUR TEAM') }}</div><h2>{{ __('Thoughtful by nature.') }}<br><span class="text-gradient">{{ __('Builders by choice.') }}</span></h2><div class="team-grid">@foreach($team as $member)<article class="team-card"><div class="team-photo">@if($member->photo_path)<img src="{{ Storage::disk('public')->url($member->photo_path) }}" alt="{{ $member->name }}">@else<span>{{ str($member->name)->substr(0, 1) }}</span>@endif</div><h3>{{ $member->name }}</h3><p>{{ $member->role }}</p>@if($member->bio)<small>{{ $member->bio }}</small>@endif
@if($member->social_links)<div class="team-social">@foreach($member->social_links as $label=>$url)<a href="{{ $url }}" target="_blank" rel="noopener noreferrer">{{ $label }} ↗</a>@endforeach</div>@endif</article>@endforeach</div></div></section>@endif
@php
    $cta = $sections['cta'] ?? null;
@endphp
@if(!$cta || $cta->is_visible)<section class="section section-cta"><div class="shell cta-panel"><div class="eyebrow"><span class="eyebrow-line"></span>{{ $cta?->eyebrow }}</div><h2>{!! nl2br(e($cta?->title)) !!}</h2><a class="button button-primary" href="{{ $cta?->primary_url ?? route('contact') }}">{{ $cta?->primary_label }} <span>↗</span></a></div></section>@endif
@endsection
