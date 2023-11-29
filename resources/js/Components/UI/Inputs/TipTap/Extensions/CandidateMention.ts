import { Mention } from '@tiptap/extension-mention'
import { PluginKey } from '@tiptap/pm/state'

const name = 'candidateMention'
const CandidateMentionPluginKey = new PluginKey(name)

export default Mention.extend({ name }).configure({
    suggestion: {
        char: '#',
        pluginKey: CandidateMentionPluginKey,
    },
})
