<template>
    <div class="flex flex-col items-start space-y-4">
        <Avatar
            size="h-24 w-24"
            :src="imageData"
            alt="Change Avatar"
            roundness="rounded"
            show-action
            @click="fileInputRef?.click()"
        />
        <input
            id="avatar"
            ref="fileInputRef"
            name="file-upload"
            type="file"
            :accept="normalisedAccept"
            :aria-invalid="hasError"
            :aria-describedby="`${id}-error`"
            class="sr-only"
            @change="handleInput"
        />
        <p v-if="hasHelperText" class="mt-2 text-center text-xs">
            <slot name="helperText" :supported="Object.values(accepts).join(', ')" />
        </p>
        <p v-if="hasError" :id="`${id}-error`" class="mt-2 text-center text-sm text-red-600">
            {{ computedError }}
        </p>
    </div>
</template>

<script lang="ts" setup>
const props = withDefaults(
    defineProps<{
        id: string
        modelValue: any
        detectErrors?: boolean
        error?: string
        src?: string
    }>(),
    { modelValue: null, detectErrors: true, error: undefined, src: undefined },
)

const emit = defineEmits(['update:modelValue'])

const fileInputRef = ref<HTMLInputElement>()
const imageData = ref<any>(props.src === undefined ? useUser.value.avatar : props.src)

const slots = useSlots()

const computedError = computed(() => {
    return props.detectErrors ? usePage().props.errors[props.id] : props.error
})
const hasHelperText = computed(() => (slots.helperText ? !!slots.helperText() : false))
const hasError = computed(() => computedError.value !== undefined && computedError.value !== '')

const accepts = {
    'image/gif': 'GIF',
    'image/jpeg': 'JPEG/JPG',
    'image/png': 'PNG',
    'image/webp': 'WEBP',
}
const normalisedAccept = computed(() => {
    return Object.keys(accepts).join(',')
})

function handleInput() {
    if (fileInputRef.value?.files && fileInputRef.value?.files[0]) {
        const reader = new FileReader()

        reader.onload = (e) => {
            imageData.value = e.target?.result
        }

        reader.readAsDataURL(fileInputRef.value?.files[0])

        emit('update:modelValue', fileInputRef.value?.files[0])
    }
}
</script>
