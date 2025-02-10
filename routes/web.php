<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('profile', 'pages.profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Volt::route('/', 'pages.dashboard')
        ->name('dashboard');

    Volt::route('users', 'pages.users.index')
        ->name('users.index');

    Volt::route('events', 'pages.events.index')
        ->name('events.index');

    Volt::route('settings', 'pages.settings')
        ->middleware('password.confirm')
        ->name('settings');
});

require __DIR__ . '/auth.php';
