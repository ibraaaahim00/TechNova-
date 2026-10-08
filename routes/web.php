<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\PublicSiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicSiteController::class, 'home'])->name('home');
Route::get('/about', [PublicSiteController::class, 'about'])->name('about');
Route::get('/services', [PublicSiteController::class, 'services'])->name('services.index');
Route::get('/services/{service:slug}', [PublicSiteController::class, 'service'])->name('services.show');
Route::get('/projects', [PublicSiteController::class, 'projects'])->name('projects.index');
Route::get('/projects/{project:slug}', [PublicSiteController::class, 'project'])->name('projects.show');
Route::get('/journal', [PublicSiteController::class, 'posts'])->name('posts.index');
Route::get('/journal/{post:slug}', [PublicSiteController::class, 'post'])->name('posts.show');
Route::get('/contact', [PublicSiteController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicSiteController::class, 'sendMessage'])->middleware('throttle:5,1')->name('contact.send');
Route::get('/sitemap.xml', [PublicSiteController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', fn () => response("User-agent: *\nAllow: /\nSitemap: ".route('sitemap')."\n", 200, ['Content-Type' => 'text/plain']))->name('robots');

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('/login', [AuthController::class, 'create'])->name('login');
        Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function (): void {
        Route::get('/account', [AccountController::class, 'edit'])->name('account.edit');
        Route::put('/account', [AccountController::class, 'update'])->name('account.update');
        Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/{type}', [ContentController::class, 'index'])->name('content.index');
        Route::get('/{type}/create', [ContentController::class, 'create'])->name('content.create');
        Route::post('/{type}', [ContentController::class, 'store'])->name('content.store');
        Route::get('/{type}/{id}/edit', [ContentController::class, 'edit'])->name('content.edit');
        Route::put('/{type}/{id}', [ContentController::class, 'update'])->name('content.update');
        Route::delete('/{type}/{id}', [ContentController::class, 'destroy'])->name('content.destroy');
    });
});
