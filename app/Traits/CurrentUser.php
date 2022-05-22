<?php

namespace App\Traits;

use Illuminate\Contracts\Auth\Authenticatable;

trait CurrentUser
{
    /**
     * Get User
     *
     * @return false|Authenticatable
     */
    function user()
    {
        if (auth('api')->check()) {
            return auth('api')->user();
        } else {
            return false;
        }
    }
}
