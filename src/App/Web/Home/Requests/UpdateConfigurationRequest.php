<?php

namespace App\Web\Home\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConfigurationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'channelName' => ['required', 'string'],
            'openAiKey' => ['nullable', 'string'],
        ];
    }
}
