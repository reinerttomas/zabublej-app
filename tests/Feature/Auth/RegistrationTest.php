<?php

declare(strict_types=1);

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Volt\Volt;

use function Pest\Laravel\assertAuthenticatedAs;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

it('forbids to show registration page without invitation', function (): void {
    $this->get('/register')
        ->assertStatus(404);
});

it('allows to show registration page with valid invitation', function (Invitation $invitation): void {
    $this->get("/register/$invitation->id")
        ->assertStatus(200);
})->with([
    fn (): Invitation => Invitation::factory()->create(),
]);

it('cannot register to application when invitation is expired', function (Invitation $invitation): void {
    $this->get("/register/$invitation->id")
        ->assertStatus(403)
        ->assertSee('Platnost této pozvánky vypršela.');
})->with([
    fn (): Invitation => Invitation::factory()->expired()->create(),
]);

it('cannot register to application when invitation was already accepted', function (Invitation $invitation): void {
    $this->get("/register/$invitation->id")
        ->assertStatus(403)
        ->assertSee('Tato pozvánka již byla použita.');
})->with([
    fn (): Invitation => Invitation::factory()->accepted()->create(),
]);

it('can register to application', function (Invitation $invitation, array $data): void {
    $response = Volt::test('auth.register', ['token' => $invitation->id])
        ->set('name', $invitation->payload->name)
        ->set('email', $invitation->email)
        ->set('phone', $data['phone'])
        ->set('password', $data['password'])
        ->set('password_confirmation', $data['password_confirmation'])
        ->call('register');

    $response
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $user = User::firstOrFail();

    assertAuthenticatedAs($user);

    expect($user)
        ->name->toBe($invitation->payload->name)
        ->email->toBe($invitation->email)
        ->email_verified_at->not->toBeNull()
        ->phone->toBe(Str::remove(' ', $data['phone']))
        ->last_login_at->not->toBeNull();

    expect($user->roles)
        ->toHaveCount(1)
        ->and($user->hasRole($invitation->payload->role))
        ->toBeTrue();
})->with([
    fn (): array => [
        Invitation::factory()->create(),
        [
            'phone' => '+420 123 456 789',
            'password' => 'password123!',
            'password_confirmation' => 'password123!',
        ],
    ],
]);
