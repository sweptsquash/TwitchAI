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
                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center"
                    :class="{ 'pl-3': !hideInput }"
                >
                    <slot name="icon"></slot>
                </div>
                <input
                    :id="id"
                    type="number"
                    :name="name ?? id"
                    :placeholder="placeholder"
                    :autocomplete="autocomplete"
                    :required="required"
                    :disabled="disabled"
                    :readonly="readonly"
                    :value="modelValue"
                    :min="min"
                    :max="max"
                    :step="step"
                    :class="[
                        'form-input',
                        {
                            'pl-10': hasIcon && !hideInput,
                            'pr-10': hasError,
                            error: hasError,
                            'hide-input': hideInput,
                            'pl-8': hasIcon && hideInput,
                        },
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
                <slot name="helperText"></slot>
            </p>
            <p
                v-if="hasError && error"
                :id="`${name ?? id}-error`"
                class="mt-2 text-sm text-red-600"
            >
                {{ error }}
            </p>
        </template>
    </BaseInput>
</template>

<script lang="ts" setup>
import { ExclamationCircleIcon } from '@heroicons/vue/20/solid'

const props = withDefaults(
    defineProps<{
        id?: string
        name?: string
        placeholder?: string
        autocomplete?: string
        required?: boolean
        disabled?: boolean
        readonly?: boolean
        modelValue: any
        min?: number
        max?: number
        step?: number
        hideLabel?: boolean
        hideInput?: boolean
        label?: string
        labelPosition?: 'left' | 'top'
        labelAlignment?: 'left' | 'right'
        labelWidth?: number
        caption?: string
        error?: string
    }>(),
    {
        id: undefined,
        name: undefined,
        placeholder: undefined,
        autocomplete: 'off',
        required: false,
        disabled: false,
        readonly: false,
        min: undefined,
        max: undefined,
        step: 0.01,
        hideLabel: false,
        hideInput: false,
        label: undefined,
        labelPosition: 'top',
        labelAlignment: 'left',
        labelWidth: 33,
        caption: undefined,
        error: undefined,
    },
)

const emit = defineEmits(['update:modelValue', 'keyup', 'blur', 'focus', 'input'])

const slots = useSlots()

const hasIcon = computed(() => (slots.icon ? !!slots.icon() : false))
const hasHelperText = computed(() => (slots.helperText ? !!slots.helperText() : false))
const hasError = computed(() => props.error !== undefined && props.error !== '')

function handleInput(event: any) {
    emit('update:modelValue', Number(event.target.value))
    emit('input', Number(event.target.value))
}
</script>
