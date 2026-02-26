<script setup lang="ts">
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import { useAppearance } from '@/composables/useAppearance';
import PixelParticles from '@/components/PixelParticles.vue';
import Navbar from '@/components/Navbar.vue';
import Swal from 'sweetalert2';
import {
  IconUser, IconRocket, IconBriefcase, IconArticle, IconMail,
  IconBrandGithub, IconBrandLinkedin, IconBrandX, IconBrandInstagram,
  IconBrandYoutube, IconBrandTiktok, IconBrandDiscord, IconWorld,
  IconExternalLink, IconArrowUp, IconSend, IconCalendar, IconTag,
  IconCode, IconTerminal2, IconDeviceGamepad2,
} from '@tabler/icons-vue';
import { type Component, computed } from 'vue';

type Project = { id: number; title: string; description: string; image: string | null; url: string | null; repo_url: string | null; tags: string[] | null; type: string; featured: boolean };
type Blog = { id: number; title: string; slug: string; excerpt: string | null; image: string | null; tags: string[] | null; published_at: string | null };
type Paginated<T> = { data: T[]; current_page: number; last_page: number; links: { url: string | null; label: string; active: boolean }[] };
type Social = { network: string; url: string };

type Seo = {
  title: string; description: string; keywords: string; canonical: string;
  ogTitle: string; ogDescription: string; ogImage: string; ogType: string;
  twitterCard: string; twitterTitle: string; twitterDescription: string; twitterImage: string;
  favicon: string;
};

const props = defineProps<{
  heroTitle: string; heroSubtitle: string; heroBadge: string;
  about: string; hobbies: string; socials: Social[];
  sideProjects: Paginated<Project>; portfolios: Paginated<Project>; blogs: Paginated<Blog>;
  sectionVisibility: Record<string, boolean>;
  seo: Seo;
}>();

const socialIcons: Record<string, Component> = {
  github: IconBrandGithub, linkedin: IconBrandLinkedin, twitter: IconBrandX,
  instagram: IconBrandInstagram, youtube: IconBrandYoutube, tiktok: IconBrandTiktok,
  discord: IconBrandDiscord, website: IconWorld,
};

const { resolvedAppearance } = useAppearance();

const contactForm = useForm({ name: '', email: '', subject: '', message: '' });
function submitContact() {
  contactForm.post('/contact', {
    preserveScroll: true,
    onSuccess: () => {
      contactForm.reset();
      Swal.fire({
        icon: 'success',
        title: 'Mensaje enviado',
        text: 'Gracias por escribirme, te responderé pronto.',
        confirmButtonText: 'Cerrar',
        background: resolvedAppearance.value === 'dark' ? '#0a0a0a' : '#ffffff',
        color: resolvedAppearance.value === 'dark' ? '#fafafa' : '#0a0a0a',
        confirmButtonColor: resolvedAppearance.value === 'dark' ? '#fafafa' : '#171717',
        customClass: { popup: 'rounded-xl border border-white/10' },
      });
    },
  });
}

