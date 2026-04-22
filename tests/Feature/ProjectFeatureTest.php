<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_project_detail_page_is_available_for_published_projects(): void
    {
        $project = Project::create([
            'title' => 'Proyecto Visible',
            'slug' => 'proyecto-visible',
            'description' => 'Descripcion del proyecto',
            'type' => 'side_project',
            'published' => true,
        ]);

        $response = $this->get("/projects/{$project->slug}");

        $response->assertOk();
        $response->assertSee($project->title);
    }

    public function test_project_detail_returns_404_for_unpublished_projects(): void
    {
        $project = Project::create([
            'title' => 'Proyecto Privado',
            'slug' => 'proyecto-privado',
            'description' => 'No publicado',
            'type' => 'portfolio',
            'published' => false,
        ]);

        $response = $this->get("/projects/{$project->slug}");

        $response->assertNotFound();
    }

    public function test_sitemap_contains_published_project_urls(): void
    {
        $project = Project::create([
            'title' => 'Proyecto SEO',
            'slug' => 'proyecto-seo',
            'description' => 'SEO project',
            'type' => 'portfolio',
            'published' => true,
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertSee(url("/projects/{$project->slug}"), false);
    }

    public function test_admin_can_store_project_with_features_gallery_and_technologies(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $payload = [
            'title' => 'Nuevo Proyecto Completo',
            'description' => '<p>Resumen completo</p>',
            'challenge' => 'Problema principal',
            'solution' => 'Solucion propuesta',
            'results' => 'Resultados obtenidos',
            'type' => 'side_project',
            'published' => true,
            'featured' => true,
            'sort_order' => 2,
            'features' => ['Feature A', 'Feature B'],
            'technologies' => [
                ['key' => 'laravel', 'name' => 'Laravel', 'icon' => 'IconBrandLaravel'],
                ['key' => 'vue', 'name' => 'Vue.js', 'icon' => 'IconBrandVue'],
            ],
            'gallery_images' => [
                UploadedFile::fake()->image('gallery-1.jpg'),
                UploadedFile::fake()->image('gallery-2.jpg'),
            ],
        ];

        $response = $this->actingAs($admin)->post('/admin/projects', $payload);

        $response->assertRedirect();

        $project = Project::where('slug', 'nuevo-proyecto-completo')->first();
        $this->assertNotNull($project);
        $this->assertSame(['Feature A', 'Feature B'], $project->features);
        $this->assertCount(2, $project->gallery ?? []);
        $this->assertCount(2, $project->technologies ?? []);
        $this->assertSame('Laravel', $project->technologies[0]['name']);
    }

    public function test_admin_update_appends_gallery_and_updates_structured_fields(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $project = Project::create([
            'title' => 'Proyecto Inicial',
            'slug' => 'proyecto-inicial',
            'description' => 'Resumen inicial',
            'type' => 'portfolio',
            'published' => true,
            'gallery' => ['projects/existing.jpg'],
            'features' => ['Antes'],
            'technologies' => [
                ['key' => 'php', 'name' => 'PHP', 'icon' => 'IconBrandPhp'],
            ],
        ]);

        $payload = [
            '_method' => 'PUT',
            'title' => 'Proyecto Inicial',
            'description' => 'Resumen actualizado',
            'challenge' => 'Nuevo problema',
            'solution' => 'Nueva solucion',
            'results' => 'Nuevo resultado',
            'type' => 'portfolio',
            'published' => true,
            'featured' => false,
            'sort_order' => 1,
            'features' => ['Despues 1', 'Despues 2'],
            'technologies' => [
                ['key' => 'typescript', 'name' => 'TypeScript', 'icon' => 'IconBrandTypescript'],
            ],
            'gallery_images' => [
                UploadedFile::fake()->image('gallery-new.jpg'),
            ],
        ];

        $response = $this->actingAs($admin)->post("/admin/projects/{$project->id}", $payload);

        $response->assertRedirect();

        $project->refresh();

        $this->assertSame(['Despues 1', 'Despues 2'], $project->features);
        $this->assertCount(2, $project->gallery ?? []);
        $this->assertSame('TypeScript', $project->technologies[0]['name']);
        $this->assertSame('Nuevo problema', $project->challenge);
    }
}
