<script setup>
    defineProps({
        link: {
            type: Object,
            required: true,
        },
    });
</script>

<template>
    <a :href="link.url" target="_blank" rel="noopener noreferrer"
        class="group flex flex-col justify-between rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition hover:shadow-md hover:ring-gray-200 dark:bg-gray-900 dark:ring-gray-800 dark:hover:ring-gray-700">
        <div>
            <h3
                class="line-clamp-2 text-sm font-semibold text-gray-800 group-hover:text-blue-600 dark:text-gray-100 dark:group-hover:text-blue-400">
                {{ link . name }}
            </h3>
            <p class="mt-1 truncate text-xs text-gray-400 dark:text-gray-500">
                {{ link . url }}
            </p>
        </div>

        <div class="mt-4 flex flex-wrap items-center justify-between gap-2">
            <div class="flex flex-wrap gap-1">
                <span v-for="tag in link.tags" :key="tag.id"
                    class="rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                    {{ tag . name }}
                </span>
            </div>
            <span
                class="shrink-0 rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                {{ link . year }}
            </span>
        </div>
    </a>
</template>
