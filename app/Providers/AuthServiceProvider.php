<?php

namespace App\Providers;

use App\Models\Pelanggaran;
use App\Models\Prestasi;
use App\Policies\PelanggaranPolicy;
use App\Policies\PrestasiPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Pelanggaran::class => PelanggaranPolicy::class,
        Prestasi::class => PrestasiPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
