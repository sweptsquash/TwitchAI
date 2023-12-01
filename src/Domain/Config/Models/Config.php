<?php

namespace Domain\Config\Models;

use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use EncryptableDbAttribute;

    protected $fillable = [
        'key',
        'value',
    ];

    protected $encryptable = ['value'];
}
