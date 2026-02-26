<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\SiteSetting;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function show(string $slug)
    {
        $blog = Blog::published()->where('slug', $slug)->firstOrFail();

        // Get related blogs (same tags, exclude current)
        $relatedBlogs = Blog::published()
            ->where('id', '!=', $blog->id)
            ->where(function ($query) use ($blog) {
                if ($blog->tags) {
                    foreach ($blog->tags as $tag) {
                        $query->orWhereJsonContains('tags', $tag);
                    }
                }
            })
            ->latest('published_at')
            ->limit(3)
            ->get();

        // SEO data with fallbacks
        $siteTitle = SiteSetting::get('seo_title', '');
        $siteUrl = url('/');
        $blogUrl = url("/blog/{$blog->slug}");

        return Inertia::render('Blog', [
            'blog' => $blog,
            'relatedBlogs' => $relatedBlogs,
            'seo' => [
                'title' => $blog->title . ($siteTitle ? " | {$siteTitle}" : ''),
                'description' => $blog->excerpt ?? strip_tags(substr($blog->content, 0, 160)),
                'keywords' => $blog->tags ? implode(', ', $blog->tags) : '',
                'canonical' => $blogUrl,
                'ogTitle' => $blog->title,
                'ogDescription' => $blog->excerpt ?? strip_tags(substr($blog->content, 0, 160)),
                'ogImage' => $blog->image ? asset("storage/{$blog->image}") : '',
                'ogType' => 'article',
                'twitterCard' => 'summary_large_image',
                'twitterTitle' => $blog->title,
                'twitterDescription' => $blog->excerpt ?? strip_tags(substr($blog->content, 0, 160)),
                'twitterImage' => $blog->image ? asset("storage/{$blog->image}") : '',
                'favicon' => SiteSetting::get('favicon', ''),
                'publishedAt' => $blog->published_at?->toIso8601String(),
            ],
        ]);
    }
}
