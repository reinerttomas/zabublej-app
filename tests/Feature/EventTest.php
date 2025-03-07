<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\actingAs;

it('redirects to login page as guest', function (): void {
    $this->get('/events')->assertRedirect('/login');
});

it('allows admin and staff to show events list', function (User $user): void {
    actingAs($user)
        ->get('/events')
        ->assertStatus(200);
})->with([
    fn () => User::factory()->superAdmin()->create(),
    fn () => User::factory()->admin()->create(),
    fn () => User::factory()->staff()->create(),
]);
