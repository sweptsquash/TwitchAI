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
            <div
                class="relative rounded"
                :class="{ 'shadow-sm': !hideInput, 'dp-hide-input': hideInput }"
            >
                <Datepicker
                    :id="id"
                    :name="name ?? id"
                    :model-value="modelValue"
                    :model-type="modelType"
                    :model-auto="modelAuto"
                    :format="format"
                    :range="range"
                    :partial-range="partialRange"
                    :auto-apply="autoApply"
                    :auto-range="autoRange"
                    :multi-calendars="multiCalendars"
                    :month-picker="monthPicker"
                    :enable-time-picker="enableTimePicker"
                    :time-picker="timePicker"
                    :year-picker="yearPicker"
                    :week-picker="weekPicker"
                    :text-input="textInput"
                    :inline="inline"
                    :multi-dates="multiDates"
                    :flow="flow"
                    :utc="utc"
                    :vertical="vertical"
                    :timezone="timezone"
                    :clearable="clearable"
                    :placeholder="placeholder"
                    :autocomplete="autocomplete"
                    :required="required"
                    :disabled="disabled"
                    :disabled-dates="disabledDates"
                    :filters="filters"
                    :readonly="readonly"
                    :min-date="minDate"
                    :max-date="maxDate"
                    :state="!hasError ? undefined : false"
                    @update:model-value="handleInput"
                    @text-submit="(e: any) => emit('text-submit', e)"
                    @open="(e: any) => emit('open', e)"
                    @closed="(e: any) => emit('closed', e)"
                    @cleared="(e: any) => emit('cleared', e)"
                    @focus="(e: any) => emit('focus', e)"
                    @blur="(e: any) => emit('blur', e)"
                />
            </div>
            <p v-if="hasHelperText" class="mt-2 text-sm text-gray-400">
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
import Datepicker from '@vuepic/vue-datepicker'

const props = withDefaults(
    defineProps<{
        id: string
        name?: string
        modelType?: string
        format?: string
        range?: boolean
        partialRange?: boolean
        autoApply?: boolean
        autoRange?: number | string
        multiCalendars?: boolean | number | string
        monthPicker?: boolean
        enableTimePicker?: boolean
        timePicker?: boolean
        yearPicker?: boolean
        weekPicker?: boolean
        textInput?: boolean
        inline?: boolean
        multiDates?: boolean
        flow?: ('month' | 'year' | 'calendar' | 'time' | 'minutes' | 'hours' | 'seconds')[]
        utc?: boolean | 'preserve'
        vertical?: boolean
        modelAuto?: boolean
        timezone?: string
        clearable?: boolean
        minDate?: Date | string
        maxDate?: Date | string
        placeholder?: string
        autocomplete?: string
        required?: boolean
        disabled?: boolean
        disabledDates?: any[]
        filters?: any
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
    }>(),
    {
        name: undefined,
        modelType: 'yyyy-MM-dd',
        format: 'dd/MM/yyyy',
        range: false,
        partialRange: false,
        autoApply: false,
        autoRange: undefined,
        multiCalendars: false,
        monthPicker: false,
        enableTimePicker: false,
        timePicker: false,
        yearPicker: false,
        weekPicker: false,
        textInput: false,
        inline: false,
        multiDates: false,
        flow: undefined,
        utc: false,
        vertical: false,
        modelAuto: false,
        timezone: undefined,
        clearable: true,
        minDate: undefined,
        maxDate: undefined,
        placeholder: 'dd/mm/yyyy',
        autocomplete: 'off',
        required: false,
        disabled: false,
        disabledDates: undefined,
        filters: undefined,
        readonly: false,
        hideLabel: false,
        hideInput: false,
        label: 'Label',
        labelPosition: 'top',
        labelAlignment: 'left',
        labelWidth: 33,
        caption: undefined,
        error: undefined,
    },
)

const emit = defineEmits([
    'update:modelValue',
    'keyup',
    'blur',
    'focus',
    'text-submit',
    'open',
    'closed',
    'cleared',
    'change',
])

const slots = useSlots()

const hasHelperText = computed(() => (slots.helperText ? !!slots.helperText() : false))
const hasError = computed(() => props.error !== undefined && props.error !== '')

function handleInput(value: any) {
    emit('update:modelValue', value)
    emit('change', value)
}
</script>
