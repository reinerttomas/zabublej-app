<?php

declare(strict_types=1);

namespace App\Support\Facades;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth as LaravelAuth;

final class Auth extends LaravelAuth
{
    /**
     * @throws AuthenticationException
     */
    public static function userOrFail(): User
    {
        return LaravelAuth::user() ?? throw new AuthenticationException;
    }
}
