<template>
    <div class="my-auto w-full px-4 py-16 sm:px-6 sm:py-24 md:grid md:place-items-center lg:px-8">
        <div class="mx-auto max-w-max">
            <AppHead :title="errorTitle" />
            <main class="sm:flex">
                <p
                    class="text-4xl font-bold tracking-tight text-indigo-600 sm:text-5xl sm:tracking-tight"
                >
                    {{ status }}
                </p>
                <div class="sm:ml-6">
                    <div class="sm:border-l sm:border-gray-200 sm:pl-6">
                        <h1
                            class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl sm:tracking-tight"
                        >
                            {{ errorHeaderTitle }}
                        </h1>
                        <p class="mt-1 text-base text-gray-500">
                            {{ errorDescription }}
                        </p>
                    </div>
                    <div class="mt-10 flex space-x-3 sm:border-l sm:border-transparent sm:pl-6">
                        <InertiaLink :href="route('home')" class="btn-primary">
                            Go back home
                        </InertiaLink>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<script lang="ts" setup>
const props = defineProps<{
    status: number | string
    message?: string
}>()

const errorHeaderTitle = computed(() => {
    const statuses: { [key: number | string]: string } = {
        503: 'Service Unavailable',
        500: 'Server Error',
        404: 'Page Not Found',
        403: 'Forbidden',
    }

    return statuses[props.status]
})

const errorTitle = computed(() => {
    const statuses: { [key: number | string]: string } = {
        503: 'Service Unavailable',
        500: 'Server Error',
        404: 'Page Not Found',
        403: 'Forbidden',
    }

    return statuses[props.status]
})

const errorDescription = computed(() => {
    if (props.message) {
        return props.message
    }

    const statuses: { [key: number | string]: string } = {
        503: 'Sorry, we are doing some maintenance. Please check back soon.',
        500: 'Whoops, something went wrong on our servers.',
        404: 'Sorry, the page you are looking for could not be found.',
        403: 'Sorry, you are forbidden from accessing this page.',
    }

    return statuses[props.status]
})
</script>
