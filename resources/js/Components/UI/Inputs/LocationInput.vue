<template>
    <SelectInput
        :id="id"
        :label="label"
        :mode="multiple ? 'tags' : 'single'"
        :no-options-text="noOptionsText"
        :loading="data.isSearching"
        :placeholder="placeholder"
        :model-value="modelValue"
        :options="data.locations"
        :hide-label="hideLabel"
        :filter-results="false"
        :value-prop="valueProp"
        :label-prop="labelProp"
        :required="required"
        searchable
        object
        @search-change="searchLocation"
        @change="handleChange"
    />
</template>

<script setup lang="ts">
import { reactive, onMounted } from 'vue'

interface DataPayload {
    locations: Array<any>
    isSearching: boolean
    autocomplete: any
}

const props = withDefaults(
    defineProps<{
        id: string
        label?: string
        modelValue: any
        required?: boolean
        multiple?: boolean
        labelProp?: string
        valueProp?: string
        hideLabel?: boolean
        placeholder?: string
        noOptionsText?: string
    }>(),
    {
        labelProp: 'id',
        multiple: false,
        hideLabel: false,
        label: undefined,
        valueProp: 'value',
        placeholder: undefined,
        noOptionsText: 'Start typing...',
    },
)

const emit = defineEmits(['update:modelValue', 'change'])

const data: DataPayload = reactive({
    isSearching: false,
    autocomplete: null,
    locations: [],
})

onMounted(() => {
    useGoogle()
        .load()
        .then((google) => {
            data.autocomplete = new google.maps.places.AutocompleteService()
        })
})

async function searchLocation(input: any) {
    if (!input || !data.autocomplete) return

    data.isSearching = true

    const response = await data.autocomplete.getPlacePredictions({ input })

    data.locations = response.predictions.map((x: any) => {
        return {
            [props.valueProp]: x.place_id,
            [props.labelProp]: x.description,
        }
    })

    data.isSearching = false
}

function handleChange(value: any) {
    emit('update:modelValue', value)
    emit('change', value)
}
</script>

<style scoped></style>
