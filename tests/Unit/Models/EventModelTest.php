<?php

declare(strict_types=1);

use App\Models\Event;
use App\Models\User;

test('has events', function (): void {
    $user = User::factory()
        ->has(Event::factory()->count(3))
        ->create();

    expect($user->events)->toHaveCount(3);
});
