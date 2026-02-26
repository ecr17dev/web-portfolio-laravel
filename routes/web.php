<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\IndexController;
use App\Http\Middleware\EnsureIsAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexController::class)->name('home');
Route::get('/blog/{slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Admin panel
Route::middleware(['auth', 'verified', EnsureIsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', Admin\DashboardController::class)->name('dashboard');

        Route::get('/projects', [Admin\ProjectController::class, 'index'])->name('projects.index');
        Route::post('/projects', [Admin\ProjectController::class, 'store'])->name('projects.store');
        Route::put('/projects/{project}', [Admin\ProjectController::class, 'update'])->name('projects.update');
        Route::delete('/projects/{project}', [Admin\ProjectController::class, 'destroy'])->name('projects.destroy');

        Route::get('/blogs', [Admin\BlogController::class, 'index'])->name('blogs.index');
        Route::post('/blogs', [Admin\BlogController::class, 'store'])->name('blogs.store');
        Route::put('/blogs/{blog}', [Admin\BlogController::class, 'update'])->name('blogs.update');
        Route::delete('/blogs/{blog}', [Admin\BlogController::class, 'destroy'])->name('blogs.destroy');

        Route::get('/contacts', [Admin\ContactController::class, 'index'])->name('contacts.index');
        Route::patch('/contacts/{contact}/read', [Admin\ContactController::class, 'markAsRead'])->name('contacts.read');
        Route::delete('/contacts/{contact}', [Admin\ContactController::class, 'destroy'])->name('contacts.destroy');

        Route::get('/analytics', [Admin\AnalyticsController::class, 'index'])->name('analytics.index');

        Route::get('/settings', [Admin\SiteSettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [Admin\SiteSettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/seo-image', [Admin\SiteSettingController::class, 'uploadSeoImage'])->name('settings.seo-image.upload');
        Route::delete('/settings/seo-image', [Admin\SiteSettingController::class, 'deleteSeoImage'])->name('settings.seo-image.delete');
    });

require __DIR__.'/settings.php';
