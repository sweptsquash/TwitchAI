<template>
    <span
        :class="[
            'badge',
            `badge-${type}`,
            size ? `badge-${size}` : null,
            rounded ? `rounded-${rounded}` : null,
            { 'badge-outline': outline },
        ]"
    >
        <slot />
    </span>
</template>

<script lang="ts" setup>
withDefaults(
    defineProps<{
        type?: 'default' | 'primary' | 'muted' | 'success' | 'info' | 'warning' | 'danger' | 'neon'
        size?: 'xs' | 'sm' | 'md' | 'lg' | 'xl' | null
        rounded?: 'sm' | 'md' | 'lg' | 'full' | null
        outline?: boolean
    }>(),
    {
        type: 'default',
        size: null,
        rounded: null,
        outline: false,
    },
)
</script>
