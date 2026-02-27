<script setup>
import { Link } from '@inertiajs/vue3';
import { useDarkMode } from '@/Composables/useDarkMode';

const { isDark, toggle } = useDarkMode();
</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-gray-50 dark:bg-gray-950">
        <!-- Dark mode toggle -->
        <button
            @click="toggle"
            class="absolute right-4 top-4 rounded-lg border border-gray-200 p-1.5 text-gray-500 transition hover:border-gray-300 hover:bg-white dark:border-gray-700 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:bg-gray-800"
            :title="isDark ? 'Mode Terang' : 'Mode Gelap'"
        >
            <svg v-if="isDark" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
            </svg>
            <svg v-else class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
            </svg>
        </button>

        <div class="w-full max-w-sm px-6">
            <div class="mb-8 text-center">
                <Link href="/" class="text-xl font-semibold tracking-tight text-gray-800 hover:text-gray-600 dark:text-gray-100 dark:hover:text-gray-300">
                    BPS Enrekang
                </Link>
                <p class="mt-1 text-xs tracking-widest text-gray-400 uppercase dark:text-gray-500">Admin Panel</p>
            </div>
            <div class="rounded-xl bg-white px-8 py-8 shadow-sm ring-1 ring-gray-100 dark:bg-gray-900 dark:ring-gray-800">
                <slot />
            </div>
        </div>
    </div>
</template>
