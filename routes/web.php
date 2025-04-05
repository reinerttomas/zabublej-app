<?php

declare(strict_types=1);

use App\Http\Controllers\Events\SetStatusEventAttendanceController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function (): void {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::middleware(['auth', 'verified'])->group(function (): void {
    // Users
    Volt::route('users', 'users.index')->name('users.index');

    // Events
    Volt::route('events', 'events.index')->name('events.index');
    Volt::route('events/{event}', 'events.show')->name('events.show');
    Volt::route('events/{event}/edit', 'events.edit')->name('events.edit');
    Volt::route('event-attendances', 'event-attendances.index')->name('event-attendances.index');
});

Route::get('/event-attendances/{eventAttendance}/status/{status}', SetStatusEventAttendanceController::class)
    ->name('event-attendances.status')
    ->middleware('signed');

require __DIR__ . '/auth.php';