const allSections = [
  { id: 'about', label: 'Sobre mí', icon: IconUser, visKey: 'about' },
  { id: 'side-projects', label: 'Projects', icon: IconRocket, visKey: 'projects' },
  { id: 'portfolio', label: 'Portafolio', icon: IconBriefcase, visKey: 'portfolio' },
  { id: 'blog', label: 'Blog', icon: IconArticle, visKey: 'blog' },
  { id: 'contact', label: 'Contacto', icon: IconMail, visKey: 'contact' },
];
function scrollTo(id: string) { document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' }); }
function goToPage(url: string | null, sectionId: string) {
  if (!url) return;
  router.get(url, {}, { preserveScroll: true, preserveState: true, only: ['sideProjects', 'portfolios', 'blogs'], onSuccess: () => { setTimeout(() => scrollTo(sectionId), 100); } });
}
function paginationLabel(label: string): string {
  return label.replace('&laquo; Previous', '« Anterior').replace('Next &raquo;', 'Siguiente »');
}
</script>

<template>
<Head :title="seo.title || 'Portafolio'">
  <meta v-if="seo.description" name="description" :content="seo.description" />
  <meta v-if="seo.keywords" name="keywords" :content="seo.keywords" />
  <link v-if="seo.canonical" rel="canonical" :href="seo.canonical" />
  <link v-if="seo.favicon" rel="icon" :href="`/storage/${seo.favicon}`" />
  <!-- Open Graph -->
  <meta v-if="seo.ogTitle || seo.title" property="og:title" :content="seo.ogTitle || seo.title" />
  <meta v-if="seo.ogDescription || seo.description" property="og:description" :content="seo.ogDescription || seo.description" />
  <meta property="og:type" :content="seo.ogType || 'website'" />
  <meta v-if="seo.ogImage" property="og:image" :content="`/storage/${seo.ogImage}`" />
  <meta v-if="seo.canonical" property="og:url" :content="seo.canonical" />
  <!-- Twitter Card -->
  <meta name="twitter:card" :content="seo.twitterCard || 'summary_large_image'" />
  <meta v-if="seo.twitterTitle || seo.ogTitle || seo.title" name="twitter:title" :content="seo.twitterTitle || seo.ogTitle || seo.title" />
  <meta v-if="seo.twitterDescription || seo.ogDescription || seo.description" name="twitter:description" :content="seo.twitterDescription || seo.ogDescription || seo.description" />
  <meta v-if="seo.twitterImage || seo.ogImage" name="twitter:image" :content="`/storage/${seo.twitterImage || seo.ogImage}`" />
</Head>
<PixelParticles :isDark="resolvedAppearance === 'dark'" />
<Navbar :sections="allSections" :sectionVisibility="sectionVisibility" :showSections="true" />

<main class="min-h-screen bg-background pt-14">

  <!-- ===== HERO ===== -->
  <section class="relative z-20 mx-auto max-w-5xl px-6 pt-20 pb-12 text-center">
    <span v-if="heroBadge" class="mb-4 inline-flex items-center gap-1.5 rounded-full border border-border/50 bg-white/50 dark:bg-white/5 backdrop-blur-lg px-3 py-1 text-xs font-medium text-muted-foreground">
      <IconTerminal2 class="h-3 w-3 text-primary" :stroke-width="1.5" /> {{ heroBadge }}
    </span>
    <h1 class="text-3xl font-bold tracking-tight text-foreground sm:text-4xl lg:text-5xl leading-tight">{{ heroTitle }}</h1>
    <p class="mx-auto mt-4 max-w-xl text-base text-muted-foreground leading-relaxed">{{ heroSubtitle }}</p>
    <div class="mt-6 flex items-center justify-center gap-3">
      <button @click="scrollTo('contact')" class="inline-flex items-center gap-2 rounded-lg bg-primary px-5 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 transition-colors">
        <IconMail class="h-4 w-4" :stroke-width="1.5" /> Contactar
      </button>
      <button @click="scrollTo('portfolio')" class="inline-flex items-center gap-2 rounded-lg border border-border/50 bg-white/50 dark:bg-white/5 backdrop-blur-lg px-5 py-2 text-sm font-medium text-foreground hover:bg-white/70 dark:hover:bg-white/10 transition-colors">
        <IconBriefcase class="h-4 w-4" :stroke-width="1.5" /> Ver trabajo
      </button>
    </div>
  </section>

  <!-- ===== ABOUT + HOBBIES ===== -->
  <section v-if="sectionVisibility.about" id="about" class="relative z-20 mx-auto max-w-5xl px-6 py-10">
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
      <div class="rounded-xl border border-border/50 bg-white/50 dark:bg-white/5 backdrop-blur-lg p-6 lg:col-span-2">
        <div class="mb-3 inline-flex items-center gap-1.5 text-xs font-medium text-primary">
          <IconUser class="h-3.5 w-3.5" :stroke-width="1.5" /> Sobre mí
        </div>
        <div v-if="about" class="prose prose-sm dark:prose-invert text-muted-foreground" v-html="about"></div>
        <p v-else class="text-sm text-muted-foreground">Desarrollador especializado en Laravel y Vue.js.</p>
      </div>
      <div class="rounded-xl border border-border/50 bg-white/50 dark:bg-white/5 backdrop-blur-lg p-6">
        <div class="mb-3 inline-flex items-center gap-1.5 text-xs font-medium text-primary">
          <IconDeviceGamepad2 class="h-3.5 w-3.5" :stroke-width="1.5" /> Hobbies
        </div>
        <div v-if="hobbies" class="prose prose-sm dark:prose-invert text-muted-foreground text-[13px]" v-html="hobbies"></div>
        <p v-else class="text-sm text-muted-foreground">Configura desde admin.</p>
      </div>
    </div>
  </section>

  <!-- ===== SIDE PROJECTS ===== -->
  <section v-if="sectionVisibility.projects" id="side-projects" class="relative z-20 mx-auto max-w-5xl px-6 py-10">
    <div class="mb-5 flex items-center gap-2">
      <IconRocket class="h-5 w-5 text-primary" :stroke-width="1.5" />
      <h2 class="text-lg font-bold text-foreground">Side Projects</h2>
    </div>
    <div v-if="sideProjects.data.length" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
      <template v-for="(p, i) in sideProjects.data" :key="p.id">
        <!-- Pattern: wide, compact, compact (repeats every 3) -->
        <div v-if="i % 3 === 0" class="group overflow-hidden rounded-xl border border-border/50 bg-white/50 dark:bg-white/5 backdrop-blur-lg transition-colors hover:border-primary/30 sm:col-span-2">
          <div class="grid grid-cols-1 sm:grid-cols-2">
            <img v-if="p.image" :src="`/storage/${p.image}`" :alt="p.title" class="h-52 w-full object-cover sm:h-full" />
            <div class="flex flex-col p-5">
              <div v-if="p.featured" class="mb-2 inline-flex w-fit items-center gap-1 rounded-full bg-primary/10 px-2 py-0.5 text-[11px] font-medium text-primary">
                <IconRocket class="h-3 w-3" :stroke-width="1.5" /> Destacado
              </div>
              <h3 class="text-base font-semibold text-foreground">{{ p.title }}</h3>
              <div class="mt-2 text-[13px] text-muted-foreground prose prose-sm leading-relaxed" v-html="p.description"></div>
              <div class="mt-3 flex flex-wrap gap-1">
                <span v-for="tag in p.tags" :key="tag" class="rounded-full bg-white/60 dark:bg-white/10 px-2 py-0.5 text-[11px] text-secondary-foreground">{{ tag }}</span>
              </div>
              <div class="mt-auto flex gap-3 pt-4">
                <a v-if="p.url" :href="p.url" target="_blank" class="inline-flex items-center gap-1 text-[13px] text-primary hover:underline"><IconExternalLink class="h-3 w-3" :stroke-width="1.5" /> Demo</a>
                <a v-if="p.repo_url" :href="p.repo_url" target="_blank" class="inline-flex items-center gap-1 text-[13px] text-muted-foreground hover:text-foreground"><IconBrandGithub class="h-3 w-3" :stroke-width="1.5" /> Código</a>
              </div>
            </div>
          </div>
        </div>
        <div v-else class="group overflow-hidden rounded-xl border border-border/50 bg-white/50 dark:bg-white/5 backdrop-blur-lg transition-colors hover:border-primary/30">
          <img v-if="p.image" :src="`/storage/${p.image}`" :alt="p.title" class="h-40 w-full object-cover" />
          <div class="flex flex-col p-5">
            <h3 class="text-sm font-semibold text-foreground">{{ p.title }}</h3>
            <div class="mt-1.5 line-clamp-3 text-[13px] text-muted-foreground prose prose-sm" v-html="p.description"></div>
            <div class="mt-3 flex flex-wrap gap-1">
              <span v-for="tag in p.tags" :key="tag" class="rounded-full bg-white/60 dark:bg-white/10 px-2 py-0.5 text-[11px] text-secondary-foreground">{{ tag }}</span>
            </div>
            <div class="mt-auto flex gap-3 pt-3">
              <a v-if="p.url" :href="p.url" target="_blank" class="inline-flex items-center gap-1 text-[13px] text-primary hover:underline"><IconExternalLink class="h-3 w-3" :stroke-width="1.5" /> Demo</a>
              <a v-if="p.repo_url" :href="p.repo_url" target="_blank" class="inline-flex items-center gap-1 text-[13px] text-muted-foreground hover:text-foreground"><IconBrandGithub class="h-3 w-3" :stroke-width="1.5" /> Código</a>
            </div>
          </div>
        </div>
      </template>
    </div>
    <div v-else class="rounded-xl border border-dashed border-border/50 bg-white/50 dark:bg-white/5 backdrop-blur-lg p-10 text-center text-sm text-muted-foreground">Próximamente...</div>
    <div v-if="sideProjects.last_page > 1" class="mt-4 flex items-center justify-center gap-1">
      <button v-for="link in sideProjects.links" :key="link.label" :disabled="!link.url" @click="goToPage(link.url, 'side-projects')" class="rounded-md border px-2.5 py-1 text-xs transition-colors disabled:opacity-30" :class="link.active ? 'border-primary bg-primary text-primary-foreground' : 'border-border/50 bg-white/50 dark:bg-white/5 backdrop-blur-lg text-muted-foreground hover:text-foreground'" v-html="paginationLabel(link.label)" />
    </div>
  </section>

  <!-- ===== PORTFOLIO ===== -->
  <section v-if="sectionVisibility.portfolio" id="portfolio" class="relative z-20 mx-auto max-w-5xl px-6 py-10">
    <div class="mb-5 flex items-center gap-2">
      <IconBriefcase class="h-5 w-5 text-primary" :stroke-width="1.5" />
      <h2 class="text-lg font-bold text-foreground">Portafolio</h2>
    </div>
    <div v-if="portfolios.data.length" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
      <template v-for="(p, i) in portfolios.data" :key="p.id">
        <!-- Pattern: compact, compact, wide (repeats every 3) -->
        <div v-if="i % 3 === 2" class="group overflow-hidden rounded-xl border border-border/50 bg-white/50 dark:bg-white/5 backdrop-blur-lg transition-colors hover:border-primary/30 sm:col-span-2">
          <div class="grid grid-cols-1 sm:grid-cols-2">
            <img v-if="p.image" :src="`/storage/${p.image}`" :alt="p.title" class="h-52 w-full object-cover sm:h-full" />
            <div class="flex flex-col p-5">
              <h3 class="text-base font-semibold text-foreground">{{ p.title }}</h3>
              <div class="mt-2 text-[13px] text-muted-foreground prose prose-sm leading-relaxed" v-html="p.description"></div>
              <div class="mt-3 flex flex-wrap gap-1">
                <span v-for="tag in p.tags" :key="tag" class="rounded-full bg-white/60 dark:bg-white/10 px-2 py-0.5 text-[11px] text-secondary-foreground">{{ tag }}</span>
              </div>
              <div class="mt-auto flex gap-3 pt-4">
                <a v-if="p.url" :href="p.url" target="_blank" class="inline-flex items-center gap-1 text-[13px] text-primary hover:underline"><IconExternalLink class="h-3 w-3" :stroke-width="1.5" /> Ver proyecto</a>
                <a v-if="p.repo_url" :href="p.repo_url" target="_blank" class="inline-flex items-center gap-1 text-[13px] text-muted-foreground hover:text-foreground"><IconBrandGithub class="h-3 w-3" :stroke-width="1.5" /> Código</a>
              </div>
            </div>
          </div>
        </div>
        <div v-else class="group overflow-hidden rounded-xl border border-border/50 bg-white/50 dark:bg-white/5 backdrop-blur-lg transition-colors hover:border-primary/30">
          <img v-if="p.image" :src="`/storage/${p.image}`" :alt="p.title" class="h-44 w-full object-cover transition-transform group-hover:scale-[1.02]" />
          <div class="p-5">
            <h3 class="text-sm font-semibold text-foreground">{{ p.title }}</h3>
            <div class="mt-1.5 line-clamp-2 text-[13px] text-muted-foreground prose prose-sm" v-html="p.description"></div>
            <div class="mt-3 flex flex-wrap gap-1">
              <span v-for="tag in p.tags" :key="tag" class="rounded-full bg-white/60 dark:bg-white/10 px-2 py-0.5 text-[11px] text-secondary-foreground">{{ tag }}</span>
            </div>
            <div class="mt-3 flex gap-3">
              <a v-if="p.url" :href="p.url" target="_blank" class="inline-flex items-center gap-1 text-[13px] text-primary hover:underline"><IconExternalLink class="h-3 w-3" :stroke-width="1.5" /> Ver proyecto</a>
              <a v-if="p.repo_url" :href="p.repo_url" target="_blank" class="inline-flex items-center gap-1 text-[13px] text-muted-foreground hover:text-foreground"><IconBrandGithub class="h-3 w-3" :stroke-width="1.5" /> Código</a>
            </div>
          </div>
        </div>
      </template>
    </div>
    <div v-else class="rounded-xl border border-dashed border-border/50 bg-white/50 dark:bg-white/5 backdrop-blur-lg p-10 text-center text-sm text-muted-foreground">Próximamente...</div>
    <div v-if="portfolios.last_page > 1" class="mt-4 flex items-center justify-center gap-1">
      <button v-for="link in portfolios.links" :key="link.label" :disabled="!link.url" @click="goToPage(link.url, 'portfolio')" class="rounded-md border px-2.5 py-1 text-xs transition-colors disabled:opacity-30" :class="link.active ? 'border-primary bg-primary text-primary-foreground' : 'border-border/50 bg-white/50 dark:bg-white/5 backdrop-blur-lg text-muted-foreground hover:text-foreground'" v-html="paginationLabel(link.label)" />
    </div>
  </section>

  <!-- ===== BLOG ===== -->
  <section v-if="sectionVisibility.blog" id="blog" class="relative z-20 mx-auto max-w-5xl px-6 py-10">
    <div class="mb-5 flex items-center gap-2">
      <IconArticle class="h-5 w-5 text-primary" :stroke-width="1.5" />
      <h2 class="text-lg font-bold text-foreground">Blog</h2>
    </div>
    <div v-if="blogs.data.length" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
      <Link v-for="b in blogs.data" :key="b.id" :href="`/blog/${b.slug}`" class="group overflow-hidden rounded-xl border border-border/50 bg-white/50 dark:bg-white/5 backdrop-blur-lg transition-colors hover:border-primary/30">
        <img v-if="b.image" :src="`/storage/${b.image}`" :alt="b.title" class="h-40 w-full object-cover" />
        <div class="flex flex-col p-5">
          <div class="mb-1.5 flex items-center gap-1.5 text-[11px] text-muted-foreground">
            <IconCalendar class="h-3 w-3" :stroke-width="1.5" />
            <span v-if="b.published_at">{{ new Date(b.published_at).toLocaleDateString('es') }}</span>
          </div>
          <h3 class="text-sm font-semibold text-foreground">{{ b.title }}</h3>
          <p v-if="b.excerpt" class="mt-1.5 line-clamp-3 text-[13px] text-muted-foreground">{{ b.excerpt }}</p>
          <div class="mt-auto flex flex-wrap gap-1 pt-3">
            <span v-for="tag in b.tags" :key="tag" class="inline-flex items-center gap-0.5 rounded-full bg-white/60 dark:bg-white/10 px-2 py-0.5 text-[11px] text-secondary-foreground"><IconTag class="h-2.5 w-2.5" :stroke-width="1.5" /> {{ tag }}</span>
          </div>
        </div>
      </Link>
    </div>
    <div v-else class="rounded-xl border border-dashed border-border/50 bg-white/50 dark:bg-white/5 backdrop-blur-lg p-10 text-center text-sm text-muted-foreground">Próximamente...</div>
    <div v-if="blogs.last_page > 1" class="mt-4 flex items-center justify-center gap-1">
      <button v-for="link in blogs.links" :key="link.label" :disabled="!link.url" @click="goToPage(link.url, 'blog')" class="rounded-md border px-2.5 py-1 text-xs transition-colors disabled:opacity-30" :class="link.active ? 'border-primary bg-primary text-primary-foreground' : 'border-border/50 bg-white/50 dark:bg-white/5 backdrop-blur-lg text-muted-foreground hover:text-foreground'" v-html="paginationLabel(link.label)" />
    </div>
  </section>

  <!-- ===== CONTACT ===== -->
  <section v-if="sectionVisibility.contact" id="contact" class="relative z-20 mx-auto max-w-5xl px-6 py-10">
    <div class="mb-5 flex items-center gap-2">
      <IconMail class="h-5 w-5 text-primary" :stroke-width="1.5" />
      <h2 class="text-lg font-bold text-foreground">Contacto</h2>
    </div>
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-5">
      <!-- Info + socials -->
      <div class="rounded-xl border border-border/50 bg-white/50 dark:bg-white/5 backdrop-blur-lg p-6 lg:col-span-2 flex flex-col">
        <h3 class="text-base font-semibold text-foreground">¿Tienes un proyecto en mente?</h3>
        <p class="mt-2 text-[13px] text-muted-foreground leading-relaxed">Escríbeme y hablemos sobre cómo puedo ayudarte a construir algo increíble.</p>
        <div class="mt-4 flex items-center gap-2 text-[13px] text-muted-foreground"><IconMail class="h-3.5 w-3.5" :stroke-width="1.5" /> Respondo en menos de 24h</div>
        <!-- Social links -->
        <div v-if="socials.length" class="mt-auto pt-5 flex flex-wrap gap-2">
          <a v-for="s in socials" :key="s.network" :href="s.url" target="_blank" class="rounded-lg border border-border/50 bg-white/40 dark:bg-white/5 p-2 text-muted-foreground transition-colors hover:text-foreground hover:border-primary/30" :aria-label="s.network">
            <component :is="socialIcons[s.network]" class="h-4 w-4" :stroke-width="1.5" />
          </a>
        </div>
      </div>
      <!-- Form -->
      <form class="rounded-xl border border-border/50 bg-white/50 dark:bg-white/5 backdrop-blur-lg p-6 lg:col-span-3" @submit.prevent="submitContact">
        <div class="grid gap-3 sm:grid-cols-2">
          <div>
            <label for="c-name" class="mb-1 block text-[13px] font-medium text-foreground">Nombre</label>
            <input id="c-name" v-model="contactForm.name" type="text" required class="w-full rounded-lg border border-input bg-white/60 dark:bg-white/5 backdrop-blur px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none" placeholder="Tu nombre" />
            <span v-if="contactForm.errors.name" class="mt-1 text-xs text-destructive">{{ contactForm.errors.name }}</span>
          </div>
          <div>
            <label for="c-email" class="mb-1 block text-[13px] font-medium text-foreground">Email</label>
            <input id="c-email" v-model="contactForm.email" type="email" required class="w-full rounded-lg border border-input bg-white/60 dark:bg-white/5 backdrop-blur px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none" placeholder="tu@email.com" />
            <span v-if="contactForm.errors.email" class="mt-1 text-xs text-destructive">{{ contactForm.errors.email }}</span>
          </div>
        </div>
        <div class="mt-3">
          <label for="c-subject" class="mb-1 block text-[13px] font-medium text-foreground">Asunto</label>
          <input id="c-subject" v-model="contactForm.subject" type="text" class="w-full rounded-lg border border-input bg-white/60 dark:bg-white/5 backdrop-blur px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none" placeholder="¿De qué quieres hablar?" />
        </div>
        <div class="mt-3">
          <label for="c-message" class="mb-1 block text-[13px] font-medium text-foreground">Mensaje</label>
          <textarea id="c-message" v-model="contactForm.message" required rows="3" class="w-full resize-none rounded-lg border border-input bg-white/60 dark:bg-white/5 backdrop-blur px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none" placeholder="Cuéntame sobre tu proyecto..." />
          <span v-if="contactForm.errors.message" class="mt-1 text-xs text-destructive">{{ contactForm.errors.message }}</span>
        </div>
        <button type="submit" :disabled="contactForm.processing" class="mt-3 inline-flex items-center gap-2 rounded-lg bg-primary px-5 py-2 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90 disabled:opacity-50">
          <IconSend class="h-3.5 w-3.5" :stroke-width="1.5" />{{ contactForm.processing ? 'Enviando...' : 'Enviar mensaje' }}
        </button>
      </form>
    </div>
  </section>

  <!-- Footer -->
  <footer class="relative z-20 border-t border-border/30 py-6">
    <div class="mx-auto max-w-5xl px-6 text-center text-[13px] text-muted-foreground">
      <p class="inline-flex items-center gap-1.5">&copy; {{ new Date().getFullYear() }} — Hecho con <IconCode class="h-3.5 w-3.5 text-primary" :stroke-width="1.5" /> Laravel + Vue</p>
    </div>
  </footer>

  <!-- Scroll to top -->
  <button class="fixed right-5 bottom-5 z-30 rounded-full border border-border/50 bg-white/50 dark:bg-white/5 backdrop-blur-lg p-2.5 shadow-lg transition-all hover:bg-white/70 dark:hover:bg-white/10" @click="scrollTo('about')" aria-label="Volver arriba">
    <IconArrowUp class="h-4 w-4 text-foreground" :stroke-width="1.5" />
  </button>
</main>
</template>
