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
            <div class="flex-1">
                <Multiselect
                    v-if="isMounted"
                    :id="id"
                    :mode="mode"
                    :classes="getClasses()"
                    :options="options"
                    :label="labelProp"
                    :value="modelValue"
                    :can-clear="canClear"
                    :can-deselect="canDeselect"
                    :create-option="canCreate"
                    :value-prop="valueProp"
                    :searchable="searchable"
                    :loading="loading"
                    :caret="!hideInput"
                    :no-options-text="noOptionsText"
                    :filter-results="filterResults"
                    :placeholder="placeholder"
                    :hide-selected="hideSelected"
                    :multiple-label="multipleLabel"
                    :object="object"
                    :close-on-select="closeOnSelect"
                    :disabled="disabled"
                    :resolve-on-load="resolveOnLoad"
                    :min-chars="minChars"
                    :delay="delay"
                    :required="required"
                    :groups="groups"
                    :clear-on-select="clearOnSelect"
                    :group-label="groupLabel"
                    :group-select="groupSelect"
                    :group-options="groupOptions"
                    :group-hide-empty="groupHideEmpty"
                    :append-to-body="appendToBody"
                    :append-to="appendTo"
                    @change="handleChange"
                    @search-change="searchChange"
                >
                    <template #singlelabel="{ value }">
                        <slot name="singlelabel" :value="value">
                            <div
                                class="multiselect-single-label text-sm"
                                :class="{ 'pl-0': hideInput }"
                            >
                                <span class="multiselect-single-label-text">
                                    {{ value[labelProp] }}
                                </span>
                            </div>
                        </slot>
                    </template>
                    <template #option="{ option }">
                        <slot name="item" :option="option">
                            <div class="flex flex-col">
                                <p v-text="option[labelProp]"></p>
                                <span
                                    v-if="option[captionProp] !== undefined"
                                    class="text-gray-400"
                                    v-text="option[captionProp]"
                                ></span>
                            </div>
                        </slot>
                    </template>
                </Multiselect>
                <p v-if="hasHelperText" class="mt-2 text-xs text-gray-400">
                    <slot name="helperText"></slot>
                </p>
                <p
                    v-if="hasError && computedError"
                    :id="`${id}-error`"
                    class="mt-2 text-sm text-red-600"
                >
                    {{ computedError }}
                </p>
            </div>
        </template>
    </BaseInput>
</template>

<script lang="ts" setup>
import Multiselect from '@vueform/multiselect'

const props = withDefaults(
    defineProps<{
        id: string
        modelValue: any
        required?: boolean
        mode?: 'single' | 'multiple' | 'tags'
        options: any
        valueProp?: string
        labelProp?: string
        captionProp?: string
        noOptionsText?: string
        multipleLabel?: any
        hideSelected?: boolean
        canClear?: boolean
        clearOnSelect?: boolean
        closeOnSelect?: boolean
        canDeselect?: boolean
        canCreate?: boolean
        searchable?: boolean
        placeholder?: string
        loading?: boolean
        filterResults?: boolean
        object?: boolean
        hideLabel?: boolean
        hideInput?: boolean
        label?: string
        labelPosition?: 'left' | 'top'
        labelAlignment?: 'left' | 'right'
        labelWidth?: number
        caption?: string
        disabled?: boolean
        error?: string
        resolveOnLoad?: boolean
        minChars?: number
        delay?: number
        detectErrors?: boolean
        groups?: boolean
        groupLabel?: string
        groupOptions?: string
        groupSelect?: boolean
        groupHideEmpty?: boolean
        appendToBody?: boolean
        appendTo?: string
    }>(),
    {
        required: false,
        mode: 'single',
        valueProp: 'id',
        labelProp: 'name',
        captionProp: 'caption',
        noOptionsText: 'This list is empty',
        multipleLabel: undefined,
        hideSelected: false,
        canClear: false,
        clearOnSelect: true,
        closeOnSelect: true,
        canDeselect: false,
        canCreate: false,
        searchable: false,
        placeholder: 'Select option',
        loading: false,
        filterResults: true,
        object: false,
        hideLabel: false,
        hideInput: false,
        label: 'Label',
        labelPosition: 'top',
        labelAlignment: 'left',
        labelWidth: 33,
        caption: undefined,
        disabled: false,
        error: undefined,
        resolveOnLoad: false,
        minChars: 0,
        delay: -1,
        detectErrors: false,
        groups: false,
        groupLabel: 'label',
        groupOptions: 'options',
        groupSelect: true,
        groupHideEmpty: false,
        appendToBody: false,
        appendTo: undefined,
    },
)

const emit = defineEmits(['update:modelValue', 'change', 'search-change'])

const slots = useSlots()

const hasHelperText = computed(() => (slots.helperText ? !!slots.helperText() : false))

const hasError = computed(() => computedError.value !== undefined && computedError.value !== '')
const computedError = computed(() => {
    return props.detectErrors ? usePage().props.errors[props.id] : props.error
})

const isMounted = ref(false)

onMounted(() => {
    isMounted.value = true
})

function getClasses() {
    return {
        container: [
            'multiselect-container relative mx-auto w-full flex items-center justify-end box-border cursor-pointer text-base outline-none leading-5 min-h-[32px]',
            hasError.value ? 'has-error' : '',
        ].join(' '),
        containerActive: 'is-active',
        containerDisabled: 'is-disabled',
        caret: 'bg-multiselect-caret bg-center bg-no-repeat w-2.5 h-4 py-px box-content mr-3.5 relative z-10 opacity-40 flex-shrink-0 flex-grow-0 transition-transform transform pointer-events-none rtl:mr-0 rtl:ml-3.5',

        dropdown:
            'max-h-60 absolute -bottom-1 border border-slate-300 -mt-px overflow-y-auto z-50 bg-white flex flex-col rounded shadow-sm text-sm' +
            (props.appendTo || props.appendToBody
                ? ''
                : 'transform translate-y-full -left-px -right-px'),
        dropdownTop: '-translate-y-full top-px bottom-auto rounded',
        dropdownHidden: 'hidden',

        singleLabel: 'multiselect-single-label text-sm ' + (props.hideInput ? 'pl-0' : ''),
        multipleLabel: 'multiselect-multiple-label text-sm ' + (props.hideInput ? 'pl-0' : ''),
        options: '',
        option: 'text-sm px-3 py-1.5',
        optionPointed: 'bg-gray-50',
        optionSelected: 'bg-neon bg-opacity-30 text-black',
        optionDisabled: 'text-gray-300 cursor-not-allowed',
        optionSelectedPointed: 'bg-neon bg-opacity-50',
        search: 'border-0 bg-transparent focus:ring-0 m-0 w-full text-sm py-0',
        tagsSearch:
            'absolute inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full',
        tags: 'multiselect-tags ' + (props.hideInput ? 'pl-0' : ''),
        tag: 'bg-black text-white text-sm font-semibold py-0.5 pl-2 rounded mr-1 mb-1 flex items-center whitespace-nowrap rtl:pl-0 rtl:pr-2 rtl:mr-0 rtl:ml-1',
        tagDisabled: 'pr-2 opacity-50 rtl:pl-2',
        tagRemoveIcon: 'bg-multiselect-remove invert bg-center bg-no-repeat inline-block w-3 h-3',
        placeholder: 'multiselect-placeholder text-sm ' + (props.hideInput ? 'pl-0' : ''),
    }
}

function handleChange(value: any) {
    emit('update:modelValue', value)
    emit('change', value)
}

function searchChange(value: any) {
    emit('search-change', value)
}
</script>

<style scoped></style>
