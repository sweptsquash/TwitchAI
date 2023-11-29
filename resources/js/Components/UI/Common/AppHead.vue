<template>
    <InertiaHead :title="title ?? 'Oops'">
        <link rel="canonical" :content="url" />
        <meta name="description" :content="description" />
        <meta name="author" content="Stewart Marsh" />
        <slot />
    </InertiaHead>
</template>

<script lang="ts" setup>
withDefaults(
    defineProps<{
        title?: string | null
        ogType?: string
        description?: string
        thumbnail?: string
    }>(),
    {
        title: null,
        ogType: 'website',
        description: '',
        thumbnail: '',
    },
)

const url = usePage().props.location ?? undefined
</script>
