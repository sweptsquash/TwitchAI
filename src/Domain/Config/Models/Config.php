<?php

namespace Domain\Config\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];
}
