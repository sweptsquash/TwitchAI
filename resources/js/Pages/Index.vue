<template>
    <div class="flex flex-col space-y-4">
        <div class="flex items-center justify-between">
            <div class="font-bold">AI Assistant</div>
            <div>
                <button
                    type="button"
                    class="btn-primary inline-flex items-center space-x-4"
                    @click.prevent="isNewStreamOpen = true"
                >
                    <VideoCameraIcon class="h-4 w-4" />
                    <span>New Stream</span>
                </button>
            </div>
        </div>

        <div class="border-b pb-2 font-bold">Previous Generations</div>

        <div class="flex flex-col space-y-4">
            <template v-if="streamSuggestions.length > 0">
                <div
                    v-for="(streamSuggestion, index) in streamSuggestions"
                    :key="`streamSuggestion${index}`"
                    class="flex items-center justify-between space-x-4 rounded-md border bg-white p-2 shadow hover:bg-slate-200"
                >
                    <div class="flex-1 text-sm">
                        <div class="mb-1 font-bold">Activity</div>
                        <div>{{ streamSuggestion.activity }}</div>
                        <div class="my-1 font-bold">Category</div>
                        <div>{{ streamSuggestion.category }}</div>
                        <div class="my-1 font-bold">Language</div>
                        <div>
                            {{ findLanguage(streamSuggestion.language) }}
                        </div>
                    </div>
                    <InertiaLink
                        v-if="streamSuggestion.response"
                        :href="route('show', { streamSuggestion: streamSuggestion.id })"
                        type="button"
                        class="btn-primary flex-0"
                    >
                        View
                    </InertiaLink>
                    <button
                        v-else
                        type="button"
                        class="btn-primary"
                        :disabled="retryForm.processing"
                        @click.prevent="retry(streamSuggestion.id)"
                    >
                        <IconLoading v-if="retryForm.processing" class="h-4 w-4 animate-spin" />
                        <ArrowPathIcon v-else class="h-4 w-4" />
                    </button>
                </div>
            </template>
            <button
                v-else
                type="button"
                class="relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                @click.prevent="isNewStreamOpen = true"
            >
                <VideoCameraIcon class="mx-auto h-12 w-12 text-gray-400" />
                <span class="mt-2 block text-sm font-semibold text-gray-900">New Stream</span>
            </button>
        </div>

        <!-- New Stream Modal -->
        <ModalForm
            :is-open="isNewStreamOpen"
            :is-processing="form.processing"
            title="New Stream"
            submit-text="Generate Suggestions"
            @submit="submit"
            @cancel="cancel"
        >
            <div class="flex flex-col space-y-4">
                <!-- TODO Autocomplete? -->
                <TextInput
                    id="category"
                    v-model="form.category"
                    label="Category"
                    required
                    placeholder="Example: Starfield"
                    :error="form.errors?.category"
                />

                <TextInput
                    id="activity"
                    v-model="form.activity"
                    label="Activity"
                    placeholder="Example: Taking over the galaxy one system at a time"
                    required
                    :error="form.errors?.activity"
                />

                <SelectInput
                    id="language"
                    v-model="form.language"
                    label="Stream Language"
                    searchable
                    :options="languages"
                    label-prop="label"
                    value-prop="value"
                    close-on-select
                />
            </div>
        </ModalForm>

        <!-- Configuration Modal -->
        <ModalForm
            :is-open="isConfigurationOpen"
            title="Initial configuration"
            submit-text="Save configuration"
            :has-cancel-button="false"
            :is-processing="configForm.processing"
            :submit-disabled="configForm.openAiKey === null"
            @submit="submitConfig"
            @close="closeConfig"
        >
            <div class="flex flex-col space-y-4">
                <TextInput
                    id="twitchUsername"
                    v-model="configForm.twitchUsername"
                    required
                    label="Twitch Username"
                    :error="configForm.errors?.twitchUsername"
                />

                <TextInput
                    id="openAiKey"
                    v-model="configForm.openAiKey"
                    required
                    label="OpenAI API Key"
                    :error="configForm.errors?.openAiKey"
                />

                <TextInput
                    id="openAiOrg"
                    v-model="configForm.openAiOrg"
                    label="OpenAI Organisation ID"
                    :error="configForm.errors?.openAiOrg"
                />
            </div>
        </ModalForm>
    </div>
</template>

<script lang="ts" setup>
import { VideoCameraIcon, ArrowPathIcon } from '@heroicons/vue/24/solid'

const props = defineProps<{
    intitialConfiguration: boolean
    streamSuggestions: Array<App.StreamSuggestion>
}>()

const isNewStreamOpen = ref(false)
const isConfigurationOpen = ref(false)

const languages = computed(() => usePage().props.languages)

const configForm = useForm({
    twitchUsername: null,
    openAiKey: null,
    openAiOrg: null,
})

const form = useForm({
    category: null,
    activity: null,
    language: 'English (British)',
})

const retryForm = useForm({})

onMounted(() => {
    if (props.intitialConfiguration) {
        isConfigurationOpen.value = true
    }
})

function submit() {
    form.post(route('store'))
}

function cancel() {
    isNewStreamOpen.value = false
}

function submitConfig() {
    configForm.put(route('update'), {
        onSuccess: () => (isConfigurationOpen.value = false),
    })
}

function closeConfig() {
    isConfigurationOpen.value = false
}

function findLanguage(streamLanguage: string): string {
    return languages.value.find(
        (language: { label: string; value: string }) => language.value === streamLanguage,
    )?.label
}

function retry(streamSuggestion: number) {
    retryForm.put(route('retry', { streamSuggestion }))
}
</script>
