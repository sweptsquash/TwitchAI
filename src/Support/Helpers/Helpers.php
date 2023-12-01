<?php

use Domain\User\Models\User;

if (! function_exists('user')) {
    function user(): ?User
    {
        return auth()->user();
    }
}
