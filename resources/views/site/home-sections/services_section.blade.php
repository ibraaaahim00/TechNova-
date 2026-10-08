@if($services->isNotEmpty())
<section class="section section-muted">
    <div class="shell"><div class="section-heading"><div><div class="eyebrow eyebrow-dark"><span class="eyebrow-line"></span>{{ $section->eyebrow }}</div><h2>{!! nl2br(e($section->title)) !!}</h2></div><a class="text-link" href="{{ route('services.index') }}">{{ __('All capabilities') }} <span>↗</span></a></div>
        <div class="service-grid">@foreach($services as $index => $service)<a class="service-card" href="{{ route('services.show', $service) }}"><div class="service-card-top"><span class="service-icon">{{ $service->icon ?: ['⌘', '◈', '✳'][$index % 3] }}</span><span class="card-index">0{{ $index + 1 }}</span></div><h3>{{ $service->title }}</h3><p>{{ $service->summary }}</p><span class="card-arrow">↗</span></a>@endforeach</div>
    </div>
</section>
@endif
