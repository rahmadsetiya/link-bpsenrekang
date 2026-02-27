import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { config } from '@inertiajs/vue3'

// Set a single value using dot notation...
config.set('form.recentlySuccessfulDuration', 1000)
config.set('prefetch.cacheFor', '5m')

// Set multiple values at once...
config.set({
    'form.recentlySuccessfulDuration': 1000,
    'prefetch.cacheFor': '5m',
})

const appName = import.meta.env.VITE_APP_NAME || 'LINKANG';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
    defaults: {
        form: {
            recentlySuccessfulDuration: 5000,
        },
        prefetch: {
            cacheFor: "1m",
            hoverDelay: 150,
        },
        visitOptions: (href, options) => {
            return {
                headers: {
                    ...options.headers,
                    "X-Custom-Header": "value",
                },
            };
        },
        defaults: {
            future: {
                useScriptElementForInitialPage: true,
            },
        },
    },
});
