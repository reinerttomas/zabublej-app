<?php

declare(strict_types=1);

namespace App\Actions;

use App\Events\Login;
use App\Models\Invitation;
use App\Models\User;
use App\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Throwable;

final readonly class RegisterUserAction
{
    /**
     * @param  array{ name: string, email: string, phone: string, password: string }  $data
     *
     * @throws Throwable
     */
    public function execute(Invitation $invitation, array $data): void
    {
        $user = DB::transaction(function () use ($invitation, $data): User {
            // Mark invitation as accepted
            $invitation->update([
                'accepted_at' => now(),
            ]);

            // Create user
            $user = User::create([
                'email_verified_at' => now(),
                ...$data,
            ]);

            // Assign role
            $user->assignRole($invitation->payload->role);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        event(new Login($user));
    }
}
