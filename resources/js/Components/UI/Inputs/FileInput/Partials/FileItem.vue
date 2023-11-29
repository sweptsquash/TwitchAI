<template>
    <div
        class="flex max-w-full flex-row items-center space-x-4 overflow-hidden px-4 py-2"
        :class="{
            'border-b border-gray-200': index < filesCount - 1,
        }"
    >
        <div class="flex flex-1 items-center justify-between overflow-hidden">
            <div class="flex w-4/5 flex-col items-start">
                <p class="w-full overflow-hidden truncate whitespace-nowrap" v-text="file.name" />
                <span class="text-xs text-gray-400" v-text="usePrettyBytes(file.size)" />

                <div v-if="errors && hasError(index)" class="mt-2 text-red-400">
                    {{ errors[`files.${index}`] }}
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <span
                    v-if="alreadyUploaded"
                    title="This file is already uploaded. So if you delete it, it will be deleted from the server as well."
                    class="inline-flex cursor-help items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10"
                >
                    Already uploaded
                </span>

                <button
                    type="button"
                    class="rounded-full p-1.5 text-gray-400 hover:bg-gray-100"
                    :title="`Remove ${file.name}`"
                    @click="emits('deleteFile', index)"
                >
                    <XMarkIcon class="h-4 w-4" />
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { XMarkIcon } from '@heroicons/vue/24/outline'

const props = withDefaults(
    defineProps<{
        alreadyUploaded?: boolean
        filesCount: number
        errors: any
        index: any
        file: any
    }>(),
    {
        alreadyUploaded: false,
    },
)

const emits = defineEmits(['deleteFile'])

function hasError(index: number): boolean {
    if (!props.errors) {
        return false
    }

    return `files.${index}` in props.errors
}
</script>

<style scoped></style>
