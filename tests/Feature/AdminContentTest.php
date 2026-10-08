<?php

use App\Models\ContactMessage;
use App\Models\HomeSection;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('redirects guests away from the CMS and forbids a regular account', function () {
    $this->get('/admin/services')->assertRedirect(route('admin.login'));

    $this->actingAs(User::factory()->create())->get('/admin/services')->assertForbidden();
});

it('renders the dashboard and an editable CMS form for an administrator', function () {
    $this->actingAs(User::factory()->create(['is_admin' => true]));

    $this->get('/admin')->assertOk()->assertSee('Good to see you')->assertSee('Quick actions');
    $this->get('/admin/services/create')->assertOk()->assertSee('Content details')->assertSee('Create item')->assertSee('translations[en][title]')->assertSee('translations[ar][title]');
});

it('renders editable navigation and translated footer content on the public site', function () {
    NavigationItem::query()->create([
        'label' => 'Solutions',
        'url' => '/services',
        'is_visible' => true,
        'sort_order' => 1,
        'translations' => ['en' => ['label' => 'Solutions'], 'ar' => ['label' => 'الحلول']],
    ]);
    SiteSetting::query()->create([
        'key' => 'footer_blurb',
        'value' => 'Thoughtful digital products.',
        'translations' => ['en' => ['value' => 'Thoughtful digital products.'], 'ar' => ['value' => 'منتجات رقمية مدروسة.']],
    ]);

    $english = $this->get('/?lang=en')->assertOk()->getContent();
    $englishFooter = substr($english, strpos($english, '<footer class="site-footer">'));
    $this->assertStringContainsString('Solutions', $englishFooter);
    $this->assertStringContainsString('Thoughtful digital products.', $englishFooter);

    $arabic = $this->get('/?lang=ar')->assertOk()->getContent();
    $arabicFooter = substr($arabic, strpos($arabic, '<footer class="site-footer">'));
    $this->assertStringContainsString('الحلول', $arabicFooter);
    $this->assertStringContainsString('منتجات رقمية مدروسة.', $arabicFooter);
});

