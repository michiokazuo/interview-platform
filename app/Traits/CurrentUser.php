<?php

namespace App\Traits;

use App\Models\User;

trait CurrentUser
{
    /**
     * Get User
     *
     * @return User | false
     */
    function user(array $fields = ['*'])
    {
        if (auth('api')->check()) {
            return User::find(auth('api')->user()->id, $fields);
        } else {
            return false;
        }
    }
}
