<script setup>
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link } from '@inertiajs/vue3';
import { useDarkMode } from '@/Composables/useDarkMode';

const showingNavigationDropdown = ref(false);
const { isDark, toggle } = useDarkMode();
</script>

<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-950">
        <!-- Navbar -->
        <nav class="border-b border-gray-100 bg-white dark:border-gray-800 dark:bg-gray-900">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-14 items-center justify-between">
                    <!-- Logo -->
                    <div class="flex items-center gap-6">
                        <Link :href="route('dashboard')" class="flex flex-col">
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-100">BPS Enrekang</span>
                            <span class="text-xs text-gray-400 dark:text-gray-500">Admin Panel</span>
                        </Link>

                        <!-- Nav Links -->
                        <div class="hidden sm:flex sm:gap-1">
                            <Link
                                :href="route('dashboard')"
                                class="rounded-lg px-3 py-1.5 text-xs font-medium transition"
                                :class="route().current('dashboard')
                                    ? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100'
                                    : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200'"
                            >
                                Dashboard
                            </Link>
                            <Link
                                :href="route('dashboard.links.index')"
                                class="rounded-lg px-3 py-1.5 text-xs font-medium transition"
                                :class="route().current('dashboard.links.*')
                                    ? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100'
                                    : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200'"
                            >
                                Tautan
                            </Link>
                            <Link
                                :href="route('dashboard.tags.index')"
                                class="rounded-lg px-3 py-1.5 text-xs font-medium transition"
                                :class="route().current('dashboard.tags.*')
                                    ? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100'
                                    : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200'"
                            >
                                Tag
                            </Link>
                        </div>
                    </div>

                    <!-- Right side -->
                    <div class="flex items-center gap-2">
                        <!-- Dark mode toggle -->
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

                        <!-- User dropdown (desktop) -->
                        <div class="hidden sm:block">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <button
                                        type="button"
                                        class="flex items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 transition hover:border-gray-300 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:bg-gray-800"
                                    >
                                        {{ $page.props.auth.user.name }}
                                        <svg class="h-3 w-3 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')">
                                        Profil
                                    </DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">
                                        Keluar
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>

                        <!-- Hamburger (mobile) -->
                        <button
                            @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="rounded-lg border border-gray-200 p-1.5 text-gray-500 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 sm:hidden"
                        >
                            <svg class="h-4 w-4" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path v-if="!showingNavigationDropdown" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div v-if="showingNavigationDropdown" class="border-t border-gray-100 dark:border-gray-800 sm:hidden">
                <div class="space-y-1 px-4 py-3">
                    <Link
                        :href="route('dashboard')"
                        class="block rounded-lg px-3 py-2 text-sm font-medium transition"
                        :class="route().current('dashboard')
                            ? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100'
                            : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800'"
                    >
                        Dashboard
                    </Link>
                    <Link
                        :href="route('dashboard.links.index')"
                        class="block rounded-lg px-3 py-2 text-sm font-medium transition"
                        :class="route().current('dashboard.links.*')
                            ? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100'
                            : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800'"
                    >
                        Tautan
                    </Link>
                    <Link
                        :href="route('dashboard.tags.index')"
                        class="block rounded-lg px-3 py-2 text-sm font-medium transition"
                        :class="route().current('dashboard.tags.*')
                            ? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100'
                            : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800'"
                    >
                        Tag
                    </Link>
                </div>
                <div class="border-t border-gray-100 px-4 py-3 dark:border-gray-800">
                    <p class="text-xs font-medium text-gray-800 dark:text-gray-200">{{ $page.props.auth.user.name }}</p>
                    <p class="text-xs text-gray-500">{{ $page.props.auth.user.email }}</p>
                    <div class="mt-2 space-y-1">
                        <Link :href="route('profile.edit')" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800">
                            Profil
                        </Link>
                        <Link :href="route('logout')" method="post" as="button" class="block w-full rounded-lg px-3 py-2 text-left text-sm text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800">
                            Keluar
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <header v-if="$slots.header" class="border-b border-gray-100 bg-white dark:border-gray-800 dark:bg-gray-900">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Page Content -->
        <main>
            <slot />
        </main>
    </div>
</template>
