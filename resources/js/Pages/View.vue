<template>
    <div class="relative -mt-4">
        <div
            class="sticky top-0 -mx-4 flex items-center justify-between border-b bg-muted-100 p-4 shadow-sm"
        >
            <InertiaLink :href="route('home')" class="btn-primary">Back</InertiaLink>
            <div class="text-sm text-slate-400">
                Created at: {{ useDayJs(streamSuggestion.created_at) }}
            </div>
        </div>

        <div class="mt-4 flex flex-col space-y-4">
            <div
                v-if="!streamSuggestion.response"
                class="alert alert-danger flex items-center justify-between rounded border p-4 shadow"
            >
                <div class="text-sm">
                    AI Assistant failed to generate suggestions, you can try again by click to the
                    right.
                </div>

                <button
                    type="button"
                    class="btn-primary"
                    :disabled="retryForm.processing"
                    @click.prevent="retry(streamSuggestion.id)"
                >
                    <IconLoading v-if="retryForm.processing" class="h-4 w-4 animate-spin" />
                    <ArrowPathIcon v-else class="h-4 w-4" />
                </button>
            </div>

            <div class="font-bold">Request</div>

            <div class="rounded border bg-white p-4 shadow">
                <div class="mb-1 font-bold">Activity</div>
                <div class="mb-2 text-sm">{{ streamSuggestion.activity }}</div>
                <div class="mb-1 font-bold">Category</div>
                <div class="mb-2 text-sm">{{ streamSuggestion.category }}</div>
                <div class="mb-1 font-bold">Language</div>
                <div class="text-sm">{{ findLanguage(streamSuggestion.language) }}</div>
            </div>

            <div class="font-bold">Suggested Titles</div>

            <div class="space-y-2 rounded border bg-white p-4 shadow">
                <template
                    v-if="
                        streamSuggestion.response && streamSuggestion.response?.titles?.length > 0
                    "
                >
                    <Copyable
                        v-for="(title, titleIndex) in streamSuggestion.response.titles"
                        :key="`title${titleIndex}`"
                        :value="title"
                    >
                        {{ title }}
                    </Copyable>
                </template>
                <div
                    v-else
                    class="relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 text-center"
                >
                    <div class="text-sm font-semibold text-gray-900">No titles suggested</div>
                </div>
            </div>

            <div class="font-bold">Suggested Tags</div>

            <div class="space-y-2 rounded border bg-white p-4 shadow">
                <template
                    v-if="streamSuggestion.response && streamSuggestion.response?.tags?.length > 0"
                >
                    <Badge
                        v-for="(tag, tagIndex) in streamSuggestion.response.tags"
                        :key="`tag${tagIndex}`"
                        class="mr-2 last:mr-0"
                    >
                        {{ tag }}
                    </Badge>
                </template>
                <div
                    v-else
                    class="relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 text-center"
                >
                    <div class="text-sm font-semibold text-gray-900">No tags suggested</div>
                </div>
            </div>

            <div class="font-bold">Suggested Go Live Notification</div>

            <div class="space-y-2 rounded border bg-white p-4 shadow">
                <template
                    v-if="
                        streamSuggestion.response && streamSuggestion.response?.goLiveNotification
                    "
                >
                    <Copyable :value="streamSuggestion.response?.goLiveNotification">
                        {{ streamSuggestion.response?.goLiveNotification }}
                    </Copyable>
                </template>
                <div
                    v-else
                    class="relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 text-center"
                >
                    <div class="text-sm font-semibold text-gray-900">
                        No go live notifcation suggested
                    </div>
                </div>
            </div>

            <div class="font-bold">Suggested Content Classifications</div>

            <div class="space-y-2 rounded border bg-white p-4 shadow">
                <template
                    v-if="
                        streamSuggestion.response &&
                        streamSuggestion.response?.contentClassification?.length > 0
                    "
                >
                    <Badge
                        v-for="(classification, classificationIndex) in streamSuggestion.response
                            .contentClassification"
                        :key="`classification${classificationIndex}`"
                        class="mr-2 last:mr-0"
                    >
                        {{ classification }}
                    </Badge>
                </template>
                <div
                    v-else
                    class="relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 text-center"
                >
                    <div class="text-sm font-semibold text-gray-900">
                        No go live notifcation suggested
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ArrowPathIcon } from '@heroicons/vue/24/solid'

defineProps<{ streamSuggestion: App.StreamSuggestion }>()

const languages = computed(() => usePage().props.languages)
const retryForm = useForm({})

function findLanguage(streamLanguage: string): string {
    return languages.value.find(
        (language: { label: string; value: string }) => language.value === streamLanguage,
    )?.label
}

function retry(streamSuggestion: number) {
    retryForm.put(route('retry', { streamSuggestion }))
}
</script>
