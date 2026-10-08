@if($testimonials->isNotEmpty())
<section class="section section-muted">
    <div class="shell"><div class="eyebrow eyebrow-dark"><span class="eyebrow-line"></span>{{ $section->eyebrow }}</div><h2>{!! nl2br(e($section->title)) !!}</h2><div class="testimonial-grid">@foreach($testimonials as $testimonial)<blockquote>“{{ $testimonial->quote }}”<footer><b>{{ $testimonial->person_name }}</b><span>{{ $testimonial->person_role }}{{ $testimonial->company ? ' · '.$testimonial->company : '' }}</span></footer></blockquote>@endforeach</div></div>
</section>
@endif
