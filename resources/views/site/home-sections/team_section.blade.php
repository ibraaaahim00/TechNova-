@if($team->isNotEmpty())
<section class="section">
    <div class="shell"><div class="section-heading"><div><div class="eyebrow eyebrow-dark"><span class="eyebrow-line"></span>{{ $section->eyebrow }}</div><h2>{!! nl2br(e($section->title)) !!}</h2></div><a class="text-link" href="{{ route('about') }}">{{ __('Meet the team') }} <span>↗</span></a></div>
        <div class="team-grid">@foreach($team as $member)<article class="team-card"><div class="team-photo">@if($member->photo_path)<img src="{{ Storage::disk('public')->url($member->photo_path) }}" alt="{{ $member->name }}">@else<span>{{ str($member->name)->substr(0, 1) }}</span>@endif</div><h3>{{ $member->name }}</h3><p>{{ $member->role }}</p></article>@endforeach</div>
    </div>
</section>
@endif
