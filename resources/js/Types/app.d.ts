declare namespace App {
    declare namespace Enums {
        export type NotificationType = keyof typeof import("@/Enums/NotificationTypeEnum").NotificationTypeEnum;
    }

    export type NotificationData = {
        type: Enums.NotificationType;
        body: string;
    };

    export type SharedData = {
        env: string;
        languages: array<{ label: string; value: string; }>;
        location: string;
        notification: NotificationData | null;
        version: string;
    };

    export type StreamSuggestion = {
        id: number;
        activity: string;
        category: string;
        language: string;
        response: any;
        created_at: string;
    };

    export type User = {
        id: number;
    };
}
