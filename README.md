<p align="center">
 <img width="100%" alt="image" src="https://github.com/user-attachments/assets/5528873f-6eef-4b96-a8eb-37c2108d0ec2" />

</p>

<h1 align="center">Web Portfolio Laravel</h1>

<p align="center">
  A modern, open-source developer portfolio built with Laravel 12, Vue 3, Inertia.js and TailwindCSS v4.<br/>
  Bento-style design with dark/light mode, one-page landing, blog, contact form and a full admin panel.
</p>

<p align="center">
  <a href="https://github.com/ecr17dev/web-portfolio-laravel/blob/main/LICENSE"><img src="https://img.shields.io/badge/license-MIT-blue.svg" alt="License" /></a>
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white" alt="Laravel 12" />
  <img src="https://img.shields.io/badge/Vue-3.5-42b883?logo=vuedotjs&logoColor=white" alt="Vue 3" />
  <img src="https://img.shields.io/badge/TailwindCSS-4-06b6d4?logo=tailwindcss&logoColor=white" alt="TailwindCSS 4" />
  <img src="https://img.shields.io/badge/Inertia.js-2-9553e9?logo=inertia&logoColor=white" alt="Inertia.js" />
</p>

---

## Features

- **One-page landing** — Hero, About, Hobbies, Side Projects, Portfolio, Blog and Contact sections in a single scroll
- **Bento-style layout** — Inspired by Laravel 12's design language
- **Dark / Light mode** — System-aware with manual toggle, logo swap included
- **Blog** — Individual post pages with rich content, tags and pagination
- **Contact form** — Submissions stored in DB with SweetAlert2 feedback
- **Pixel particles** — Animated canvas background component
- **Blur navbar** — Sticky navigation with glassmorphism effect
- **SEO ready** — Open Graph, Twitter Cards and meta tags configurable from admin
- **Visit analytics** — Page view tracking with chart.js dashboards
- **Two-factor authentication** — Powered by Laravel Fortify
- **Admin panel** — Full CRUD for projects, blogs, contacts, site settings and analytics

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | PHP 8.2+, Laravel 12, Laravel Fortify |
| Frontend | Vue 3.5, TypeScript, Inertia.js 2 |
| Styling | TailwindCSS 4, tw-animate-css |
| UI Components | Reka UI, Lucide Icons, Tabler Icons |
| Rich Editor | CKEditor 5 |
| Charts | Chart.js + vue-chartjs |
| Database | SQLite (default) / MySQL / PostgreSQL |
| Build | Vite 7 |

## Screenshots

> Add your own screenshots here after deploying.

## Requirements

- PHP >= 8.2
- Composer
- Node.js >= 18
- npm

## Installation

```bash
# Clone the repository
git clone https://github.com/ecr17dev/web-portfolio-laravel.git
cd web-portfolio-laravel

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Create database and run migrations
touch database/database.sqlite
php artisan migrate

# (Optional) Seed demo data
php artisan db:seed

# Build frontend assets
npm run build
```

## Development

```bash
# Start all services concurrently (server + queue + vite)
composer dev
```

This runs the Laravel dev server, queue worker and Vite HMR in parallel. Open [http://localhost:8000](http://localhost:8000).

## Admin Panel

After seeding, log in at `/login` with:

| Field | Value |
|-------|-------|
| Email | `admin@example.com` |
| Password | `password` |

The admin panel is available at `/admin` and includes:

- **Dashboard** — Stats overview (projects, blogs, contacts, visits)
- **Projects** — CRUD for side projects and portfolio items
- **Blogs** — CRUD with rich text editor (CKEditor 5)
- **Contacts** — View and manage contact form submissions
- **Analytics** — Visit tracking with charts
- **Site Settings** — Edit hero, about, hobbies, social links and SEO metadata

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Admin CRUD controllers
│   │   ├── IndexController # Landing page
│   │   ├── BlogController  # Blog detail
│   │   └── ContactController
│   └── Middleware/
│       ├── EnsureIsAdmin   # Admin route guard
│       └── TrackVisits     # Page view tracking
├── Models/                 # User, Project, Blog, Contact, SiteSetting, Visit
resources/js/
├── components/             # Navbar, PixelParticles, RichEditor
├── layouts/                # AdminLayout, AuthLayout
└── pages/
    ├── Index.vue           # Landing page (one-scroll)
    ├── Blog.vue            # Blog detail page
    ├── admin/              # Admin panel pages
    └── auth/               # Login, 2FA, password reset
```

## Customization

All site content is editable from the admin panel:

- **Hero section** — Title, subtitle and badge text
- **About & Hobbies** — Rich text content
- **Social links** — GitHub, LinkedIn, Twitter, etc.
- **SEO** — Meta title, description, OG image and more
- **Section visibility** — Toggle sections on/off from settings

## Scripts

```bash
composer dev          # Start development environment
npm run build         # Build for production
npm run dev           # Vite dev server only
npm run lint          # ESLint fix
npm run format        # Prettier format
composer lint         # PHP Pint
composer test         # Run tests
```

## Contributing

Contributions are welcome. Please open an issue first to discuss what you'd like to change.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/my-feature`)
3. Commit your changes (`git commit -m 'feat: add my feature'`)
4. Push to the branch (`git push origin feature/my-feature`)
5. Open a Pull Request

## License

This project is open-sourced under the [MIT License](LICENSE).
