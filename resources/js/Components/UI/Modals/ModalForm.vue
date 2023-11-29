<template>
    <Modal
        :only-close-by-buttons="onlyCloseByButtons"
        :confirm-to-close="confirmToClose"
        :is-open="isOpen"
        :size="size"
        @close="close"
    >
        <form @submit.prevent="submit" @input="checkValidity">
            <div class="relative border-b border-gray-300 px-4 py-3">
                <h2 v-if="title" class="text-xl font-bold" v-text="title" />
                <slot name="caption" />
            </div>

            <div class="bg-muted-100 p-4">
                <slot />
            </div>

            <div
                class="flex flex-col space-y-2 rounded-b border-t border-gray-300 bg-gray-50 p-4 sm:flex-row sm:space-x-2 sm:space-y-0"
            >
                <button
                    type="submit"
                    class="w-full sm:w-auto"
                    :class="['btn-primary flex justify-center', { 'opacity-25': isProcessing }]"
                    :disabled="isProcessing || submitDisabled"
                >
                    <IconLoading v-if="isProcessing" class="mr-2 h-5 w-5 animate-spin text-white" />
                    {{ submitText }}
                </button>
                <button
                    v-if="hasCancelButton"
                    type="button"
                    class="btn-default w-full sm:w-auto"
                    :disabled="isProcessing"
                    @click="cancel"
                    v-text="cancelText"
                />
            </div>
        </form>
    </Modal>
</template>

<script lang="ts" setup>
const props = withDefaults(
    defineProps<{
        confirmToCloseMessage?: string
        onlyCloseByButtons?: boolean
        hasCancelButton?: boolean
        confirmToClose?: boolean
        submitDisabled?: boolean
        isProcessing?: boolean
        cancelText?: string
        submitText?: string
        isOpen: boolean
        title?: string
        size?: '2xl' | 'xl' | 'lg' | 'md' | 'sm' | 'xs'
    }>(),
    {
        confirmToCloseMessage: 'Are you sure?',
        confirmToClose: false,
        cancelText: 'Cancel',
        hasCancelButton: true,
        isProcessing: false,
        onlyCloseByButtons: true,
        size: 'md',
        submitDisabled: false,
        submitText: 'Submit',
        title: undefined,
    },
)

const emit = defineEmits(['cancel', 'submit', 'close', 'valid', 'invalid'])

function close() {
    emit('close')
}

function submit() {
    emit('submit')
}

function cancel() {
    if (props.confirmToClose && !confirm(props.confirmToCloseMessage)) return

    emit('cancel')
    close()
}

function checkValidity(event: any) {
    const eventName = event.target.form?.checkValidity() ? 'valid' : 'invalid'
    emit(eventName)
}
</script>
