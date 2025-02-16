<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('redirects to login page as guest', function (): void {
    expect(get('/'))->assertRedirectToRoute('login');
});

it('can show dashboard page', function (): void {
    actingAs(User::factory()->create());

    get('/')->assertStatus(200);
});
