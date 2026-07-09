<?php

namespace App\Providers;

use App\Models\Laporan;
use App\Policies\LaporanPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Laporan::class => LaporanPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
