<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\User>
 */
final class UserFactory extends Factory
{
    private static ?string $password = null;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'phone' => fake()->optional()->phoneNumber(),
            'password' => self::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'last_login_at' => now(),
        ];
    }

    public function unverified(): self
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }

    public function superAdmin(): self
    {
        return $this->afterCreating(function (User $user): void {
            $user->assignRole(Role::SuperAdmin);
        });
    }

    public function admin(): self
    {
        return $this->afterCreating(function (User $user): void {
            $user->assignRole(Role::Admin);
        });
    }

    public function staff(): self
    {
        return $this->afterCreating(function (User $user): void {
            $user->assignRole(Role::Staff);
        });
    }
}
