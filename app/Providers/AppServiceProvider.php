<?php

namespace App\Providers;

use App\Models\User;
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
        // Gate para gestionar usuarios
        Gate::define('manage-users', function (User $user) {
            return $user->isSuperAdministrator();
        });

        // Gate para gestionar paquetes
        Gate::define('manage-packages', function (User $user) {
            return $user->isSuperAdministrator();
        });

        // Gate para gestionar cupones
        Gate::define('manage-coupons', function (User $user) {
            return $user->isSuperAdministrator();
        });

        // Gate para gestionar negocios
        Gate::define('manage-businesses', function (User $user) {
            return $user->isSuperAdministrator();
        });

        // Gate para acceder a reportes
        Gate::define('access-reports', function (User $user) {
            if (!$user->isBusinessAdministrator()) {
                return false;
            }

            $business = $user->business;
            $activePackage = $business?->activePackage;

            return $activePackage && $activePackage->package->has_reports;
        });

        // Gate para acceder a estadísticas
        Gate::define('access-statistics', function (User $user) {
            if (!$user->isBusinessAdministrator()) {
                return false;
            }

            $business = $user->business;
            $activePackage = $business?->activePackage;

            return $activePackage && $activePackage->package->has_statistics;
        });

        // Gate para acceder a filtros avanzados
        Gate::define('access-filters', function (User $user) {
            if (!$user->isBusinessAdministrator()) {
                return false;
            }

            $business = $user->business;
            $activePackage = $business?->activePackage;

            return $activePackage && $activePackage->package->has_filters;
        });
    }
}
