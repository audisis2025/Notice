<?php

/**
 * Nombre de la clase           : FortifyServiceProvider
 * Descripción de la clase      : Proveedor de servicios que configura Laravel Fortify
 *                                para autenticación con vistas Livewire
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 1.0
 * Fecha de mantenimiento       : 
 * Folio de mantenimiento       : 
 * Tipo de mantenimiento        :
 * Descripción del mantenimiento: 
 * Responsable                  : 
 * Revisor                      : 
 */

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class FortifyServiceProvider extends ServiceProvider
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
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // =========================================================================
        // CONFIGURAR VISTAS DE FORTIFY PARA USAR LIVEWIRE
        // =========================================================================

        // Vista de login
        Fortify::loginView(function () {
            return view('livewire.auth.login');
        });

        // Vista de registro
        Fortify::registerView(function () {
            return view('livewire.auth.register');
        });

        // Vista de verificación de email
        Fortify::verifyEmailView(function () {
            return view('livewire.auth.verify-email');
        });

        // Vista de solicitud de reset de contraseña
        Fortify::requestPasswordResetLinkView(function () {
            return view('livewire.auth.forgot-password');
        });

        // Vista de reset de contraseña
        Fortify::resetPasswordView(function ($request) {
            return view('livewire.auth.reset-password', ['request' => $request]);
        });

        // Vista de confirmación de contraseña
        Fortify::confirmPasswordView(function () {
            return view('livewire.auth.confirm-password');
        });

        // Vista de autenticación de dos factores
        Fortify::twoFactorChallengeView(function () {
            return view('livewire.auth.two-factor-challenge');
        });

        // =========================================================================
        // AUTENTICACIÓN PERSONALIZADA (PHONE O EMAIL)
        // =========================================================================

        Fortify::authenticateUsing(function (Request $request) {
            // Buscar por phone
            $user = User::where('phone', $request->phone)->first();

            // Si no se encuentra por phone y hay email, buscar por email
            if (!$user && $request->filled('email')) {
                $user = User::where('email', $request->email)->first();
            }

            // Verificar contraseña
            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }

            return null;
        });
        // Configurar vistas de Livewire
        Fortify::loginView(fn() => view('livewire.auth.login'));
        Fortify::registerView(fn() => view('livewire.auth.register'));
        Fortify::verifyEmailView(fn() => view('livewire.auth.verify-email'));
        Fortify::requestPasswordResetLinkView(fn() => view('livewire.auth.forgot-password'));
        Fortify::resetPasswordView(fn($request) => view('livewire.auth.reset-password', ['request' => $request]));
        Fortify::confirmPasswordView(fn() => view('livewire.auth.confirm-password'));
        Fortify::twoFactorChallengeView(fn() => view('livewire.auth.two-factor-challenge'));
    }
}
