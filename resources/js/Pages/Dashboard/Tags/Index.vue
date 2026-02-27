<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    tags: { type: Array, default: () => [] },
});

const editingTag = ref(null);
const editName = ref('');

const createForm = useForm({ name: '' });

function submitCreate() {
    createForm.post(route('dashboard.tags.store'), {
        onSuccess: () => createForm.reset(),
    });
}

function startEdit(tag) {
    editingTag.value = tag.id;
    editName.value = tag.name;
}

function cancelEdit() {
    editingTag.value = null;
    editName.value = '';
}

function submitEdit(tag) {
    router.put(route('dashboard.tags.update', tag.id), { name: editName.value }, {
        onSuccess: cancelEdit,
    });
}

function deleteTag(tag) {
    if (!confirm(`Hapus tag "${tag.name}"? Tag akan dihapus dari semua tautan.`)) return;
    router.delete(route('dashboard.tags.destroy', tag.id));
}
</script>

<template>
    <Head title="Kelola Tag" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Kelola Tag
                </h2>
                <Link :href="route('dashboard')" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    ← Dashboard
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">

                <!-- Create Form -->
                <div class="mb-6 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100 dark:bg-gray-800 dark:ring-gray-700">
                    <h3 class="mb-3 text-sm font-semibold text-gray-700 dark:text-gray-300">Tambah Tag Baru</h3>
                    <form @submit.prevent="submitCreate" class="flex gap-2">
                        <input
                            v-model="createForm.name"
                            type="text"
                            required
                            placeholder="Nama tag..."
                            class="flex-1 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200"
                        />
                        <button
                            type="submit"
                            :disabled="createForm.processing"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50"
                        >
                            Tambah
                        </button>
                    </form>
                    <p v-if="createForm.errors.name" class="mt-1 text-xs text-red-500">{{ createForm.errors.name }}</p>
                </div>

                <!-- Tags List -->
                <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100 dark:bg-gray-800 dark:ring-gray-700">
                    <div v-if="tags.length === 0" class="px-4 py-12 text-center text-sm text-gray-400">
                        Belum ada tag.
                    </div>
                    <ul class="divide-y divide-gray-50 dark:divide-gray-700">
                        <li
                            v-for="tag in tags"
                            :key="tag.id"
                            class="flex items-center justify-between px-4 py-3"
                        >
                            <!-- View mode -->
                            <div v-if="editingTag !== tag.id" class="flex flex-1 items-center gap-3">
                                <span class="rounded-full bg-blue-50 px-2.5 py-0.5 text-sm font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ tag.name }}
                                </span>
                                <span class="text-xs text-gray-400">{{ tag.links_count }} tautan</span>
                            </div>

                            <!-- Edit mode -->
                            <div v-else class="flex flex-1 items-center gap-2">
                                <input
                                    v-model="editName"
                                    type="text"
                                    class="flex-1 rounded-lg border border-blue-300 bg-white px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-blue-700 dark:bg-gray-900 dark:text-gray-200"
                                    @keyup.enter="submitEdit(tag)"
                                    @keyup.escape="cancelEdit"
                                    autofocus
                                />
                            </div>

                            <!-- Actions -->
                            <div class="ms-3 flex gap-2">
                                <template v-if="editingTag === tag.id">
                                    <button @click="submitEdit(tag)" class="rounded-lg bg-blue-600 px-3 py-1 text-xs font-medium text-white hover:bg-blue-700">
                                        Simpan
                                    </button>
                                    <button @click="cancelEdit" class="rounded-lg border border-gray-200 px-3 py-1 text-xs text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-400">
                                        Batal
                                    </button>
                                </template>
                                <template v-else>
                                    <button @click="startEdit(tag)" class="rounded-lg border border-gray-200 px-2.5 py-1 text-xs font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700">
                                        Edit
                                    </button>
                                    <button @click="deleteTag(tag)" class="rounded-lg border border-red-100 px-2.5 py-1 text-xs font-medium text-red-500 hover:bg-red-50 dark:border-red-900 dark:text-red-400 dark:hover:bg-red-900/20">
                                        Hapus
                                    </button>
                                </template>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
