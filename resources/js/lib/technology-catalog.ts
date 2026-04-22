export type TechnologyCatalogItem = {
  key: string;
  name: string;
  icon: string;
};

export const technologyCatalog: TechnologyCatalogItem[] = [
  { key: 'laravel', name: 'Laravel', icon: 'IconBrandLaravel' },
  { key: 'php', name: 'PHP', icon: 'IconBrandPhp' },
  { key: 'vue', name: 'Vue.js', icon: 'IconBrandVue' },
  { key: 'nuxt', name: 'Nuxt', icon: 'IconBrandNuxt' },
  { key: 'react', name: 'React', icon: 'IconBrandReact' },
  { key: 'nextjs', name: 'Next.js', icon: 'IconBrandNextjs' },
  { key: 'typescript', name: 'TypeScript', icon: 'IconBrandTypescript' },
  { key: 'javascript', name: 'JavaScript', icon: 'IconBrandJavascript' },
  { key: 'tailwind', name: 'Tailwind CSS', icon: 'IconBrandTailwind' },
  { key: 'nodejs', name: 'Node.js', icon: 'IconBrandNodejs' },
  { key: 'postgresql', name: 'PostgreSQL', icon: 'IconDatabase' },
  { key: 'mysql', name: 'MySQL', icon: 'IconDatabase' },
  { key: 'redis', name: 'Redis', icon: 'IconDatabase' },
  { key: 'docker', name: 'Docker', icon: 'IconBrandDocker' },
  { key: 'aws', name: 'AWS', icon: 'IconBrandAws' },
  { key: 'git', name: 'Git', icon: 'IconBrandGit' },
  { key: 'github', name: 'GitHub', icon: 'IconBrandGithub' },
  { key: 'inertia', name: 'Inertia.js', icon: 'IconArrowsDiff' },
  { key: 'livewire', name: 'Livewire', icon: 'IconBolt' },
  { key: 'api', name: 'REST API', icon: 'IconApi' },
];
