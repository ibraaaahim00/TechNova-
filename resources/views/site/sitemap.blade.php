@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url><loc>{{ route('home') }}</loc></url>
    <url><loc>{{ route('about') }}</loc></url>
    <url><loc>{{ route('services.index') }}</loc></url>
    <url><loc>{{ route('projects.index') }}</loc></url>
    <url><loc>{{ route('posts.index') }}</loc></url>
    <url><loc>{{ route('contact') }}</loc></url>
    @foreach($services as $service)<url><loc>{{ route('services.show', $service) }}</loc><lastmod>{{ $service->updated_at->toAtomString() }}</lastmod></url>@endforeach
    @foreach($projects as $project)<url><loc>{{ route('projects.show', $project) }}</loc><lastmod>{{ $project->updated_at->toAtomString() }}</lastmod></url>@endforeach
    @foreach($posts as $post)<url><loc>{{ route('posts.show', $post) }}</loc><lastmod>{{ $post->updated_at->toAtomString() }}</lastmod></url>@endforeach
    @foreach($pages as $page)<url><loc>{{ route('pages.show', $page) }}</loc><lastmod>{{ $page->updated_at->toAtomString() }}</lastmod></url>@endforeach
</urlset>
