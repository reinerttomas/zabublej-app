<?php

declare(strict_types=1);

use App\Models\Event;
use App\Models\User;
use Livewire\Volt\Volt;

use function Pest\Faker\fake;
use function Pest\Laravel\actingAs;

it('redirects to login page as guest', function (): void {
    $this->get('/users')->assertRedirect('/login');
});

it('allows admin to show users list', function (User $user): void {
    actingAs($user)
        ->get('/users')
        ->assertStatus(200);
})->with([
    fn () => User::factory()->superAdmin()->create(),
    fn () => User::factory()->admin()->create(),
]);

it('forbids staff to show users list', function (User $user): void {
    actingAs($user)
        ->get('/users')
        ->assertStatus(403);
})->with([
    fn () => User::factory()->staff()->create(),
]);

it('allows admin to update user', function (User $user, Event $event, array $data): void {
    actingAs($user);

    Volt::test('users.table-row', ['user' => $user])
        ->set('name', $data['name'])
        ->set('email', $data['email'])
        ->set('phone', $data['phone'])
        ->call('update')
        ->assertStatus(200)
        ->assertHasNoErrors();

    $user->refresh();

    expect($user)
        ->name->toEqual($data['name'])
        ->email->toEqual($data['email'])
        ->email_verified_at->not->toBeNull()
        ->phone->toEqual($data['phone']);
})->with([
    fn (): array => [
        User::factory()->superAdmin()->create(),
        Event::factory()->create(),
        [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),
        ],
    ],
]);

it('allows admin to delete user', function (User $user): void {
    actingAs($user);

    Volt::test('users.table')
        ->call('delete', $user->id)
        ->assertHasNoErrors();

    $user->refresh();

    expect($user->deleted_at)->not->toBeNull();
})->with([
    fn (): User => User::factory()->superAdmin()->create(),
    fn (): User => User::factory()->admin()->create(),
]);
