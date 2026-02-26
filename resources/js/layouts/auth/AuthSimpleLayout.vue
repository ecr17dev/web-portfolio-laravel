<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useAppearance } from '@/composables/useAppearance';
import PixelParticles from '@/components/PixelParticles.vue';
import { IconSun, IconMoon } from '@tabler/icons-vue';

defineProps<{
    title?: string;
    description?: string;
}>();

const { appearance, resolvedAppearance, updateAppearance } = useAppearance();
function toggleTheme() { updateAppearance(appearance.value === 'dark' ? 'light' : 'dark'); }
</script>

<template>
    <div class="relative flex min-h-svh flex-col items-center justify-center bg-background p-6 md:p-10">
        <PixelParticles :isDark="resolvedAppearance === 'dark'" />

        <!-- Theme toggle -->
        <button
            class="fixed top-5 right-5 z-30 rounded-md p-1.5 text-muted-foreground hover:text-foreground transition-colors"
            @click="toggleTheme"
            aria-label="Cambiar tema"
        >
            <IconSun v-if="appearance === 'dark'" class="h-4 w-4" :stroke-width="1.5" />
            <IconMoon v-else class="h-4 w-4" :stroke-width="1.5" />
        </button>

        <div class="relative z-20 w-full max-w-sm">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4">
                    <Link href="/" class="flex flex-col items-center gap-2">
                        <img src="/logo-dark.png" alt="Logo" class="h-8 dark:hidden" />
                        <img src="/logo-white.png" alt="Logo" class="hidden h-8 dark:block" />
                    </Link>
                    <div class="space-y-2 text-center">
                        <h1 class="text-xl font-semibold tracking-tight text-foreground">{{ title }}</h1>
                        <p class="text-sm text-muted-foreground">{{ description }}</p>
                    </div>
                </div>
                <div class="rounded-xl border border-border/50 bg-white/50 dark:bg-white/5 backdrop-blur-lg p-6">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
