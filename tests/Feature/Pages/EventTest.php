<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('redirects to login page as guest', function (): void {
    // Act & Assert
    expect(get('/events'))
        ->assertRedirect('/login');
});

it('can show users page', function (): void {
    // Arrange
    actingAs(User::factory()->create());

    // Act & Assert
    get('/events')
        ->assertOk()
        ->assertSeeVolt('events.index');
});
