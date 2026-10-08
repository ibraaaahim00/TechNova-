<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\HomeSection;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PublicSiteController extends Controller
{
    public function home(): View
    {
        $sectionKeys = ['intro', 'services_section', 'projects_section', 'process', 'team_section', 'testimonials_section', 'posts_section', 'cta'];
        $homeSections = HomeSection::query()->orderBy('sort_order')->get();

        return view('site.home', [
            'hero' => $homeSections->firstWhere('key', 'hero'),
            'sections' => $homeSections->where('is_visible', true)->keyBy('key'),
            'orderedSections' => $homeSections->whereIn('key', $sectionKeys)->where('is_visible', true)->values(),
            'services' => Service::query()->where('is_published', true)->where('is_featured', true)->orderBy('sort_order')->limit(3)->get(),
            'projects' => Project::query()->where('is_published', true)->where('is_featured', true)->orderBy('sort_order')->limit(3)->get(),
            'team' => TeamMember::query()->where('is_active', true)->orderBy('sort_order')->limit(4)->get(),
            'posts' => Post::query()->where('is_published', true)->whereNotNull('published_at')->latest('published_at')->limit(3)->get(),
            'testimonials' => Testimonial::query()->where('is_approved', true)->orderBy('sort_order')->limit(3)->get(),
        ]);
    }

    public function about(): View
    {
        return view('site.about', ['page' => $this->page('about'), 'sections' => HomeSection::query()->where('is_visible', true)->orderBy('sort_order')->get()->keyBy('key'), 'team' => TeamMember::query()->where('is_active', true)->orderBy('sort_order')->get()]);
    }

    public function services(): View
    {
        return view('site.services', ['page' => $this->page('services'), 'services' => Service::query()->where('is_published', true)->orderBy('sort_order')->paginate(9)]);
    }

    public function service(Service $service): View
    {
        abort_unless($service->is_published, 404);

        return view('site.service', compact('service'));
    }

    public function projects(Request $request): View
    {
        $projects = Project::query()->with('category')->where('is_published', true)->when($request->filled('category'), fn ($query) => $query->whereHas('category', fn ($category) => $category->where('slug', $request->string('category'))))->orderBy('sort_order')->latest()->paginate(9)->withQueryString();

        return view('site.projects', ['page' => $this->page('projects'), 'projects' => $projects, 'categories' => ProjectCategory::query()->orderBy('name')->get()]);
    }

    public function project(Project $project): View
    {
        abort_unless($project->is_published, 404);
        $project->load(['category', 'technologies']);

        return view('site.project', compact('project'));
    }

    public function posts(): View
    {
        return view('site.posts', ['page' => $this->page('journal'), 'posts' => Post::query()->with('category')->where('is_published', true)->whereNotNull('published_at')->where('published_at', '<=', now())->latest('published_at')->paginate(9)]);
    }

    public function post(Post $post): View
    {
        abort_unless($post->is_published && $post->published_at?->isPast(), 404);

        return view('site.post', compact('post'));
    }

    public function contact(): View
    {
        return view('site.contact', ['page' => $this->page('contact')]);
    }

    public function sendMessage(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'subject' => ['required', 'string', 'max:190'],
            'message' => ['required', 'string', 'min:20', 'max:5000'],
            'website' => ['prohibited'],
        ]);

        unset($validated['website']);
        ContactMessage::query()->create($validated);

        return back()->with('status', 'Thanks for reaching out. Your message is safely in our inbox.');
    }

    public function sitemap(): Response
    {
        return response()->view('site.sitemap', [
            'services' => Service::query()->where('is_published', true)->get(),
            'projects' => Project::query()->where('is_published', true)->get(),
            'posts' => Post::query()->where('is_published', true)->whereNotNull('published_at')->where('published_at', '<=', now())->get(),
        ])->header('Content-Type', 'application/xml');
    }

    private function page(string $slug): Page
    {
        $page = Page::query()->where('slug', $slug)->firstOrFail();
        abort_unless($page->is_published, 404);

        return $page;
    }
}