it('creates, edits, publishes, unpublishes and deletes translated CMS content with uploads', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create(['is_admin' => true]));

    $serviceData = [
        'translations' => ['en' => ['title' => 'Bilingual service', 'summary' => 'English summary', 'description' => 'English service description.'], 'ar' => ['title' => 'خدمة بلغتين', 'summary' => 'ملخص عربي', 'description' => 'وصف الخدمة باللغة العربية.']],
        'slug' => 'bilingual-service', 'image_path' => UploadedFile::fake()->image('service.png'), 'is_published' => '1', 'is_featured' => '1',
    ];
    $this->post('/admin/services', $serviceData)->assertRedirect(route('admin.content.index', 'services'));
    $service = Service::query()->where('slug', 'bilingual-service')->firstOrFail();
    $this->assertDatabaseHas('services', ['id' => $service->id, 'is_published' => true]);
    Storage::disk('public')->assertExists($service->image_path);
    $this->get('/services/bilingual-service?lang=en')->assertOk()->assertSee('Bilingual service')->assertSee(Storage::disk('public')->url($service->image_path));
    $this->get('/services/bilingual-service?lang=ar')->assertOk()->assertSee('خدمة بلغتين');

    $serviceData['image_path'] = UploadedFile::fake()->create('payload.php', 512, 'text/php');
    $this->put('/admin/services/'.$service->id, $serviceData)->assertSessionHasErrors('image_path');
    Storage::disk('public')->assertExists($service->image_path);

    $serviceData['translations']['ar']['title'] = 'خدمة مترجمة ومحدثة';
    unset($serviceData['image_path']);
    $serviceData['is_published'] = '0';
    $this->put('/admin/services/'.$service->id, $serviceData)->assertRedirect(route('admin.content.index', 'services'));
    $this->get('/services/bilingual-service')->assertNotFound();
    $this->delete('/admin/services/'.$service->id)->assertRedirect(route('admin.content.index', 'services'));
    $this->assertDatabaseMissing('services', ['id' => $service->id]);

    $this->post('/admin/projects', [
        'translations' => ['en' => ['title' => 'Bilingual project', 'summary' => 'Project summary'], 'ar' => ['title' => 'مشروع بلغتين', 'summary' => 'ملخص المشروع']],
        'slug' => 'bilingual-project', 'image_path' => UploadedFile::fake()->image('project.png'), 'is_published' => '1', 'is_featured' => '0', 'is_concept' => '0',
    ])->assertRedirect(route('admin.content.index', 'projects'));
    $project = Project::query()->where('slug', 'bilingual-project')->firstOrFail();
    $this->get('/projects/bilingual-project?lang=ar')->assertOk()->assertSee('مشروع بلغتين');
    $this->put('/admin/projects/'.$project->id, [
        'translations' => ['en' => ['title' => 'Edited project'], 'ar' => ['title' => 'مشروع معدل']],
        'slug' => 'bilingual-project', 'is_published' => '0', 'is_featured' => '0', 'is_concept' => '0',
    ])->assertRedirect(route('admin.content.index', 'projects'));
    $this->get('/projects/bilingual-project')->assertNotFound();
    $this->delete('/admin/projects/'.$project->id)->assertRedirect(route('admin.content.index', 'projects'));

    $this->post('/admin/posts', [
        'translations' => ['en' => ['title' => 'Bilingual article', 'excerpt' => 'English excerpt', 'body' => 'English article body.'], 'ar' => ['title' => 'مقال بلغتين', 'excerpt' => 'مقتطف عربي', 'body' => 'نص المقال باللغة العربية.']],
        'slug' => 'bilingual-article', 'published_at' => now()->subMinute()->format('Y-m-d\TH:i'), 'is_published' => '1',
    ])->assertRedirect(route('admin.content.index', 'posts'));
    $post = Post::query()->where('slug', 'bilingual-article')->firstOrFail();
    $this->get('/journal/bilingual-article?lang=ar')->assertOk()->assertSee('مقال بلغتين');
    $this->put('/admin/posts/'.$post->id, [
        'translations' => ['en' => ['title' => 'Edited article'], 'ar' => ['title' => 'مقال معدل']],
        'slug' => 'bilingual-article', 'published_at' => now()->subMinute()->format('Y-m-d\TH:i'), 'is_published' => '0',
    ])->assertRedirect(route('admin.content.index', 'posts'));
    $this->get('/journal/bilingual-article')->assertNotFound();
    $this->delete('/admin/posts/'.$post->id)->assertRedirect(route('admin.content.index', 'posts'));

    $this->post('/admin/team', [
        'translations' => ['en' => ['name' => 'Avery Person', 'role' => 'Engineer'], 'ar' => ['name' => 'أفري', 'role' => 'مهندس']],
        'photo_path' => UploadedFile::fake()->image('member.png'), 'is_active' => '1', 'sort_order' => '1',
    ])->assertRedirect(route('admin.content.index', 'team'));
    $member = TeamMember::query()->firstOrFail();
    $this->assertDatabaseHas('team_members', ['id' => $member->id, 'is_active' => true]);
    Storage::disk('public')->assertExists($member->photo_path);
    $this->put('/admin/team/'.$member->id, [
        'translations' => ['en' => ['name' => 'Avery Updated', 'role' => 'Engineer'], 'ar' => ['name' => 'أفري محدث', 'role' => 'مهندس']],
        'is_active' => '0', 'sort_order' => '1',
    ])->assertRedirect(route('admin.content.index', 'team'));
    $this->delete('/admin/team/'.$member->id)->assertRedirect(route('admin.content.index', 'team'));
});

it('saves bilingual homepage sections and site settings, and renders the selected language', function () {
    $this->actingAs(User::factory()->create(['is_admin' => true]));
    $section = HomeSection::factory()->create(['key' => 'intro', 'is_visible' => true]);
    $this->put('/admin/sections/'.$section->id, [
        'key' => 'intro', 'is_visible' => '1', 'sort_order' => '1',
        'translations' => ['en' => ['eyebrow' => 'Review intro', 'title' => 'English homepage title', 'body' => 'English page copy.'], 'ar' => ['eyebrow' => 'مقدمة تجريبية', 'title' => 'عنوان الصفحة الرئيسية بالعربية', 'body' => 'محتوى الصفحة بالعربية.']],
    ])->assertRedirect(route('admin.content.index', 'sections'));
    $this->get('/?lang=ar')->assertOk()->assertSee('عنوان الصفحة الرئيسية بالعربية');
    $this->get('/?lang=en')->assertOk()->assertSee('English homepage title');

    $setting = SiteSetting::query()->create(['key' => 'brand_statement', 'value' => 'Original public brand statement']);
    $this->put('/admin/settings/'.$setting->id, [
        'key' => 'brand_statement',
        'translations' => ['en' => ['value' => 'Brand copy in English'], 'ar' => ['value' => 'عبارة العلامة التجارية بالعربية']],
    ])->assertRedirect(route('admin.content.index', 'settings'));
    $this->get('/?lang=ar')->assertOk()->assertSee('عبارة العلامة التجارية بالعربية');
    $this->get('/?lang=en')->assertOk()->assertSee('Brand copy in English');
});

