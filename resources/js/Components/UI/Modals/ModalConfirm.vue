<template>
    <Modal
        :is-open="isOpen"
        :only-close-by-buttons="onlyCloseByButtons"
        :size="size"
        @close="close"
    >
        <div class="rounded-t bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:text-left">
                    <DialogTitle as="h3" class="text-lg font-bold leading-6 text-gray-900">
                        {{ title }}
                    </DialogTitle>
                    <div class="mt-2">
                        <slot>
                            <p class="text-sm text-gray-500" v-text="message" />
                        </slot>
                    </div>
                </div>
            </div>
        </div>
        <div class="gap-x-2 rounded-b bg-white px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
            <button
                type="button"
                :class="['btn-primary flex justify-center', { 'opacity-25': isProcessing }]"
                :disabled="isProcessing"
                @click="confirm"
            >
                <IconLoading v-if="isProcessing" class="mr-2 h-5 w-5 animate-spin text-white" />
                {{ confirmText }}
            </button>
            <button
                type="button"
                class="btn-default"
                :disabled="isProcessing"
                @click="cancel"
                v-text="cancelText"
            />
        </div>
    </Modal>
</template>

<script lang="ts" setup>
import { DialogTitle } from '@headlessui/vue'

withDefaults(
    defineProps<{
        cancelText?: string
        confirmText?: string
        isOpen: boolean
        isProcessing?: boolean
        message?: string
        onlyCloseByButtons?: boolean
        title: string
        size?: '2xl' | 'xl' | 'lg' | 'md' | 'sm' | 'xs'
    }>(),
    {
        cancelText: 'Cancel',
        confirmText: 'Confirm',
        isProcessing: false,
        message: undefined,
        onlyCloseByButtons: true,
        size: 'lg',
    },
)

const emit = defineEmits(['cancel', 'confirm', 'close'])

function close() {
    emit('close')
}

function confirm() {
    emit('confirm')
    close()
}

function cancel() {
    emit('cancel')
    close()
}
</script>
