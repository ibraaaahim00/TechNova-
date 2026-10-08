<?php

use App\Models\ContactMessage;
use App\Models\HomeSection;
use App\Models\Page;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the TechNova homepage', function () {
    $this->get('/')->assertOk()->assertSee('TechNova')->assertSee('Digital products');
});

it('renders homepage sections in their managed order and respects visibility', function () {
    $servicesSection = HomeSection::factory()->create(['key' => 'services_section', 'eyebrow' => 'CAPABILITIES MARKER', 'title' => 'Services marker', 'is_visible' => true, 'sort_order' => 1]);
    HomeSection::factory()->create(['key' => 'intro', 'eyebrow' => 'INTRO MARKER', 'title' => 'Intro marker', 'is_visible' => true, 'sort_order' => 2]);
    Service::factory()->published()->create(['title' => 'Managed product design', 'slug' => 'managed-product-design', 'is_featured' => true]);

    $this->get('/')->assertOk()->assertSeeInOrder(['Services marker', 'Intro marker'])->assertSee('Managed product design');

    $servicesSection->update(['is_visible' => false]);
    $this->get('/')->assertOk()->assertDontSee('Services marker')->assertDontSee('Managed product design');
});

it('shows published services and hides drafts', function () {
    Service::factory()->published()->create(['title' => 'Published service', 'slug' => 'published-service', 'summary' => 'Available now', 'description' => 'Service details']);
    Service::factory()->create(['title' => 'Private draft', 'slug' => 'private-draft', 'summary' => 'Not for visitors']);

    $this->get('/services/published-service')->assertOk()->assertSee('Published service');
    $this->get('/services/private-draft')->assertNotFound();
    $this->get('/sitemap.xml')->assertOk()->assertSee('/services/published-service')->assertDontSee('/services/private-draft');
});

it('stores valid contact messages and rejects a filled honeypot', function () {
    $message = ['name' => 'Avery Example', 'email' => 'avery@example.test', 'subject' => 'Product collaboration', 'message' => 'We would like to discuss a thoughtful software project.'];

    $this->from('/contact')->post('/contact', $message)->assertRedirect('/contact');
    $this->assertDatabaseHas('contact_messages', ['email' => 'avery@example.test', 'subject' => 'Product collaboration']);

    $this->from('/contact')->post('/contact', $message + ['website' => 'spam'])->assertSessionHasErrors('website');
    expect(ContactMessage::query()->count())->toBe(1);
});

it('returns not found when a CMS page is unpublished', function () {
    Page::factory()->create(['title' => 'About', 'slug' => 'about', 'is_published' => false]);

    $this->get('/about')->assertNotFound();
});
