<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import RichEditor from '@/components/RichEditor.vue';
import {
    IconBrandGithub, IconBrandLinkedin, IconBrandX, IconBrandInstagram,
    IconBrandYoutube, IconBrandTiktok, IconBrandDiscord, IconWorld,
    IconUpload, IconTrash,
} from '@tabler/icons-vue';
import { ref } from 'vue';

const socialFields = [
    { key: 'social_github', label: 'GitHub', icon: IconBrandGithub, placeholder: 'https://github.com/usuario' },
    { key: 'social_linkedin', label: 'LinkedIn', icon: IconBrandLinkedin, placeholder: 'https://linkedin.com/in/usuario' },
    { key: 'social_twitter', label: 'X / Twitter', icon: IconBrandX, placeholder: 'https://x.com/usuario' },
    { key: 'social_instagram', label: 'Instagram', icon: IconBrandInstagram, placeholder: 'https://instagram.com/usuario' },
    { key: 'social_youtube', label: 'YouTube', icon: IconBrandYoutube, placeholder: 'https://youtube.com/@canal' },
    { key: 'social_tiktok', label: 'TikTok', icon: IconBrandTiktok, placeholder: 'https://tiktok.com/@usuario' },
    { key: 'social_discord', label: 'Discord', icon: IconBrandDiscord, placeholder: 'https://discord.gg/invite' },
    { key: 'social_website', label: 'Sitio web', icon: IconWorld, placeholder: 'https://tusitio.com' },
] as const;

const props = defineProps<{
    settings: Record<string, string>;
}>();

const sectionFields = [
    { key: 'section_about_visible', label: 'Sobre mí' },
    { key: 'section_projects_visible', label: 'Side Projects' },
    { key: 'section_portfolio_visible', label: 'Portafolio' },
    { key: 'section_blog_visible', label: 'Blog' },
    { key: 'section_contact_visible', label: 'Contacto' },
] as const;

const form = useForm({
    hero_title: props.settings.hero_title || '',
    hero_subtitle: props.settings.hero_subtitle || '',
    hero_badge: props.settings.hero_badge || '',
    about: props.settings.about || '',
    hobbies: props.settings.hobbies || '',
    social_github: props.settings.social_github || '',
    social_linkedin: props.settings.social_linkedin || '',
    social_twitter: props.settings.social_twitter || '',
    social_instagram: props.settings.social_instagram || '',
    social_youtube: props.settings.social_youtube || '',
    social_tiktok: props.settings.social_tiktok || '',
    social_discord: props.settings.social_discord || '',
    social_website: props.settings.social_website || '',
    section_about_visible: props.settings.section_about_visible ?? '1',
    section_projects_visible: props.settings.section_projects_visible ?? '1',
    section_portfolio_visible: props.settings.section_portfolio_visible ?? '1',
    section_blog_visible: props.settings.section_blog_visible ?? '1',
    section_contact_visible: props.settings.section_contact_visible ?? '1',
    seo_title: props.settings.seo_title || '',
    seo_description: props.settings.seo_description || '',
    seo_keywords: props.settings.seo_keywords || '',
    seo_canonical: props.settings.seo_canonical || '',
    og_title: props.settings.og_title || '',
    og_description: props.settings.og_description || '',
    og_type: props.settings.og_type || 'website',
    twitter_card: props.settings.twitter_card || 'summary_large_image',
    twitter_title: props.settings.twitter_title || '',
    twitter_description: props.settings.twitter_description || '',
});

const ogImagePreview = ref(props.settings.og_image || '');
const twitterImagePreview = ref(props.settings.twitter_image || '');
const faviconPreview = ref(props.settings.favicon || '');
const uploadingImage = ref(false);

function save() {
    form.put('/admin/settings');
}

function uploadSeoImage(event: Event, type: 'og_image' | 'twitter_image' | 'favicon') {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (!file) return;
    uploadingImage.value = true;
    const formData = new FormData();
    formData.append('image', file);
    formData.append('type', type);
    router.post('/admin/settings/seo-image', formData, {
        preserveScroll: true,
        onSuccess: () => {
            const url = URL.createObjectURL(file);
            if (type === 'og_image') ogImagePreview.value = url;
            else if (type === 'twitter_image') twitterImagePreview.value = url;
            else faviconPreview.value = url;
        },
        onFinish: () => { uploadingImage.value = false; },
    });
}

function deleteSeoImage(type: 'og_image' | 'twitter_image' | 'favicon') {
    router.delete('/admin/settings/seo-image', {
        data: { type },
        preserveScroll: true,
        onSuccess: () => {
            if (type === 'og_image') ogImagePreview.value = '';
            else if (type === 'twitter_image') twitterImagePreview.value = '';
            else faviconPreview.value = '';
        },
    });
}
</script>

