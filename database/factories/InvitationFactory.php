<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Role;
use App\ValueObjects\InvitationPayload;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invitation>
 */
class InvitationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->email,
            'payload' => new InvitationPayload(
                name: fake()->name,
                role: fake()->randomElement(Role::class),
                description: fake()->optional()->sentence,
            ),
            'expires_at' => now()->addDay(),
            'accepted_at' => null,
        ];
    }

    public function expired(): self
    {
        return $this->state(fn (): array => [
            'expires_at' => now(),
        ]);
    }

    public function accepted(): self
    {
        return $this->state(fn (): array => [
            'accepted_at' => now(),
        ]);
    }
}
