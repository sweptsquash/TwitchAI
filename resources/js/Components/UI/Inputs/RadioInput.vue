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
    >
        <template #actions>
            <slot name="actions" />
        </template>

        <template #input>
            <fieldset>
                <div
                    class="mb-2 space-y-2 sm:flex sm:items-center"
                    :class="{
                        'flex-row sm:space-x-10 sm:space-y-0': optionsAlignment === 'row',
                        'flex-col': optionsAlignment === 'col',
                    }"
                >
                    <div
                        v-for="option in options"
                        :key="option.id"
                        class="flex items-center"
                        :class="{ 'w-full': optionsAlignment === 'col' }"
                    >
                        <input
                            :id="option.id"
                            :name="id"
                            type="radio"
                            :required="required"
                            :checked="option.id === modelValue?.id"
                            class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"
                            @change="handleChange(option)"
                        />
                        <label
                            :for="option.id"
                            class="ml-3 block text-sm font-medium leading-6 text-gray-900"
                            v-text="option.name"
                        />
                    </div>
                </div>
            </fieldset>
        </template>
    </BaseInput>
</template>

<script setup lang="ts">
type RadioOption = {
    id: string
    name: string
}

const props = withDefaults(
    defineProps<{
        id: string
        label?: string
        modelValue: any
        caption?: string
        required?: boolean
        hideLabel?: boolean
        labelWidth?: number
        options: Array<RadioOption>
        labelPosition?: 'left' | 'top'
        labelAlignment?: 'left' | 'right'
        optionsAlignment?: 'row' | 'col'
    }>(),
    {
        label: undefined,
        labelWidth: 33,
        required: false,
        hideLabel: false,
        caption: undefined,
        labelPosition: 'top',
        labelAlignment: 'left',
        optionsAlignment: 'row',
    },
)

const emits = defineEmits(['update:modelValue', 'change'])

function handleChange(option: RadioOption) {
    emits('update:modelValue', option)
    emits('change', option)
}
</script>

<style scoped></style>
