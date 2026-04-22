<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\SiteSetting;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function show(string $slug)
    {
        $project = Project::published()->where('slug', $slug)->firstOrFail();

        $relatedProjects = Project::published()
            ->where('id', '!=', $project->id)
            ->where('type', $project->type)
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        $projectUrl = url("/projects/{$project->slug}");
        $seoTitle = Str::limit($project->title, 60);
        $seoSource = $project->description ?: strip_tags((string) ($project->challenge ?: $project->solution));
        $seoDescription = Str::limit(trim(strip_tags($seoSource)), 150);

        return Inertia::render('Project', [
            'project' => $project,
            'relatedProjects' => $relatedProjects,
            'seo' => [
                'title' => $seoTitle,
                'description' => $seoDescription,
                'keywords' => $project->tags ? implode(', ', $project->tags) : '',
                'canonical' => $projectUrl,
                'ogTitle' => $seoTitle,
                'ogDescription' => $seoDescription,
                'ogImage' => $project->image ? asset("storage/{$project->image}") : '',
                'ogType' => 'article',
                'twitterCard' => 'summary_large_image',
                'twitterTitle' => $seoTitle,
                'twitterDescription' => $seoDescription,
                'twitterImage' => $project->image ? asset("storage/{$project->image}") : '',
                'favicon' => SiteSetting::get('favicon', ''),
                'publishedAt' => $project->created_at?->toIso8601String(),
            ],
        ]);
    }
}
