<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    links: { type: Array, default: () => [] },
    tags: { type: Array, default: () => [] },
});

// ── Form modal ──
const showModal = ref(false);
const editingLink = ref(null);

const form = useForm({
    name: '',
    url: '',
    year: '',
    is_active: true,
    tag_ids: [],
});

function openCreate() {
    editingLink.value = null;
    form.reset();
    form.is_active = true;
    form.tag_ids = [];
    showModal.value = true;
}

function openEdit(link) {
    editingLink.value = link;
    form.name = link.name;
    form.url = link.url;
    form.year = link.year ?? '';
    form.is_active = link.is_active;
    form.tag_ids = link.tags.map(t => t.id);
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
    editingLink.value = null;
    form.reset();
}

function submitForm() {
    if (editingLink.value) {
        form.put(route('dashboard.links.update', editingLink.value.id), {
            onSuccess: closeModal,
        });
    } else {
        form.post(route('dashboard.links.store'), {
            onSuccess: closeModal,
        });
    }
}

function deleteLink(link) {
    if (!confirm(`Hapus tautan "${link.name}"?`)) return;
    router.delete(route('dashboard.links.destroy', link.id));
}

// ── Bulk action ──
const selected = ref([]);
const showBulkModal = ref(false);
const bulkAction = ref('');

const bulkForm = useForm({
    ids: [],
    year: '',
    tag_ids: [],
    is_active: null,
});

const allSelected = computed(() =>
    props.links.length > 0 && selected.value.length === props.links.length
);

function toggleAll() {
    selected.value = allSelected.value ? [] : props.links.map(l => l.id);
}

function toggleSelect(id) {
    const idx = selected.value.indexOf(id);
    if (idx === -1) selected.value.push(id);
    else selected.value.splice(idx, 1);
}

function bulkDelete() {
    if (!confirm(`Hapus ${selected.value.length} tautan terpilih?`)) return;
    router.post(route('dashboard.links.bulk-destroy'), { ids: selected.value }, {
        onSuccess: () => { selected.value = []; },
    });
}

function openBulkEdit(action) {
    bulkAction.value = action;
    bulkForm.ids = [...selected.value];
    bulkForm.year = '';
    bulkForm.tag_ids = [];
    bulkForm.is_active = null;
    showBulkModal.value = true;
}

function submitBulk() {
    const payload = { ids: bulkForm.ids };
    if (bulkAction.value === 'year') payload.year = bulkForm.year || null;
    if (bulkAction.value === 'tags') payload.tag_ids = bulkForm.tag_ids;
    if (bulkAction.value === 'status') payload.is_active = bulkForm.is_active;

    router.post(route('dashboard.links.bulk-update'), payload, {
        onSuccess: () => {
            showBulkModal.value = false;
            selected.value = [];
        },
    });
}

function toggleTagInForm(tagId, formObj) {
    const idx = formObj.tag_ids.indexOf(tagId);
    if (idx === -1) formObj.tag_ids.push(tagId);
    else formObj.tag_ids.splice(idx, 1);
}
</script>

