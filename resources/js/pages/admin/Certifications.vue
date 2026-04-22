<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import Swal from 'sweetalert2';
import { Plus, Pencil, Trash2, FileText } from 'lucide-vue-next';
import { ref } from 'vue';

type Certification = {
    id: number;
    title: string;
    description: string;
    image: string;
    certificate_file: string | null;
    published: boolean;
    sort_order: number;
};

const props = defineProps<{ certifications: Certification[] }>();
const showForm = ref(false);
const editing = ref<Certification | null>(null);

const form = useForm({
    title: '',
    description: '',
    image: null as File | null,
    certificate_file: null as File | null,
    published: false,
    sort_order: 0,
});

function openCreate() {
    editing.value = null;
    form.reset();
    form.image = null;
    form.certificate_file = null;
    showForm.value = true;
}

function openEdit(certification: Certification) {
    editing.value = certification;
    form.title = certification.title;
    form.description = certification.description;
    form.published = certification.published;
    form.sort_order = certification.sort_order;
    form.image = null;
    form.certificate_file = null;
    showForm.value = true;
}

function submit() {
    const url = editing.value
        ? `/admin/certifications/${editing.value.id}`
        : '/admin/certifications';

    const opts = {
        forceFormData: true,
        onSuccess: () => {
            showForm.value = false;
            editing.value = null;
            form.reset();
            form.image = null;
            form.certificate_file = null;
        },
    };

    if (editing.value) {
        form.post(url, { ...opts, headers: { 'X-HTTP-Method-Override': 'PUT' } });
        return;
    }

    form.post(url, opts);
}

async function destroy(certification: Certification) {
    const isDarkMode =
        typeof document !== 'undefined' && document.documentElement.classList.contains('dark');

    const result = await Swal.fire({
        icon: 'warning',
        title: '¿Eliminar certificación?',
        text: 'Esta acción también eliminará la imagen y el diploma asociado.',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#dc2626',
        background: isDarkMode ? '#0a0a0a' : '#ffffff',
        color: isDarkMode ? '#fafafa' : '#0a0a0a',
        customClass: {
            popup: 'rounded-xl border border-white/10',
        },
    });

    if (!result.isConfirmed) return;

    router.delete(`/admin/certifications/${certification.id}`);
}
</script>

<template>
    <Head title="Certificaciones" />

    <AdminLayout>
        <template #title>Certificaciones</template>

        <div class="mb-6">
            <button
                class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                @click="openCreate"
            >
                <Plus class="h-4 w-4" /> Nueva certificación
            </button>
        </div>

        <div v-if="showForm" class="mb-6 rounded-xl border border-border bg-card p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-foreground">
                {{ editing ? 'Editar' : 'Nueva' }} certificación
            </h3>

            <form class="space-y-4" @submit.prevent="submit">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-foreground">Título</label>
                        <input
                            v-model="form.title"
                            type="text"
                            required
                            class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none"
                        />
                        <p v-if="form.errors.title" class="mt-1 text-xs text-destructive">{{ form.errors.title }}</p>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-foreground">Orden</label>
                        <input
                            v-model.number="form.sort_order"
                            type="number"
                            class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none"
                        />
                        <p v-if="form.errors.sort_order" class="mt-1 text-xs text-destructive">{{ form.errors.sort_order }}</p>
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-foreground">Descripción</label>
                    <textarea
                        v-model="form.description"
                        rows="4"
                        required
                        class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none"
                    />
                    <p v-if="form.errors.description" class="mt-1 text-xs text-destructive">{{ form.errors.description }}</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-foreground">
                            Imagen {{ editing ? '(opcional al editar)' : '(requerida)' }}
                        </label>
                        <input
                            type="file"
                            accept="image/*"
                            class="text-sm"
                            @change="(e: Event) => form.image = ((e.target as HTMLInputElement).files ?? [])[0] || null"
                        />
                        <p v-if="form.errors.image" class="mt-1 text-xs text-destructive">{{ form.errors.image }}</p>

                        <div v-if="editing?.image" class="mt-2 flex items-center gap-2">
                            <img
                                :src="`/storage/${editing.image}`"
                                :alt="editing.title"
                                class="h-12 w-20 rounded border border-border object-cover"
                            />
                            <span class="text-xs text-muted-foreground">Imagen actual</span>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-foreground">
                            Diploma (PDF opcional)
                        </label>
                        <input
                            type="file"
                            accept="application/pdf"
                            class="text-sm"
                            @change="(e: Event) => form.certificate_file = ((e.target as HTMLInputElement).files ?? [])[0] || null"
                        />
                        <p v-if="form.errors.certificate_file" class="mt-1 text-xs text-destructive">{{ form.errors.certificate_file }}</p>

                        <a
                            v-if="editing?.certificate_file"
                            :href="`/storage/${editing.certificate_file}`"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="mt-2 inline-flex items-center gap-1 text-xs text-primary hover:underline"
                        >
                            <FileText class="h-3.5 w-3.5" /> Ver PDF actual
                        </a>
                    </div>
                </div>

                <label class="flex items-center gap-2 text-sm text-foreground">
                    <input v-model="form.published" type="checkbox" class="rounded" />
                    Visible en sitio público
                </label>

                <div class="flex gap-2">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Guardando...' : 'Guardar' }}
                    </button>
                    <button
                        type="button"
                        class="rounded-lg border border-border px-4 py-2 text-sm"
                        @click="showForm = false"
                    >
                        Cancelar
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto rounded-xl border border-border">
            <table class="w-full text-sm">
                <thead class="border-b border-border bg-muted/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-muted-foreground">Título</th>
                        <th class="px-4 py-3 text-left font-medium text-muted-foreground">Estado</th>
                        <th class="px-4 py-3 text-left font-medium text-muted-foreground">Orden</th>
                        <th class="px-4 py-3 text-right font-medium text-muted-foreground">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="certification in certifications"
                        :key="certification.id"
                        class="border-b border-border last:border-0"
                    >
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img
                                    :src="`/storage/${certification.image}`"
                                    :alt="certification.title"
                                    class="h-10 w-16 rounded border border-border object-cover"
                                />
                                <div>
                                    <p class="font-medium text-foreground">{{ certification.title }}</p>
                                    <p class="line-clamp-1 text-xs text-muted-foreground">{{ certification.description }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span
                                :class="certification.published ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300'"
                                class="rounded-full px-2 py-0.5 text-xs"
                            >
                                {{ certification.published ? 'Visible' : 'Oculta' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-muted-foreground">{{ certification.sort_order }}</td>
                        <td class="px-4 py-3 text-right">
                            <button
                                class="mr-2 text-muted-foreground hover:text-foreground"
                                @click="openEdit(certification)"
                            >
                                <Pencil class="h-4 w-4" />
                            </button>
                            <button
                                class="text-muted-foreground hover:text-destructive"
                                @click="destroy(certification)"
                            >
                                <Trash2 class="h-4 w-4" />
                            </button>
                        </td>
                    </tr>
                    <tr v-if="!certifications.length">
                        <td colspan="4" class="px-4 py-8 text-center text-muted-foreground">
                            No hay certificaciones aún.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
