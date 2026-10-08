@extends('layouts.site')
@section('title', ($project->seo_title ?: $project->title).' — '.($siteSettings['company_name'] ?? 'TechNova'))
@section('meta_description', $project->seo_description ?: $project->summary)
@section('content')
<section class="detail-hero">
    <div class="shell">
        <a class="back-link" href="{{ route('projects.index') }}">← All work</a>
        <div class="eyebrow"><span class="eyebrow-line"></span>{{ $project->is_concept ? 'CONCEPT PROJECT' : ($project->category?->name ?? 'SELECTED WORK') }}</div>
        <h1>{{ $project->title }}</h1>
        <p>{{ $project->summary }}</p>
        @if($project->is_concept)
            <span class="notice-pill">Independent concept project</span>
        @endif
    </div>
</section>
<section class="section">
    <div class="shell">
        @if($project->image_path)
            <img class="detail-cover" src="{{ Storage::disk('public')->url($project->image_path) }}" alt="{{ $project->title }}">
        @endif
        <div class="detail-layout">
            <article class="prose">
                <h2>The story behind the work.</h2>
                <p>{!! nl2br(e($project->description)) !!}</p>
                @if($project->technologies->isNotEmpty())
                    <h3>Built with</h3>
                    <div class="technology-pills">
                        @foreach($project->technologies as $technology)
                            <span>{{ $technology->name }}</span>
                        @endforeach
                    </div>
                @endif
                @if($project->client_name)
                    <p><b>Client:</b> {{ $project->client_name }}</p>
                @endif
            </article>
            <aside class="detail-aside">
                <span>PROJECT DETAILS</span>
                @if($project->completed_at)
                    <p>Completed {{ $project->completed_at->format('Y') }}</p>
                @endif
                @if($project->project_url)
                    <a class="text-link" href="{{ $project->project_url }}" target="_blank" rel="noopener noreferrer">Visit project ↗</a>
                @endif
                @if($project->github_url)
                    <a class="text-link" href="{{ $project->github_url }}" target="_blank" rel="noopener noreferrer">Source code ↗</a>
                @endif
            </aside>
        </div>
    </div>
</section>
@endsection
