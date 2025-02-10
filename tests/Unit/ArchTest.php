<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Factories\Factory;

arch()->preset()->php();
arch()->preset()->laravel();
arch()->preset()->security();

arch('controllers')
    ->expect('App\Http\Controllers')
    ->not->toBeUsed();

arch('avoid mutation')
    ->expect('App')
    ->classes()
    ->toBeReadonly()
    ->ignoring([
        'App\Console\Commands',
        'App\Livewire\Forms',
        'App\Models',
        'App\Notifications',
        'App\Providers',
        'App\Support',
        'App\View',
    ]);

arch('avoid inheritance')
    ->expect('App')
    ->classes()
    ->toExtendNothing()
    ->ignoring([
        'App\Console\Commands',
        'App\Livewire\Forms',
        'App\Models',
        'App\Notifications',
        'App\Providers',
        'App\Support',
        'App\View',
    ]);

arch('avoid open for extension')
    ->expect('App')
    ->classes()
    ->toBeFinal();

arch('avoid abstraction')
    ->expect('App')
    ->not->toBeAbstract();

arch('factories')
    ->expect('Database\Factories')
    ->toExtend(Factory::class)
    ->toHaveMethod('definition')
    ->toOnlyBeUsedIn([
        'App\Models',
    ]);

arch('models')
    ->expect('App\Models')
    ->toHaveMethod('casts')
    ->toOnlyBeUsedIn([
        'App\Actions',
        'App\Console\Commands',
        'App\Models',
        'App\Notifications',
        'App\Observers',
        'App\Support',
        'Database\Factories',
        'Database\Seeders',
    ]);

arch('actions')
    ->expect('App\Actions')
    ->toHaveMethod('execute');
