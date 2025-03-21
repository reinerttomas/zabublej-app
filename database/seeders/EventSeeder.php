<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;

final class EventSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()
            ->whereHasRole(function (Builder $query): void {
                $query->where('name', Role::Staff);
            })
            ->pluck('id');

        Event::factory()
            ->count(50)
            ->create()
            ->each(function (Event $event) use ($users): void {
                $event->users()->attach($users->random(random_int(1, 2)));
            });
    }
}
