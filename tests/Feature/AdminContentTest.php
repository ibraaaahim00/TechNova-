<?php

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects guests away from the CMS and forbids a regular account', function () {
    $this->get('/admin/services')->assertRedirect(route('admin.login'));

    $this->actingAs(User::factory()->create())->get('/admin/services')->assertForbidden();
});

it('renders the dashboard and an editable CMS form for an administrator', function () {
    $this->actingAs(User::factory()->create(['is_admin' => true]));

    $this->get('/admin')->assertOk()->assertSee('Good to see you')->assertSee('Quick actions');
    $this->get('/admin/services/create')->assertOk()->assertSee('Content details')->assertSee('Create item');
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
