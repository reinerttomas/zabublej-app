<?php

declare(strict_types=1);

use App\Models\Event;
use App\Models\User;

test('has users', function (): void {
    $event = Event::factory()
        ->has(User::factory()->count(3))
        ->create();

    expect($event->users)->toHaveCount(3);
});
