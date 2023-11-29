<template>
    <BaseInput
        :hide-label="hideLabel"
        :label="label"
        :label-position="labelPosition"
        :label-alignment="labelAlignment"
        :label-width="labelWidth"
        :label-for="id"
        :caption="caption"
        :required="required"
        :has-error="hasError"
    >
        <template #actions>
            <slot name="actions" />
        </template>

        <template #input>
            <div class="relative rounded" :class="{ 'shadow-sm': !hideInput }">
                <div
                    v-if="hasIcon"
                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
                >
                    <slot name="icon"></slot>
                </div>
                <input
                    :id="id"
                    :type="type"
                    :name="name ?? id"
                    :placeholder="placeholder"
                    :autocomplete="autocomplete"
                    :required="required"
                    :disabled="disabled"
                    :readonly="readonly"
                    :value="modelValue"
                    :class="[
                        'form-input',
                        { 'pl-10': hasIcon, 'error pr-10': hasError },
                        { 'hide-input': hideInput },
                    ]"
                    :aria-invalid="hasError"
                    :aria-describedby="`${name ?? id}-error`"
                    @blur="(e) => emit('blur', e)"
                    @focus="(e) => emit('focus', e)"
                    @input="handleInput"
                    @keyup="(e) => emit('keyup', e)"
                />
                <div
                    v-if="hasError"
                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3"
                >
                    <ExclamationCircleIcon class="h-5 w-5 text-red-500" aria-hidden="true" />
                </div>
            </div>
            <p v-if="hasHelperText" class="mt-2 text-xs text-gray-400">
                <slot name="helperText" />
            </p>
            <p v-if="hasError" :id="`${name ?? id}-error`" class="mt-2 text-sm text-red-600">
                {{ computedError }}
            </p>
        </template>
    </BaseInput>
</template>

<script lang="ts" setup>
import { ExclamationCircleIcon } from '@heroicons/vue/20/solid'

const props = withDefaults(
    defineProps<{
        id: string
        name?: string
        type?: string
        placeholder?: string
        autocomplete?: string
        required?: boolean
        disabled?: boolean
        readonly?: boolean
        modelValue: any
        hideLabel?: boolean
        hideInput?: boolean
        label?: string
        labelPosition?: 'left' | 'top'
        labelAlignment?: 'left' | 'right'
        labelWidth?: number
        caption?: string
        error?: string
        detectErrors?: boolean
    }>(),
    {
        name: undefined,
        type: 'text',
        placeholder: undefined,
        autocomplete: 'off',
        required: false,
        disabled: false,
        readonly: false,
        hideLabel: false,
        hideInput: false,
        label: undefined,
        labelPosition: 'top',
        labelAlignment: 'left',
        labelWidth: 33,
        caption: undefined,
        error: undefined,
        detectErrors: false,
    },
)

const emit = defineEmits(['update:modelValue', 'keyup', 'blur', 'focus', 'input'])

const slots = useSlots()

const hasIcon = computed(() => (slots.icon ? !!slots.icon() : false))
const hasHelperText = computed(() => (slots.helperText ? !!slots.helperText() : false))

const hasError = computed(() => computedError.value !== undefined && computedError.value !== '')
const computedError = computed(() => {
    return props.detectErrors ? usePage().props.errors[props.id] : props.error
})

function handleInput(event: any) {
    emit('update:modelValue', event.target.value)
    emit('input', event.target.value)
}
</script>
