import {
  IconApi,
  IconArrowsDiff,
  IconBolt,
  IconBrandAws,
  IconBrandDocker,
  IconBrandGit,
  IconBrandGithub,
  IconBrandJavascript,
  IconBrandLaravel,
  IconBrandNextjs,
  IconBrandNodejs,
  IconBrandNuxt,
  IconBrandPhp,
  IconBrandReact,
  IconBrandTailwind,
  IconBrandTypescript,
  IconBrandVue,
  IconCpu,
  IconDatabase,
} from '@tabler/icons-vue';
import type { Component } from 'vue';

const TABLER_ICON_MAP: Record<string, Component> = {
  IconApi,
  IconArrowsDiff,
  IconBolt,
  IconBrandAws,
  IconBrandDocker,
  IconBrandGit,
  IconBrandGithub,
  IconBrandJavascript,
  IconBrandLaravel,
  IconBrandNextjs,
  IconBrandNodejs,
  IconBrandNuxt,
  IconBrandPhp,
  IconBrandReact,
  IconBrandTailwind,
  IconBrandTypescript,
  IconBrandVue,
  IconDatabase,
};

export function resolveTablerIcon(iconName?: string | null): Component {
  if (!iconName) {
    return IconCpu;
  }

  const candidate = TABLER_ICON_MAP[iconName];
  return candidate ?? IconCpu;
}
