<section class="section section-intro">
    <div class="shell intro-grid">
        <div><div class="eyebrow eyebrow-dark"><span class="eyebrow-line"></span>{{ $section->eyebrow }}</div><h2>{{ $section->title }}</h2></div>
        <div class="intro-copy"><p>{{ $section->body }}</p><a class="text-link" href="{{ route('about') }}">{{ __('Get to know us') }} <span>↗</span></a></div>
    </div>
</section>
