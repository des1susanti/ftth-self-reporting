<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // Pastikan ini di-import

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
        // HAPUS baris $this->registerPolicies(); <--- Ini penyebab errornya

        // Tambahkan Gate ini untuk akses Admin & Manager
        Gate::define('akses-admin', function ($user) {
            return in_array($user->role, ['admin', 'manager']);
        });
    }
}