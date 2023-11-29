<?php

namespace App\Web\Home\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStreamSuggestionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'activity' => ['required', 'string'],
            'category' => ['required', 'string'],
            'language' => ['required', 'string'],
        ];
    }
}
