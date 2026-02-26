<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SiteSettingController extends Controller
{
    private const SEO_KEYS = [
        'seo_title', 'seo_description', 'seo_keywords', 'seo_canonical',
        'og_title', 'og_description', 'og_image', 'og_type',
        'twitter_card', 'twitter_title', 'twitter_description', 'twitter_image',
        'favicon',
    ];

    public function edit()
    {
        $settings = [
            'hero_title' => SiteSetting::get('hero_title', 'Hola, soy un Desarrollador Laravel'),
            'hero_subtitle' => SiteSetting::get('hero_subtitle', 'Creando experiencias web con código limpio y diseño funcional.'),
            'hero_badge' => SiteSetting::get('hero_badge', 'Full Stack Developer'),
            'about' => SiteSetting::get('about', ''),
            'hobbies' => SiteSetting::get('hobbies', ''),
            'social_github' => SiteSetting::get('social_github', ''),
            'social_linkedin' => SiteSetting::get('social_linkedin', ''),
            'social_twitter' => SiteSetting::get('social_twitter', ''),
            'social_instagram' => SiteSetting::get('social_instagram', ''),
            'social_youtube' => SiteSetting::get('social_youtube', ''),
            'social_tiktok' => SiteSetting::get('social_tiktok', ''),
            'social_discord' => SiteSetting::get('social_discord', ''),
            'social_website' => SiteSetting::get('social_website', ''),
            'section_about_visible' => SiteSetting::get('section_about_visible', '1'),
            'section_projects_visible' => SiteSetting::get('section_projects_visible', '1'),
            'section_portfolio_visible' => SiteSetting::get('section_portfolio_visible', '1'),
            'section_blog_visible' => SiteSetting::get('section_blog_visible', '1'),
            'section_contact_visible' => SiteSetting::get('section_contact_visible', '1'),
        ];

        foreach (self::SEO_KEYS as $key) {
            $settings[$key] = SiteSetting::get($key, '');
        }

        return Inertia::render('admin/SiteSettings', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => 'nullable|string|max:500',
            'hero_subtitle' => 'nullable|string|max:1000',
            'hero_badge' => 'nullable|string|max:200',
            'about' => 'nullable|string|max:50000',
            'hobbies' => 'nullable|string|max:50000',
            'social_github' => 'nullable|url|max:500',
            'social_linkedin' => 'nullable|url|max:500',
            'social_twitter' => 'nullable|url|max:500',
            'social_instagram' => 'nullable|url|max:500',
            'social_youtube' => 'nullable|url|max:500',
            'social_tiktok' => 'nullable|url|max:500',
            'social_discord' => 'nullable|url|max:500',
            'social_website' => 'nullable|url|max:500',
            'section_about_visible' => 'nullable|string|in:0,1',
            'section_projects_visible' => 'nullable|string|in:0,1',
            'section_portfolio_visible' => 'nullable|string|in:0,1',
            'section_blog_visible' => 'nullable|string|in:0,1',
            'section_contact_visible' => 'nullable|string|in:0,1',
            'seo_title' => 'nullable|string|max:200',
            'seo_description' => 'nullable|string|max:500',
            'seo_keywords' => 'nullable|string|max:500',
            'seo_canonical' => 'nullable|url|max:500',
            'og_title' => 'nullable|string|max:200',
            'og_description' => 'nullable|string|max:500',
            'og_type' => 'nullable|string|max:50',
            'twitter_card' => 'nullable|string|in:summary,summary_large_image',
            'twitter_title' => 'nullable|string|max:200',
            'twitter_description' => 'nullable|string|max:500',
        ]);

        foreach ($validated as $key => $value) {
            SiteSetting::set($key, $value ?? '');
        }

        return back()->with('success', 'Configuración actualizada.');
    }

    public function uploadSeoImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
            'type' => 'required|string|in:og_image,twitter_image,favicon',
        ]);

        $type = $request->input('type');
        $old = SiteSetting::get($type, '');

        if ($old && Storage::disk('public')->exists($old)) {
            Storage::disk('public')->delete($old);
        }

        $path = $request->file('image')->store('seo', 'public');
        SiteSetting::set($type, $path);

        return back()->with('success', 'Imagen actualizada.');
    }

    public function deleteSeoImage(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:og_image,twitter_image,favicon',
        ]);

        $type = $request->input('type');
        $old = SiteSetting::get($type, '');

        if ($old && Storage::disk('public')->exists($old)) {
            Storage::disk('public')->delete($old);
        }

        SiteSetting::set($type, '');

        return back()->with('success', 'Imagen eliminada.');
    }
}
