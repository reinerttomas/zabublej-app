<?php

declare(strict_types=1);

use App\Builders\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;

arch()->preset()->php();
arch()->preset()->laravel()->ignoring([
    'App\Livewire',
]);
arch()->preset()->security();

arch('controllers')
    ->expect('App\Http\Controllers')
    ->not->toBeUsed();

arch('avoid mutation')
    ->expect('App')
    ->classes()
    ->toBeReadonly()
    ->ignoring([
        'App\Builders',
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
        'App\Builders',
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
    ->toBeFinal()
    ->ignoring([
        Builder::class,
    ]);

arch('avoid abstraction')
    ->expect('App')
    ->not->toBeAbstract()
    ->ignoring([
        Builder::class,
    ]);

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
        'App\Builders',
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
