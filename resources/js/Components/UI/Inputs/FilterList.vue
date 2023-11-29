<template>
    <template v-if="!withoutWrapper">
        <div
            v-if="mappedFilters.length > 0"
            class="mt-4 flex w-full flex-col gap-y-2 sm:flex-row sm:gap-x-2 sm:gap-y-0"
        >
            <div
                v-for="(filter, index) in mappedFilters"
                :key="`filter${index}Value`"
                class="flex items-center gap-x-2 rounded bg-slate-200 px-2 py-1 text-sm"
                :class="{ 'cursor-pointer hover:bg-slate-300': clearable }"
                @click="
                    clearable ? emits('remove-filter', buildQuery(filter.query, true)) : undefined
                "
            >
                <div>
                    {{ filter.name }} {{ filter.operator }}
                    <strong>{{ filter.value }}</strong>
                </div>
                <XMarkIcon v-if="clearable" class="h-4 w-4" />
            </div>
        </div>
    </template>
    <template v-else>
        <div
            v-for="(filter, index) in mappedFilters"
            :key="`filter${index}Value`"
            class="flex items-center gap-x-2 rounded bg-slate-200 px-2 py-1 text-sm"
            :class="{ 'cursor-pointer hover:bg-slate-300': clearable }"
            @click="clearable ? emits('remove-filter', buildQuery(filter.query, true)) : undefined"
        >
            <div>
                {{ filter.name }} {{ filter.operator }}
                <strong>{{ filter.value }}</strong>
            </div>
            <XMarkIcon v-if="clearable" class="h-4 w-4" />
        </div>
    </template>
</template>

<script lang="ts" setup>
import { XMarkIcon } from '@heroicons/vue/24/solid'

const props = withDefaults(
    defineProps<{
        filters: Array<{
            query: string
            name: string
        }>
        clearable?: boolean
        withoutWrapper?: boolean
    }>(),
    { clearable: true, withoutWrapper: false },
)

const emits = defineEmits(['remove-filter'])

// @ts-ignore
const mappedFilters: Array<{ query: string; name: string; operator: string; value: string }> =
    computed(() => {
        const query = useSearchParams() ?? {}

        if (query && query?.filter) {
            return Object.keys(query.filter)
                .map((parameter) => {
                    const filter = props.filters.find(
                        (filterItem) => filterItem.query === parameter,
                    )

                    if (filter) {
                        return {
                            query: filter.query,
                            name: filter.name,
                            operator: query?.operator[filter.query]?.replace('_', ' ') ?? 'is',
                            value:
                                typeof query.filter[parameter] === 'object' &&
                                'label' in query.filter[parameter]
                                    ? Array.isArray(query.filter[parameter]['label'])
                                        ? query.filter[parameter]['label'].join(', ')
                                        : query.filter[parameter]['label']
                                    : Array.isArray(query.filter[parameter])
                                      ? query.filter[parameter].join(', ')
                                      : query.filter[parameter],
                        }
                    }

                    return null
                })
                .filter((filter) => filter !== null)
        }

        return []
    })

function buildQuery(value: any, remove: boolean = false) {
    let parameters = useBuildQuery() ?? {}

    if (remove) {
        delete parameters.filter[value]
        delete parameters.operator[value]
    }

    return parameters
}
</script>