it('publishes bilingual CMS pages on their public URL and sitemap, then hides drafts', function () {
    $this->actingAs(User::factory()->create(['is_admin' => true]));

    $this->post('/admin/pages', [
        'translations' => ['en' => ['title' => 'Custom English page', 'eyebrow' => 'Company', 'intro' => 'English page introduction.'], 'ar' => ['title' => 'صفحة عربية مخصصة', 'eyebrow' => 'الشركة', 'intro' => 'مقدمة الصفحة بالعربية.']],
        'slug' => 'review-custom-page', 'is_published' => '1',
    ])->assertRedirect(route('admin.content.index', 'pages'));

    $page = Page::query()->where('slug', 'review-custom-page')->firstOrFail();
    $this->get('/p/review-custom-page?lang=ar')->assertOk()->assertSee('صفحة عربية مخصصة')->assertSee('مقدمة الصفحة بالعربية.');
    $this->get('/p/review-custom-page?lang=en')->assertOk()->assertSee('Custom English page');
    $this->get('/sitemap.xml')->assertOk()->assertSee('/p/review-custom-page');

    $this->put('/admin/pages/'.$page->id, [
        'translations' => ['en' => ['title' => 'Custom English page', 'eyebrow' => 'Company', 'intro' => 'English page introduction.'], 'ar' => ['title' => 'صفحة عربية مخصصة', 'eyebrow' => 'الشركة', 'intro' => 'مقدمة الصفحة بالعربية.']],
        'slug' => 'review-custom-page', 'is_published' => '0',
    ])->assertRedirect(route('admin.content.index', 'pages'));

    $this->get('/p/review-custom-page')->assertNotFound();
    $this->get('/sitemap.xml')->assertDontSee('/p/review-custom-page');
});

it('shows contact submissions in the admin inbox and supports read and archive actions', function () {
    $administrator = User::factory()->create(['is_admin' => true]);
    $message = ContactMessage::factory()->create(['subject' => 'CMS inbox review']);
    $this->actingAs($administrator);

    $this->get('/admin/messages?lang=ar')->assertOk()->assertSee('CMS inbox review')->assertSee($message->email);
    $this->put('/admin/messages/'.$message->id, ['action' => 'read'])->assertRedirect();
    $message->refresh();
    $this->assertNotNull($message->read_at);
    $this->put('/admin/messages/'.$message->id, ['action' => 'archive'])->assertRedirect();
    $this->assertDatabaseHas('contact_messages', ['id' => $message->id]);
    $this->get('/admin/messages')->assertDontSee('CMS inbox review');
});

it('authenticates administrators and rejects ordinary accounts', function () {
    $administrator = User::factory()->create(['email' => 'editor@example.test', 'password' => 'CurrentReviewPass!2026', 'is_admin' => true]);
    $this->post('/admin/login', ['email' => $administrator->email, 'password' => 'CurrentReviewPass!2026'])->assertRedirect(route('admin.dashboard'));
    $this->get('/admin')->assertOk();
    $this->post('/admin/logout')->assertRedirect(route('home'));

    $user = User::factory()->create(['email' => 'regular@example.test', 'password' => 'CurrentReviewPass!2026', 'is_admin' => false]);
    $this->from('/admin/login')->post('/admin/login', ['email' => $user->email, 'password' => 'CurrentReviewPass!2026'])->assertRedirect('/admin/login')->assertSessionHasErrors('email');
});

it('creates and publishes a service from the CMS, then unpublishes it', function () {
    $administrator = User::factory()->create(['is_admin' => true]);
    $this->actingAs($administrator);

    $service = ['title' => 'Secure portal', 'slug' => 'secure-portal', 'summary' => 'A custom customer portal.', 'description' => 'An example description.', 'is_published' => '1', 'is_featured' => '1', 'sort_order' => '1'];
    $this->post('/admin/services', $service)->assertRedirect(route('admin.content.index', 'services'));
    $record = Service::query()->where('slug', 'secure-portal')->firstOrFail();
    $this->assertDatabaseHas('services', ['id' => $record->id, 'is_published' => true]);
    $this->get('/services/secure-portal')->assertOk()->assertSee('Secure portal');

    $service['is_published'] = '0';
    $this->put('/admin/services/'.$record->id, $service)->assertRedirect(route('admin.content.index', 'services'));
    $this->get('/services/secure-portal')->assertNotFound();
});
