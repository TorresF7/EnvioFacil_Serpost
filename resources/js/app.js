import './bootstrap';
import 'flag-icons/css/flag-icons.min.css';
import 'vue-sonner/style.css';
import { fixLeafletIconos } from './Utils/leaflet';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { createPinia } from 'pinia';

fixLeafletIconos();

const appName = 'SERPOST Envío Fácil';
const pinia = createPinia();

createInertiaApp({
    title: (title) => (title ? `${title} · ${appName}` : appName),
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .mount(el);
    },
    progress: {
        color: '#C62828',
    },
});
