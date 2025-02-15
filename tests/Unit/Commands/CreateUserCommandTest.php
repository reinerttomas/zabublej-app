<?php

declare(strict_types=1);

use function Pest\Laravel\artisan;
use function Pest\Laravel\assertDatabaseHas;

it('can create user', function (array $data): void {
    // Act & Assert
    artisan('user:create')
        ->expectsQuestion('What is your first name?', $data['first_name'])
        ->expectsQuestion('What is your last name?', $data['last_name'])
        ->expectsQuestion('What is your email?', $data['email'])
        ->expectsQuestion('What is your password?', $data['password'])
        ->assertExitCode(0);

    assertDatabaseHas('users', [
        'email' => $data['email'],
    ]);
})->with([
    fn (): array => [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
        'password' => 'John123!',
    ],
]);
