<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('verify-pelanggaran', function ($user) {
            return in_array($user->role, ['admin', 'kesiswaan']);
        });

        Gate::define('verify-prestasi', function ($user) {
            return in_array($user->role, ['admin', 'kesiswaan', 'guru']);
        });
    }
}
