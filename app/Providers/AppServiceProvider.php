<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

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
        Gate::define('admin', function (User $user) {
            return $user->rol === 'admin';
        });

        Gate::define('profesor', function (User $user) {
            return $user->rol === 'profesor';
        });

        Gate::define('alumno', function (User $user) {
            return $user->rol === 'alumno';
        });

        Gate::define('access-management', function (User $user) {
            return in_array($user->rol, ['admin', 'profesor']);
        });
    }
}
