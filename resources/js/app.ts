import '@/../css/app.css'

import Layout from '@/Layouts/Index.vue'
import { notifications } from '@/Plugins/Notification'
import routes from '@/Routes/routes.json'
import { createInertiaApp } from '@inertiajs/vue3'
import { vOnClickOutside } from '@vueuse/components'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { trail } from 'momentum-trail'
import { DefineComponent, createApp, h } from 'vue'
import Toast from 'vue-toastification'

createInertiaApp({
    progress: {
        color: '#4B5563',
    },
    resolve: (name: string) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./Pages/**/*.vue'),
        ).then((module) => {
            const page = module.default

            page.layout = Layout

            return page
        }) as Promise<DefineComponent>,
    setup({ el, App, props, plugin }) {
        // eslint-disable-next-line vue/component-api-style
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(trail, { routes })
            .use(notifications)
            .use(Toast)
            .directive('OnClickOutside', vOnClickOutside)
            .mount(el)
    },
})
