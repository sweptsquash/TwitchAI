<template>
    <div class="relative">
        <div
            class="flex cursor-pointer items-center gap-x-2 whitespace-nowrap rounded border border-gray-300 bg-white px-3 py-1 text-sm font-medium shadow-sm hover:bg-gray-50"
            @click="isFilterOpen = !isFilterOpen"
        >
            {{ filter.label }}
            <ChevronDownIcon v-if="!isFilterOpen" class="h-4 w-4" />
            <ChevronUpIcon v-else class="h-4 w-4" />
        </div>

        <div
            v-if="isFilterOpen"
            v-on-click-outside="close"
            class="absolute left-0 top-full z-[21] mt-1 w-full rounded border border-gray-300 bg-white p-2 shadow-sm sm:w-auto sm:min-w-[250px]"
        >
            <RadioInput
                id="options"
                v-model="filter.option"
                :options="radioOptions"
                options-alignment="col"
                hide-label
            />

            <slot
                name="input"
                :field-id="`${id}FilterValue`"
                :value="filter.value"
                :placeholder="placeholder"
                :update-input-value="updateInputValue"
            >
                <TextInput
                    :id="`${id}FilterValue`"
                    v-model="filter.value"
                    :placeholder="placeholder"
                    hide-label
                />
            </slot>

            <div class="mt-2 flex flex-col space-y-2">
                <button type="button" class="btn-primary w-full" @click="applyFilter">
                    Apply filter
                </button>
                <button
                    v-if="clearable"
                    type="button"
                    class="btn-outline w-full"
                    @click="removeFilter"
                >
                    Remove filter
                </button>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ChevronDownIcon, ChevronUpIcon } from '@heroicons/vue/24/solid'

const props = withDefaults(
    defineProps<{
        id: string
        placeholder: string
        queryKey: string
        valueKey?: string
        labelKey?: string
        hideOperators?: Array<string>
        isTag?: boolean
        clearable?: boolean
    }>(),
    {
        valueKey: 'value',
        labelKey: 'id',
        hideOperators: () => [],
        isTag: false,
        clearable: true,
    },
)

const emits = defineEmits(['apply', 'remove'])

const isFilterOpen = ref(false)

const radioOptions = computed<Array<{ id: string; name: string }>>(() => {
    return (
        [
            {
                id: 'contains',
                name: 'Contains',
            },
            {
                id: 'is',
                name: 'Is',
            },
            {
                id: 'is_not',
                name: 'Is not',
            },
        ].filter((option) => !props.hideOperators.includes(option.id)) ?? []
    )
})

const filter = reactive<{
    option:
        | {
              id: string
              name: string
          }
        | undefined
    value: any
    label: string | null
}>({
    option: radioOptions.value.at(0),
    value: [],
    label: props.placeholder,
})

onBeforeMount(() => {
    const optionOperator = useSearchParams(`operator.${props.queryKey}`) ?? null

    if (optionOperator) {
        filter.option =
            radioOptions.value.find((option) => option.id === optionOperator) ??
            radioOptions.value.at(0)
    }

    const parameterValue = useSearchParams(`filter.${props.queryKey}.value`)

    if (typeof parameterValue !== 'undefined') {
        if (typeof parameterValue === 'string') {
            filter.value = {
                [props.labelKey]: useSearchParams(`filter.${props.queryKey}.label`) ?? null,
                [props.valueKey]: parameterValue ?? null,
            }
        } else if (Object.keys(parameterValue).length > 0) {
            if (Array.isArray(parameterValue)) {
                filter.value = []

                useSearchParams(`filter.${props.queryKey}.value`)?.forEach(
                    (value: string | number, index: number) => {
                        filter.value.push({
                            [props.labelKey]:
                                useSearchParams(`filter.${props.queryKey}.label`)?.at(index) ??
                                null,
                            [props.valueKey]: value,
                        })
                    },
                )
            }
        }
    } else {
        if (props.isTag) {
            filter.value = useSearchParams(`filter.${props.queryKey}`)?.split(',')
        } else {
            filter.value = useSearchParams(`filter.${props.queryKey}`)
        }
    }
})

function updateInputValue(value: any) {
    if (typeof value === 'object' && !props.isTag) {
        if (Array.isArray(value)) {
            filter.value = []

            value.forEach((value, index) => {
                filter.value.push({
                    [props.labelKey]: value[props.labelKey],
                    [props.valueKey]: value[props.valueKey],
                })
            })
        } else {
            filter.value = {
                [props.labelKey]: value[props.labelKey],
                [props.valueKey]: value[props.valueKey],
            }
        }
    } else {
        filter.value = value
    }
}

function applyFilter() {
    if (isEmpty()) {
        return
    }

    let value: any = null

    if (Array.isArray(filter.value) && props.isTag) {
        value = filter.value.join(',')
    } else if (Array.isArray(filter.value)) {
        value = {
            label: [],
            value: [],
        }

        filter.value.forEach((item, index) => {
            value.label.push(item[props.labelKey])
            value.value.push(item[props.valueKey])
        })

        value.label = value.label.join(',')
        value.value = value.value.join(',')
    } else if (typeof filter.value === 'object') {
        value = { label: filter.value[props.labelKey], value: filter.value[props.valueKey] }
    } else {
        value = filter.value
    }

    emits('apply', buildQuery(value), props.queryKey)
}

function removeFilter() {
    if (isEmpty()) {
        return
    }

    emits('remove', buildQuery(filter.value, true), props.queryKey)

    filter.label = props.placeholder
    filter.value = []
    filter.option = radioOptions.value.at(0)
}

function isEmpty() {
    if (typeof filter.value === 'string' && filter.value === null) {
        return true
    } else if (Array.isArray(filter.value) && filter.value?.length === 0) {
        return true
    } else if (typeof filter.value === 'object' && Object.keys(filter.value).length === 0) {
        return true
    }

    return false
}

function buildQuery(value: any, remove: boolean = false) {
    let parameters = useBuildQuery() ?? {}

    if (parameters?.filter) {
        parameters['filter'] = {
            ...(parameters.filter ?? {}),
            [props.queryKey]: value,
        }
    } else {
        parameters['filter'] = {
            [props.queryKey]: value,
        }
    }

    if (filter.option) {
        parameters['operator'] = {
            ...(parameters.operator ?? {}),
            [props.queryKey]: filter.option?.id,
        }
    }

    if (remove) {
        delete parameters.filter[props.queryKey]
        delete parameters.operator[props.queryKey]
    }

    return parameters
}

function close() {
    setTimeout(() => {
        isFilterOpen.value = false
    }, 100)
}
</script>
