@extends('layouts.site')
@section('title', ($service->seo_title ?: $service->title).' — '.($siteSettings['company_name'] ?? 'TechNova'))
@section('meta_description', $service->seo_description ?: $service->summary)
@section('content')
<section class="detail-hero"><div class="shell"><a class="back-link" href="{{ route('services.index') }}">← {{ __('All services') }}</a><div class="eyebrow"><span class="eyebrow-line"></span>{{ $service->icon }} {{ __('CAPABILITY') }}</div><h1>{{ $service->title }}</h1><p>{{ $service->summary }}</p></div></section>
@if($service->image_path)<section class="section"><div class="shell"><img class="detail-cover" src="{{ Storage::disk('public')->url($service->image_path) }}" alt="{{ $service->title }}"></div></section>@endif
<section class="section"><div class="shell detail-layout"><article class="prose"><h2>{{ __('Made for your next stage.') }}</h2><p>{!! nl2br(e($service->description)) !!}</p>@if($service->features)<h3>{{ __('What we can do together') }}</h3><ul>@foreach($service->features as $feature)<li>{{ $feature }}</li>@endforeach</ul>@endif</article><aside class="detail-aside"><span>{{ __('HAVE SOMETHING IN MIND?') }}</span><h3>{{ __('Let\'s talk through it.') }}</h3><a class="button button-primary" href="{{ route('contact') }}">{{ __('Start a conversation') }} <span>↗</span></a></aside></div></section>
@endsection
