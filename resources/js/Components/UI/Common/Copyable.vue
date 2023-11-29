<template>
    <div class="flex items-center rounded border">
        <div class="h-full text-ellipsis border-r p-2" :title="value">
            <slot>
                {{ value }}
            </slot>
        </div>

        <button
            v-if="isSupported && value && !disable"
            title="Copy to clipboard!"
            class="p-2 text-gray-400 hover:text-gray-600 active:text-gray-900"
            type="button"
            @click="copy(value)"
        >
            <CheckIcon v-if="copied" class="h-4 w-4 text-green-600" />
            <IconCopy v-else class="h-4 w-4" />
        </button>
    </div>
</template>

<script setup lang="ts">
import IconCopy from '@/Components/UI/Icons/IconCopy.vue'
import { CheckIcon } from '@heroicons/vue/24/outline'
import { useClipboard } from '@vueuse/core'

const props = withDefaults(
    defineProps<{
        disable?: boolean
        value: any
    }>(),
    {
        disable: false,
    },
)

const { text, copy, copied, isSupported } = useClipboard({ source: props.value, legacy: true })
</script>

<style scoped></style>
