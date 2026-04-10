import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import axios from 'axios';
import { buildBearerToken, getAuthToken } from '@/lib/authToken';
import '../css/app.css';
import '@fortawesome/fontawesome-free/css/all.min.css';
import { initializeTheme } from '@/composables/useAppearance';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

if (csrfToken) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

const initialToken = buildBearerToken(getAuthToken());
if (initialToken) {
    axios.defaults.headers.common.Authorization = initialToken;
}

axios.interceptors.request.use((config) => {
    const bearerToken = buildBearerToken(getAuthToken());

    if (bearerToken) {
        config.headers.Authorization = bearerToken;
    } else if (config.headers?.Authorization) {
        delete config.headers.Authorization;
    }

    return config;
});

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
