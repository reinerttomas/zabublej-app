<?php

declare(strict_types=1);

use App\Builders\UserBuilder;
use App\Models\User;

test('has user builder', function (): void {
    // Act & Assert
    expect(User::query())->toBeInstanceOf(UserBuilder::class);
});

test('has events', function (): void {
    // Arrange
    $user = User::factory()->hasEvents(3)->create();

    // Act & Assert
    expect($user->events)->toHaveCount(3);
});
