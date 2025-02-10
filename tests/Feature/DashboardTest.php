<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('returns a successful response as authenticated', function (): void {
    actingAs(User::factory()->create());

    expect(get('/'))->assertStatus(200);
});

it('redirects to login page as guest', function (): void {
    expect(get('/'))->assertRedirectToRoute('login');
});
