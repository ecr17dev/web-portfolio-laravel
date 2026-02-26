<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Project;
use App\Models\SiteSetting;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function __invoke()
    {
        $socialKeys = ['github', 'linkedin', 'twitter', 'instagram', 'youtube', 'tiktok', 'discord', 'website'];
        $socials = [];
        foreach ($socialKeys as $key) {
            $url = SiteSetting::get("social_{$key}", '');
            if ($url) {
                $socials[] = ['network' => $key, 'url' => $url];
            }
        }

        $sectionVisibility = [
            'about' => SiteSetting::get('section_about_visible', '1') === '1',
            'projects' => SiteSetting::get('section_projects_visible', '1') === '1',
            'portfolio' => SiteSetting::get('section_portfolio_visible', '1') === '1',
            'blog' => SiteSetting::get('section_blog_visible', '1') === '1',
            'contact' => SiteSetting::get('section_contact_visible', '1') === '1',
        ];

        return Inertia::render('Index', [
            'heroTitle' => SiteSetting::get('hero_title', 'Hola, soy un Desarrollador Laravel'),
            'heroSubtitle' => SiteSetting::get('hero_subtitle', 'Creando experiencias web con código limpio y diseño funcional.'),
            'heroBadge' => SiteSetting::get('hero_badge', 'Full Stack Developer'),
            'about' => SiteSetting::get('about', ''),
            'hobbies' => SiteSetting::get('hobbies', ''),
            'socials' => $socials,
            'sideProjects' => Project::published()->sideProjects()->orderBy('sort_order')->paginate(3, ['*'], 'side_projects_page'),
            'portfolios' => Project::published()->portfolio()->orderBy('sort_order')->paginate(3, ['*'], 'portfolios_page'),
            'blogs' => Blog::published()->latest('published_at')->paginate(3, ['*'], 'blogs_page'),
            'sectionVisibility' => $sectionVisibility,
            'seo' => [
                'title' => SiteSetting::get('seo_title', ''),
                'description' => SiteSetting::get('seo_description', ''),
                'keywords' => SiteSetting::get('seo_keywords', ''),
                'canonical' => SiteSetting::get('seo_canonical', ''),
                'ogTitle' => SiteSetting::get('og_title', ''),
                'ogDescription' => SiteSetting::get('og_description', ''),
                'ogImage' => SiteSetting::get('og_image', ''),
                'ogType' => SiteSetting::get('og_type', 'website'),
                'twitterCard' => SiteSetting::get('twitter_card', 'summary_large_image'),
                'twitterTitle' => SiteSetting::get('twitter_title', ''),
                'twitterDescription' => SiteSetting::get('twitter_description', ''),
                'twitterImage' => SiteSetting::get('twitter_image', ''),
                'favicon' => SiteSetting::get('favicon', ''),
            ],
        ]);
    }
}