<template>
    <Head title="Configuración del sitio" />
    <AdminLayout>
        <template #title>Configuración del sitio</template>
        <form class="max-w-4xl space-y-6" @submit.prevent="save">
            <!-- Section Visibility -->
            <div class="rounded-lg border border-border p-5 space-y-4">
                <h3 class="text-sm font-semibold text-foreground">Visibilidad de secciones</h3>
                <p class="text-xs text-muted-foreground">Elige qué secciones se muestran en tu portafolio público. Las secciones ocultas no aparecerán en el navbar ni en la página.</p>
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    <label v-for="s in sectionFields" :key="s.key" class="flex items-center justify-between rounded-lg border border-input px-4 py-3 cursor-pointer transition-colors" :class="(form as any)[s.key] === '1' ? 'bg-primary/5 border-primary/30' : 'bg-background'">
                        <span class="text-sm font-medium text-foreground">{{ s.label }}</span>
                        <button type="button" class="relative inline-flex h-5 w-9 shrink-0 items-center rounded-full transition-colors" :class="(form as any)[s.key] === '1' ? 'bg-primary' : 'bg-muted'" @click="(form as any)[s.key] = (form as any)[s.key] === '1' ? '0' : '1'">
                            <span class="inline-block h-3.5 w-3.5 rounded-full bg-white shadow-sm transition-transform" :class="(form as any)[s.key] === '1' ? 'translate-x-4' : 'translate-x-0.5'" />
                        </button>
                    </label>
                </div>
            </div>

            <!-- Hero -->
            <div class="rounded-lg border border-border p-5 space-y-4">
                <h3 class="text-sm font-semibold text-foreground">Hero / Encabezado</h3>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-foreground">Badge</label>
                    <input v-model="form.hero_badge" type="text" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none" placeholder="Full Stack Developer" />
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-foreground">Título principal</label>
                    <input v-model="form.hero_title" type="text" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none" placeholder="Hola, soy un Desarrollador Laravel" />
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-foreground">Subtítulo</label>
                    <textarea v-model="form.hero_subtitle" rows="2" class="w-full resize-none rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none" placeholder="Creando experiencias web..." />
                </div>
            </div>

            <!-- About & Hobbies -->
            <div>
                <label class="mb-2 block text-sm font-medium text-foreground">Sobre mí</label>
                <RichEditor v-model="form.about" :height="300" />
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-foreground">Hobbies</label>
                <RichEditor v-model="form.hobbies" :height="200" />
            </div>

            <!-- Social Links -->
            <div class="rounded-lg border border-border p-5 space-y-4">
                <h3 class="text-sm font-semibold text-foreground">Redes sociales</h3>
                <p class="text-xs text-muted-foreground">Deja vacío para ocultar. Solo se muestran las que tengan URL.</p>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div v-for="s in socialFields" :key="s.key" class="flex items-center gap-2">
                        <component :is="s.icon" class="h-5 w-5 shrink-0 text-muted-foreground" :stroke-width="1.5" />
                        <input v-model="(form as any)[s.key]" type="url" class="w-full rounded-lg border border-input bg-background px-3 py-1.5 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none" :placeholder="s.placeholder" />
                    </div>
                </div>
            </div>

            <!-- SEO General -->
            <div class="rounded-lg border border-border p-5 space-y-4">
                <h3 class="text-sm font-semibold text-foreground">SEO</h3>
                <p class="text-xs text-muted-foreground">Configura el título, descripción y keywords para buscadores.</p>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-foreground">Título SEO</label>
                    <input v-model="form.seo_title" type="text" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none" placeholder="Mi Portafolio — Desarrollador Full Stack" />
                    <span class="mt-1 block text-xs text-muted-foreground">{{ form.seo_title.length }}/200 caracteres</span>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-foreground">Meta descripción</label>
                    <textarea v-model="form.seo_description" rows="2" class="w-full resize-none rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none" placeholder="Desarrollador web especializado en Laravel y Vue.js..." />
                    <span class="mt-1 block text-xs text-muted-foreground">{{ form.seo_description.length }}/500 caracteres</span>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-foreground">Keywords</label>
                    <input v-model="form.seo_keywords" type="text" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none" placeholder="laravel, vue, desarrollador, portafolio" />
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-foreground">URL canónica</label>
                    <input v-model="form.seo_canonical" type="url" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none" placeholder="https://tusitio.com" />
                </div>
            </div>

            <!-- Favicon -->
            <div class="rounded-lg border border-border p-5 space-y-4">
                <h3 class="text-sm font-semibold text-foreground">Favicon</h3>
                <p class="text-xs text-muted-foreground">Sube un favicon personalizado (recomendado: 32×32 o 512×512 PNG).</p>
                <div class="flex items-center gap-4">
                    <div v-if="faviconPreview" class="flex items-center gap-3">
                        <img :src="faviconPreview.startsWith('blob:') ? faviconPreview : `/storage/${faviconPreview}`" alt="Favicon" class="h-10 w-10 rounded border border-border object-contain" />
                        <button type="button" class="inline-flex items-center gap-1 rounded-md border border-destructive/30 px-2.5 py-1.5 text-xs text-destructive hover:bg-destructive/10 transition-colors" @click="deleteSeoImage('favicon')">
                            <IconTrash class="h-3.5 w-3.5" :stroke-width="1.5" /> Eliminar
                        </button>
                    </div>
                    <label class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg border border-input bg-background px-3 py-2 text-sm text-muted-foreground hover:text-foreground transition-colors">
                        <IconUpload class="h-4 w-4" :stroke-width="1.5" /> Subir favicon
                        <input type="file" accept="image/*" class="hidden" @change="uploadSeoImage($event, 'favicon')" />
                    </label>
                </div>
            </div>

            <!-- Open Graph -->
            <div class="rounded-lg border border-border p-5 space-y-4">
                <h3 class="text-sm font-semibold text-foreground">Open Graph (Facebook, LinkedIn, etc.)</h3>
                <p class="text-xs text-muted-foreground">Configura cómo se ve tu sitio al compartirlo en redes sociales. Si dejas vacío, se usarán los valores de SEO.</p>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-foreground">OG Título</label>
                    <input v-model="form.og_title" type="text" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none" placeholder="Usa el título SEO si está vacío" />
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-foreground">OG Descripción</label>
                    <textarea v-model="form.og_description" rows="2" class="w-full resize-none rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none" placeholder="Usa la meta descripción si está vacío" />
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-foreground">OG Tipo</label>
                    <select v-model="form.og_type" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none">
                        <option value="website">website</option>
                        <option value="article">article</option>
                        <option value="profile">profile</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-foreground">OG Imagen</label>
                    <p class="mb-2 text-xs text-muted-foreground">Recomendado: 1200×630px</p>
                    <div class="flex items-start gap-4">
                        <div v-if="ogImagePreview" class="flex flex-col items-center gap-2">
                            <img :src="ogImagePreview.startsWith('blob:') ? ogImagePreview : `/storage/${ogImagePreview}`" alt="OG Image" class="h-24 w-44 rounded border border-border object-cover" />
                            <button type="button" class="inline-flex items-center gap-1 rounded-md border border-destructive/30 px-2.5 py-1.5 text-xs text-destructive hover:bg-destructive/10 transition-colors" @click="deleteSeoImage('og_image')">
                                <IconTrash class="h-3.5 w-3.5" :stroke-width="1.5" /> Eliminar
                            </button>
                        </div>
                        <label class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg border border-input bg-background px-3 py-2 text-sm text-muted-foreground hover:text-foreground transition-colors">
                            <IconUpload class="h-4 w-4" :stroke-width="1.5" /> Subir imagen
                            <input type="file" accept="image/*" class="hidden" @change="uploadSeoImage($event, 'og_image')" />
                        </label>
                    </div>
                </div>
            </div>

            <!-- Twitter Card -->
            <div class="rounded-lg border border-border p-5 space-y-4">
                <h3 class="text-sm font-semibold text-foreground">Twitter Card</h3>
                <p class="text-xs text-muted-foreground">Configura cómo se ve tu sitio al compartirlo en X/Twitter. Si dejas vacío, se usarán los valores de Open Graph.</p>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-foreground">Tipo de card</label>
                    <select v-model="form.twitter_card" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none">
                        <option value="summary_large_image">summary_large_image</option>
                        <option value="summary">summary</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-foreground">Twitter Título</label>
                    <input v-model="form.twitter_title" type="text" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none" placeholder="Usa el OG título si está vacío" />
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-foreground">Twitter Descripción</label>
                    <textarea v-model="form.twitter_description" rows="2" class="w-full resize-none rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none" placeholder="Usa la OG descripción si está vacío" />
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-foreground">Twitter Imagen</label>
                    <p class="mb-2 text-xs text-muted-foreground">Si no se sube, se usará la imagen de Open Graph.</p>
                    <div class="flex items-start gap-4">
                        <div v-if="twitterImagePreview" class="flex flex-col items-center gap-2">
                            <img :src="twitterImagePreview.startsWith('blob:') ? twitterImagePreview : `/storage/${twitterImagePreview}`" alt="Twitter Image" class="h-24 w-44 rounded border border-border object-cover" />
                            <button type="button" class="inline-flex items-center gap-1 rounded-md border border-destructive/30 px-2.5 py-1.5 text-xs text-destructive hover:bg-destructive/10 transition-colors" @click="deleteSeoImage('twitter_image')">
                                <IconTrash class="h-3.5 w-3.5" :stroke-width="1.5" /> Eliminar
                            </button>
                        </div>
                        <label class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg border border-input bg-background px-3 py-2 text-sm text-muted-foreground hover:text-foreground transition-colors">
                            <IconUpload class="h-4 w-4" :stroke-width="1.5" /> Subir imagen
                            <input type="file" accept="image/*" class="hidden" @change="uploadSeoImage($event, 'twitter_image')" />
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" :disabled="form.processing" class="rounded-lg bg-primary px-6 py-2.5 text-sm font-medium text-primary-foreground hover:bg-primary/90 disabled:opacity-50">
                {{ form.processing ? 'Guardando...' : 'Guardar cambios' }}
            </button>
            <p v-if="$page.props.flash?.success" class="text-sm text-green-600 dark:text-green-400">{{ $page.props.flash.success }}</p>
        </form>
    </AdminLayout>
</template>
