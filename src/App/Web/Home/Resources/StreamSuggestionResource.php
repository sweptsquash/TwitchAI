<?php

namespace App\Web\Home\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StreamSuggestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'activity' => $this->activity,
            'category' => $this->category,
            'language' => $this->language,
            'response' => $this->response,
        ];
    }
}
