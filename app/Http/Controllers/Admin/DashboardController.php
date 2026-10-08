<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\HomeSection;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'counts' => [
                'services' => Service::query()->count(),
                'projects' => Project::query()->count(),
                'posts' => Post::query()->count(),
                'team' => TeamMember::query()->count(),
                'sections' => HomeSection::query()->count() + Page::query()->count(),
            ],
            'unreadMessages' => ContactMessage::query()->whereNull('read_at')->whereNull('archived_at')->count(),
            'recentMessages' => ContactMessage::query()->whereNull('archived_at')->latest()->limit(5)->get(),
        ]);
    }
}
