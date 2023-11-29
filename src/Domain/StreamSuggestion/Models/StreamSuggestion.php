<?php

namespace Domain\StreamSuggestion\Models;

use Illuminate\Database\Eloquent\Model;

class StreamSuggestion extends Model
{
    protected $fillable = [
        'activity',
        'category',
        'language',
        'response',
    ];

    protected $casts = [
        'response' => 'array',
    ];
}
