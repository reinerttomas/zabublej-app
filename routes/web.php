<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Volt::route('/', 'dashboard')
        ->name('dashboard');

    Volt::route('users', 'users.index')
        ->name('users.index');

    Volt::route('events', 'events.index')
        ->name('events.index');

    Volt::route('settings', 'settings')
        ->middleware('password.confirm')
        ->name('settings');
});

require __DIR__ . '/auth.php';
