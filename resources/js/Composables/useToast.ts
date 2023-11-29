import DangerIcon from '@/Components/UI/Common/ToastIcons/DangerIcon.vue'
import InfoIcon from '@/Components/UI/Common/ToastIcons/InfoIcon.vue'
import SuccessIcon from '@/Components/UI/Common/ToastIcons/SuccessIcon.vue'
import WarningIcon from '@/Components/UI/Common/ToastIcons/WarningIcon.vue'
import { TYPE as NotificationType, useToast } from 'vue-toastification'

export default ({
    message,
    type = 'default',
    timeout = 3000,
}: {
    message: string
    type?: 'default' | 'success' | 'info' | 'warning' | 'error'
    timeout?: number | false | undefined
}) => {
    const toast = useToast()

    let iconComponent = InfoIcon

    if (type === 'success') {
        iconComponent = SuccessIcon
    }

    if (type === 'error') {
        iconComponent = DangerIcon
    }

    if (type === 'warning') {
        iconComponent = WarningIcon
    }

    toast(message, {
        type: type as NotificationType,
        icon: iconComponent,
        timeout,
    })
}
