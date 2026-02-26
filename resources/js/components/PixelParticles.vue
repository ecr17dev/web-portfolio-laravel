<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps<{ isDark: boolean }>();
const canvasRef = ref<HTMLCanvasElement | null>(null);
let animationId = 0;
let particles: Particle[] = [];

const CODE_WORDS = [
  'if', 'else', 'while', 'for', 'return', 'function',
  'const', 'let', 'var', 'class', 'extends',
  '<?php', '/>', '<div>', '</div>', '<html>', '<head>',
  'echo', 'foreach', 'namespace', 'use', 'public',
  'Route::', 'Blade', 'Eloquent', 'migrate',
  'artisan', 'composer', 'npm run', 'php artisan',
  '$ git push', '$ npm install', '=> {}',
  'import', 'export', 'async', 'await',
  'try {', '} catch', 'throw new',
  'SELECT *', 'INSERT INTO', 'WHERE',
  '{{ $slot }}', '@foreach', '@if', '@extends',
  'v-model', 'v-for', ':class', '@click',
  'console.log', 'dd()', '->get()',
  'true', 'false', 'null', '===',
  'npm run dev', 'php artisan serve',
];

interface Particle {
  x: number;
  y: number;
  vx: number;
  vy: number;
  text: string;
  opacity: number;
  targetOpacity: number;
  fontSize: number;
}

function createParticle(w: number, h: number, cellX?: number, cellY?: number, cellW?: number, cellH?: number): Particle {
  // Si se pasa una celda, posicionar dentro de ella con jitter
  const x = cellX !== undefined && cellW !== undefined
    ? cellX + Math.random() * cellW
    : Math.random() * w;
  const y = cellY !== undefined && cellH !== undefined
    ? cellY + Math.random() * cellH
    : Math.random() * h;

  return {
    x,
    y,
    vx: (Math.random() - 0.5) * 0.2,
    vy: -Math.random() * 0.12 - 0.03,
    text: CODE_WORDS[Math.floor(Math.random() * CODE_WORDS.length)],
    opacity: 0,
    targetOpacity: Math.random() * 0.22 + 0.06,
    fontSize: Math.floor(Math.random() * 5) + 10,
  };
}

function startAnimation() {
  const canvas = canvasRef.value;
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  if (!ctx) return;

  const resize = () => {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
  };
  resize();
  window.addEventListener('resize', resize);

  // Distribuir partículas en una grilla para evitar sobreposición
  const cols = Math.floor(canvas.width / 120);
  const rows = Math.floor(canvas.height / 80);
  const cellW = canvas.width / cols;
  const cellH = canvas.height / rows;
  particles = [];
  for (let r = 0; r < rows; r++) {
    for (let c = 0; c < cols; c++) {
      particles.push(createParticle(canvas.width, canvas.height, c * cellW, r * cellH, cellW, cellH));
    }
  }

  const lightColors = ['#7c3aed', '#6366f1', '#8b5cf6', '#a855f7', '#6d28d9'];
  const darkColors = ['#a78bfa', '#818cf8', '#c4b5fd', '#d8b4fe', '#7c3aed'];

  const animate = () => {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    const colors = props.isDark ? darkColors : lightColors;

    for (const p of particles) {
      p.x += p.vx;
      p.y += p.vy;

      if (p.opacity < p.targetOpacity) p.opacity += 0.003;

      // Wrap — reposicionar con separación
      if (p.y < -30) {
        p.y = canvas.height + 30 + Math.random() * 60;
        p.x = Math.random() * canvas.width;
        p.opacity = 0;
        p.text = CODE_WORDS[Math.floor(Math.random() * CODE_WORDS.length)];
      }
      if (p.x < -80) p.x = canvas.width + 40;
      if (p.x > canvas.width + 80) p.x = -40;

      ctx.save();
      ctx.globalAlpha = p.opacity;
      ctx.font = `${p.fontSize}px "Courier New", "Consolas", monospace`;
      ctx.fillStyle = colors[Math.abs(p.text.charCodeAt(0)) % colors.length];
      ctx.fillText(p.text, p.x, p.y);
      ctx.restore();
    }

    animationId = requestAnimationFrame(animate);
  };

  animate();
}

onMounted(() => startAnimation());
onUnmounted(() => {
  cancelAnimationFrame(animationId);
});
</script>

<template>
  <canvas
    ref="canvasRef"
    class="pointer-events-none fixed inset-0 z-10"
    aria-hidden="true"
  />
</template>
