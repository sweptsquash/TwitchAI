<template>
    <div>
        <BaseInput
            v-if="!multiple"
            :label="label"
            :label-for="id"
            :hide-label="hideLabel"
            :label-width="labelWidth"
            :label-position="labelPosition"
            :label-alignment="labelAlignment"
            :caption="
                accepts
                    ? `${Object.values(accepts).join(', ')} up to ${usePrettyBytes(maxSizeInBytes)}`
                    : undefined
            "
            :required="required"
            :has-error="hasError"
        >
            <template #actions>
                <slot name="actions" />
            </template>

            <template #input>
                <label class="flex cursor-pointer text-sm" :for="id">
                    <span
                        class="flex items-center rounded-l border border-r-0 border-gray-300 px-3 py-1"
                    >
                        <ArrowUpTrayIcon class="mr-2 h-4 w-4" />
                        Browse
                    </span>
                    <span
                        class="form-input overflow-hidden whitespace-nowrap rounded-l-none text-gray-600"
                    >
                        {{
                            data.files.length === 0
                                ? `Select new file to upload`
                                : data.files[0].name
                        }}
                    </span>
                    <input
                        :id="id"
                        ref="fileInputRef"
                        name="file-upload"
                        type="file"
                        :accept="normalisedAccept"
                        :aria-invalid="hasError"
                        :aria-describedby="`${id}-error`"
                        class="sr-only"
                        @change="handleInput"
                    />
                </label>
                <p v-if="hasError && error" :id="`${id}-error`" class="mt-2 text-sm text-red-600">
                    {{ error }}
                </p>
            </template>
        </BaseInput>
        <div v-else>
            <label
                v-if="!hideLabel"
                class="form-label inline-flex w-full items-center justify-between pb-1"
                :for="id"
            >
                <span :class="required && 'required'" v-text="label" />

                <slot name="actions" />
            </label>
            <label
                ref="dropZoneRef"
                :for="id"
                class="flex cursor-pointer justify-center rounded-md border-2 border-dashed border-gray-300 bg-gray-100 px-6 py-5 hover:bg-gray-200"
            >
                <span class="space-y-1 text-center">
                    <svg
                        v-if="showIcon"
                        class="mx-auto h-12 w-12 text-gray-400"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 48 48"
                        aria-hidden="true"
                    >
                        <path
                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                    <span class="flex text-sm font-semibold text-gray-600">
                        <span>{{ !isOverDropZone ? caption : dragDropText }}</span>
                        <input
                            :id="id"
                            ref="fileInputRef"
                            name="file-upload"
                            type="file"
                            :multiple="multiple"
                            :accept="normalisedAccept"
                            class="sr-only"
                            @change="handleInput"
                        />
                    </span>
                    <span v-if="accepts && !Array.isArray(accepts)" class="text-xs text-gray-500">
                        {{ Object.values(accepts).join(', ') }} up to
                        {{ usePrettyBytes(maxSizeInBytes) }}
                    </span>
                </span>
            </label>
            <div v-if="data.files.length > 0" class="mt-2 rounded border border-gray-200 text-sm">
                <FileItem
                    v-for="(file, index) in data.files"
                    :key="index"
                    :files-count="data.files.length"
                    :errors="errors"
                    :index="index"
                    :file="file"
                    @delete-file="removeFile(Number(index))"
                />
            </div>
            <div
                v-if="uploadedFiles.length > 0"
                class="mt-2 rounded border border-gray-200 text-sm"
            >
                <FileItem
                    v-for="(file, index) in uploadedFiles"
                    :key="index"
                    :files-count="uploadedFiles.length"
                    :errors="errors"
                    :index="index"
                    :file="file"
                    already-uploaded
                />
            </div>
            <p v-if="hasHelperText" class="mt-2 text-xs text-gray-400">
                <slot name="helperText"></slot>
            </p>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { TYPE as NotificationType, useToast } from 'vue-toastification'
import { ArrowUpTrayIcon } from '@heroicons/vue/24/outline'
import { useDropZone } from '@vueuse/core'

const props = withDefaults(
    defineProps<{
        id: string
        modelValue: any
        uploadedFiles?: any
        label?: string
        labelPosition?: 'top' | 'left'
        labelAlignment?: 'left' | 'right'
        labelWidth?: number
        hideLabel?: boolean
        caption?: string
        dragDropText?: string
        accepts?: object | Array<string> | null
        maxSizeInBytes?: number
        multiple?: boolean
        required?: boolean
        detectErrors?: boolean
        error?: string | null
        errors?: any
        showIcon?: boolean
    }>(),
    {
        id: 'files',
        modelValue: [],
        label: 'Files',
        uploadedFiles: [],
        labelPosition: 'top',
        labelAlignment: 'left',
        labelWidth: 33,
        hideLabel: false,
        caption: 'Upload your file(s) or drag and drop',
        dragDropText: 'Drop your file(s) to upload',
        accepts: () => ({
            'image/jpeg': 'JPG',
            'image/png': 'PNG',
            'application/pdf': 'PDF',
            'application/msword': 'DOC',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'DOCX',
        }),
        maxSizeInBytes: 1000 * 1000 * 64,
        multiple: false,
        required: false,
        error: null,
        errors: () => [],
        detectErrors: false,
        showIcon: false,
    },
)

const emit = defineEmits(['update:modelValue', 'change', 'deleteFile'])
const toast = useToast()
const slots = useSlots()

const dropZoneRef = ref<HTMLDivElement>()
const fileInputRef = ref<HTMLInputElement>()

const data = reactive<{ files: any }>({ files: [] })

const hasHelperText = computed(() => (slots.helperText ? !!slots.helperText() : false))
const hasError = computed(() => computedError.value !== undefined && computedError.value !== '')
const computedError = computed(() => {
    return props.detectErrors ? usePage().props.errors[props.id] : props.error
})

const normalisedAccept = computed(() => {
    if (Array.isArray(props.accepts)) {
        return props.accepts.join(',')
    }

    return Object.keys(props.accepts ?? []).join(',')
})

const { isOverDropZone } = useDropZone(dropZoneRef, onDrop)

function onDrop(files: File[] | null) {
    selectFiles(files)
}

function handleInput() {
    selectFiles(fileInputRef.value?.files)

    // @ts-ignore
    fileInputRef.value.value = null
}

function selectFiles(files?: FileList | File[] | null) {
    if (!files) {
        return
    }

    const newFiles = [...Array.from(files)].filter(
        (x) => !props.maxSizeInBytes || x.size <= props.maxSizeInBytes,
    )

    if (props.maxSizeInBytes && files.length > newFiles.length) {
        toast(
            `Some files were omitted, because they exceed the maximum size of ${usePrettyBytes(
                props.maxSizeInBytes,
            )}`,
            {
                type: NotificationType.INFO,
                timeout: 5000,
            },
        )
    }

    data.files = props.multiple ? data.files.concat([...newFiles]) : [[...newFiles].at(0)][0]

    updateModelValue()
}

function removeFile(index: number) {
    data.files.splice(index, 1)
    updateModelValue()
}

function updateModelValue() {
    emit('update:modelValue', data.files)
    emit('change', data.files)
}
</script>
