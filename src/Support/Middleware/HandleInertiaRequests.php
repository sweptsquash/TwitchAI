<?php

namespace Support\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Inertia\Middleware;
use Symfony\Component\HttpFoundation\Response;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    public function handle(Request $request, Closure $next): Response
    {
        Vite::useIntegrityKey('integrity');

        return parent::handle($request, $next);
    }

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return Vite::manifestHash();
    }

    /**
     * Define the props that are shared by default.
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'env' => config('app.env'),
            'languages' => $this->getLanguages(),
            'location' => $request->url(),
            'notification' => $request->hasSession() ? session('notification') : null,
            'version' => config('app.version'),
        ]);
    }

    private function getLanguages(): array
    {
        return [
            [
                'label' => 'English (American)',
                'value' => 'English (American)',
            ],
            [
                'label' => 'English (British)',
                'value' => 'English (British)',
            ],
            [
                'label' => 'Català',
                'value' => 'Catalan',
            ],
            [
                'label' => 'Bahasa Indonesia',
                'value' => 'Bahasa',
            ],
            [
                'label' => 'Dansk',
                'value' => 'Danish',
            ],
            [
                'label' => 'Deutsch',
                'value' => 'Deutsch',
            ],
            [
                'label' => 'Español',
                'value' => 'Spanish',
            ],
            [
                'label' => 'Français',
                'value' => 'French',
            ],
            [
                'label' => 'Italiano',
                'value' => 'Italian',
            ],
            [
                'label' => 'Magyar',
                'value' => 'Hungarian',
            ],
            [
                'label' => 'Nederlands',
                'value' => 'Dutch',
            ],
            [
                'label' => 'Norsk',
                'value' => 'Norwegian',
            ],
            [
                'label' => 'Polski',
                'value' => 'Polish',
            ],
            [
                'label' => 'Português',
                'value' => 'Portuguese',
            ],
            [
                'label' => 'Română',
                'value' => 'Romanian',
            ],
            [
                'label' => 'Slovenčina',
                'value' => 'Slovak',
            ],
            [
                'label' => 'Svenska',
                'value' => 'Swedish',
            ],
            [
                'label' => 'Tagalog',
                'value' => 'Tagalog',
            ],
            [
                'label' => 'Tiếng Việt',
                'value' => 'Vietnamese',
            ],
            [
                'label' => 'Türkçe',
                'value' => 'Turkish',
            ],
            [
                'label' => 'Čeština',
                'value' => 'Czech',
            ],
            [
                'label' => 'Ελληνικά',
                'value' => 'Greek',
            ],
            [
                'label' => 'Български',
                'value' => 'Bulgarian',
            ],
            [
                'label' => 'Русский',
                'value' => 'Russian',
            ],
            [
                'label' => 'Українська',
                'value' => 'Ukrainian',
            ],
            [
                'label' => 'العربية',
                'value' => 'Arabic',
            ],
            [
                'label' => 'بهاس ملايو',
                'value' => 'Malay',
            ],
            [
                'label' => 'मानक हिन्दी',
                'value' => 'Hindi',
            ],
            [
                'label' => 'ภาษาไทย',
                'value' => 'Thai',
            ],
            [
                'label' => '中文',
                'value' => 'Chinese',
            ],
            [
                'label' => '日本語',
                'value' => 'Japanese',
            ],
            [
                'label' => '粵語',
                'value' => 'Cantonese',
            ],
            [
                'label' => '한국어',
                'value' => 'Korean',
            ],
            [
                'label' => 'American Sign Language',
                'value' => 'American Sign Language',
            ],
        ];
    }
}
