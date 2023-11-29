<?php

namespace App\Providers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ServiceProvider;
use Support\Enums\NotificationType;

class InertiaServiceProvider extends ServiceProvider
{
    public function register()
    {
        RedirectResponse::macro('flash', function (NotificationType $type, string $body) {
            session()->flash('notification', [
                'type' => $type,
                'body' => $body,
            ]);

            /** @var RedirectResponse $this */
            return $this;
        });

        RedirectResponse::macro('success', function (string $body) {
            /** @var RedirectResponse $this */
            return $this->flash(NotificationType::Success, $body);
        });

        RedirectResponse::macro('error', function (string $body) {
            /** @var RedirectResponse $this */
            return $this->flash(NotificationType::Error, $body);
        });
    }
}
