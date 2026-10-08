@if($projects->isNotEmpty())
<section class="section">
    <div class="shell"><div class="section-heading"><div><div class="eyebrow eyebrow-dark"><span class="eyebrow-line"></span>{{ $section->eyebrow }}</div><h2>{!! nl2br(e($section->title)) !!}</h2></div><a class="text-link" href="{{ route('projects.index') }}">Explore all work <span>↗</span></a></div>
        <div class="project-grid">@foreach($projects as $project)<a class="project-card" href="{{ route('projects.show', $project) }}"><div class="project-image">@if($project->image_path)<img src="{{ Storage::disk('public')->url($project->image_path) }}" alt="{{ $project->title }}">@else<div class="project-art project-art-{{ $loop->iteration }}"><div class="art-window"><span></span><span></span><span></span><b>{{ str($project->title)->limit(1, '') }}</b></div></div>@endif</div><div class="project-meta"><div><span>{{ $project->is_concept ? 'CONCEPT PROJECT' : ($project->category?->name ?? 'SELECTED WORK') }}</span><h3>{{ $project->title }}</h3></div><b>↗</b></div><p>{{ $project->summary }}</p></a>@endforeach</div>
    </div>
</section>
@endif
