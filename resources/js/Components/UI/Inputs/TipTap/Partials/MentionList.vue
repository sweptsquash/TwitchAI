<template>
    <div class="relative max-h-32 overflow-y-auto rounded bg-white p-1 text-sm text-black shadow">
        <template v-if="items.length">
            <button
                v-for="(item, index) in items"
                :key="index"
                :class="{ 'is-selected': index === selectedIndex }"
                class="item"
                @click="selectItem(index)"
            >
                {{ item.label }}
            </button>
        </template>
        <div v-else class="item">No result</div>
    </div>
</template>

<script>
// This is in Options API, because I just could not make it work with Composition. So I
// copied over what was in the docs and it works. We can revisit this if needed.

export default {
    props: {
        items: {
            type: Array,
            required: true,
        },

        command: {
            type: Function,
            required: true,
        },
    },

    data() {
        return {
            selectedIndex: 0,
        }
    },

    watch: {
        items() {
            this.selectedIndex = 0
        },
    },

    methods: {
        onKeyDown({ event }) {
            if (event.key === 'ArrowUp') {
                this.upHandler()
                return true
            }

            if (event.key === 'ArrowDown') {
                this.downHandler()
                return true
            }

            if (event.key === 'Enter') {
                this.enterHandler()
                return true
            }

            return false
        },

        upHandler() {
            this.selectedIndex = (this.selectedIndex + this.items.length - 1) % this.items.length
        },

        downHandler() {
            this.selectedIndex = (this.selectedIndex + 1) % this.items.length
        },

        enterHandler() {
            this.selectItem(this.selectedIndex)
        },

        selectItem(index) {
            const item = this.items[index]

            if (item) {
                this.command(item)
            }
        },
    },
}
</script>

<style scoped>
.item {
    @apply m-0 block w-full rounded bg-transparent px-2 py-1 text-left;
}

.item.is-selected {
    @apply bg-black/10;
}
</style>
