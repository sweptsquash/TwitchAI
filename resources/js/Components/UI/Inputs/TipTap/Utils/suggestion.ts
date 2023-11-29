import MentionList from '@/Components/UI/Inputs/TipTap/Partials/MentionList.vue'
import { VueRenderer } from '~/@tiptap/vue-3'
import tippy from '~/tippy.js'

export default (items: Array<App.SuggestionItem>) => ({
    items: ({ query }: any) => {
        return items?.filter(
            (item: App.SuggestionItem) => item.label?.toLowerCase().startsWith(query.toLowerCase()),
        )
    },

    render: () => {
        let component: any
        let popup: any

        return {
            onStart: (props: any) => {
                component = new VueRenderer(MentionList, {
                    editor: props.editor,
                    props,
                })

                if (!props.clientRect) {
                    return
                }

                popup = tippy('body', {
                    getReferenceClientRect: props.clientRect,
                    appendTo: () => document.body,
                    content: component.element,
                    showOnCreate: true,
                    interactive: true,
                    trigger: 'manual',
                    placement: 'bottom-start',
                })
            },

            onUpdate(props: any) {
                component.updateProps(props)

                if (!props.clientRect) {
                    return
                }

                popup[0].setProps({
                    getReferenceClientRect: props.clientRect,
                })
            },

            onKeyDown(props: any) {
                if (props.event.key === 'Escape') {
                    popup[0].hide()

                    return true
                }

                return component.ref?.onKeyDown(props)
            },

            onExit() {
                popup[0].destroy()
                component.destroy()
            },
        }
    },
})
