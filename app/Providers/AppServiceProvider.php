<?php

namespace App\Providers;

use App\Models\DataDiri;
use App\Models\DataPerjalanan;
use App\Models\DataSppd;
use App\Policies\PegawaiPolicy;
use App\Policies\PerjalananPolicy;
use App\Policies\SppdPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        Gate::policy(DataSppd::class, SppdPolicy::class);
        Gate::policy(DataPerjalanan::class, PerjalananPolicy::class);
        Gate::policy(DataDiri::class, PegawaiPolicy::class);
    }
}
