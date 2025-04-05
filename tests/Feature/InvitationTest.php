<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\Invitation;
use App\Models\User;
use App\Notifications\Invitation\InvitationRegisterNotification;
use Livewire\Volt\Volt;

use function Pest\Faker\fake;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;

it('allows admin to send invitation to show invitation button', function (User $user): void {
    actingAs($user)
        ->get('/users')
        ->assertStatus(200)
        ->assertSeeLivewire('users.invite-user-form');
})->with([
    fn () => User::factory()->superAdmin()->create(),
    fn () => User::factory()->admin()->create(),
]);

it('allows admin to send invitation', function (User $user, array $data): void {
    Notification::fake();

    actingAs($user);

    Volt::test('users.invite-user-form')
        ->set('name', $data['name'])
        ->set('email', $data['email'])
        ->set('role', $data['role'])
        ->set('description', $data['description'])
        ->call('invite')
        ->assertStatus(200)
        ->assertHasNoErrors();

    assertDatabaseCount(Invitation::class, 1);

    $invitation = Invitation::firstOrFail();

    expect($invitation)
        ->email->toBe($data['email'])
        ->payload->name->toBe($data['name'])
        ->payload->role->toBe($data['role'])
        ->payload->description->toBe($data['description']);

    Notification::assertSentTo($invitation, InvitationRegisterNotification::class);
})->with([
    fn (): array => [
        User::factory()->superAdmin()->create(),
        [
            'name' => fake()->name,
            'email' => fake()->email,
            'role' => fake()->randomElement(Role::class),
            'description' => fake()->optional()->sentence,
        ],
    ],
    fn (): array => [
        User::factory()->admin()->create(),
        [
            'name' => fake()->name,
            'email' => fake()->email,
            'role' => fake()->randomElement(Role::class),
            'description' => fake()->optional()->sentence,
        ],
    ],
]);
