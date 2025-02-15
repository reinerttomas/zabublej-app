<?php

declare(strict_types=1);

use App\Models\User;
use Livewire\Volt\Volt;

use function Pest\Laravel\assertAuthenticated;

test('login screen can be rendered', function (): void {
    $response = $this->get('/login');

    $response
        ->assertOk()
        ->assertSeeVolt('auth.login');
});

test('users can authenticate using the login screen', function (): void {
    $user = User::factory()->notLoginYet()->create();

    expect($user->last_login_at)->toBeNull();

    $component = Volt::test('auth.login')
        ->set('email', $user->email)
        ->set('password', 'password');

    $component->call('login');

    $component
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    assertAuthenticated();

    $user->refresh();

    expect($user->last_login_at)->not()->toBeNull();
});

test('users can not authenticate with invalid password', function (): void {
    $user = User::factory()->create();

    $component = Volt::test('auth.login')
        ->set('email', $user->email)
        ->set('password', 'wrong-password');

    $component->call('login');

    $component
        ->assertHasErrors()
        ->assertNoRedirect();

    $this->assertGuest();
});

test('sidebar profile menu can be rendered', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get('/');

    $response
        ->assertOk()
        ->assertSeeVolt('layout.sidebar.profile.dropdown');
});

test('header profile menu can be rendered', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get('/');

    $response
        ->assertOk()
        ->assertSeeVolt('layout.header.profile.dropdown');
});

test('users can logout from sidebar profile menu', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Volt::test('layout.sidebar.profile.dropdown');

    $component->call('logout');

    $component
        ->assertHasNoErrors()
        ->assertRedirect('/login');

    $this->assertGuest();
});

test('users can logout from header profile menu', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Volt::test('layout.header.profile.dropdown');

    $component->call('logout');

    $component
        ->assertHasNoErrors()
        ->assertRedirect('/login');

    $this->assertGuest();
});
