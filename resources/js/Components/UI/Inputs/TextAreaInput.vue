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
            <div class="flex-1">
                <div class="relative rounded" :class="{ 'shadow-sm': !hideInput }">
                    <textarea
                        :id="id"
                        ref="textarea"
                        :class="[
                            'form-input',
                            { 'pr-10': hasError, error: hasError, 'hide-input': hideInput },
                        ]"
                        :name="name ?? id"
                        :placeholder="placeholder"
                        :autocomplete="autocomplete"
                        :rows="rows"
                        :required="required"
                        :disabled="disabled"
                        :readonly="readonly"
                        :value="modelValue"
                        :aria-invalid="hasError"
                        :aria-describedby="`${name ?? id}-error`"
                        @blur="(e) => $emit('blur', e)"
                        @focus="(e) => $emit('focus', e)"
                        @input="handleInput"
                        @keyup="(e) => $emit('keyup', e)"
                    ></textarea>
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
            </div>
        </template>
    </BaseInput>
</template>

<script lang="ts" setup>
import { ExclamationCircleIcon } from '@heroicons/vue/20/solid'

const props = withDefaults(
    defineProps<{
        id: string
        name?: string
        placeholder?: string
        autocomplete?: string
        required?: boolean
        disabled?: boolean
        readonly?: boolean
        rows?: number
        modelValue?: string | number | string[] | null
        hideLabel?: boolean
        hideInput?: boolean
        label?: string
        labelPosition?: 'left' | 'top'
        labelAlignment?: 'left' | 'right'
        labelWidth?: number
        caption?: string
        error?: string
        autosize?: boolean
    }>(),
    {
        name: undefined,
        placeholder: undefined,
        autocomplete: 'off',
        required: false,
        disabled: false,
        readonly: false,
        rows: 3,
        modelValue: undefined,
        hideLabel: false,
        hideInput: false,
        label: undefined,
        labelPosition: 'top',
        labelAlignment: 'left',
        labelWidth: 33,
        caption: undefined,
        error: undefined,
        autosize: false,
    },
)

const emit = defineEmits(['update:modelValue', 'keyup', 'blur', 'focus', 'input'])

const textarea = ref(null)

const slots = useSlots()

const hasHelperText = computed(() => (slots.helperText ? !!slots.helperText() : false))
const hasError = computed(() => props.error !== undefined && props.error !== '')

watch(
    () => props.modelValue,
    () => {
        resize()
    },
    { deep: true },
)

onMounted(() => {
    resize()
})

function handleInput(event: any) {
    emit('update:modelValue', event.target.value)
    emit('input', event.target.value)
}

function resize() {
    if (props.autosize && textarea.value) {
        // @ts-expect-error
        if (textarea.value.scrollHeight > textarea.value.offsetHeight) {
            // @ts-expect-error
            textarea.value.style.height = textarea.value.scrollHeight + 2 + 'px'
        }
    }
}
</script>
