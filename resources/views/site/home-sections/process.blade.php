<section class="section section-dark">
    <div class="shell process-layout"><div><div class="eyebrow"><span class="eyebrow-line"></span>{{ $section->eyebrow }}</div><h2>{{ $section->title }}</h2><p>{{ $section->body }}</p>@if($section->primary_label && $section->primary_url)<a class="button button-light" href="{{ $section->primary_url }}">{{ $section->primary_label }} <span>↗</span></a>@endif</div>
        <div class="process-steps">@foreach($sections->filter(fn ($step, $key) => str_starts_with($key, 'process_step_')) as $step)<div><span>{{ $step->eyebrow }}</span><div><h3>{{ $step->title }}</h3><p>{{ $step->body }}</p></div></div>@endforeach</div>
    </div>
</section>
