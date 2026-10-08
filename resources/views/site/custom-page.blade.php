@extends('layouts.site')
@section('title', $page->seo_title ?: $page->title)
@section('meta_description', $page->seo_description ?: $page->intro)
@section('content')
<section class="page-hero"><div class="shell"><div class="eyebrow"><span class="eyebrow-line"></span>{{ $page->eyebrow }}</div><h1>{{ $page->title }}</h1><p>{{ $page->intro }}</p></div></section>
@if($page->image_path)<section class="section"><div class="shell"><img class="detail-cover" src="{{ Storage::disk('public')->url($page->image_path) }}" alt="{{ $page->title }}"></div></section>@endif
@endsection
