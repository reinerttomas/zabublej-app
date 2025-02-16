<?php

declare(strict_types=1);

use App\Builders\EventBuilder;
use App\Models\Event;

test('has event builder', function (): void {
    // Act & Assert
    expect(Event::query())->toBeInstanceOf(EventBuilder::class);
});

test('has users', function (): void {
    $event = Event::factory()->hasUsers(3)->create();

    expect($event->users)->toHaveCount(3);
});
