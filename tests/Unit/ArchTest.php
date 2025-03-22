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
        'App\Builders',
        'App\Console',
        'App\Exceptions',
        'App\Models',
        'App\Notifications',
        'App\Providers',
        'App\States',
        'App\Support',
        'App\View',
    ]);

arch('avoid inheritance')
    ->expect('App')
    ->classes()
    ->toExtendNothing()
    ->ignoring([
        'App\Builders',
        'App\Console',
        'App\Exceptions',
        'App\Models',
        'App\Notifications',
        'App\Providers',
        'App\States',
        'App\Support',
        'App\View',
    ]);

arch('avoid open for extension')
    ->expect('App')
    ->classes()
    ->toBeFinal()
    ->ignoring([
        'App\Builders',
        'App\States',
    ]);

arch('avoid abstraction')
    ->expect('App')
    ->not->toBeAbstract()
    ->ignoring([
        'App\Builders',
        'App\Contracts',
        'App\States',
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
        'App\Builders',
        'App\Console',
        'App\Contracts',
        'App\Events',
        'App\Models',
        'App\Notifications',
        'App\Observers',
        'App\Policies',
        'App\Services',
        'App\States',
        'App\Support',
        'Database\Factories',
        'Database\Seeders',
    ]);

arch('concerns')
    ->expect('App\Concerns')
    ->traits();
