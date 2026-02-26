<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/Projects', [
            'projects' => Project::latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'url' => 'nullable|url|max:255',
            'repo_url' => 'nullable|url|max:255',
            'tags' => 'nullable|array',
            'type' => 'required|in:side_project,portfolio',
            'featured' => 'boolean',
            'sort_order' => 'integer',
            'published' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('projects', 'public');
        }

        Project::create($validated);

        return back()->with('success', 'Proyecto creado.');
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'url' => 'nullable|url|max:255',
            'repo_url' => 'nullable|url|max:255',
            'tags' => 'nullable|array',
            'type' => 'required|in:side_project,portfolio',
            'featured' => 'boolean',
            'sort_order' => 'integer',
            'published' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('projects', 'public');
        }

        $project->update($validated);

        return back()->with('success', 'Proyecto actualizado.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return back()->with('success', 'Proyecto eliminado.');
    }
}
