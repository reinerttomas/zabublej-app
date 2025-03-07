<?php

declare(strict_types=1);

namespace App\Providers;

use App\Enums\Role;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureCommands();
        $this->configureModels();
        $this->configureDate();
        $this->configurePermissions();
    }

    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands(
            $this->app->isProduction(),
        );
    }

    private function configureModels(): void
    {
        Model::preventLazyLoading();
        Model::preventSilentlyDiscardingAttributes();
        Model::unguard();
    }

    private function configureDate(): void
    {
        Date::use(CarbonImmutable::class);
        Carbon::macro('human', fn (): string => $this->format('d.m.Y H:i'));
    }

    private function configurePermissions(): void
    {
        Gate::before(fn ($user): ?true => $user->hasRole(Role::SuperAdmin) ? true : null);
    }
}
