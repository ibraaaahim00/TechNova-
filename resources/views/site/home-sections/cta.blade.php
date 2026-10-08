<section class="section section-cta">
    <div class="shell cta-panel"><div class="cta-orb"></div><div class="eyebrow"><span class="eyebrow-line"></span>{{ $section->eyebrow }}</div><h2>{!! nl2br(e($section->title)) !!}</h2>@if($section->primary_label && $section->primary_url)<a class="button button-primary" href="{{ $section->primary_url }}">{{ $section->primary_label }} <span>↗</span></a>@endif</div>
</section>
