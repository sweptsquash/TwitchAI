export const notifications = () => {
    router.on('finish', () => {
        const notification = usePage().props.notification

        if (notification) {
            useToast({ message: notification.body, type: notification.type })
        }
    })
}
