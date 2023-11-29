<template>
    <div
        :class="[
            'flex',
            { 'flex-col': labelPosition === 'top', 'flex-row': labelPosition === 'left' },
        ]"
    >
        <div class="flex items-center justify-between">
            <label
                v-if="!hideLabel"
                :class="[
                    'form-label',
                    {
                        'w-full': labelPosition === 'top',
                        'px-0': labelPosition === 'top',
                        'text-left': labelAlignment === 'left',
                        'text-right': labelAlignment === 'right',
                        required,
                        error: hasError,
                    },
                ]"
                :for="labelFor"
                :style="`width: ${labelWidth}%`"
            >
                <span class="mr-1" v-text="label" />
            </label>

            <slot name="actions" />
        </div>
        <div class="flex-1">
            <slot name="input" />
            <div v-if="caption" class="mt-2 text-sm font-normal text-gray-400" v-text="caption" />
        </div>
    </div>
</template>

<script lang="ts" setup>
withDefaults(
    defineProps<{
        caption?: string
        hideLabel?: boolean
        label?: string
        labelAlignment?: 'left' | 'right'
        labelFor?: string
        labelPosition?: 'left' | 'top'
        labelWidth?: number
        required?: boolean
        hasError?: boolean
    }>(),
    {
        caption: undefined,
        hideLabel: false,
        label: undefined,
        labelAlignment: 'left',
        labelFor: undefined,
        labelPosition: 'top',
        labelWidth: 33,
        required: false,
        hasError: false,
    },
)
</script>
