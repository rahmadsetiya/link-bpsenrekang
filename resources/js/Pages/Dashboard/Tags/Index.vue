<script setup>
import { ref, computed } from "vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    tags: { type: Array, default: () => [] },
    search: { type: String, default: "" },
    page: { type: Number, default: 1 },
    total: { type: Number, default: 0 },
    perPage: { type: Number, default: 10 },
    totalPages: { type: Number, default: 1 },
});

// ── Search ──
const searchQuery = ref(props.search);

function handleSearch() {
    router.get(route("dashboard.tags.index"), {
        search: searchQuery.value,
        page: 1,
    });
}

function clearSearch() {
    searchQuery.value = "";
    router.get(route("dashboard.tags.index"));
}

function goToPage(pageNum) {
    router.get(route("dashboard.tags.index"), {
        search: searchQuery.value,
        page: pageNum,
    });
}

// ── Computed: Pagination range ──
const currentPage = computed(() => Number(props.page));

const pageRange = computed(() => {
    const pages = [];
    const maxVisible = 5;
    let start = Math.max(1, currentPage.value - Math.floor(maxVisible / 2));
    let end = Math.min(props.totalPages, start + maxVisible - 1);

    if (end - start + 1 < maxVisible) {
        start = Math.max(1, end - maxVisible + 1);
    }

    for (let i = start; i <= end; i++) {
        pages.push(i);
    }

    return pages;
});

const editingTag = ref(null);
const editName = ref("");

const createForm = useForm({ name: "" });

function submitCreate() {
    createForm.post(route("dashboard.tags.store"), {
        onSuccess: () => createForm.reset(),
    });
}

function startEdit(tag) {
    editingTag.value = tag.id;
    editName.value = tag.name;
}

function cancelEdit() {
    editingTag.value = null;
    editName.value = "";
}

function submitEdit(tag) {
    router.put(
        route("dashboard.tags.update", tag.id),
        { name: editName.value },
        {
            onSuccess: cancelEdit,
        },
    );
}

function deleteTag(tag) {
    if (
        !confirm(`Hapus tag "${tag.name}"? Tag akan dihapus dari semua tautan.`)
    )
        return;
    router.delete(route("dashboard.tags.destroy", tag.id));
}
</script>

<template>
    <Head title="Kelola Tag" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    Kelola Tag
                </h2>
                <Link
                    :href="route('dashboard')"
                    class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                >
                    ← Dashboard
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
                <!-- Create Form -->
                <div
                    class="mb-6 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100 dark:bg-gray-800 dark:ring-gray-700"
                >
                    <h3
                        class="mb-3 text-sm font-semibold text-gray-700 dark:text-gray-300"
                    >
                        Tambah Tag Baru
                    </h3>
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
                    <p
                        v-if="createForm.errors.name"
                        class="mt-1 text-xs text-red-500"
                    >
                        {{ createForm.errors.name }}
                    </p>
                </div>

                <!-- Search Bar -->
                <div class="mb-4 flex gap-2">
                    <div class="flex-1">
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Cari tag..."
                            @keyup.enter="handleSearch"
                            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-800 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                        />
                    </div>
                    <button
                        @click="handleSearch"
                        class="rounded-lg bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700"
                    >
                        Cari
                    </button>
                    <button
                        v-if="searchQuery"
                        @click="clearSearch"
                        class="rounded-lg bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300"
                    >
                        Reset
                    </button>
                </div>

                <!-- Results info -->
                <div
                    v-if="search"
                    class="mb-4 text-sm text-gray-600 dark:text-gray-400"
                >
                    Menampilkan <strong>{{ tags.length }}</strong> dari
                    <strong>{{ total }}</strong> hasil untuk "<strong>{{
                        search
                    }}</strong
                    >"
                </div>

                <!-- Tags List -->
                <div
                    class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100 dark:bg-gray-800 dark:ring-gray-700"
                >
                    <div
                        v-if="tags.length === 0"
                        class="px-4 py-12 text-center text-sm text-gray-400"
                    >
                        Belum ada tag.
                    </div>
                    <ul class="divide-y divide-gray-50 dark:divide-gray-700">
                        <li
                            v-for="tag in tags"
                            :key="tag.id"
                            class="flex items-center justify-between px-4 py-3"
                        >
                            <!-- View mode -->
                            <div
                                v-if="editingTag !== tag.id"
                                class="flex flex-1 items-center gap-3"
                            >
                                <span
                                    class="rounded-full bg-blue-50 px-2.5 py-0.5 text-sm font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-400"
                                >
                                    {{ tag.name }}
                                </span>
                                <span class="text-xs text-gray-400"
                                    >{{ tag.links_count }} tautan</span
                                >
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
                                    <button
                                        @click="submitEdit(tag)"
                                        class="rounded-lg bg-blue-600 px-3 py-1 text-xs font-medium text-white hover:bg-blue-700"
                                    >
                                        Simpan
                                    </button>
                                    <button
                                        @click="cancelEdit"
                                        class="rounded-lg border border-gray-200 px-3 py-1 text-xs text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-400"
                                    >
                                        Batal
                                    </button>
                                </template>
                                <template v-else>
                                    <button
                                        @click="startEdit(tag)"
                                        class="rounded-lg border border-gray-200 px-2.5 py-1 text-xs font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        @click="deleteTag(tag)"
                                        class="rounded-lg border border-red-100 px-2.5 py-1 text-xs font-medium text-red-500 hover:bg-red-50 dark:border-red-900 dark:text-red-400 dark:hover:bg-red-900/20"
                                    >
                                        Hapus
                                    </button>
                                </template>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Pagination -->
                <div
                    v-if="totalPages > 1"
                    class="mt-6 flex items-center justify-between"
                >
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Halaman <strong>{{ currentPage }}</strong> dari
                        <strong>{{ totalPages }}</strong> | Total
                        <strong>{{ total }}</strong> tag
                    </div>
                    <div class="flex gap-2">
                        <button
                            v-if="currentPage > 1"
                            @click="goToPage(currentPage - 1)"
                            class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700"
                        >
                            ← Sebelumnya
                        </button>
                        <div class="flex gap-1">
                            <button
                                v-for="p in pageRange"
                                :key="p"
                                @click="goToPage(p)"
                                :class="
                                    currentPage === p
                                        ? 'bg-blue-600 text-white'
                                        : 'border border-gray-200 text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700'
                                "
                                class="rounded-lg px-3 py-2 text-sm font-medium"
                            >
                                {{ p }}
                            </button>
                            <div
                                v-if="
                                    pageRange[pageRange.length - 1] < totalPages
                                "
                                class="flex items-center px-2 text-gray-400"
                            >
                                ...
                            </div>
                            <button
                                v-if="
                                    pageRange[pageRange.length - 1] < totalPages
                                "
                                @click="goToPage(totalPages)"
                                :class="
                                    currentPage === totalPages
                                        ? 'bg-blue-600 text-white'
                                        : 'border border-gray-200 text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700'
                                "
                                class="rounded-lg px-3 py-2 text-sm font-medium"
                            >
                                {{ totalPages }}
                            </button>
                        </div>
                        <button
                            v-if="currentPage < totalPages"
                            @click="goToPage(currentPage + 1)"
                            class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700"
                        >
                            Berikutnya →
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
