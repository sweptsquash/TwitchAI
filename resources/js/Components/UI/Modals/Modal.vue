<template>
    <TransitionRoot as="template" :show="isOpen">
        <Dialog as="div" class="relative z-50" @close="close">
            <TransitionChild
                as="template"
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 backdrop-blur-sm transition-opacity"
                />
            </TransitionChild>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div
                    class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
                >
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <DialogPanel
                            class="relative w-full transform rounded bg-white text-left shadow-xl transition-all sm:my-8"
                            :class="{
                                'max-w-2xl': size === '2xl',
                                'max-w-xl': size === 'xl',
                                'max-w-lg': size === 'lg',
                                'max-w-md': size === 'md',
                                'max-w-sm': size === 'sm',
                                'max-w-xs': size === 'xs',
                            }"
                        >
                            <slot />
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script lang="ts" setup>
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'

const props = withDefaults(
    defineProps<{
        confirmToCloseMessage?: string
        onlyCloseByButtons?: boolean
        confirmToClose?: boolean
        isOpen: boolean
        size?: '2xl' | 'xl' | 'lg' | 'md' | 'sm' | 'xs'
    }>(),
    {
        confirmToCloseMessage: undefined,
        onlyCloseByButtons: false,
        confirmToClose: false,
        isOpen: false,
        size: '2xl',
    },
)

const emit = defineEmits(['close'])

function close() {
    if (props.onlyCloseByButtons) return
    if (props.confirmToClose && !confirm(props.confirmToCloseMessage || 'Are you sure?')) return

    emit('close')
}
</script>
