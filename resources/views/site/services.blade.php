@extends('layouts.site')
@section('title', $page->seo_title ?: $page->title)
@section('meta_description', $page->seo_description ?: $page->intro)
@section('content')
<section class="page-hero"><div class="shell"><div class="eyebrow"><span class="eyebrow-line"></span>{{ $page->eyebrow }}</div><h1>{{ $page->title }}</h1><p>{{ $page->intro }}</p></div></section>
<section class="section section-muted"><div class="shell">@if($services->isEmpty())<div class="empty-state"><span>◈</span><h2>{{ __('Our capabilities are being updated.') }}</h2><p>{{ __('Check back soon, or get in touch to discuss your project.') }}</p><a class="button button-primary" href="{{ route('contact') }}">{{ __('Contact us') }} <span>↗</span></a></div>@else<div class="service-grid">@foreach($services as $index => $service)<a class="service-card" href="{{ route('services.show', $service) }}"><div class="service-card-top"><span class="service-icon">{{ $service->icon ?: '◈' }}</span><span class="card-index">0{{ $index + 1 }}</span></div><h3>{{ $service->title }}</h3><p>{{ $service->summary }}</p><span class="card-arrow">↗</span></a>@endforeach</div>{{ $services->links() }}@endif</div></section>
@endsection
