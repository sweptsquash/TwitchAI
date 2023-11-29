<template>
    <div>
        <div class="flex items-center">
            <input
                :id="id"
                :name="name"
                type="checkbox"
                :checked="modelValue"
                :disabled="disabled"
                :required="required"
                :class="[
                    'h-[22px] w-[22px] rounded p-px shadow-sm',
                    {
                        'border-gray-300 text-black focus:ring-black': !hasError,
                        'border-red-300 text-red-900 focus:ring-red-500': hasError,
                        'cursor-not-allowed bg-gray-100 opacity-70': disabled,
                    },
                ]"
                :aria-invalid="hasError"
                :aria-describedby="`${name}-error`"
                @input="handleInput"
                @keyup="(e) => emit('keyup', e)"
            />
            <label
                v-if="!hideLabel"
                :for="id"
                :class="['ml-2 block text-sm text-gray-700', { required }]"
            >
                {{ label }}
            </label>
        </div>
        <p v-if="hasError && error" :id="`${name}-error`" class="mt-2 text-sm text-red-600">
            {{ error }}
        </p>
    </div>
</template>

<script lang="ts" setup>
const props = withDefaults(
    defineProps<{
        disabled?: boolean
        error?: string
        id?: string
        label?: string
        modelValue: boolean
        name?: string
        required?: boolean
        hideLabel?: boolean
    }>(),
    {
        disabled: false,
        error: undefined,
        id: undefined,
        name: undefined,
        required: false,
        hideLabel: false,
        label: 'Label',
    },
)

const emit = defineEmits(['update:modelValue', 'keyup', 'change'])
const hasError = computed(() => props.error !== undefined && props.error !== '')

function handleInput(event: any) {
    emit('update:modelValue', event.target.checked)
    emit('change', event.target.checked)
}
</script>
