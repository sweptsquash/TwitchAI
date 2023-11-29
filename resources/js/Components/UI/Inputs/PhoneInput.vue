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
        <template #input>
            <div class="relative flex rounded" :class="{ 'shadow-sm': !hideInput }">
                <div class="absolute inset-y-0 left-0 flex items-center">
                    <select
                        id="phone_prefix"
                        name="phone_prefix"
                        autocomplete="country"
                        :class="[
                            'form-input-phone-select',
                            {
                                error: hasError,
                                'cursor-not-allowed': readonly,
                                'hide-input': hideInput,
                            },
                        ]"
                        :disabled="disabled || readonly"
                        @change="handlePhoneCountryInput"
                    >
                        <option :value="null" disabled :selected="phoneCountry === null" />
                        <option
                            v-for="(country, index) in countries"
                            :key="`country${index}`"
                            :value="country.code"
                            :selected="phoneCountry === country.code"
                        >
                            {{ country.code }} (+{{ country.prefix }})
                        </option>
                    </select>
                </div>
                <input
                    :id="id"
                    type="text"
                    :name="name ?? id"
                    :placeholder="placeholder"
                    :autocomplete="autocomplete"
                    :required="required"
                    :disabled="disabled"
                    :readonly="readonly"
                    :value="phone"
                    :class="[
                        'form-input',
                        {
                            'pr-10': hasError,
                            error: hasError,
                            'hide-input pl-20': hideInput,
                            'pl-28': !hideInput,
                        },
                    ]"
                    :aria-invalid="hasError"
                    :aria-describedby="`${name ?? id}-error`"
                    @blur="(e) => emit('blur', e)"
                    @focus="(e) => emit('focus', e)"
                    @input="handlePhoneInput"
                    @keyup="(e) => emit('keyup', e)"
                />
                <div
                    v-if="hasError"
                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3"
                >
                    <ExclamationCircleIcon class="h-5 w-5 text-red-500" aria-hidden="true" />
                </div>
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
import { ExclamationCircleIcon } from '@heroicons/vue/20/solid'

const props = withDefaults(
    defineProps<{
        id: string
        name?: string
        autocomplete?: string
        placeholder?: string
        required?: boolean
        disabled?: boolean
        readonly?: boolean
        hideLabel?: boolean
        hideInput?: boolean
        label?: string
        labelPosition?: 'left' | 'top'
        labelAlignment?: 'left' | 'right'
        labelWidth?: number
        caption?: string
        error?: string
        phone?: string | null
        phoneCountry?: string | null
        countries: any
    }>(),
    {
        name: undefined,
        autocomplete: 'off',
        placeholder: undefined,
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
        phone: null,
        phoneCountry: null,
    },
)

const emit = defineEmits([
    'update:phone',
    'update:phoneCountry',
    'blur',
    'focus',
    'keyup',
    'updatePhone',
    'updateCountry',
])

const slots = useSlots()

const hasHelperText = computed(() => (slots.helperText ? !!slots.helperText() : false))
const hasError = computed(() => props.error !== undefined && props.error !== '')

function handlePhoneCountryInput(event: any) {
    emit('update:phoneCountry', event.target.value)
    emit('updateCountry', event.target.value)
}

function handlePhoneInput(event: any) {
    emit('update:phone', event.target.value)
    emit('updatePhone', event.target.value)
}
</script>
