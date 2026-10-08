@extends('layouts.site')
@section('title', $page->seo_title ?: $page->title)
@section('meta_description', $page->seo_description ?: $page->intro)
@section('content')
<section class="page-hero"><div class="shell"><div class="eyebrow"><span class="eyebrow-line"></span>{{ $page->eyebrow }}</div><h1>{{ $page->title }}</h1><p>{{ $page->intro }}</p></div></section><section class="section section-muted"><div class="shell">@if($posts->isEmpty())<div class="empty-state"><span>✳</span><h2>Our journal is getting ready.</h2><p>There aren't any published articles yet.</p></div>@else<div class="post-grid">@foreach($posts as $post)<a class="post-card" href="{{ route('posts.show', $post) }}"><div class="post-cover">@if($post->cover_path)<img src="{{ Storage::disk('public')->url($post->cover_path) }}" alt="{{ $post->title }}">@else<span>TN<span>✦</span></span>@endif</div><small>{{ $post->published_at?->format('M d, Y') }}{{ $post->category ? ' · '.$post->category->name : '' }}</small><h3>{{ $post->title }}</h3><p>{{ $post->excerpt }}</p></a>@endforeach</div>{{ $posts->links() }}@endif</div></section>
@endsection