<template>
    <Head title="Kelola Tautan" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Kelola Tautan
                </h2>
                <div class="flex items-center gap-2">
                    <Link :href="route('dashboard')" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        ← Dashboard
                    </Link>
                    <button
                        @click="openCreate"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                    >
                        + Tambah Tautan
                    </button>
                </div>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <!-- Bulk action toolbar -->
                <div
                    v-if="selected.length > 0"
                    class="mb-4 flex items-center gap-3 rounded-xl bg-blue-50 px-4 py-3 dark:bg-blue-900/20"
                >
                    <span class="text-sm font-medium text-blue-700 dark:text-blue-300">
                        {{ selected.length }} tautan dipilih
                    </span>
                    <div class="flex gap-2">
                        <button @click="openBulkEdit('year')" class="rounded-lg border border-blue-200 bg-white px-3 py-1 text-xs font-medium text-blue-700 hover:bg-blue-50 dark:border-blue-700 dark:bg-transparent dark:text-blue-300">
                            Ubah Tahun
                        </button>
                        <button @click="openBulkEdit('tags')" class="rounded-lg border border-blue-200 bg-white px-3 py-1 text-xs font-medium text-blue-700 hover:bg-blue-50 dark:border-blue-700 dark:bg-transparent dark:text-blue-300">
                            Ubah Tag
                        </button>
                        <button @click="openBulkEdit('status')" class="rounded-lg border border-blue-200 bg-white px-3 py-1 text-xs font-medium text-blue-700 hover:bg-blue-50 dark:border-blue-700 dark:bg-transparent dark:text-blue-300">
                            Ubah Status
                        </button>
                        <button @click="bulkDelete" class="rounded-lg border border-red-200 bg-white px-3 py-1 text-xs font-medium text-red-600 hover:bg-red-50 dark:border-red-800 dark:bg-transparent dark:text-red-400">
                            Hapus
                        </button>
                    </div>
                    <button @click="selected = []" class="ms-auto text-xs text-gray-400 hover:text-gray-600">Batal</button>
                </div>

                <!-- Table -->
                <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100 dark:bg-gray-800 dark:ring-gray-700">
                    <table class="w-full text-sm">
                        <thead class="border-b border-gray-100 bg-gray-50 dark:border-gray-700 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-4 py-3 text-left">
                                    <input type="checkbox" :checked="allSelected" @change="toggleAll" class="rounded border-gray-300 dark:border-gray-600" />
                                </th>
                                <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Nama</th>
                                <th class="hidden px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300 md:table-cell">URL</th>
                                <th class="hidden px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300 sm:table-cell">Tahun</th>
                                <th class="hidden px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300 lg:table-cell">Tag</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Status</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-600 dark:text-gray-300">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                            <tr v-if="links.length === 0">
                                <td colspan="7" class="px-4 py-12 text-center text-sm text-gray-400">
                                    Belum ada tautan. Klik "+ Tambah Tautan" untuk mulai.
                                </td>
                            </tr>
                            <tr
                                v-for="link in links"
                                :key="link.id"
                                class="transition hover:bg-gray-50 dark:hover:bg-gray-700/30"
                                :class="{ 'bg-blue-50/50 dark:bg-blue-900/10': selected.includes(link.id) }"
                            >
                                <td class="px-4 py-3">
                                    <input
                                        type="checkbox"
                                        :checked="selected.includes(link.id)"
                                        @change="toggleSelect(link.id)"
                                        class="rounded border-gray-300 dark:border-gray-600"
                                    />
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-800 dark:text-gray-100">{{ link.name }}</p>
                                    <p class="mt-0.5 truncate text-xs text-gray-400 md:hidden">{{ link.url }}</p>
                                </td>
                                <td class="hidden px-4 py-3 md:table-cell">
                                    <a :href="link.url" target="_blank" class="truncate text-blue-600 hover:underline dark:text-blue-400 max-w-xs block">
                                        {{ link.url }}
                                    </a>
                                </td>
                                <td class="hidden px-4 py-3 text-gray-600 dark:text-gray-400 sm:table-cell">
                                    {{ link.year ?? '—' }}
                                </td>
                                <td class="hidden px-4 py-3 lg:table-cell">
                                    <div class="flex flex-wrap gap-1">
                                        <span
                                            v-for="tag in link.tags"
                                            :key="tag.id"
                                            class="rounded-full bg-blue-50 px-2 py-0.5 text-xs text-blue-600 dark:bg-blue-900/30 dark:text-blue-400"
                                        >{{ tag.name }}</span>
                                        <span v-if="link.tags.length === 0" class="text-xs text-gray-300">—</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="rounded-full px-2 py-0.5 text-xs font-medium"
                                        :class="link.is_active
                                            ? 'bg-green-50 text-green-600 dark:bg-green-900/30 dark:text-green-400'
                                            : 'bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-500'"
                                    >
                                        {{ link.is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button
                                            @click="openEdit(link)"
                                            class="rounded-lg border border-gray-200 px-2.5 py-1 text-xs font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700"
                                        >Edit</button>
                                        <button
                                            @click="deleteLink(link)"
                                            class="rounded-lg border border-red-100 px-2.5 py-1 text-xs font-medium text-red-500 hover:bg-red-50 dark:border-red-900 dark:text-red-400 dark:hover:bg-red-900/20"
                                        >Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <!-- Create/Edit Modal -->
    <Teleport to="body">
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="closeModal" />
            <div class="relative z-10 w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-900">
                <h3 class="mb-5 text-base font-semibold text-gray-800 dark:text-gray-100">
                    {{ editingLink ? 'Edit Tautan' : 'Tambah Tautan' }}
                </h3>

                <form @submit.prevent="submitForm" class="space-y-4">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-400">Nama Tautan</label>
                        <input
                            v-model="form.name"
                            type="text"
                            required
                            placeholder="Contoh: Laporan Tahunan 2024"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                        />
                        <p v-if="form.errors.name" class="mt-1 text-xs text-red-500">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-400">URL</label>
                        <input
                            v-model="form.url"
                            type="url"
                            required
                            placeholder="https://..."
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                        />
                        <p v-if="form.errors.url" class="mt-1 text-xs text-red-500">{{ form.errors.url }}</p>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-400">Tahun</label>
                            <input
                                v-model="form.year"
                                type="number"
                                min="2000"
                                max="2100"
                                placeholder="2024"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                            />
                        </div>
                        <div class="flex items-end pb-0.5">
                            <label class="flex cursor-pointer items-center gap-2">
                                <input type="checkbox" v-model="form.is_active" class="rounded border-gray-300 dark:border-gray-600" />
                                <span class="text-sm text-gray-600 dark:text-gray-400">Aktif</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-medium text-gray-600 dark:text-gray-400">Tag</label>
                        <div v-if="tags.length === 0" class="text-xs text-gray-400">
                            Belum ada tag. <Link :href="route('dashboard.tags.index')" class="text-blue-500 hover:underline">Buat tag terlebih dahulu</Link>.
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button
                                v-for="tag in tags"
                                :key="tag.id"
                                type="button"
                                @click="toggleTagInForm(tag.id, form)"
                                class="rounded-full border px-3 py-1 text-xs font-medium transition"
                                :class="form.tag_ids.includes(tag.id)
                                    ? 'border-blue-500 bg-blue-500 text-white'
                                    : 'border-gray-200 bg-white text-gray-600 hover:border-blue-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400'"
                            >
                                {{ tag.name }}
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="closeModal" class="rounded-lg border border-gray-200 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50"
                        >
                            {{ editingLink ? 'Simpan Perubahan' : 'Tambah' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </Teleport>

    <!-- Bulk Edit Modal -->
    <Teleport to="body">
        <div v-if="showBulkModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showBulkModal = false" />
            <div class="relative z-10 w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-900">
                <h3 class="mb-4 text-base font-semibold text-gray-800 dark:text-gray-100">
                    Ubah {{ bulkAction === 'year' ? 'Tahun' : bulkAction === 'tags' ? 'Tag' : 'Status' }} — {{ bulkForm.ids.length }} tautan
                </h3>

                <div v-if="bulkAction === 'year'" class="mb-4">
                    <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-400">Tahun Baru</label>
                    <input
                        v-model="bulkForm.year"
                        type="number"
                        min="2000"
                        max="2100"
                        placeholder="2024"
                        class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                    />
                </div>

                <div v-if="bulkAction === 'tags'" class="mb-4">
                    <label class="mb-2 block text-xs font-medium text-gray-600 dark:text-gray-400">Pilih Tag (akan mengganti tag lama)</label>
                    <div class="flex flex-wrap gap-2">
                        <button
                            v-for="tag in tags"
                            :key="tag.id"
                            type="button"
                            @click="toggleTagInForm(tag.id, bulkForm)"
                            class="rounded-full border px-3 py-1 text-xs font-medium transition"
                            :class="bulkForm.tag_ids.includes(tag.id)
                                ? 'border-blue-500 bg-blue-500 text-white'
                                : 'border-gray-200 bg-white text-gray-600 hover:border-blue-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400'"
                        >
                            {{ tag.name }}
                        </button>
                    </div>
                </div>

                <div v-if="bulkAction === 'status'" class="mb-4 flex gap-3">
                    <button
                        type="button"
                        @click="bulkForm.is_active = true"
                        class="flex-1 rounded-lg border py-2 text-sm font-medium transition"
                        :class="bulkForm.is_active === true ? 'border-green-500 bg-green-500 text-white' : 'border-gray-200 text-gray-600 dark:border-gray-600 dark:text-gray-400'"
                    >Aktif</button>
                    <button
                        type="button"
                        @click="bulkForm.is_active = false"
                        class="flex-1 rounded-lg border py-2 text-sm font-medium transition"
                        :class="bulkForm.is_active === false ? 'border-gray-500 bg-gray-500 text-white' : 'border-gray-200 text-gray-600 dark:border-gray-600 dark:text-gray-400'"
                    >Nonaktif</button>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="showBulkModal = false" class="rounded-lg border border-gray-200 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400">
                        Batal
                    </button>
                    <button
                        type="button"
                        @click="submitBulk"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                    >
                        Terapkan
                    </button>
                </div>
            </div>
        </div>
    </Teleport>
</template>
