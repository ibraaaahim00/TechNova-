@extends('layouts.site')
@section('title', ($post->seo_title ?: $post->title).' — '.($siteSettings['company_name'] ?? 'TechNova'))
@section('meta_description', $post->seo_description ?: $post->excerpt)
@section('content')
<article><header class="article-hero"><div class="shell"><a class="back-link" href="{{ route('posts.index') }}">← Journal</a><div class="eyebrow"><span class="eyebrow-line"></span>{{ $post->category?->name ?? 'FIELD NOTES' }} · {{ $post->published_at->format('M d, Y') }}</div><h1>{{ $post->title }}</h1><p>{{ $post->excerpt }}</p></div></header><div class="shell article-shell">@if($post->cover_path)<img class="detail-cover" src="{{ Storage::disk('public')->url($post->cover_path) }}" alt="{{ $post->title }}">@endif<div class="prose article-body">{!! nl2br(e($post->body)) !!}</div></div></article>
@endsection
