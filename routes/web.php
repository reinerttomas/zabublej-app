<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function (): void {
    // Dashboard
    Volt::route('/', 'dashboard')
        ->name('dashboard');

    // Users
    Volt::route('users', 'users.index')
        ->name('users.index');

    // Events
    Volt::route('events', 'events.index')
        ->name('events.index');

    Volt::route('events/{event}', 'events.edit')
        ->name('events.update');

    // Settings
    Volt::route('settings', 'settings')
        ->middleware('password.confirm')
        ->name('settings');
});

require __DIR__ . '/auth.php';
