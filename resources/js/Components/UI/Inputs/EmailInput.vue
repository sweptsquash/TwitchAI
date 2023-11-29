<template>
    <TextInput
        :id="id"
        :name="name"
        type="email"
        :placeholder="placeholder"
        :autocomplete="autocomplete"
        :required="required"
        :readonly="readonly"
        :model-value="modelValue"
        :hide-label="hideLabel"
        :hide-input="hideInput"
        :label-position="labelPosition"
        :label-alignment="labelAlignment"
        :label-width="labelWidth"
        :disabled="disabled"
        :caption="caption"
        :label="label"
        :detect-errors="detectErrors"
        :error="error"
        @update:model-value="(value) => emit('update:modelValue', value)"
        @input="(value) => emit('input', value)"
        @keyup="(e) => emit('keyup', e)"
        @focus="(e) => emit('focus', e)"
        @blur="(e) => emit('blur', e)"
    >
        <template v-if="Boolean(slots.actions)" #actions>
            <slot name="actions" />
        </template>

        <template v-if="Boolean(slots.icon)" #icon>
            <slot name="icon" />
        </template>

        <template v-if="Boolean(slots.helperText)" #helper-text>
            <slot name="helperText" />
        </template>
    </TextInput>
</template>

<script lang="ts" setup>
const props = withDefaults(
    defineProps<{
        id: string
        name?: string
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
        id: undefined,
        name: undefined,
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
</script>
