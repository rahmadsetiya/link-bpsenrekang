<script setup>
import { computed, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import LinkCard from '@/Components/LinkCard.vue';
import { useDarkMode } from '@/Composables/useDarkMode';

const props = defineProps({
    canLogin: { type: Boolean },
    links: { type: Array, default: () => [] },
    years: { type: Array, default: () => [] },
    tags: { type: Array, default: () => [] },
});

const { isDark, toggle } = useDarkMode();

const selectedYears = ref([]);
const selectedTags = ref([]);
const search = ref('');

function toggleYear(year) {
    const idx = selectedYears.value.indexOf(year);
    if (idx === -1) selectedYears.value.push(year);
    else selectedYears.value.splice(idx, 1);
}

function toggleTag(tagId) {
    const idx = selectedTags.value.indexOf(tagId);
    if (idx === -1) selectedTags.value.push(tagId);
    else selectedTags.value.splice(idx, 1);
}

function clearFilters() {
    selectedYears.value = [];
    selectedTags.value = [];
    search.value = '';
}

const hasFilter = computed(() =>
    selectedYears.value.length > 0 || selectedTags.value.length > 0 || search.value !== ''
);

const filteredLinks = computed(() => {
    return props.links.filter((l) => {
        const matchYear =
            selectedYears.value.length === 0 || selectedYears.value.includes(l.year);
        const matchTag =
            selectedTags.value.length === 0 ||
            l.tags.some((t) => selectedTags.value.includes(t.id));
        const matchSearch =
            search.value === '' ||
            l.name.toLowerCase().includes(search.value.toLowerCase());
        return matchYear && matchTag && matchSearch;
    });
});
</script>

<template>
    <Head title="Link BPS Enrekang" />

    <div class="min-h-screen bg-gray-50 dark:bg-gray-950">
        <!-- Header -->
        <header class="border-b border-gray-100 bg-white dark:border-gray-800 dark:bg-gray-900">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 sm:px-6">
                <div>
                    <h1 class="text-base font-semibold text-gray-800 dark:text-gray-100">BPS Enrekang</h1>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Direktori Tautan</p>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        @click="toggle"
                        class="rounded-lg border border-gray-200 p-1.5 text-gray-500 transition hover:border-gray-300 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:bg-gray-800"
                        :title="isDark ? 'Mode Terang' : 'Mode Gelap'"
                    >
                        <svg v-if="isDark" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                        </svg>
                        <svg v-else class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                        </svg>
                    </button>
                    <Link
                        v-if="canLogin && !$page.props.auth.user"
                        :href="route('login')"
                        class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 transition hover:border-gray-300 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:bg-gray-800"
                    >
                        Admin
                    </Link>
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 transition hover:border-gray-300 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:bg-gray-800"
                    >
                        Dashboard
                    </Link>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6">

            <!-- Search -->
            <div class="relative mb-5">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input
                    v-model="search"
                    type="text"
                    placeholder="Cari nama tautan..."
                    class="w-full rounded-lg border border-gray-200 bg-white py-2.5 pl-9 pr-4 text-sm text-gray-700 placeholder-gray-400 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:placeholder-gray-500 dark:focus:border-blue-700 dark:focus:ring-blue-900"
                />
            </div>

            <!-- Filters -->
            <div class="mb-6 space-y-3">
                <!-- Year filter -->
                <div v-if="years.length > 0" class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-medium text-gray-400 dark:text-gray-500">Tahun:</span>
                    <button
                        v-for="year in years"
                        :key="year"
                        @click="toggleYear(year)"
                        class="rounded-full border px-3 py-0.5 text-xs font-medium transition"
                        :class="selectedYears.includes(year)
                            ? 'border-blue-500 bg-blue-500 text-white'
                            : 'border-gray-200 bg-white text-gray-600 hover:border-blue-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:border-blue-700'"
                    >
                        {{ year }}
                    </button>
                </div>

                <!-- Tag filter -->
                <div v-if="tags.length > 0" class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-medium text-gray-400 dark:text-gray-500">Tag:</span>
                    <button
                        v-for="tag in tags"
                        :key="tag.id"
                        @click="toggleTag(tag.id)"
                        class="rounded-full border px-3 py-0.5 text-xs font-medium transition"
                        :class="selectedTags.includes(tag.id)
                            ? 'border-blue-500 bg-blue-500 text-white'
                            : 'border-gray-200 bg-white text-gray-600 hover:border-blue-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:border-blue-700'"
                    >
                        {{ tag.name }}
                    </button>
                </div>

                <!-- Clear filter -->
                <div v-if="hasFilter" class="flex items-center gap-2">
                    <span class="text-xs text-gray-400 dark:text-gray-500">
                        {{ filteredLinks.length }} dari {{ links.length }} tautan
                    </span>
                    <button
                        @click="clearFilters"
                        class="text-xs text-blue-500 hover:underline dark:text-blue-400"
                    >
                        Hapus semua filter
                    </button>
                </div>
            </div>

            <!-- Grid Links -->
            <div
                v-if="filteredLinks.length > 0"
                class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
            >
                <LinkCard
                    v-for="link in filteredLinks"
                    :key="link.id"
                    :link="link"
                />
            </div>

            <!-- Empty State -->
            <div v-else class="flex flex-col items-center justify-center py-24 text-center">
                <svg class="mb-3 h-10 w-10 text-gray-300 dark:text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
                <p class="text-sm font-medium text-gray-400 dark:text-gray-500">Tidak ada tautan ditemukan</p>
                <p class="mt-1 text-xs text-gray-300 dark:text-gray-600">Coba ubah filter atau kata kunci pencarian</p>
            </div>

            <p class="mt-12 text-center text-xs text-gray-300 dark:text-gray-700">
                &copy; {{ new Date().getFullYear() }} BPS Kabupaten Enrekang
            </p>
        </main>
    </div>
</template>
