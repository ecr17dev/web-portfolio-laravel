<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useAppearance } from '@/composables/useAppearance';
import { IconSun, IconMoon } from '@tabler/icons-vue';
import { type Component, computed } from 'vue';

type Section = { id: string; label: string; icon: Component; visKey: string };

const props = defineProps<{
  sections?: Section[];
  sectionVisibility?: Record<string, boolean>;
  showSections?: boolean;
}>();

const { appearance, resolvedAppearance, updateAppearance } = useAppearance();
function toggleTheme() { updateAppearance(appearance.value === 'dark' ? 'light' : 'dark'); }

const visibleSections = computed(() => {
  if (!props.showSections || !props.sections || !props.sectionVisibility) return [];
  return props.sections.filter(s => props.sectionVisibility[s.visKey] !== false);
});

function scrollTo(id: string) {
  document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' });
}
</script>

<template>
<nav class="fixed top-0 right-0 left-0 z-50 border-b border-white/10 dark:border-white/5 backdrop-blur-2xl bg-white/60 dark:bg-zinc-950/60">
  <div class="mx-auto flex h-14 max-w-5xl items-center justify-between px-6">
    <Link href="/" class="flex items-center gap-3">
      <img src="/logo-dark.png" alt="Logo" class="h-12 dark:hidden" />
      <img src="/logo-white.png" alt="Logo" class="hidden h-12 dark:block" />
    </Link>
    <div v-if="showSections && visibleSections.length" class="hidden items-center gap-0.5 md:flex">
      <button v-for="s in visibleSections" :key="s.id" class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 text-[13px] text-muted-foreground transition-colors hover:text-foreground" @click="scrollTo(s.id)">
        <component :is="s.icon" class="h-3.5 w-3.5" :stroke-width="1.5" />{{ s.label }}
      </button>
    </div>
    <div class="flex items-center gap-2">
      <button class="rounded-md p-1.5 text-muted-foreground hover:text-foreground transition-colors" @click="toggleTheme" aria-label="Cambiar tema">
        <IconSun v-if="appearance === 'dark'" class="h-4 w-4" :stroke-width="1.5" />
        <IconMoon v-else class="h-4 w-4" :stroke-width="1.5" />
      </button>
      <Link v-if="$page.props.auth?.user" href="/admin" class="rounded-md bg-primary/10 px-3 py-1 text-xs font-medium text-primary hover:bg-primary/20 transition-colors">Admin</Link>
    </div>
  </div>
</nav>
</template>
