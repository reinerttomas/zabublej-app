<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
final class EventFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->realText(),
            'start_at' => fake()->dateTimeBetween('-1 year', '+1 year'),
            'estimated_hours' => fake()->numberBetween(1, 10),
            'location' => fake()->address,
            'status' => fake()->randomElement(EventStatus::class),
        ];
    }

    public function draft(): self
    {
        return $this->state([
            'description' => null,
            'estimated_hours' => null,
            'location' => null,
            'status' => EventStatus::Draft,
        ]);
    }
}
