<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\actingAs;

test('guests are redirected to the login page', function (): void {
    $this->get('/')->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function (): void {
    $user = User::factory()->create();

    actingAs($user)
        ->get('/')
        ->assertStatus(200);
});
