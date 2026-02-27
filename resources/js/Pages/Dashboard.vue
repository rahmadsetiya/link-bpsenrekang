<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <Link
                        :href="route('dashboard.links.index')"
                        class="flex items-center gap-4 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100 transition hover:shadow-md dark:bg-gray-800 dark:ring-gray-700"
                    >
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/30">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-gray-100">Kelola Tautan</p>
                            <p class="text-sm text-gray-400">Tambah, ubah, atau hapus tautan</p>
                        </div>
                    </Link>

                    <Link
                        :href="route('dashboard.tags.index')"
                        class="flex items-center gap-4 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100 transition hover:shadow-md dark:bg-gray-800 dark:ring-gray-700"
                    >
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-50 dark:bg-green-900/30">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-gray-100">Kelola Tag</p>
                            <p class="text-sm text-gray-400">Tambah, ubah, atau hapus tag</p>
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
