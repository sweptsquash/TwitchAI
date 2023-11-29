<template>
    <template v-if="editor">
        <EditorContent :model-value="modelValue" :editor="editor" />
    </template>
</template>

<script setup lang="ts">
import CandidateMention from '@/Components/UI/Inputs/TipTap/Extensions/CandidateMention'
import { Placeholder } from '@tiptap/extension-placeholder'
import { useEditor, EditorContent } from '@tiptap/vue-3'
import { Mention } from '@tiptap/extension-mention'
import { StarterKit } from '@tiptap/starter-kit'
import suggestion from './Utils/suggestion'

const props = withDefaults(
    defineProps<{
        atSuggestions?: Array<App.SuggestionItem>
        hashSuggestions?: Array<App.SuggestionItem>
        editorCallback?: Function
        placeholder?: string
        readonly?: boolean
        isEmpty?: boolean
        modelValue: any
        class?: string
        rawText?: any
    }>(),
    {
        placeholder: 'Type your message here...',
        hashSuggestions: undefined,
        editorCallback: undefined,
        atSuggestions: undefined,
        rawText: undefined,
        isEmpty: undefined,
        class: undefined,
        readonly: false,
    },
)
const emits = defineEmits(['update:modelValue', 'update:rawText', 'update:isEmpty', 'input'])

const editor = useEditor({
    editable: !props.readonly,
    extensions: [
        StarterKit.configure({
            heading: false,
        }),
        Placeholder.configure({
            placeholder: props.placeholder,
        }),
        Mention.configure({
            HTMLAttributes: {
                class: 'mention',
            },
            suggestion: suggestion(props.atSuggestions ?? []),
        }),
        CandidateMention.configure({
            HTMLAttributes: {
                class: 'mention candidate-mention',
            },
            suggestion: suggestion(props.hashSuggestions ?? []),
        }),
    ],
    editorProps: {
        attributes: {
            class: props.class,
        },
    },
    content: props.modelValue,
    onUpdate() {
        handleInput()
    },
})

onBeforeUnmount(() => editor.value?.destroy())

function handleInput() {
    emits('update:modelValue', editor.value?.getJSON())
    emits('update:rawText', editor.value?.getText())
    emits('update:isEmpty', editor.value?.isEmpty)
}

defineExpose({ editor })
</script>

<style scoped>
:deep(.tiptap p.is-editor-empty:first-child::before) {
    content: attr(data-placeholder);
    float: left;
    pointer-events: none;
    height: 0;

    @apply text-gray-400;
}

:deep(.mention) {
    @apply px-0.5 font-bold underline;
}

:deep(.candidate-mention) {
    @apply cursor-pointer;
}
</style>
