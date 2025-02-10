<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final readonly class LoginByEmailAndPasswordAction
{
    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function execute(string $email, string $password, bool $remember): void
    {
        $this->ensureIsNotRateLimited($email);

        $credentials = [
            'email' => $email,
            'password' => $password,
        ];

        if (! Auth::attempt($credentials, $remember)) {
            RateLimiter::hit($this->throttleKey($email));

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey($email));
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    private function ensureIsNotRateLimited(string $email): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($email), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey($email));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    private function throttleKey(string $email): string
    {
        return Str::transliterate(Str::lower($email) . '|' . request()->ip());
    }
}
