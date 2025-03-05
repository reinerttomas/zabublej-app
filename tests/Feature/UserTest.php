<?php

declare(strict_types=1);

use App\Models\User;
use Livewire\Volt\Volt;

use function Pest\Laravel\actingAs;

it('cannot show users page as guest', function (): void {
    $this->get('/users')->assertRedirect('/login');
});

it('can show users page as authenticated', function (): void {
    $user = User::factory()->create();

    actingAs($user)
        ->get('/users')
        ->assertStatus(200);
});

it('can update user', function (array $data): void {
    $user = User::factory()->create();

    Volt::test('users.table-row', ['user' => $user])
        ->set('name', $data['name'])
        ->set('email', $data['email'])
        ->set('phone', $data['phone'])
        ->call('update')
        ->assertHasNoErrors();

    $user->refresh();

    expect($user)
        ->name->toEqual($data['name'])
        ->email->toEqual($data['email'])
        ->email_verified_at->not->toBeNull()
        ->phone->toEqual($data['phone']);
})->with([
    fn (): array => [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '123456789',
    ],
]);

it('can delete user', function (): void {
    $user = User::factory()->create();

    Volt::test('users.table')
        ->call('delete', $user->id)
        ->assertHasNoErrors();

    $user->refresh();

    expect($user->deleted_at)->not->toBeNull();
});
