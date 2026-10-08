@if($posts->isNotEmpty())
<section class="section">
    <div class="shell"><div class="section-heading"><div><div class="eyebrow eyebrow-dark"><span class="eyebrow-line"></span>{{ $section->eyebrow }}</div><h2>{!! nl2br(e($section->title)) !!}</h2></div><a class="text-link" href="{{ route('posts.index') }}">{{ __('Visit the journal') }} <span>↗</span></a></div>
        <div class="post-grid">@foreach($posts as $post)<a class="post-card" href="{{ route('posts.show', $post) }}"><div class="post-cover">@if($post->cover_path)<img src="{{ Storage::disk('public')->url($post->cover_path) }}" alt="{{ $post->title }}">@else<span>TN<span>✦</span></span>@endif</div><small>{{ $post->published_at?->format('M d, Y') }}</small><h3>{{ $post->title }}</h3><p>{{ $post->excerpt }}</p></a>@endforeach</div>
    </div>
</section>
@endif
