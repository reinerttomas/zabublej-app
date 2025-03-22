<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

use function Pest\Faker\fake;

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
            'name' => fake()->text(50),
            'description' => fake()->optional()->text(),
            'start_at' => fake()->dateTimeBetween('-1 year', '+1 year'),
            'location' => fake()->optional()->text(50),
            'contact_person' => fake()->optional()->name(),
            'contact_email' => fake()->optional()->email(),
            'contact_phone' => fake()->optional()->phoneNumber(),
            'is_multi_person' => fake()->boolean(),
            'children_count' => fake()->optional()->numberBetween(1, 100),
            'workers_count' => fake()->optional()->numberBetween(1, 3),
            'price' => fake()->optional()->numberBetween(1000, 10000),
            'reward' => fake()->optional()->numberBetween(1000, 10000),
            'note' => fake()->optional()->text(),
            'status' => fake()->randomElement(EventStatus::cases()),
        ];
    }

    public function draft(): self
    {
        return $this->state([
            'status' => EventStatus::Draft,
        ]);
    }

    public function published(): self
    {
        return $this->state([
            'status' => EventStatus::Published,
        ]);
    }

    public function cancelled(): self
    {
        return $this->state([
            'status' => EventStatus::Cancelled,
        ]);
    }
}
