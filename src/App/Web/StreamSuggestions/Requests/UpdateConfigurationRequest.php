<?php

namespace App\Web\StreamSuggestions\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConfigurationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'twitchUsername' => ['required', 'string'],
            'openAiKey' => ['required', 'string'],
            'openAiOrg' => ['nullable', 'string'],
        ];
    }
}
