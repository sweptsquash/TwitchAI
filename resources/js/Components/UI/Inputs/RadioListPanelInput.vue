<template>
    <BaseInput
        :label-alignment="labelAlignment"
        :label-position="labelPosition"
        :label-width="labelWidth"
        :hide-label="hideLabel"
        :has-error="hasError"
        :required="required"
        :caption="caption"
        :label-for="id"
        :label="label"
    >
        <template #input>
            <RadioGroup :model-value="modelValue" @update:model-value="handleChange">
                <div class="-space-y-px rounded-md bg-white">
                    <RadioGroupOption
                        v-for="(option, i) in options"
                        :key="option.name"
                        v-slot="{ checked }"
                        as="template"
                        :value="option.id"
                    >
                        <div
                            :class="[
                                'relative flex cursor-pointer items-center border p-4 focus:outline-none',
                                i === options.length - 1 ? 'rounded-bl-md rounded-br-md' : '',
                                i === 0 ? 'rounded-tl-md rounded-tr-md' : '',
                                checked ? 'z-10 ' : 'border-slate-200',
                            ]"
                        >
                            <div
                                class="flex h-[1.2rem] w-[1.2rem] items-center justify-center rounded border border-slate-400 text-white"
                                :class="checked ? 'bg-black' : 'bg-transparent'"
                            >
                                <span class="text-sm">✓</span>
                            </div>

                            <span class="ml-3 flex flex-col">
                                <RadioGroupLabel
                                    class="'block text-slate-900', text-sm font-medium"
                                    as="span"
                                >
                                    {{ option.name }}
                                </RadioGroupLabel>
                                <RadioGroupDescription
                                    as="span"
                                    :class="[
                                        checked ? 'text-slate-700' : 'text-slate-400',
                                        'block text-sm',
                                    ]"
                                >
                                    {{ option.description }}
                                </RadioGroupDescription>
                            </span>

                            <span
                                v-if="option.note"
                                :class="checked ? 'text-black' : 'text-slate-400'"
                                class="ml-auto text-sm font-medium"
                                v-text="option.note"
                            />
                        </div>
                    </RadioGroupOption>
                </div>
            </RadioGroup>
        </template>
    </BaseInput>
</template>

<script setup lang="ts">
import {
    RadioGroup,
    RadioGroupLabel,
    RadioGroupOption,
    RadioGroupDescription,
} from '@headlessui/vue'

const props = withDefaults(
    defineProps<{
        id: string
        error?: string
        modelValue: any
        caption?: string
        required?: boolean
        options: Array<{ id: string; name: string; description: string; note?: string }>

        labelAlignment?: 'left' | 'right'
        labelPosition?: 'left' | 'top'
        labelWidth?: number
        hideLabel?: boolean
        label?: string
    }>(),
    {
        caption: undefined,
        required: false,

        labelAlignment: 'left',
        labelPosition: 'top',
        labelWidth: 33,
        hideLabel: false,
        label: 'Label',

        detectErrors: false,
        error: undefined,
    },
)

const emits = defineEmits(['update:modelValue'])

const hasError = computed(() => computedError.value !== undefined && computedError.value !== '')
const computedError = computed(() => {
    return props.detectErrors ? usePage().props.errors[props.id] : props.error
})

function handleChange(value: any) {
    emits('update:modelValue', value)
}
</script>

<style scoped></style>
