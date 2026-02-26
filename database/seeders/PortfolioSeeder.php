<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Contact;
use App\Models\Project;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure storage directories exist
        Storage::disk('public')->makeDirectory('projects');
        Storage::disk('public')->makeDirectory('blogs');

        // Generate placeholder images
        $this->generatePlaceholders();

        // Hero settings
        SiteSetting::set('hero_title', 'Hola, soy un Desarrollador Laravel');
        SiteSetting::set('hero_subtitle', 'Creando experiencias web con código limpio y diseño funcional.');
        SiteSetting::set('hero_badge', 'Full Stack Developer');

        // About & Hobbies
        SiteSetting::set('about', "Desarrollador Full Stack con +5 años de experiencia construyendo aplicaciones web robustas y escalables. Mi stack principal es Laravel + Vue.js, aunque también trabajo con Livewire, Inertia.js y TailwindCSS.\n\nMe apasiona escribir código limpio, crear arquitecturas bien pensadas y automatizar todo lo que se pueda. Actualmente trabajo como freelance ayudando a startups y empresas a llevar sus ideas al mundo digital.");

        SiteSetting::set('hobbies', "🎮 Gaming — Soy fan de los RPGs y juegos indie. Últimamente enganchado con Baldur's Gate 3.\n☕ Café — Preparo mi propio café cada mañana con método V60. El ritual es parte del proceso creativo.\n📚 Lectura — Leo sobre arquitectura de software, productividad y ciencia ficción.\n🏃 Running — Corro 3 veces por semana. Me ayuda a despejar la mente y pensar mejor.\n🎵 Música — Siempre programo con música. Lo-fi, post-rock y jazz son mis géneros favoritos.");

        // Side Projects (with placeholder images)
        Project::create([
            'title' => 'DevTracker',
            'slug' => 'devtracker',
            'description' => 'Aplicación de seguimiento de tiempo para desarrolladores. Registra horas por proyecto, genera reportes semanales y se integra con GitHub para trackear commits automáticamente.',
            'image' => 'projects/devtracker.svg',
            'url' => 'https://devtracker.example.com',
            'repo_url' => 'https://github.com/user/devtracker',
            'tags' => ['Laravel', 'Vue.js', 'TailwindCSS', 'GitHub API'],
            'type' => 'side_project',
            'featured' => true,
            'sort_order' => 1,
            'published' => true,
        ]);

        Project::create([
            'title' => 'SnippetVault',
            'slug' => 'snippetvault',
            'description' => 'Gestor de snippets de código con syntax highlighting, tags, búsqueda full-text y sincronización entre dispositivos. Pensado para devs que quieren tener su propia colección organizada.',
            'image' => 'projects/snippetvault.svg',
            'repo_url' => 'https://github.com/user/snippetvault',
            'tags' => ['Laravel', 'Livewire', 'Alpine.js', 'SQLite'],
            'type' => 'side_project',
            'featured' => false,
            'sort_order' => 2,
            'published' => true,
        ]);

        Project::create([
            'title' => 'CLI Invoice',
            'slug' => 'cli-invoice',
            'description' => 'Herramienta de línea de comandos para generar facturas en PDF desde un archivo YAML. Ideal para freelancers que quieren facturar rápido sin abrir ningún programa.',
            'image' => 'projects/cli-invoice.svg',
            'repo_url' => 'https://github.com/user/cli-invoice',
            'tags' => ['PHP', 'Laravel Zero', 'DomPDF'],
            'type' => 'side_project',
            'sort_order' => 3,
            'published' => true,
        ]);

        // Portfolio (with placeholder images)
        Project::create([
            'title' => 'MediConnect — Plataforma de Telemedicina',
            'slug' => 'mediconnect',
            'description' => 'Plataforma completa de telemedicina con videollamadas, gestión de citas, historial clínico digital y pasarela de pagos. Desarrollada para una clínica con +200 médicos activos y +15,000 pacientes registrados.',
            'image' => 'projects/mediconnect.svg',
            'url' => 'https://mediconnect.example.com',
            'tags' => ['Laravel', 'Vue.js', 'WebRTC', 'Stripe', 'Redis'],
            'type' => 'portfolio',
            'featured' => true,
            'sort_order' => 1,
            'published' => true,
        ]);

        Project::create([
            'title' => 'FreshMarket — E-commerce de Productos Orgánicos',
            'slug' => 'freshmarket',
            'description' => 'Tienda online con catálogo dinámico, carrito de compras, suscripciones semanales de canastas orgánicas y sistema de delivery con tracking en tiempo real. Procesando +500 órdenes semanales.',
            'image' => 'projects/freshmarket.svg',
            'url' => 'https://freshmarket.example.com',
            'tags' => ['Laravel', 'Inertia.js', 'Vue 3', 'MercadoPago', 'Google Maps API'],
            'type' => 'portfolio',
            'featured' => true,
            'sort_order' => 2,
            'published' => true,
        ]);

        Project::create([
            'title' => 'EduPlatform — LMS Corporativo',
            'slug' => 'eduplatform',
            'description' => 'Sistema de gestión de aprendizaje para empresas con cursos en video, evaluaciones, certificados automáticos y dashboard de progreso. Usado por 3 empresas con +2,000 empleados en total.',
            'image' => 'projects/eduplatform.svg',
            'url' => 'https://eduplatform.example.com',
            'tags' => ['Laravel', 'Vue.js', 'FFmpeg', 'AWS S3', 'Pusher'],
            'type' => 'portfolio',
            'sort_order' => 3,
            'published' => true,
        ]);

        Project::create([
            'title' => 'PropManager — Gestión Inmobiliaria',
            'slug' => 'propmanager',
            'description' => 'CRM inmobiliario con publicación automática en portales, gestión de leads, agenda de visitas y generación de contratos. Integrado con Inmuebles24 y Mercado Libre.',
            'image' => 'projects/propmanager.svg',
            'tags' => ['Laravel', 'Livewire', 'TailwindCSS', 'REST APIs'],
            'type' => 'portfolio',
            'sort_order' => 4,
            'published' => true,
        ]);

        // Blogs (with placeholder images)
        Blog::create([
            'title' => 'Cómo estructuro mis proyectos Laravel en 2025',
            'slug' => 'como-estructuro-mis-proyectos-laravel-2025',
            'excerpt' => 'Después de años trabajando con Laravel, encontré una estructura que me permite escalar proyectos sin perder la cordura. Te comparto mi approach con Actions, DTOs y Service classes.',
            'image' => 'blogs/laravel-structure.svg',
            'content' => "## El problema con la estructura por defecto\n\nLaravel es increíblemente flexible, pero esa flexibilidad puede ser un arma de doble filo.\n\n## Mi estructura actual\n\nUso una combinación de Actions, DTOs y Services.",
            'tags' => ['Laravel', 'Arquitectura', 'PHP', 'Best Practices'],
            'published' => true,
            'published_at' => now()->subDays(5),
        ]);

        Blog::create([
            'title' => 'Vue 3 Composables que uso en todos mis proyectos',
            'slug' => 'vue3-composables-que-uso-siempre',
            'excerpt' => 'Una colección de composables reutilizables que me ahorran horas de trabajo: useDebounce, usePagination, useConfirmDialog y más.',
            'image' => 'blogs/vue-composables.svg',
            'content' => "## ¿Por qué composables?\n\nLos composables son la forma más elegante de reutilizar lógica en Vue 3.",
            'tags' => ['Vue.js', 'Composables', 'TypeScript', 'Frontend'],
            'published' => true,
            'published_at' => now()->subDays(12),
        ]);

        Blog::create([
            'title' => 'Deploy de Laravel con GitHub Actions y Docker',
            'slug' => 'deploy-laravel-github-actions-docker',
            'excerpt' => 'Mi pipeline de CI/CD completo: tests automáticos, build de Docker, deploy a producción con zero downtime.',
            'image' => 'blogs/docker-deploy.svg',
            'content' => "## El setup\n\nUso un VPS con Docker Compose y GitHub Actions como CI/CD.",
            'tags' => ['DevOps', 'Docker', 'GitHub Actions', 'Laravel', 'CI/CD'],
            'published' => true,
            'published_at' => now()->subDays(20),
        ]);

        Blog::create([
            'title' => 'TailwindCSS v4: lo que cambió y cómo migrar',
            'slug' => 'tailwindcss-v4-migracion',
            'excerpt' => 'Tailwind v4 trajo cambios importantes en la configuración. Te explico las diferencias clave y cómo migrar.',
            'image' => 'blogs/tailwind-v4.svg',
            'content' => "## Los cambios principales\n\nCSS-first configuration, nuevo motor de compilación, variables CSS nativas.",
            'tags' => ['TailwindCSS', 'CSS', 'Frontend', 'Migración'],
            'published' => true,
            'published_at' => now()->subDays(30),
        ]);

        Blog::create([
            'title' => 'Autenticación con Laravel Fortify + Vue 3',
            'slug' => 'autenticacion-laravel-fortify-vue3',
            'excerpt' => 'Guía completa para implementar login, registro, 2FA y recuperación de contraseña usando Fortify como backend.',
            'image' => 'blogs/fortify-auth.svg',
            'content' => "## ¿Por qué Fortify?\n\nFortify te da toda la lógica de autenticación sin imponer vistas.",
            'tags' => ['Laravel', 'Fortify', 'Vue.js', 'Autenticación', '2FA'],
            'published' => true,
            'published_at' => now()->subDays(45),
        ]);

        // Contacts
        Contact::create([
            'name' => 'María González',
            'email' => 'maria.gonzalez@example.com',
            'subject' => 'Proyecto e-commerce',
            'message' => 'Hola, tengo una tienda de ropa y quiero lanzar mi e-commerce. ¿Podríamos agendar una llamada?',
            'read' => false,
        ]);

        Contact::create([
            'name' => 'Carlos Mendoza',
            'email' => 'carlos.mendoza@example.com',
            'subject' => 'Consultoría Laravel',
            'message' => 'Somos un equipo de 4 devs migrando de CodeIgniter a Laravel. ¿Ofreces consultoría por hora?',
            'read' => false,
        ]);

        Contact::create([
            'name' => 'Ana Rodríguez',
            'email' => 'ana.rodriguez@example.com',
            'subject' => 'Colaboración open source',
            'message' => 'Vi tu proyecto SnippetVault en GitHub. ¿Te interesaría colaborar?',
            'read' => true,
        ]);
    }

    private function generatePlaceholders(): void
    {
        $projects = [
            'projects/devtracker.svg' => ['DevTracker', '#6366f1', '#818cf8'],
            'projects/snippetvault.svg' => ['SnippetVault', '#8b5cf6', '#a78bfa'],
            'projects/cli-invoice.svg' => ['CLI Invoice', '#06b6d4', '#22d3ee'],
            'projects/mediconnect.svg' => ['MediConnect', '#10b981', '#34d399'],
            'projects/freshmarket.svg' => ['FreshMarket', '#f59e0b', '#fbbf24'],
            'projects/eduplatform.svg' => ['EduPlatform', '#3b82f6', '#60a5fa'],
            'projects/propmanager.svg' => ['PropManager', '#ef4444', '#f87171'],
        ];

        $blogs = [
            'blogs/laravel-structure.svg' => ['Laravel', '#ff2d20', '#ff6b5b'],
            'blogs/vue-composables.svg' => ['Vue.js', '#42b883', '#64d8a4'],
            'blogs/docker-deploy.svg' => ['Docker', '#2496ed', '#56b4f5'],
            'blogs/tailwind-v4.svg' => ['Tailwind', '#06b6d4', '#22d3ee'],
            'blogs/fortify-auth.svg' => ['Fortify', '#ff2d20', '#ff6b5b'],
        ];

        foreach (array_merge($projects, $blogs) as $path => [$label, $color1, $color2]) {
            $svg = $this->makePlaceholderSvg($label, $color1, $color2);
            Storage::disk('public')->put($path, $svg);
        }
    }

    private function makePlaceholderSvg(string $label, string $color1, string $color2): string
    {
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="800" height="400" viewBox="0 0 800 400">
  <defs>
    <linearGradient id="g" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:{$color1};stop-opacity:1" />
      <stop offset="100%" style="stop-color:{$color2};stop-opacity:1" />
    </linearGradient>
  </defs>
  <rect width="800" height="400" fill="url(#g)" rx="0"/>
  <text x="400" y="200" font-family="system-ui,sans-serif" font-size="48" font-weight="600" fill="white" text-anchor="middle" dominant-baseline="central" opacity="0.9">{$label}</text>
  <text x="400" y="250" font-family="system-ui,sans-serif" font-size="16" fill="white" text-anchor="middle" dominant-baseline="central" opacity="0.5">800 × 400</text>
</svg>
SVG;
    }
}
